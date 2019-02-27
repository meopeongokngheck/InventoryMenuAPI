# InventoryMenuAPI
**You can create inventory menu like java version with this API!**

### Installation
You can download converted to phar version file from [here](https://poggit.pmmp.io/ci/korado531m7/InventoryMenuAPI/InventoryMenuAPI)

### How to use
Before using, you need to import class
```php
<?php
use korado531m7\InventoryMenuAPI\InventoryMenuAPI;
```

If you use api as virion, you must call register function statically
```php
InventoryMenuAPI::register($param); //param must be included PluginBase
```

Second, you need to define an array that include items like
```php
<?php
$array = [3 => Item::get(4,0,1), 7 => Item::get(46,0,5), 12 => Item::get(246,0,1), 14 => Item::get(276,0,1)->setCustomName('MysterySword!')];
```
these items will be set on inventory menu

To send inventory menu, create InventoryMenu instance
```php
$inv = new InventoryMenu();
```

then, call send function
```php
$inv->send($player); //$player is player object
```


**SET READONLY (WRITABLE) INVENTORY**

To enable to edit inventory, use setReadonly function (default value is true)
```php
$inv->setReadonly(false); //boolean
```


**RENAMING INVENTORY MENU NAME**

To change inventory name, call setName function
```php
$inv->setName('WRITE NAME HERE');
```

**CHANGING INVENTORY TYPE**

To change inventory type, need to place type int to first parameter when create inventory menu instance (Default: chest)
```php
//Supported list
const INVENTORY_TYPE_CHEST = 1;const INVENTORY_TYPE_DOUBLE_CHEST = 2;const INVENTORY_TYPE_ENCHANTING_TABLE = 3;const INVENTORY_TYPE_HOPPER = 4;const INVENTORY_TYPE_BREWING_STAND = 5;const INVENTORY_TYPE_ANVIL = 6;const INVENTORY_TYPE_DISPENSER = 7;const INVENTORY_TYPE_DROPPER = 8;const INVENTORY_TYPE_BEACON = 9;const INVENTORY_TYPE_TRADING = 10;
const INVENTORY_TYPE_COMMAND_BLOCK = 11;
//NOTICE: Trading and command block are not supported (not implemented on PocketMine)

//Example:
$inv = new InventoryMenu(InventoryTypes::INVENTORY_TYPE_ENCHANTING_TABLE);
```
These constants are written in `korado531m7\InventoryMenuAPI\InventoryTypes` interface


**HOW TO CLOSE INVENTORY MENU**

To close inventory menu, use close function
```php
$inv->close($player); //$player is player object who is opening inventory menu
```


### DEALING WITH EVENT
This api will call event and you can use that!

**WHEN CLICKED ITEMS**

You can use event when player clicked items
it's InventoryClickEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
```
* `getPlayer()`          - Return Player object who clicked
* `getItem()`            - Return Item which player clicked
* `getInventory()`       - Return Fake Inventory
* `getAction()`          - Return NetworkInventoryAction
* `getTransactionType()` - Return integer



### REPORTING ISSUES
If you found a bug or issues, please report it at 'issue'
I'll fix that


### More Features
I'll make more features if have free time.