<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

enum SetType: string
{
	case Alchemy = 'alchemy';
	case Archenemy = 'archenemy';
	case Arsenal = 'arsenal';
	case Box = 'box';
	case Commander = 'commander';
	case Core = 'core';
	case DraftInnovation = 'draft_innovation';
	case DuelDeck = 'duel_deck';
	case Expansion = 'expansion';
	case FromTheVault = 'from_the_vault';
	case Funny = 'funny';
	case Masterpiece = 'masterpiece';
	case Masters = 'masters';
	case Memorabilia = 'memorabilia';
	case Planechase = 'planechase';
	case PremiumDeck = 'premium_deck';
	case Promo = 'promo';
	case Spellbook = 'spellbook';
	case Starter = 'starter';
	case Token = 'token';
	case TreasureChest = 'treasure_chest';
	case Vanguard = 'vanguard';

	public function label(): string
	{
		return static::getLabel($this);
	}


	public static function getLabel(self $value): string
	{
		return match ($value) {
			static::Alchemy => 'Alchemy',
			static::Archenemy => 'Archenemy',
			static::Arsenal => 'Arsenal',
			static::Box => 'Box',
			static::Commander => 'Commander',
			static::Core => 'Core',
			static::DraftInnovation => 'Draft innovation',
			static::DuelDeck => 'Duel deck',
			static::Expansion => 'Expansion',
			static::FromTheVault => 'From the vault',
			static::Funny => 'Funny',
			static::Masterpiece => 'Masterpiece',
			static::Masters => 'Masters',
			static::Memorabilia => 'Memorabilia',
			static::Planechase => 'Planechase',
			static::PremiumDeck => 'Premium deck',
			static::Promo => 'Promo',
			static::Spellbook => 'Spellbook',
			static::Starter => 'Starter',
			static::Token => 'Token',
			static::TreasureChest => 'Treasure chest',
			static::Vanguard => 'Vanguard',
		}
		;
	}
}
