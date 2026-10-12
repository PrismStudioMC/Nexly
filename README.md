<h1 align="center">
    <img src="https://github.com/Zwuiix-cmd/Nexly/blob/595046fc2733880a9f69876e5a3221ba1fcea962/icon.png?raw=true" alt="Logo Nexly" width="150" />
    <br>
    Nexly
</h1>

<p align="center">
    <a><img src="https://img.shields.io/badge/php-^8.2.0-informational" alt="Version"></a>
    <a href="https://github.com/Zwuiix-cmd/Nexly/latest"><img alt="GitHub release (latest SemVer)" src="https://img.shields.io/github/v/release/Zwuiix-cmd/Nexly?label=release&sort=semver"></a>
    <a href="https://discord.gg/KCm6rUFXbJ"><img src="https://img.shields.io/badge/Discord-Join-7289da?logo=discord&logoColor=white" alt="Discord"></a>
</p>

<p align="center">
    <a href="https://github.com/Zwuiix-cmd/Nexly/graphs/contributors">
        <img src="https://contrib.rocks/image?repo=Zwuiix-cmd/Nexly&max=500" alt="Contributors" />
    </a>
</p>

---

**Nexly** is a framework designed to make creating custom blocks and items on [PocketMine-MP](https://github.com/pmmp/pocketmine-mp) as simple as possible.

The classic approach forces you to manually build NBT trees and assemble Bedrock client `components` yourself. Nexly completely automates this process to provide you with a clean, object-oriented PHP API.

**How does it work?**
The process boils down to a simple declaration. You register the type of item or block you want to create via a PHP class. The framework then takes care of generating, formatting, and automatically assigning all the necessary `components` in the background.

**What this means for you:**

* **Zero manual NBT:** No more struggling with complex configurations, traits, or `permutations`.
* **Seamless Component management:** You barely have to touch `components` anymore. The tool handles the translation between your code and what the game expects.
* **Focus on the essentials:** You enjoy a clean codebase, allowing you to dedicate your time to what really matters: developing your server's gameplay.

## Why choose Nexly?

### Features

| Feature             | Nexly  | Symply | Customies |
|:--------------------|:------:|:------:|:---------:|
| **Block Traits**    | **✅** | **✅** |  **❌**   |
| **Block Tags**      | **✅** | **✅** |  **❌**   |
| **Item Legacy**     | **✅** | **❌** |  **❌**   |
| **Item DataDriven** | **✅** | **✅** |  **✅**   |

#### Item Legacy

While other frameworks only support `DataDriven` items, Nexly goes a step further by also supporting `Item Legacy`.

**Why does this matter?**
Currently, purely `DataDriven` items suffer from a notorious **Bedrock** client bug: the swing/hit animation often glitches or doesn't play at all when hitting entities. By allowing you to use `Item Legacy`, Nexly completely bypasses this issue. Your custom weapons and tools will have smooth, `vanilla-like` animations, offering a flawless combat experience for your players.

### Components
| Type      | Nexly  | Symply | Customies |
|:----------|:------:|:------:|:---------:|
| **Block** | **41** |   13   |    12     |
| **Item**  | **58** |   32   |    33     |

### Supported Block Types

Creating complex directional blocks like **Stairs**, **Slabs**, or **Fences** is traditionally a nightmare involving dozens of `permutations`, rotation math, and connection logic.

While other frameworks leave you alone in the dark to figure out this logic yourself, Nexly brings all these complex geometries **out of the box**. Just register your `Stair`, and the framework automatically calculates all its facing directions and shapes.

| Category | Examples | Nexly | Symply | Customies |
| --- | --- | --- | --- | --- |
| **Complex Geometries** | `Stair`, `Slab`, `Fence`, `Wall` | **✅** | **❌** | **❌** |
| **Advanced Utilities** | `Furnace`, `Hopper`, `Lever` | **✅** | **❌** | **❌** |
| **Advanced Nature** | `Wood`, `Leaves`, `Flower`, `Cactus` | **✅** | **❌** | **❌** |
| **Advanced Growth** | `NetherWart`, `Sapling` | **✅** | **❌** | **❌** |
| **Basic Farming** | `Crops/Ageable` | **✅** | **✅** | **❌** |
| **Basic Interactables** | `Door` | **✅** | **✅** | **❌** |

**Full list of natively supported types:**
`Stair`, `Slab`, `Wall`, `Fence`, `FenceGate`, `Glass`, `GlassPane`, `Door`, `Trapdoor`, `Lever`, `Furnace`, `Hopper`, `Ladder`, `Crops/Ageable`, `NetherWart`, `Wood`, `Leaves`, `Sapling`, `Farmland`, `GrassPath`, `Flower`, `Cactus`, `Slime`, `Colored (Wool, Concrete...)`.

---

## Installation

Nexly operates as a core framework. It must be installed on your server as a plugin, and required in your project as a dependency.

### 1. Server Installation
Download the latest `Nexly.phar` from the [Releases](https://github.com/Zwuiix-cmd/Nexly/releases) and place it inside your server's `plugins/` directory.

### 2. Developer Setup
To access Nexly's API and register your custom items/blocks in your own plugin, require it via Composer:

```bash
composer require zwuiix/nexly
```

## Quick Start

Forget about NBT compounds and client components. Nexly leverages PMMP's native ecosystem to make registration a breeze.

### Creating a Custom Crop (🥕)
Because Nexly handles all the complex `traits` and `components`, you can simply extend standard PMMP logic. Here, the framework automatically manages the growth stages!
```php
use pocketmine\item\Item;
use pocketmine\block\Carrot;
use pocketmine\block\utils\FortuneDropHelper;
use Nexly\Item\LegacyItemBlockTrait;

class RubyCarrot extends Carrot
{
    use LegacyItemBlockTrait;
    
    /**
     * @param Item $item
     * @return array
     */
    public function getDropsForCompatibleTool(Item $item) : array
    {
        return [
            $this->asItem()->setCount(
                $this->age >= self::MAX_AGE ? FortuneDropHelper::binomial($item, 1) : 1
            )
        ];
    }
}
```

### Registering Items & Blocks
Listen to Nexly's events and register your classes. Notice how you don't need to pass any geometry or permutation logic for complex blocks like Slab, Stair, or FenceGate.
```php
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockTypeInfo;
use pocketmine\item\ToolTier;
use Nexly\Event\NexlyEventManager;
use Nexly\Event\ItemRegistryEvent;
use Nexly\Event\BlockRegistryEvent;

class MainExample extends PluginBase
{
    protected function onLoad(): void
    {
        // 1. Registering an Item
        NexlyEventManager::getInstance()->listen(ItemRegistryEvent::class, static function (ItemRegistryEvent $ev): void {
            $ev->register("nexly:ruby", new Item(new ItemIdentifier(ItemTypeIds::newId()), "Ruby"));
        });

        // 2. Registering Blocks (Solid, Crops, and Complex Geometries)
        NexlyEventManager::getInstance()->listen(BlockRegistryEvent::class, static function (BlockRegistryEvent $ev): void {
            $breakInfo = static fn() => new BlockTypeInfo(BlockBreakInfo::pickaxe(5.0, ToolTier::IRON, 30.0));

            $ev->register("nexly:ruby_carrot", static fn(int $id) => new RubyCarrot(new BlockIdentifier($id), "Ruby Carrot", $breakInfo()));
            $ev->register("nexly:ruby_block", static fn(int $id) => new Block(new BlockIdentifier($id), "Ruby Block", $breakInfo()));
            $ev->register("nexly:ruby_slab", static fn(int $id) => new Slab(new BlockIdentifier($id), "Ruby Slab", $breakInfo()));
            $ev->register("nexly:ruby_stair", static fn(int $id) => new Stair(new BlockIdentifier($id), "Ruby Stair", $breakInfo()));
            $ev->register("nexly:ruby_fence", static fn(int $id) => new NexlyFence(new BlockIdentifier($id), "Ruby Fence", $breakInfo()));
            $ev->register("nexly:ruby_fence_gate", static fn(int $id) => new FenceGate(new BlockIdentifier($id), "Ruby Fence", $breakInfo(), WoodType::OAK));
        });
    }
}
```

## Resource Pack & Textures

Nexly handles the server-side logic and Bedrock protocol components. To display your custom items and blocks in-game, you still need to provide a client-side Resource Pack containing your textures and models matching your identifiers (e.g., `nexly:ruby`).

---

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This project is licensed under the [Apache License 2.0](LICENSE).

---

<a href="https://www.star-history.com/?repos=Zwuiix-cmd%2FNexly%2CCustomiesDevs%2FCustomies%2CAID-LEARNING%2FSymplyPlugin&type=timeline&logscale=&legend=top-left">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://api.star-history.com/svg?repos=Zwuiix-cmd/Nexly,CustomiesDevs/Customies,AID-LEARNING/SymplyPlugin&type=Date&theme=dark" />
    <source media="(prefers-color-scheme: light)" srcset="https://api.star-history.com/svg?repos=Zwuiix-cmd/Nexly,CustomiesDevs/Customies,AID-LEARNING/SymplyPlugin&type=Date" />
    <img alt="Star History Chart" src="https://api.star-history.com/svg?repos=Zwuiix-cmd/Nexly,CustomiesDevs/Customies,AID-LEARNING/SymplyPlugin&type=Date" />
  </picture>
</a>
