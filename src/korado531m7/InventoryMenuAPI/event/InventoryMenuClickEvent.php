<?php

declare(strict_types=1);

namespace korado531m7\InventoryMenuAPI\event;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\tile\Tile;

class InventoryMenuClickEvent extends PluginEvent{
    private $who;
    private $name;
    private $item;
    private $tile;

    /**
     * @param Player $who
     * @param string $name
     * @param Tile $tile
     */
    public function __construct(Player $who, Item $item,Tile $tile){
        $this->who = $who;
        $this->item = $item;
        $this->name = \preg_replace("/(§([a-z]|[0-9]))/","",$item->getCustomName());
        $this->tile = $tile;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return Item
     */
    public function getItem() : Item{
        return $this->item;
    }
    
    /**
     * @return string
     */
    public function getMenuName() : string{
        return $this->name;
    }
    
    /**
     * @return Tile
     */
    public function getTile() : Tile{
        return $this->tile;
    }
}
