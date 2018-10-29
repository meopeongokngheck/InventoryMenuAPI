<?php

declare(strict_types=1);

namespace korado531m7\InventoryMenuAPI\event;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\tile\Tile;

class InventoryMenuGenerateEvent extends PluginEvent{
    private $who;
    private $invType;
    private $items;
    private $tile;

    /**
     * @param Player $who
     * @param string $name
     * @param Tile $tile
     */
    public function __construct(Player $who, array $items,Tile $tile,int $invType){
        $this->who = $who;
        $this->items = $items;
        $this->tile = $tile;
        $this->invType = $invType;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return int
     */
    public function getInventoryType() : int{
        return $this->invType;
    }
    
    /**
     * @return Item
     */
    public function getItems() : array{
        return $this->items;
    }
    
    /**
     * @return Tile
     */
    public function getTile() : Tile{
        return $this->tile;
    }
}
