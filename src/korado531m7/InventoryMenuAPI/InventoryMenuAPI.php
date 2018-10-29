<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\event\InventoryMenuCloseEvent;
use korado531m7\event\InventoryMenuGenerateEvent;
use korado531m7\task\DelayAddWindowTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\JsonNbtParser;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Furnace as TileFurnace;
use pocketmine\tile\Tile;

class InventoryMenuAPI extends PluginBase{
    private static $inventoryMenuVar = [];
    private static $inventory = [];
    private static $pluginbase;
    
    const INVENTORY_TYPE_CHEST = 1;
    const INVENTORY_TYPE_DOUBLE_CHEST = 2;
    const INVENTORY_TYPE_FURNACE = 3;
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        self::setPluginBase($this);
    }
    
    /**
     * Send an inventory menu to player
     *
     * @param Player  $player
     * @param Item[]  $items
     * @param string  $inventoryName
     * @param int     $inventoryType
     * @param bool    $isCloseType      Default value is true and if true, the inventory menu will be automatically closed when call InventoryTransactionPacket but if not, won't be closed. so you must call 'closeInventory' funtion to close manually
     */
    public static function sendInventoryMenu(Player $player, array $items, $inventoryName = "Inventory Menu", $inventoryType = self::INVENTORY_TYPE_CHEST, bool $isCloseType = true){
        if(self::isOpeningInventoryMenu($player)) return true;
        $x = ((int)$player->x + mt_rand(-1,1));
        $y = ((int)$player->y + 4);
        $z = ((int)$player->z + mt_rand(-1,1));
        if($player->getLevel()->getTileAt($x,$y,$z) !== null) $y = ((int)$player->y + 3);
        
        switch($inventoryType){
            case self::INVENTORY_TYPE_FURNACE:
                self::sendFakeBlock($player,$x,$y,$z,BlockIds::FURNACE);
                $nbt = TileFurnace::createNBT(new Vector3($x,$y,$z), 0, Item::get(0,0), $player);
                $nbt->setString('CustomName',$inventoryName);
                $tile = Tile::createTile(Tile::FURNACE, $player->getLevel(), $nbt);
                $tag = JsonNbtParser::parseJSON(json_encode(['id' => $tile->getSaveId(), 'CustomName' => $inventoryName],JSON_UNESCAPED_UNICODE));
            break;
            
            case self::INVENTORY_TYPE_DOUBLE_CHEST:
                self::sendFakeBlock($player,$x,$y,$z + 1,BlockIds::CHEST);
                $nbt2 = TileChest::createNBT(new Vector3($x,$y,$z + 1), 0, Item::get(0,0), $player);
                $tile2 = Tile::createTile(Tile::CHEST, $player->getLevel(), $nbt2);
                $writer = new NetworkLittleEndianNBTStream();
                $tag = JsonNbtParser::parseJSON(json_encode(['id' => $tile2->getSaveId(), 'CustomName' => $inventoryName, 'pairx' => $x, 'pairz' => $z],JSON_UNESCAPED_UNICODE));
                $pk = new BlockEntityDataPacket;
                $pk->x = $x;
                $pk->y = $y;
                $pk->z = $z + 1;
                $pk->namedtag = $writer->write($tag);
                $player->dataPacket($pk);
            case self::INVENTORY_TYPE_CHEST:
                self::sendFakeBlock($player,$x,$y,$z,BlockIds::CHEST);
                $nbt = TileChest::createNBT(new Vector3($x,$y,$z), 0, Item::get(0,0), $player);
                $nbt->setString('CustomName',$inventoryName);
                $tile = Tile::createTile(Tile::CHEST, $player->getLevel(), $nbt);
                $tag = JsonNbtParser::parseJSON(json_encode(['id' => $tile->getSaveId(), 'CustomName' => $inventoryName],JSON_UNESCAPED_UNICODE));
                
                if($inventoryType == self::INVENTORY_TYPE_DOUBLE_CHEST) $tile->pairWith($tile2);
            break;
        }
        
        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockEntityDataPacket;
        $pk->x = $x;
        $pk->y = $y;
        $pk->z = $z;
        $pk->namedtag = $writer->write($tag);
        $player->dataPacket($pk);
        
        $inv = $tile->getInventory();
        foreach($items as $itemkey => $item){
            $inv->setItem($itemkey,$item);
        }
        $ev = new InventoryMenuGenerateEvent($player,$items,$tile,$inventoryType);
        $ev->call();
        self::saveInventory($player);
        switch($inventoryType){
            case self::INVENTORY_TYPE_FURNACE:
            case self::INVENTORY_TYPE_CHEST:
                self::$inventoryMenuVar[$player->getName()] = array($inventoryType,$tile->getSaveId(),$x,$y,$z,$player->getLevel()->getName(),$isCloseType);
                $player->addWindow($inv);
            break;
            
            case self::INVENTORY_TYPE_DOUBLE_CHEST:
                self::$inventoryMenuVar[$player->getName()] = array(self::INVENTORY_TYPE_DOUBLE_CHEST,$tile->getSaveId(),$x,$y,$z,$player->getLevel()->getName(),$isCloseType);
                self::getPluginBase()->getScheduler()->scheduleDelayedTask(new DelayAddWindowTask($player,$inv), 10);
            break;
        }
    }
    
    /**
     * Change old item for new items in the inventory menu but it must be isCloseType is false
     * Also you can change $isCloseType in this function (default: false)
     *
     * @param Player  $player
     * @param Item[]  $items
     * @param bool    $isCloseType
     */
    public static function fillInventoryMenu(Player $player,array $items,bool $isCloseType = false){
        if(!self::isOpeningInventoryMenu($player)) return false;
        $data = self::getData($player);
        $tile = self::getPluginBase()->getServer()->getLevelByName($data[5])->getTileAt($data[2],$data[3],$data[4]);
        if($tile === \null) return false;
        $inv = $tile->getInventory();
        $inv->clearAll();
        foreach($items as $itemkey => $item){
            $inv->setItem($itemkey,$item);
        }
        $data[6] = $isCloseType;
        self::restoreInventory($player);
        self::$inventoryMenuVar[$player->getName()] = $data;
    }
    
    /**
     * Clear all items from an inventory menu (for development)
     *
     * @param Player  $player
     * @param bool    $isCloseType
     */
    public static function clearInventoryMenu(Player $player,bool $isCloseType = false){
        if(!self::isOpeningInventoryMenu($player)) return false;
        $data = self::getData($player);
        $tile = self::getPluginBase()->getServer()->getLevelByName($data[5])->getTileAt($data[2],$data[3],$data[4]);
        if($tile === \null) return false;
        $inv = $tile->getInventory();
        $inv->clearAll();
        $data[6] = $isCloseType;
        self::$inventoryMenuVar[$player->getName()] = $data;
    }
    
    /**
     * Close an inventory menu if player is opening
     *
     * @param Player $player
     */
    public static function closeInventoryMenu(Player $player){
        if(!self::isOpeningInventoryMenu($player)) return true;
        $data = self::getData($player);
        if(self::getPluginBase()->getServer()->isLevelLoaded($data[5])){
            $level = self::getPluginBase()->getServer()->getLevelByName($data[5]);
            $ev = new InventoryMenuCloseEvent($player, $level->getTile(new Vector3($data[2],$data[3],$data[4])));
            $ev->call();
            switch($data[0]){
                case self::INVENTORY_TYPE_DOUBLE_CHEST:
                    self::sendFakeBlock($player,$data[2],$data[3],$data[4] + 1,BlockIds::AIR);
                    $level->removeTile($level->getTile(new Vector3($data[2],$data[3],$data[4] + 1)));
                case self::INVENTORY_TYPE_CHEST:
                case self::INVENTORY_TYPE_FURNACE:
                    self::sendFakeBlock($player,$data[2],$data[3],$data[4],BlockIds::AIR);
                    $level->removeTile($level->getTile(new Vector3($data[2],$data[3],$data[4])));
                break;
            }
        }
        self::restoreInventory($player, true);
        unset(self::$inventoryMenuVar[$player->getName()]);
    }
    
    /**
     * Check whether player is opening inventory menu
     *
     * @param  Player $player
     * @return bool
     */
    public static function isOpeningInventoryMenu(Player $player) : bool{
        return array_key_exists($player->getName(),self::$inventoryMenuVar);
    }
    
    /**
     * @param Player  $player
     * @return array
     */
    public static function getData(Player $player) : array{
        return self::$inventoryMenuVar[$player->getName()] ?? array();
    }
    
    public static function saveInventory(Player $player){
        self::$inventory[$player->getName()] = $player->getInventory()->getContents();
    }
    
    public static function restoreInventory(Player $player, bool $reset = false){
        $inventory = self::$inventory[$player->getName()] ?? null;
        if($inventory === null) return false;
        $player->getInventory()->setContents($inventory);
        if($reset) unset($inventory[$player->getName()]);
    }
    
    private static function getPluginBase() : PluginBase{
        return self::$pluginbase;
    }
    
    private static function setPluginBase(PluginBase $plugin){
        self::$pluginbase = $plugin;
    }
    
    private static function sendFakeBlock(Player $player,int $x,int $y,int $z,int $blockid){
        $pk = new UpdateBlockPacket();
        $pk->x = $x;
        $pk->y = $y;
        $pk->z = $z;
        $pk->blockRuntimeId = BlockFactory::toStaticRuntimeId($blockid);
        $player->dataPacket($pk);
    }
}