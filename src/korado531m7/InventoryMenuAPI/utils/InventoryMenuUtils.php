<?php
namespace korado531m7\InventoryMenuAPI\utils;

use korado531m7\InventoryMenuAPI\InventoryMenuAPI;
use korado531m7\InventoryMenuAPI\InventoryTypes;

use pocketmine\Player;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;

class InventoryMenuUtils{
    public static function sendTagData(Player $player, CompoundTag $tag, Vector3 $pos){
        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockEntityDataPacket;
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->namedtag = $writer->write($tag);
        $player->dataPacket($pk);
    }
    
    public static function sendPairData(Player $player, Vector3 $pos, int $type){
        self::sendFakeBlock($player, $pos->add(1), self::getInventoryBlockId($type));
        $tag = new CompoundTag();
        $tag->setInt('pairx', $pos->x);
        $tag->setInt('pairz', $pos->z);
        self::sendTagData($player, $tag, $pos->add(1));
    }
    
    public static function sendFakeBlock(Player $player, Vector3 $pos, int $id){
        $pk = new UpdateBlockPacket();
        $pk->x = (int) $pos->x;
        $pk->y = (int) $pos->y;
        $pk->z = (int) $pos->z;
        $pk->flags = UpdateBlockPacket::FLAG_ALL;
        $pk->blockRuntimeId = BlockFactory::toStaticRuntimeId($id);
        $player->dataPacket($pk);
    }
    
    public static function getMaxInventorySize(int $type) : int{
        switch($type){
            case InventoryTypes::INVENTORY_TYPE_DISPENSER:
            case InventoryTypes::INVENTORY_TYPE_DROPPER:
                return 9;
            case InventoryTypes::INVENTORY_TYPE_BEACON:
                return 1; //?
            case InventoryTypes::INVENTORY_TYPE_TRADING:
                throw new \RuntimeException('Trading Inventory is not supported on account of not impletented yet :(');
            case InventoryTypes::INVENTORY_TYPE_COMMAND_BLOCK:
                throw new \RuntimeException('Command Block Inventory is not supported on account of not impletented yet :(');
            case InventoryTypes::INVENTORY_TYPE_CHEST:
            case InventoryTypes::INVENTORY_TYPE_ANVIL:
                return 27;
            case InventoryTypes::INVENTORY_TYPE_ENCHANTING_TABLE:
            case InventoryTypes::INVENTORY_TYPE_BREWING_STAND:
            case InventoryTypes::INVENTORY_TYPE_HOPPER:
                return 5;
            case InventoryTypes::INVENTORY_TYPE_DOUBLE_CHEST:
                return 54;
        }
        throw new \InvalidArgumentException('Invalid Inventory Type');
    }
    
    public static function getInventoryBlockId(int $type) : int{
        switch($type){
            case InventoryTypes::INVENTORY_TYPE_DISPENSER:
                return BlockIds::DISPENSER;
            case InventoryTypes::INVENTORY_TYPE_DROPPER:
                return BlockIds::DROPPER;
            case InventoryTypes::INVENTORY_TYPE_BEACON:
                return BlockIds::BEACON; //?
            case InventoryTypes::INVENTORY_TYPE_COMMAND_BLOCK:
                return BlockIds::COMMAND_BLOCK;
            case InventoryTypes::INVENTORY_TYPE_CHEST:
            case InventoryTypes::INVENTORY_TYPE_DOUBLE_CHEST:
                return BlockIds::CHEST;
            case InventoryTypes::INVENTORY_TYPE_ANVIL:
                return BlockIds::ANVIL;
            case InventoryTypes::INVENTORY_TYPE_ENCHANTING_TABLE:
                return BlockIds::ENCHANTING_TABLE;
            case InventoryTypes::INVENTORY_TYPE_BREWING_STAND:
                return BlockIds::BREWING_STAND;
            case InventoryTypes::INVENTORY_TYPE_HOPPER:
                return BlockIds::HOPPER_BLOCK;
        }
    }
    
    public static function getInventoryWindowTypes(int $type) : int{
        switch($type){
            case InventoryTypes::INVENTORY_TYPE_DISPENSER:
                return WindowTypes::DISPENSER;
            case InventoryTypes::INVENTORY_TYPE_DROPPER:
                return WindowTypes::DROPPER;
            case InventoryTypes::INVENTORY_TYPE_BEACON:
                return WindowTypes::BEACON; //?
            case InventoryTypes::INVENTORY_TYPE_COMMAND_BLOCK:
                return WindowTypes::COMMAND_BLOCK;
            case InventoryTypes::INVENTORY_TYPE_CHEST:
            case InventoryTypes::INVENTORY_TYPE_DOUBLE_CHEST:
                return WindowTypes::CONTAINER;
            case InventoryTypes::INVENTORY_TYPE_ANVIL:
                return WindowTypes::ANVIL;
            case InventoryTypes::INVENTORY_TYPE_ENCHANTING_TABLE:
                return WindowTypes::ENCHANTMENT;
            case InventoryTypes::INVENTORY_TYPE_BREWING_STAND:
                return WindowTypes::BREWING_STAND;
            case InventoryTypes::INVENTORY_TYPE_HOPPER:
                return WindowTypes::HOPPER;
            case InventoryTypes::INVENTORY_TYPE_TRADING:
                return WindowTypes::TRADING;
        }
    }
}