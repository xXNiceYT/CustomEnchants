<?php

declare(strict_types=1);

namespace xXNiceYT\CE;

use pocketmine\item\enchantment\Enchantment as PMEnchantment;

use xXNiceYT\CE\enchants\LifeSteal;

class Enchantment{

	public const LIFESTEAL = 100;

	public function __construct(){
		$this->init();
	}

	public function init(){
		PMEnchantment::registerEnchantment(new PMEnchantment(self::LIFESTEAL, "Lifesteal", PMEnchantment::RARITY_COMMON, PMEnchantment::SLOT_SWORD, PMEnchantment::SLOT_NONE, 5));
		new LifeSteal();
	}
}
