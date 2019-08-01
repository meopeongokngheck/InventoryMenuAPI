<?php
namespace korado531m7\InventoryMenuAPI\utils;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;

class TemporaryData{
    private $instance;
    private $items = [];
    
    public function __construct(){
        
    }
    
    public function setItems(array $items){
        $this->items = $items;
    }
    
    public function getItems() : array{
        return $this->items;
    }
    
    public function setMenuInventory(MenuInventory $menu){
        $this->instance = $menu;
    }
    
    public function getMenuInventory() : MenuInventory{
        return $this->instance;
    }
}