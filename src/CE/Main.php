<?php

declare(strict_types=1);

namespace CE;

use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\{
	Command, CommandSender
};
use pocketmine\item\enchantment\{
	Enchantment as PME, EnchantmentInstance
};
use pocketmine\nbt\tag\{
	CompoundTag, ShortTag, ListTag
};

class Main extends PluginBase{

	private static $instance;

	public function onEnable(): void{
		self::$instance = $this;
		new Enchantment();
	}

	public static function get(): self{
		return self::$instance;
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if($cmd->getName() == "ce"){
			if(count($args) < 3){
				$sender->sendMessage("Usage: /ce <player> <enchantment> <level>");
				return false;
			}
			if(!$this->getServer()->getPlayer($args[0])){
				$sender->sendMessage(C::RED . $args[0] . " cannot be found.");
				return false;
			}

			$player = $this->getServer()->getPlayer($args[0]);
			$inv = $player->getInventory();

			if($inv->getItemInHand() == null){
				$player->sendMessage(C::RED . "Please hold a item.");
				$sender->sendMessage(C::RED . $player->getName() . " was not holding a item.");
				return false;
			}
			if(is_numeric($args[1])){
				$enchantment = PME::getEnchantment((int) $args[1]);
			}else{
				$enchantment = PME::getEnchantmentByName($args[1]);
			}
			// if(!($enchantment instanceof PME)){
			// 	$sender->sendMessage(C::RED . $args[1] . " enchantment cannot be found.");
			// 	return false;
			// }
			if($args[1] > 100){
				$sender->sendMessage(C::RED . "100 is the only enchant.");
				return false;
			}
			if($args[1] < 100){
				$sender->sendMessage(C::RED . "Vanilla Enchantments are not allow.");
				return false;
			}

			$item = $inv->getItemInHand();
			$this->addEnchantment($item, new EnchantmentInstance($enchantment, (int) ($args[2] ?? 1)), $enchantment);
			$inv->setItemInHand($item);
		}
		return true;
	}

	public function addEnchantment(Item $item, EnchantmentInstance $enchantment, $e): void{
		$found = false;
		$ench = $item->getNamedTagEntry(Item::TAG_ENCH);

		if(!($ench instanceof ListTag)){
			$ench = new ListTag(Item::TAG_ENCH, [], NBT::TAG_Compound);
		}else{
			foreach($ench as $k => $entry){
				if($entry->getShort("id") == $enchantment->getId()){
					$nbt = new CompoundTag("", [
						new ShortTag("id", $enchantment->getId()),
						new ShortTag("lvl", $enchantment->getLevel())
					]);
					$ench->set($k, $nbt);
					// if(array_search($item->getLore(), $e->getName() . " " . $enchantment->getLevel()) !== null){
					// 	$item->setCustomName("D");
					// }
					$item->setLore(array_merge([$e->getName() . " " . $enchantment->getLevel()], $item->getLore()));
					$found = true;
					break;
				}
			}
		}

		if(!$found){
			$nbt = new CompoundTag("", [
				new ShortTag("id", $enchantment->getId()),
				new ShortTag("lvl", $enchantment->getLevel())
			]);
			$ench->push($nbt);
			$item->setLore(array_merge([$e->getName() . " " . $enchantment->getLevel()], $item->getLore()));
		}
		$item->setNamedTagEntry($ench);
	}
}