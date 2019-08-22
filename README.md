# InventoryMenuAPI
**Advanced Inventory Menu API for PocketMine**

### Installation
You can download converted to phar version file from [here](https://poggit.pmmp.io/ci/korado531m7/InventoryMenuAPI/InventoryMenuAPI)

### Preparation
Before using, you need to import class
```php
use korado531m7\InventoryMenuAPI\InventoryMenu;
```

If you use api as virion, you must call register function statically
```php
InventoryMenu::register($param); //param must be PluginBase
```

___

### Sending Inventory
First, you need to make an array that is including items
```php
$array = [3 => Item::get(4,0,1), 7 => Item::get(46,0,5), 12 => Item::get(246,0,1), 14 => Item::get(276,0,1)->setCustomName('MysterySword!')];
```
these items will be set on inventory menu

To send inventory menu, create InventoryMenu instance
```php
//use korado531m7\InventoryMenuAPI\inventory\ChestInventory;

$inv = new ChestInventory();
$inv = InventoryMenu::createInventory();
```
(you don't have to write use sentence ChestInventory if you use createInventory function)

then, call send function
```php
$inv->send($player); //$player is player object
```

___

**SET READONLY (WRITABLE) INVENTORY**

To allow to trade, use setReadonly function (default value is true)
```php
$inv->setReadonly(false); //boolean
```

___

**RENAMING INVENTORY MENU NAME**

To change inventory name, call setName function
```php
$inv->setName('WRITE NAME HERE');
```

___

**CHANGING INVENTORY TYPE**

To change inventory type, you need to create each inventory instance, or change the parameter when use createInventory function
```php
//Supported list
const INVENTORY_TYPE_ANVIL = AnvilInventory::class;
const INVENTORY_TYPE_BEACON = BeaconInventory::class;
const INVENTORY_TYPE_BREWING_STAND = BrewingStandInventory::class;
const INVENTORY_TYPE_CHEST = ChestInventory::class;
const INVENTORY_TYPE_DISPENSER = DispenserInventory::class;
const INVENTORY_TYPE_DOUBLE_CHEST = DoubleChestInventory::class;
const INVENTORY_TYPE_DROPPER = DropperInventory::class;
const INVENTORY_TYPE_ENCHANTING_TABLE = EnchantingTableInventory::class;
const INVENTORY_TYPE_HOPPER = HopperInventory::class;
const INVENTORY_TYPE_VILLAGER = VillagerInventory::class;
```

```php
//Example:
$inv = new EnchantingTableInventory();
$inv = InventoryMenu::createInventory(InventoryType::INVENTORY_TYPE_ENCHANTING_TABLE);
```
These constants are written in `korado531m7\InventoryMenuAPI\InventoryType`

___

**HOW TO CLOSE INVENTORY MENU**

To close inventory menu, use doClose function
```php
$inv->doClose($player); //$player is player object
```

___

**Set callback**

you can set callable and will be called when player clicked an item or closed inventory.
to set callback, use setCallable()
the parameters in function is player object, inventory, clicked item
```php
//Ex
$inv->setCallable(function($player, $inventory, $item){
    $player->sendMessage('You clicked '.$item->getName());
});
```
you can select whether clicked item or closed inventory in second parameter.
constant is in `korado531m7\InventoryMenuAPI\inventory\MenuInventory`
```
const CALLBACK_CLICKED = 0;
const CALLBACK_CLOSED = 1;
```

___

**Set task**

Since 3.0.0, you can set taskscheduler to inventory so that you can edit an inventory in spite of opening inventory.
it will be started when player opened an inventory and will be cancelled when closed an inventory
in version 3.0.0, you can use SCHEDULER_REPEATING.
first, define task like
```php
use korado531m7\InventoryMenuAPI\task\InventoryTask;
class TestTask extends InventoryTask{
    public function __construct(){
    }
    
    public function onRun(int $currentTick){
        $this->getInventory()->setItem(mt_rand(0, 5), Item::get(276));
    }
}
```
You can get inventory with getInventory()

then, set this testtask class to Task class in korado531m7\InventoryMenuAPI\task\Task;
```php
$inventoryTask = new TestTask();
$task = new Task();
$task->setInventoryTask($inventoryTask); //inventorytask class
$task->setPeriod(20); //tick
$task->setType(Task::TASK_REPEATING); //type
```
to set another type, use these constant
```
const TASK_NORMAL = 0;
const TASK_REPEATING = 1;
const TASK_DELAYED = 2;
const TASK_DELAYED_REPEATING = 3;
```

to set task, use setTask()
```php
$inv->setTask($task); //$task must be korado531m7\InventoryMenuAPI\task\Task
```

___

**SET RECIPE TO VILLAGER INVENTORY**

Since 3.2.0, you can create villager inventory and set recipe to it.
To make recipe, create TradingRecipe instance and set ingredients to that, then set it to villager inventory with addRecipe().
Here's example:
```php
//use korado531m7\InventoryMenuAPI\inventory\VillagerInventory;
$villagerInventory = new VillagerInventory();

//use korado531m7\InventoryMenuAPI\utils\TradingRecipe;
$recipe = new TradingRecipe();
$recipe->setIngredient(Item::get(Item::DIAMOND));     //at least you must set an ingredient
//$recipe->setIngredient2(Item::get(Item::TRIDENT)); to set two ingredients, use setIngredient2() function
$recipe->setResult(Item::get(Item::ENDER_EYE));       //result item can trade from ingredient

$villagerInventory->addRecipe($recipe); //add recipe to villager inventory
$villagerInventory->send($player); //send to player
```
NOTE: VillagerInventory doesn't support setTask

___

**WRITING CODE IN A ROW**
You can write code in a row.
```php
//Ex1
(new ChestInventory())->setName('Test')->send($player);

//Ex2
InventoryMenu::createInventory()->setName('Test')->send($player);
```

___

### DEALING WITH EVENT
This api will call event and you can use that!

**WHEN CLICK ITEM**

You can use event when player clicked items.
it's InventoryClickEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
```
* `getPlayer()`          - Return Player object who clicked
* `getItem()`            - Return Item which player clicked
* `getInventory()`       - Return Inventory
* `getAction()`          - Return NetworkInventoryAction
* `getTransactionType()` - Return transaction type

___

**WHEN CLOSE INVENTORY MENU**

You can use event when player close inventory.
it's InventoryCloseEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;
```
* `getPlayer()`                     - Return Player object who clicked
* `getInventory()`                  - Return Inventory
* `getWindowId()`                   - Return Window Id
* `setCancelled(bool $value)`       - To cancel, use this     (from Cancellable)
* `isCancelled()`                   - Check whether cancelled (from Cancellable)

___

### REPORTING ISSUES
If you found a bug or issues, please report it at 'issue'
I'll fix that

___

### More Features
I'll make more features if have free time.
