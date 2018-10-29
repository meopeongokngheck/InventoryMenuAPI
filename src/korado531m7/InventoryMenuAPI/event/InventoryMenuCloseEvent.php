<?php

declare(strict_types=1);

namespace korado531m7\InventoryMenuAPI\event;

use pocketmine\Player;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\tile\Tile;

class InventoryMenuCloseEvent extends PluginEvent{
    private $who;
    private $tile;

    /**
     * @param Player $who
     * @param Tile $tile
     */
    public function __construct(Player $who, Tile $tile){
        $this->who = $who;
        $this->tile = $tile;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return Tile
     */
    public function getTile() : Tile{
        return $this->tile;
    }
}
