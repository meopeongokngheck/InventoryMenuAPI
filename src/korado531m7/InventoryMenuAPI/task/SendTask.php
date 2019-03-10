<?php
namespace korado531m7\InventoryMenuAPI\task;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class SendTask extends Task{
    public function __construct(Player $player, $menu, $inventory){
        $this->player = $player;
        $this->menu = $menu;
        $this->inventory = $inventory;
        
        $menu->sendFakeBlock($player);
    }
    
    public function onRun(int $tick) : void{
        $this->menu->setData($this->player, $this->inventory);
        $this->player->addWindow($this->inventory);
    }
}
