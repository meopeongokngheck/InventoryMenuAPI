<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

class EventListener implements Listener{
    public function __construct(){
    }
    
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        if(InventoryMenuAPI::isOpeningInventoryMenu($player)){
            InventoryMenuAPI::unsetData($player);
        }
    }
    
    public function onReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if($pk instanceof ContainerClosePacket && InventoryMenuAPI::isOpeningInventoryMenu($player)){
            $data = InventoryMenuAPI::getData($player);
            $ev = new InventoryCloseEvent($player, $data[2], $pk->windowId);
            $ev->call();
            if($ev->isCancelled()){
                $data[0]->removeBlock($player);
                $data[0]->send($player);
            }else{
                $data[0]->close($player);
            }
        }elseif($pk instanceof InventoryTransactionPacket){
            if(InventoryMenuAPI::isOpeningInventoryMenu($player) && array_key_exists(0,$pk->actions)){
                $data = InventoryMenuAPI::getData($player);
                $action = $pk->actions[0];
                if($data[0]->isReadonly()){
                    $data[0]->close($player);
                    $player->getInventory()->setContents($data[3]);
                    $event->setCancelled();
                }
                $ev = new InventoryClickEvent($player, $action->oldItem->getId() === ItemIds::AIR ? $action->newItem : $action->oldItem, $pk, $data[2]);
                $ev->call();
            }
        }
    }
}