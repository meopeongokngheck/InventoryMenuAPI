<?php
namespace korado531m7\InventoryMenuAPI\task;

use korado531m7\InventoryMenuAPI\InventoryMenu;
use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class SendTask extends Task{
    public function __construct(Player $player, InventoryMenu $menu, FakeMenuInventory $inventory){
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
