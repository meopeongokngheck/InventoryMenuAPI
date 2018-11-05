# InventoryMenuAPI
**You can create inventory menu like java version with this API!**

### Installation
You can download converted to phar version file from [here](https://poggit.pmmp.io/ci/korado531m7/InventoryMenuAPI/InventoryMenuAPI)

### How to use
First, you need to write use
```php
<?php
use korado531m7\InventoryMenuAPI\InventoryMenuAPI;
```

Second, you need to define an array that include items like
```php
<?php
$array = [3 => Item::get(4,0,1), 7 => Item::get(46,0,5), 12 => Item::get(246,0,1), 14 => Item::get(276,0,1)->setCustomName('MysterySword!')];
```
these items will be set on inventory menu

To send inventory menu, use 'sendInventoryMenu' function like this
```php
$array = '';// that you defined items array
$player = ''; //Player object

InventoryMenuAPI::sendInventoryMenu($player, $array);
```
Call this function to send and will be displayed inventory menu on your screen :D
(And i will use this $array and $player in this documentation)

**RENAMING INVENTORY MENU NAME**

To change inventory name, place a text to third parameter like
```php
InventoryMenuAPI::sendInventoryMenu($player, $array, "NAME HERE WHAT YOU WANT TO RENAME");
```
Wow its easy!


**CHANGING INVENTORY TYPE**

To change inventory type, need to place type int to fourth parameter from supported type (Default: chest)
```php
//Supported list
const INVENTORY_TYPE_CHEST = 1;const INVENTORY_TYPE_DOUBLE_CHEST = 2;const INVENTORY_TYPE_ENCHANTING_TABLE = 3;const INVENTORY_TYPE_HOPPER = 4;const INVENTORY_TYPE_BREWING_STAND = 5;const INVENTORY_TYPE_ANVIL = 6;const INVENTORY_TYPE_DISPENSER = 7;const INVENTORY_TYPE_DROPPER = 8;const INVENTORY_TYPE_BEACON = 9;const INVENTORY_TYPE_TRADING = 10;
const INVENTORY_TYPE_COMMAND_BLOCK = 11;
NOTICE: Trading and command block is not supported (not implemented on PocketMine)


//Example:
InventoryMenuAPI::sendInventoryMenu($player, $array, 'INVNAME', InventoryMenuAPI::INVENTORY_TYPE_DOUBLE_CHEST);
```


**CHANGING ITEMS TO INVENTORY MENU**
```php
//$array is a new array included items
InventoryMenuAPI::fillInventoryMenu($player, $array);
```



**HOW TO CLOSE INVENTORY MENU**

To close inventory menu, use 'closeInventoryMenu' function
```php
InventoryMenuAPI::closeInventoryMenu($player);
```
it's simple



### DEALING WITH EVENT
This api will call event and you can use that!

**ON CLICKED ITEMS**

You can an event when player clicked items
it's InventoryMenuClickEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryMenuClickEvent;
```
`getPlayer()`       - Return Player object who clicked

`getItem()`         - Return Item which player clicked

`getMenuName()`     - Return Inventory Menu Name ( getTile()->getCustomName() )

`getTile()`         - Return Inventory Menu Name Tile



**ON CLOSED INVENTORY MENU**
```php
use korado531m7\InventoryMenuAPI\event\InventoryMenuCloseEvent;
```
`getPlayer()`       - Return Player object who clicked

`getTile()`         - Return Inventory Menu Name Tile



**ON GENERATED INVENTORY MENU**
```php
use korado531m7\InventoryMenuAPI\event\InventoryMenuGenerateEvent;
```
`getPlayer()`        - Return Player object who clicked

`getInventoryType()` - Return generated inventory type as int

`getTile()`          - Return Inventory Menu Name Tile

`getItems()`         - Return array include items which generated with




### REPORTING ISSUES
If you found a bug or issues, please report it at 'issue'
I'll fix that


### More Features
I'll make more features if have free time.