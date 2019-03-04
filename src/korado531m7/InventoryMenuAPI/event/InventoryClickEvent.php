<?php
namespace korado531m7\InventoryMenuAPI\event;

use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

use pocketmine\Player;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\item\Item;

class InventoryClickEvent extends PluginEvent{
    protected $who;
    protected $item;
    protected $inventory;
    protected $transaction;
    
    /**
     * @param Player                     $who
     * @param Item                       $item
     * @param InventoryTransactionPacket $transaction
     * @param FakeMenuInventory          $inventory
     */
    public function __construct(Player $who, Item $item, InventoryTransactionPacket $transaction, FakeMenuInventory $inventory){
        $this->who = $who;
        $this->item = $item;
        $this->inventory = $inventory;
        $this->transaction = $transaction;
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
     * @return FakeMenuInventory
     */
    public function getInventory() : FakeMenuInventory{
        return $this->inventory;
    }
    
    /**
     * @return NetworkInventoryAction
     */
    public function getActions() : array{
        return $this->transaction->actions;
    }
    
    /**
     * @return int
     */
    public function getTransactionType() : int{
        return $this->transaction->transactionType;
    }
}
