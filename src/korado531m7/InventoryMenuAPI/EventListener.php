<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

class EventListener implements Listener{
    public function __construct(){
    }
    
    public function onTransactionInventory(InventoryTransactionEvent $event){
        $object = $event->getTransaction()->getSource();
        if($object instanceof Player){
            if(InventoryMenuAPI::isOpeningInventoryMenu($object)){
                $data = InventoryMenuAPI::getData($object);
                $object->getInventory()->setContents($data[3]);
            }
        }
    }
    
    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if($pk instanceof ContainerClosePacket){
            if(!InventoryMenuAPI::isOpeningInventoryMenu($player)) return true;
            $data = InventoryMenuAPI::getData($player);
            $player->getInventory()->setContents($data[3]);
            $data[0]->close($player);
        }elseif($pk instanceof InventoryTransactionPacket){
            if(InventoryMenuAPI::isOpeningInventoryMenu($player) && array_key_exists(0,$pk->actions)){
                $action = $pk->actions[0];
                $data = InventoryMenuAPI::getData($player);
                if($data[0]->isReadonly()){
                    $itemresult = $action->oldItem->getId() === ItemIds::AIR ? $action->newItem : $action->oldItem;
                    $data[0]->close($player);
                    $ev = new InventoryClickEvent($player, $itemresult, $pk, $data[2]);
                    $ev->call();
                }
            }
        }
    }
}