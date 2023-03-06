<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

/**
 * @see \MallardDuck\MtgCardsSdk\Generator\Actions\GenerateCardBorderColorAction
 */
enum CardBorderColor: string
{
	case Black = 'black';
	case Borderless = 'borderless';
	case Gold = 'gold';
	case Silver = 'silver';
	case White = 'white';

	public function label(): string
	{
		return static::getLabel($this);
	}


	public static function getLabel(self $value): string
	{
		return match ($value) {
			CardBorderColor::Black => "black",
			CardBorderColor::Borderless => "borderless",
			CardBorderColor::Gold => "gold",
			CardBorderColor::Silver => "silver",
			CardBorderColor::White => "white",
		}
		;
	}
}
