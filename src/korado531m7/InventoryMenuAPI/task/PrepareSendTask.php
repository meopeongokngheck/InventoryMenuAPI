<?php
namespace korado531m7\InventoryMenuAPI\task;

use korado531m7\InventoryMenuAPI\InventoryMenuAPI;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class PrepareSendTask extends Task{
    public function __construct(Player $player, $menu, $inventory){
        $this->player = $player;
        $this->menu = $menu;
        $this->inventory = $inventory;
    }
    
    public function onRun(int $tick) : void{
        $this->menu->sendFakeBlock($this->player);
        InventoryMenuAPI::getPluginBase()->getScheduler()->scheduleDelayedTask(new SendInventoryTask($this->player, $this->menu, $this->inventory), 5);
    }
}
