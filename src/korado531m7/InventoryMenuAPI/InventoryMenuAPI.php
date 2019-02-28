<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\FakeMenuInventory;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class InventoryMenuAPI extends PluginBase{
    private static $inventoryMenuVar = [];
    private static $pluginbase = null; //PLUGINBASE
    
    public function onEnable(){
        self::register($this);
    }
    
    /**
     * You need to call this function statically to use this api
     *
     * @param PluginBase $plugin
     */
    public static function register(PluginBase $plugin) : void{
        if(self::$pluginbase === null){
            self::$pluginbase = $plugin;
            $plugin->getServer()->getPluginManager()->registerEvents(new EventListener(), $plugin);
        }
    }
    
    /**
     * Check whether player is opening inventory menu
     *
     * @param  Player $player
     * @return bool
     */
    public static function isOpeningInventoryMenu(Player $player) : bool{
        return array_key_exists($player->getName(), self::$inventoryMenuVar);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public static function unsetData(Player $player){
        unset(self::$inventoryMenuVar[$player->getName()]);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public static function getData(Player $player) : array{
        return self::$inventoryMenuVar[$player->getName()] ?? [];
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public static function setData(Player $player, InventoryMenu $menu, string $name, FakeMenuInventory $im, array $inv){
        self::$inventoryMenuVar[$player->getName()] = [$menu, $name, $im, $inv];
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public static function getPluginBase() : PluginBase{
        return self::$pluginbase;
    }
}