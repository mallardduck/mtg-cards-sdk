<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

use MallardDuck\MtgCardsSdk\Generator\Actions\AbstractGenerateEnumAction;

/**
 * @see GenerateCardTypeAction
 */
enum CardType: string
{
	case Artifact = 'artifact';
	case Conspiracy = 'conspiracy';
	case Creature = 'creature';
	case Eaturecray = 'eaturecray';
	case Elemental = 'elemental';
	case Enchantment = 'enchantment';
	case Hero = 'hero';
	case Instant = 'instant';
	case Land = 'land';
	case Phenomenon = 'phenomenon';
	case Plane = 'plane';
	case Planeswalker = 'planeswalker';
	case Scariest = 'scariest';
	case Scheme = 'scheme';
	case Sorcery = 'sorcery';
	case Stickers = 'stickers';
	case Summon = 'summon';
	case Tribal = 'tribal';
	case Universewalker = 'universewalker';
	case Vanguard = 'vanguard';

	public function label(): string
	{
		return static::getLabel($this);
	}


	public static function getLabel(self $value): string
	{
		return match ($value) {
			CardType::Artifact => "Artifact",
			CardType::Conspiracy => "Conspiracy",
			CardType::Creature => "Creature",
			CardType::Eaturecray => "Eaturecray",
			CardType::Elemental => "Elemental",
			CardType::Enchantment => "Enchantment",
			CardType::Hero => "Hero",
			CardType::Instant => "Instant",
			CardType::Land => "Land",
			CardType::Phenomenon => "Phenomenon",
			CardType::Plane => "Plane",
			CardType::Planeswalker => "Planeswalker",
			CardType::Scariest => "Scariest",
			CardType::Scheme => "Scheme",
			CardType::Sorcery => "Sorcery",
			CardType::Stickers => "Stickers",
			CardType::Summon => "Summon",
			CardType::Tribal => "Tribal",
			CardType::Universewalker => "Universewalker",
			CardType::Vanguard => "Vanguard",
		}
		;
	}
}
