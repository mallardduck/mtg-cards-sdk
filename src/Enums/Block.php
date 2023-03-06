<?php declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

/**
 * @see \MallardDuck\MtgCardsSdk\Generator\Actions\GenerateBlocksAction
 */
enum Block: string
{
	case Alara = 'alara';
	case Alchemy2022 = 'alchemy2022';
	case Alchemy2023 = 'alchemy2023';
	case Amonkhet = 'amonkhet';
	case ArenaLeague = 'arena_league';
	case BattleForZendikar = 'battle_for_zendikar';
	case Commander = 'commander';
	case Conspiracy = 'conspiracy';
	case CoreSet = 'core_set';
	case FridayNightMagic = 'friday_night_magic';
	case GuildsOfRavnica = 'guilds_of_ravnica';
	case HeroesOfTheRealm = 'heroes_of_the_realm';
	case IceAge = 'ice_age';
	case Innistrad = 'innistrad';
	case InnistradDoubleFeature = 'innistrad_double_feature';
	case Invasion = 'invasion';
	case Ixalan = 'ixalan';
	case JudgeGiftCards = 'judge_gift_cards';
	case Kaladesh = 'kaladesh';
	case Kamigawa = 'kamigawa';
	case KhansOfTarkir = 'khans_of_tarkir';
	case Lorwyn = 'lorwyn';
	case MagicPlayerRewards = 'magic_player_rewards';
	case Masques = 'masques';
	case Mirage = 'mirage';
	case Mirrodin = 'mirrodin';
	case Odyssey = 'odyssey';
	case Onslaught = 'onslaught';
	case Ravnica = 'ravnica';
	case ReturnToRavnica = 'return_to_ravnica';
	case ScarsOfMirrodin = 'scars_of_mirrodin';
	case Shadowmoor = 'shadowmoor';
	case ShadowsOverInnistrad = 'shadows_over_innistrad';
	case Tempest = 'tempest';
	case Theros = 'theros';
	case TimeSpiral = 'time_spiral';
	case Urza = 'urza';
	case Zendikar = 'zendikar';

	public function label(): string
	{
		return static::getLabel($this);
	}


	public static function getLabel(self $value): string
	{
		return match ($value) {
			Block::Alara => "Alara",
			Block::Alchemy2022 => "Alchemy 2022",
			Block::Alchemy2023 => "Alchemy 2023",
			Block::Amonkhet => "Amonkhet",
			Block::ArenaLeague => "Arena League",
			Block::BattleForZendikar => "Battle for Zendikar",
			Block::Commander => "Commander",
			Block::Conspiracy => "Conspiracy",
			Block::CoreSet => "Core Set",
			Block::FridayNightMagic => "Friday Night Magic",
			Block::GuildsOfRavnica => "Guilds of Ravnica",
			Block::HeroesOfTheRealm => "Heroes of the Realm",
			Block::IceAge => "Ice Age",
			Block::Innistrad => "Innistrad",
			Block::InnistradDoubleFeature => "Innistrad: Double Feature",
			Block::Invasion => "Invasion",
			Block::Ixalan => "Ixalan",
			Block::JudgeGiftCards => "Judge Gift Cards",
			Block::Kaladesh => "Kaladesh",
			Block::Kamigawa => "Kamigawa",
			Block::KhansOfTarkir => "Khans of Tarkir",
			Block::Lorwyn => "Lorwyn",
			Block::MagicPlayerRewards => "Magic Player Rewards",
			Block::Masques => "Masques",
			Block::Mirage => "Mirage",
			Block::Mirrodin => "Mirrodin",
			Block::Odyssey => "Odyssey",
			Block::Onslaught => "Onslaught",
			Block::Ravnica => "Ravnica",
			Block::ReturnToRavnica => "Return to Ravnica",
			Block::ScarsOfMirrodin => "Scars of Mirrodin",
			Block::Shadowmoor => "Shadowmoor",
			Block::ShadowsOverInnistrad => "Shadows over Innistrad",
			Block::Tempest => "Tempest",
			Block::Theros => "Theros",
			Block::TimeSpiral => "Time Spiral",
			Block::Urza => "Urza",
			Block::Zendikar => "Zendikar",
		}
		;
	}
}
