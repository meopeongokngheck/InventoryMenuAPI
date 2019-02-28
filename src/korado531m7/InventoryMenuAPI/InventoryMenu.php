<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;
use korado531m7\InventoryMenuAPI\task\PrepareSendTask;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils as IMU;

use pocketmine\Player;
use pocketmine\block\BlockIds;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

class InventoryMenu implements InventoryTypes{
    private $title = 'Virtual Inventory';
    private $type;
    private $item = [];
    private $position;
    private $readonly = true;
    private $continuity = false;
    
    public function __construct(int $type = InventoryTypes::INVENTORY_TYPE_CHEST){
        $this->type = $type;
    }
    
    public function setItem(int $index, Item $item){
        $this->item[$index] = $item;
        return $this;
    }
    
    public function setContents(array $items){
        $this->item = $items;
        return $this;
    }
    
    public function setName(string $title){
        $this->title = $title;
        return $this;
    }
    
    public function setReadonly(bool $bool){
        $this->readonly = $bool;
        return $this;
    }
    
    public function getItem(int $index){
        return $this->item[$index];
    }
    
    public function isReadonly() : bool{
        return $this->readonly;
    }
    
    public function getContents(){
        return $this->item;
    }
    
    public function getName(){
        return $this->title;
    }
    
    public function getType() : int{
        return $this->type;
    }
    
    public function getPos() : Vector3{
        return $this->position;
    }
    
    public function send(Player $player){
        $pos = clone $player->floor()->add(0, 4);
        $this->position = $pos;
        $inv = new FakeMenuInventory($pos, IMU::getInventoryWindowTypes($this->getType()), IMU::getMaxInventorySize($this->getType()), $this->getName());
        $inv->setHolderPos($pos);
        foreach($this->item as $k => $i){
            $inv->setItem($k, $i);
        }
        $tag = new CompoundTag();
        $tag->setString('CustomName', $this->getName());
        IMU::sendTagData($player, $tag, $pos);
        InventoryMenuAPI::getPluginBase()->getScheduler()->scheduleDelayedTask(new PrepareSendTask($player, $this, $inv), 5);
    }
    
    public function close(Player $player){
        InventoryMenuAPI::unsetData($player);
        IMU::sendFakeBlock($player, $this->getPos(), BlockIds::AIR);
        if($this->getType() === InventoryTypes::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendFakeBlock($player, $this->getPos()->add(1), BlockIds::AIR);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public function sendFakeBlock(Player $player){
        $pos = $this->getPos();
        IMU::sendFakeBlock($player, $pos, IMU::getInventoryBlockId($this->getType()));
        if($this->getType() === InventoryTypes::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendPairData($player, $pos, $this->getType());
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public function setData(Player $player, $inv){
        InventoryMenuAPI::setData($player, $this, $this->getName(), $inv, $player->getInventory()->getContents());
    }
}