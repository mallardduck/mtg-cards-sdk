<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

/**
 * @see \MallardDuck\MtgCardsSdk\Generator\Actions\GenerateSetTypeAction
 */
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
			SetType::Alchemy => "Alchemy",
			SetType::Archenemy => "Archenemy",
			SetType::Arsenal => "Arsenal",
			SetType::Box => "Box",
			SetType::Commander => "Commander",
			SetType::Core => "Core",
			SetType::DraftInnovation => "Draft innovation",
			SetType::DuelDeck => "Duel deck",
			SetType::Expansion => "Expansion",
			SetType::FromTheVault => "From the vault",
			SetType::Funny => "Funny",
			SetType::Masterpiece => "Masterpiece",
			SetType::Masters => "Masters",
			SetType::Memorabilia => "Memorabilia",
			SetType::Planechase => "Planechase",
			SetType::PremiumDeck => "Premium deck",
			SetType::Promo => "Promo",
			SetType::Spellbook => "Spellbook",
			SetType::Starter => "Starter",
			SetType::Token => "Token",
			SetType::TreasureChest => "Treasure chest",
			SetType::Vanguard => "Vanguard",
		}
		;
	}
}
