<?php
# SETTINGS #############################################################################
$moduleName = "menu_stat";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "stat_rows" => $prefix . "stat_rows.tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
		"table"=>"pages",
		"select"=>"id, title",
		"where"=>"group_id=0 AND view=1",
		"order"=>"sortfield"
		)
	);
$numRows = $dbc->count; 
if ($numRows > 0) {
	$cur_m = false;
	foreach($rows as $row){
		if($row['id']==PAGE_ID){
			$tpl->assign("STAT_M_CLASS", ' class="current"');
			$cur_m = true;
		}
		else{
			$tpl->assign("STAT_M_CLASS", '');
		}
		$url = 'system.php?menu='.$row['id'];
		$tpl->assign("STAT_M_URL", $url);
		$tpl->assign("STAT_M_TITLE", $row['title']);
		
		if($cur_m){
			$tpl->assign("CUR_MM_SET", '');
			$tpl->assign("CUR_MM_STAT", '  current');
			$tpl->assign("CUR_MM_USERS", '');
			$tpl->assign("CUR_MM_MODULES", '');
		}
		else{
			$tpl->assign("CUR_MM_STAT", '');
		}
		
		$tpl->parse("STAT_ROWS", ".".$moduleName."stat_rows");
	}
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
	$tpl->assign(strtoupper($moduleName), "");
}






?>