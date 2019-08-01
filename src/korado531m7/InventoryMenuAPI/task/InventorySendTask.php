<?php
namespace korado531m7\InventoryMenuAPI\task;

use korado531m7\InventoryMenuAPI\InventoryMenu;
use korado531m7\InventoryMenuAPI\task\InventoryTask;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;
use korado531m7\InventoryMenuAPI\utils\TemporaryData;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\scheduler\Task;

class InventorySendTask extends Task{
    private $player, $inventory;
    
    public function __construct(Player $player, TemporaryData $tmpData){
        $this->player = $player;
        $this->temp = $tmpData;
        
        $this->inventory = $tmpData->getMenuInventory();
        InventoryMenuUtils::sendFakeBlock($player, $this->inventory->getPosition(), $this->inventory->getBlock());
        if($this->inventory->isDouble())
            InventoryMenuUtils::sendPairData($player, $this->inventory->getPosition(), $this->inventory->getBlock());
        $tag = new CompoundTag();
        $tag->setString('CustomName', $this->inventory->getName());
        InventoryMenuUtils::sendTagData($player, $tag, $this->inventory->getPosition());
        $tmpData->setItems($player->getInventory()->getContents());
    }
    
    public function onRun(int $tick) : void{
        InventoryMenu::setData($this->player, $this->temp);
        $this->player->addWindow($this->inventory);
        $task = $this->inventory->getTask();
        if($task !== null){
            $pb = InventoryMenu::getPluginBase();
            switch($this->inventory->getTaskType()){
                case InventoryTask::SCHEDULER_REPEATING:
                    $task->setInventory($this->inventory);
                    $pb->getScheduler()->scheduleRepeatingTask($task, $this->inventory->getTick());
                break;
                
                //TODO
            }
        }
    }
}
