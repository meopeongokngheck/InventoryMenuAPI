<?php
namespace korado531m7\InventoryMenuAPI\inventory; 

use pocketmine\math\Vector3;
use pocketmine\inventory\ContainerInventory;

class FakeMenuInventory extends ContainerInventory{
    protected $network_type;
    protected $title;
    protected $size;
    protected $holder;
    
    public function __construct(Vector3 $pos, int $network_type, int $size = null, string $title){
        $this->network_type = $network_type;
        $this->title = $title;
        $this->size = $size;
        $this->holder = $pos;
        parent::__construct($pos, [], $size, $title);
    }

    public function getNetworkType() : int{
        return $this->network_type;
    }
    
    public function getName() : string{
        return $this->title;
    }
    
    public function getDefaultSize() : int{
        return $this->size;
    }
    
    public function getHolder(){
        return $this->holder;
    }
    
    public function setName(string $title){
        $this->title = $title;
    }
}