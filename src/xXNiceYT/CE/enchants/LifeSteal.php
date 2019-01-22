<?php

declare(strict_types=1);

namespace xXNiceYT\CE\enchants;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\{
	EntityDamageEvent, EntityDamageByEntityEvent
};

use xXNiceYT\CE\Main;

class LifeSteal implements Listener{

	public function __construct(){
		Main::get()->getServer()->getPluginManager()->registerEvents($this, Main::get());
	}

	public function onDamage(EntityDamageEvent $e): void{
		if($e instanceof EntityDamageByEntityEvent){
			$damager = $e->getDamager();

			if(!$damager instanceof Player) return;
			if($damager->getInventory()->getItemInHand()->getEnchantment(100)){
				$damager->setHealth($damager->getMaxHealth());
			}
		}
	}
}
