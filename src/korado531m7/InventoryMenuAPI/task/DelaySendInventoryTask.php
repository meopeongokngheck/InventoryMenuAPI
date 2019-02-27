<?php
namespace korado531m7\InventoryMenuAPI\task;

use korado531m7\InventoryMenuAPI\InventoryMenu;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class DelaySendInventoryTask extends Task{
    public function __construct(Player $player, $menu, $inventory){
        $this->player = $player;
        $this->menu = $menu;
        $this->inventory = $inventory;
    }
    
    public function onRun(int $tick) : void{
        $this->player->addWindow($this->inventory);
    }
}
