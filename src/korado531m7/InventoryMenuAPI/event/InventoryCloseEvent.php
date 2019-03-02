<?php
namespace korado531m7\InventoryMenuAPI\event;

use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;

use pocketmine\Player;
use pocketmine\event\plugin\PluginEvent;

class InventoryCloseEvent extends PluginEvent{
    protected $who;
    protected $inventory;
    
    /**
     * @param Player            $who
     * @param FakeMenuInventory $inventory
     */
    public function __construct(Player $who, FakeMenuInventory $inventory){
        $this->who = $who;
        $this->inventory = $inventory;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return FakeMenuInventory
     */
    public function getInventory() : FakeMenuInventory{
        return $this->inventory;
    }
}
