<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

use MallardDuck\MtgCardsSdk\Generator\Actions\AbstractGenerateEnumAction;

/**
 * @see GenerateCardSupertypeAction
 */
enum CardSupertype: string
{
	case Basic = 'basic';
	case Host = 'host';
	case Legendary = 'legendary';
	case Ongoing = 'ongoing';
	case Snow = 'snow';
	case World = 'world';

	public function label(): string
	{
		return static::getLabel($this);
	}


	public static function getLabel(self $value): string
	{
		return match ($value) {
			CardSupertype::Basic => "Basic",
			CardSupertype::Host => "Host",
			CardSupertype::Legendary => "Legendary",
			CardSupertype::Ongoing => "Ongoing",
			CardSupertype::Snow => "Snow",
			CardSupertype::World => "World",
		}
		;
	}
}
