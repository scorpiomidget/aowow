<?php
require_once('includes/allachievements.php');

$smarty->config_load($conf_file, 'achievement');

$category = intval($podrazdel);
$cache_str = $category ? $category : 'x';

if(!$achievements = load_cache(24, $cache_str))
{
	unset($achievements);

	$rows = $DB->select('
			SELECT a.id, a.faction, a.name_loc?d AS name, a.description_loc?d AS description, a.category, a.points, s.iconname, z.areatableID
			FROM ?_spellicons s, ?_achievement a
			LEFT JOIN (?_zones z) ON a.map != -1 AND a.map = z.mapID
			WHERE
				a.icon = s.id
				{ AND a.category = ? }
			GROUP BY a.id
			ORDER BY a.`order` ASC
		',
		$_SESSION['locale'],
		$_SESSION['locale'],
		$category ? $category : DBSIMPLE_SKIP
	);

	if($rows)
	{
		$achievements = array();
		$achievements['data'] = array();
		foreach($rows as $row)
			$achievements['data'][] = achievementinfo2($row);

		if($category)
		{
			$catrow = $DB->selectRow('
					SELECT c1.id, c1.name_loc?d AS name, c2.id AS id2
					FROM ?_achievementcategory c1
					LEFT JOIN (?_achievementcategory c2) ON c1.parentAchievement != -1 AND c1.parentAchievement = c2.id
					WHERE
						c1.id = ?
				',
				$_SESSION['locale'],
				$category
			);

			if($catrow)
			{
				$achievements['category1'] = $catrow['id'];
				$achievements['category2'] = $catrow['id2'];
				$achievements['category'] = $catrow['name'];
			}
		}

		save_cache(24, $cache_str, $achievements);
	}
}
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => ($achievements['category']?($achievements['category'].' - '):'').$smarty->get_config_vars('Achievements'),
	'tab' => 0,
	'type' => 9,
	'typeid' => 0,
	'path' => '[0, 9'.($achievements['category2']?(', '.$achievements['category2']):'').($achievements['category1']?(', '.$achievements['category1']):'').']',
);
$smarty->assign('page', $page);

// ���������� ���������� mysql ��������
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('allachievements', $allachievements);
$smarty->assign('achievements', $achievements);
// ��������� ��������
$smarty->display('achievements.tpl');
?>