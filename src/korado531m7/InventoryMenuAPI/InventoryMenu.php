<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\InventoryMenuAPI as IM;
use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;
use korado531m7\InventoryMenuAPI\task\SendTask;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils as IMU;

use pocketmine\Player;
use pocketmine\block\BlockIds;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

class InventoryMenu implements InventoryTypes{
    private $title;
    private $type;
    private $item = [];
    private $position;
    private $readonly = true;
    
    public function __construct(int $type = self::INVENTORY_TYPE_CHEST){
        $this->type = $type;
        $this->title = IMU::getDefaultInventoryName($type);
    }
    
    /**
     * Set item to specific index
     *
     * @param int  $index
     * @param Item $item
     *
     * @return InventoryMenu
     */
    public function setItem(int $index, Item $item) : InventoryMenu{
        $this->item[$index] = $item;
        return $this;
    }
    
    /**
     * Set items
     *
     * @param Item[] $items
     *
     * @return InventoryMenu
     */
    public function setContents(array $items) : InventoryMenu{
        $this->item = $items;
        return $this;
    }
    
    /**
     * Set inventory name
     *
     * @param string $title
     *
     * @return InventoryMenu
     */
    public function setName(string $title) : InventoryMenu{
        $this->title = $title;
        return $this;
    }
    
    /**
     * Enable to trade between player and inventory
     *
     * @param bool $value
     *
     * @return InventoryMenu
     */
    public function setReadonly(bool $value) : InventoryMenu{
        $this->readonly = $value;
        return $this;
    }
    
    /**
     * Get item from specific index
     *
     * @return Item|null
     */
    public function getItem(int $index) : ?Item{
        return $this->item[$index] ?? null;
    }
    
    /**
     * @return bool
     */
    public function isReadonly() : bool{
        return $this->readonly;
    }
    
    /**
     * @return Item[]
     */
    public function getContents(){
        return $this->item;
    }
    
    /**
     * @return string
     */
    public function getName(){
        return $this->title;
    }
    
    /**
     * @return int
     */
    public function getType() : int{
        return $this->type;
    }
    
    /**
     * @return Vector3
     */
    public function getPos() : Vector3{
        return $this->position;
    }
    
    /**
     * Send inventory to player
     *
     * @param Player $player 
     */
    public function send(Player $player){
        $this->position = clone $player->floor()->add(0, 4);
        $inv = new FakeMenuInventory($this->getPos(), IMU::getInventoryWindowTypes($this->getType()), IMU::getMaxInventorySize($this->getType()), $this->getName());
        $inv->setContents($this->item);
        InventoryMenuAPI::getPluginBase()->getScheduler()->scheduleDelayedTask(new SendTask($player, clone $this, clone $inv), 4);
    }
    
    /**
     * Close inventory if player is opening
     * 
     * @param Player $player
     */
    public function close(Player $player){
        if(!InventoryMenuAPI::isOpeningInventoryMenu($player)) return;
        $data = InventoryMenuAPI::getData($player);
        $player->removeWindow($data[IM::TEMP_FMINV_INSTANCE]);
        $this->removeBlock($player);
        InventoryMenuAPI::unsetData($player);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public function removeBlock(Player $player){
        IMU::sendFakeBlock($player, $this->getPos(), BlockIds::AIR);
        if($this->getType() === self::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendFakeBlock($player, $this->getPos()->add(1), BlockIds::AIR);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public function sendFakeBlock(Player $player){
        $pos = $this->getPos();
        IMU::sendFakeBlock($player, $pos, IMU::getInventoryBlockId($this->getType()));
        if($this->getType() === self::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendPairData($player, $pos, $this->getType());
        $tag = new CompoundTag();
        $tag->setString('CustomName', $this->getName());
        IMU::sendTagData($player, $tag, $pos);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public function setData(Player $player, FakeMenuInventory $inv){
        InventoryMenuAPI::setData($player, $this, $inv, $player->getInventory()->getContents());
    }
}