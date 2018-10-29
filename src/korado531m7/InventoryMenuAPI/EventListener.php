<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\event\InventoryMenuClickEvent;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Vector3;

class EventListener implements Listener{
    private $plugin;
    
    public function __construct(InventoryMenuAPI $plugin){
        $this->plugin = $plugin;
    }
    
    public function onTransactionInventory(InventoryTransactionEvent $event){
        $object = $event->getTransaction()->getSource();
        if($object instanceof Player){
            if($this->plugin->isOpeningInventoryMenu($object)){
                $this->plugin->restoreInventory($object);
                $event->setCancelled();
            }
        }
    }
    
    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        switch(\get_class($pk)){
            case 'pocketmine\network\mcpe\protocol\ContainerClosePacket':
                $this->plugin->closeInventoryMenu($player);
            break;
                
            case 'pocketmine\network\mcpe\protocol\InventoryTransactionPacket':
                if($this->plugin->isOpeningInventoryMenu($player) && array_key_exists(0,$pk->actions)){
                    $action = $pk->actions[0];
                    $data = $this->plugin->getData($player);
                    $itemresult = $action->oldItem;
                    if($action->oldItem->getId() == 0) $itemresult = $action->newItem;
                    $ev = new InventoryMenuClickEvent($player, $itemresult, $player->getLevel()->getTile(new Vector3($data[2],$data[3],$data[4])));
                    $ev->call();
                    if($data[6] == true) $this->plugin->closeInventoryMenu($player);
                }
            break;
        }
    }
}