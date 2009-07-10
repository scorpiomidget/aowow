<?php
define('AOWOW_REVISION', 1);

error_reporting(2039);
ini_set('serialize_precision', 4);
session_start();

global $languages;
$languages = array(
	0 => 'enus',
	8 => 'ruru',
);
function checklocale()
{
	global $AoWoWconf, $languages;
	if(!isset($_SESSION['locale']) || !in_array($_SESSION['locale'], array_keys($languages)))
		$_SESSION['locale'] = $AoWoWconf['locale'];
}
checklocale();
// ��� ������ ���� ����� checklocale()
require_once('includes/alllocales.php');

function str_normalize($str)
{
	return str_replace("'", "\'", $str);
}
function point_delim(&$str, &$a, &$b)
{
	@list($a, $b) = explode('.', $str, 2);
	return;
}
function sign($val)
{
	if($val > 0) return 1;
	if($val < 0) return -1;
	if($val == 0) return 0;
}
// ������ ���������� (������� �����)
define('CLASS_WARRIOR', 1);
define('CLASS_PALADIN', 2);
define('CLASS_HUNTER', 4);
define('CLASS_ROGUE', 8);
define('CLASS_PRIEST', 16);
define('CLASS_SHAMAN', 64);
define('CLASS_MAGE', 128);
define('CLASS_WARLOCK', 256);
define('CLASS_DRUID', 1024);

// ������ ���������� (�����)
$classes = array(
	1 => LOCALE_WARRIOR,
	2 => LOCALE_PALADIN,
	3 => LOCALE_HUNTER,
	4 => LOCALE_ROGUE,
	5 => LOCALE_PRIEST,
	6 => LOCALE_DEATH_KNIGHT,
	7 => LOCALE_SHAMAN,
	8 => LOCALE_MAGE,
	9 => LOCALE_WARLOCK,
	11 => LOCALE_DRUID
);

define('RACE_HUMAN', 1);
define('RACE_ORC', 2);
define('RACE_DWARF', 4);
define('RACE_NIGHTELF', 8);
define('RACE_UNDEAD', 16);
define('RACE_TAUREN', 32);
define('RACE_GNOME', 64);
define('RACE_TROLL', 128);
define('RACE_BLOODELF', 512);
define('RACE_DRAENEI', 1024);

// ���� ��������
global $types;
$types = array(
	1 => 'npc',
	2 => 'object',
	3 => 'item',
	4 => 'itemset',
	5 => 'quest',
	6 => 'spell',
	7 => 'zone',
	8 => 'faction'
);

// ��������� �� ���������
$reputations = array(
	1 => LOCALE_NEUTRAL,
	3000 => LOCALE_FRIENDLY,
	9000 => LOCALE_HONORED,
	21000 => LOCALE_REVERED,
	42000 => LOCALE_EXALTED
);
// TODO: �������� ����� �������������� ������ � ������ �������
function sec_to_time($secs)
{
	$time = array();
	if($secs>=3600)
	{
		$time['h'] = floor($secs/3600);
		$secs = $secs - $time['h']*3600;
	}
	if($secs>=60)
	{
		$time['m'] = floor($secs/60);
		$secs = $secs - $time['m']*60;
	}
	if($secs>0)
		$time['s'] = $secs;
	return $time;
}
function money2coins($money)
{
	$coins = array();

	if($money >= 10000)
	{
		$coins['moneygold'] = floor($money / 10000);
		$money = $money - $coins['moneygold']*10000;
	}

	if($money >= 100)
	{
		$coins['moneysilver'] = floor($money / 100);
		$money = $money - $coins['moneysilver']*100;
	}

	if($money > 0)
		$coins['moneycopper'] = $money;

	return $coins;
}
function divideThousand($val)
{
	$thousands = array();

	do
	{
		array_unshift($thousands, $val % 1000);
		$val = floor($val / 1000);
	} while($val);

	return implode(',', $thousands);
}
// ������, ��� ������� ������������� ����
function classes($class)
{
	$tmp = '';
	if($class & CLASS_WARRIOR)
		$tmp = LOCALE_WARRIOR;
	if($class & CLASS_PALADIN)
		if($tmp) $tmp = $tmp.', '.LOCALE_PALADIN; else $tmp = LOCALE_PALADIN;
	if($class & CLASS_HUNTER)
		if($tmp) $tmp = $tmp.', '.LOCALE_HUNTER; else $tmp = LOCALE_HUNTER;
	if($class & CLASS_ROGUE)
		if($tmp) $tmp = $tmp.', '.LOCALE_ROGUE; else $tmp = LOCALE_ROGUE;
	if($class & CLASS_PRIEST)
		if($tmp) $tmp = $tmp.', '.LOCALE_PRIEST; else $tmp = LOCALE_PRIEST;
	if($class & CLASS_SHAMAN)
		if($tmp) $tmp = $tmp.', '.LOCALE_SHAMAN; else $tmp = LOCALE_SHAMAN;
	if($class & CLASS_MAGE)
		if($tmp) $tmp = $tmp.', '.LOCALE_MAGE; else $tmp = LOCALE_MAGE;
	if($class & CLASS_WARLOCK)
		if($tmp) $tmp = $tmp.', '.LOCALE_WARLOCK; else $tmp = LOCALE_WARLOCK;
	if($class & CLASS_DRUID)
		if($tmp) $tmp = $tmp.', '.LOCALE_DRUID; else $tmp = LOCALE_DRUID;
	if($tmp == LOCALE_WARRIOR.', '.LOCALE_PALADIN.', '.LOCALE_HUNTER.', '.LOCALE_ROGUE
		.', '.LOCALE_PRIEST.', '.LOCALE_SHAMAN.', '.LOCALE_MAGE.', '.LOCALE_WARLOCK.', '.LOCALE_DRUID)
		return;
	else
		return $tmp;
}
function races($race)
{
	// ������� ��������:
	if($race == RACE_HUMAN|RACE_ORC|RACE_DWARF|RACE_NIGHTELF|RACE_UNDEAD|RACE_TAUREN|RACE_GNOME|RACE_TROLL|RACE_BLOODELF|RACE_DRAENEI || $race == 0)
		return array('side' => 3, 'name' => LOCALE_BOTH);
	elseif($race == RACE_ORC|RACE_UNDEAD|RACE_TAUREN|RACE_TROLL|RACE_BLOODELF)
		return array('side' => 2, 'name' => LOCALE_HORDE);
	elseif($race == RACE_HUMAN|RACE_DWARF|RACE_NIGHTELF|RACE_GNOME|RACE_DRAENEI)
		return array('side' => 1, 'name' => LOCALE_ALLIANCE);
	else
	{
		$races = array('name' => '', 'side' => 0);
		if($race & RACE_HUMAN)
		{
			(($races['side']==2) && ($races['side']==3))? $races['side']=3 : $races['side']=1;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_HUMAN;
		}
		if($race & RACE_ORC)
		{
			(($races['side']==1) && ($races['side']==3))? $races['side']=3 : $races['side']=2;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_ORC;
		}
		if($race & RACE_DWARF)
		{
			(($races['side']==2) && ($races['side']==3))? $races['side']=3 : $races['side']=1;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_DWARF;
		}
		if($race & RACE_NIGHTELF)
		{
			(($races['side']==2) && ($races['side']==3))? $races['side']=3 : $races['side']=1;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_NIGHT_ELF;
		}
		if($race & RACE_UNDEAD)
		{
			(($races['side']==1) && ($races['side']==3))? $races['side']=3 : $races['side']=2;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_UNDEAD;
		}
		if($race & RACE_TAUREN)
		{
			(($races['side']==1) && ($races['side']==3))? $races['side']=3 : $races['side']=2;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_TAUREN;
		}
		if($race & RACE_GNOME)
		{
			(($races['side']==2) && ($races['side']==3))? $races['side']=3 : $races['side']=1;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_GNOME;
		}
		if($race & RACE_TROLL)
		{
			(($races['side']==1) && ($races['side']==3))? $races['side']=3 : $races['side']=2;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_TROLL;
		}
		if($race & RACE_BLOODELF)
		{
			(($races['side']==1) && ($races['side']==3))? $races['side']=3 : $races['side']=2;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_BLOOD_ELF;
		}
		if($race & RACE_DRAENEI)
		{
			(($races['side']==2) && ($races['side']==3))? $races['side']=3 : $races['side']=1;
			if($races['name']) $races['name'] .= ', '; $races['name'] .= LOCALE_DRAENEI;
		}
		return $races;
	}
}
function sum_subarrays_by_key( $tab, $key ) {
	$sum = 0;
	foreach($tab as $sub_array) {
		$sum += $sub_array[$key];
	}
	return $sum;
}
// ����������� // PRECACHING
/*
���������� �����:
=========================
cache_delete_timestamp
serialized data
serialized allitems
serialized allspells
=========================
*/
$cache_types = array(
	1	=> 'npc_page',
	2	=> 'npc_listing',

	3	=> 'object_page',
	4	=> 'object_listing',

	5	=> 'item_page',
	6	=> 'item_tooltip',
	7	=> 'item_listing',

	8	=> 'itemset_page',
	9	=> 'itemset_listing',

	10	=> 'quest_page',
	11	=> 'quest_tooltip',
	12	=> 'quest_listing',

	13	=> 'spell_page',
	14	=> 'spell_tooltip',
	15	=> 'spell_listing',

	16	=> 'zone_page',
	17	=> 'zone_listing',

	18	=> 'faction_page',
	19	=> 'faction_listing'
);
function save_cache($type, $type_id, $data, $prefix = '')
{
	global $cache_types, $allitems, $allspells, $AoWoWconf, $exdata, $zonedata;

	if($AoWoWconf['debug'])
		return;

	$type_str = $cache_types[$type];

	$cache_data = '';

	if(empty($type_str))
		return false;

	// {$type_str}_{$type_id}.aww
	$file = $prefix.'cache/mangos/'.$type_str.'_'.$type_id.'_'.$_SESSION['locale'].'.aww';

	$time = time()+$AoWoWconf['aowow']['cache_time'];

	if(!$file)
		return false;

	// ���������� ���� � ������� � ����
	$cache_data .= $time.' '.AOWOW_REVISION;
	$cache_data .= "\n".serialize($data)."\n";

	$cache_data .= serialize($allitems);
	$cache_data .= "\n";
	$cache_data .= serialize($allspells);

	file_put_contents($file, $cache_data);
	
	return true;
}
function load_cache($type, $type_id, $prefix = '')
{
	global $cache_types, $smarty, $allitems, $allspells, $exdata, $zonedata, $AoWoWconf;

	if($AoWoWconf['debug'])
		return false;

	$type_str = $cache_types[$type];

	if(empty($type_str))
		return false;

	$data = @file_get_contents($prefix.'cache/mangos/'.$type_str.'_'.$type_id.'_'.$_SESSION['locale'].'.aww');
	if(!$data)
		return false;

	$data = explode("\n", $data);

	@list($time, $rev) = explode(' ', $data[0]);
	if($time < time() || $rev < AOWOW_REVISION)
		return false;

	if($data[2])
		$allitems = unserialize($data[2]);
	if($data[3])
		$allspells = unserialize($data[3]);

	return unserialize($data[1]);
}
function SideByRace($race)
{
	switch ($race)
	{
		case '0':
			// ��� ����?
			return 3;
		case '690':
			// ����?
			return 2;
		case '1101':
			// ������?
			return 1;
		default:
			return 0;
	}
}
function ajax_str_normalize($string)
{
	return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
}
?>