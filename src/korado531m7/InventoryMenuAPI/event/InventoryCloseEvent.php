<?php
namespace korado531m7\InventoryMenuAPI\event;

use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;

use pocketmine\Player;
use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

class InventoryCloseEvent extends PluginEvent implements Cancellable{
    protected $who;
    protected $inventory;
    protected $windowId;
    
    /**
     * @param Player            $who
     * @param FakeMenuInventory $inventory
     */
    public function __construct(Player $who, FakeMenuInventory $inventory, int $windowId){
        $this->who = $who;
        $this->inventory = $inventory;
        $this->windowId = $windowId;
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
    
    /**
     * @return int
     */
    public function getWindowId() : int{
        return $this->windowId;
    }
}
