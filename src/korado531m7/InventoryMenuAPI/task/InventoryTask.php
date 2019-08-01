<?php
namespace korado531m7\InventoryMenuAPI\task;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;

use pocketmine\scheduler\Task;

abstract class InventoryTask extends Task{
    const SCHEDULER_REPEATING = 0;
    
    protected $type;
    protected $inventory;
    
    public function __construct(){
        
    }
    
    public function setType(int $type){
        $this->type = $type;
    }
    
    /**
     * To access inventory, use this function
     *
     * @return MenuInventory
     */
    final public function getInventory() : MenuInventory{
        return $this->inventory;
    }
    
    /**
     * Don't call
     *
     * @param MenuInventory $inventory
     */
    final public function setInventory(MenuInventory $inventory){
        $this->inventory = $inventory;
        return $this;
    }
}
