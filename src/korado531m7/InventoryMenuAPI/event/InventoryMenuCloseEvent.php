<?php

declare(strict_types=1);

namespace korado531m7\InventoryMenuAPI\event;

use pocketmine\Player;
use pocketmine\event\plugin\PluginEvent;

class InventoryMenuCloseEvent extends PluginEvent{
    private $who;

    /**
     * @param Player $who
     */
    public function __construct(Player $who){
        $this->who = $who;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
}
