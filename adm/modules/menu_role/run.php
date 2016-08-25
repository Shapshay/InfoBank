<?php
# SETTINGS #############################################################################
$moduleName = "menu_role";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "role_rows" => $prefix . "role_rows.tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
		"table"=>"pages",
		"select"=>"id, title",
		"where"=>"group_id=5 AND view=1",
		"order"=>"sortfield"
		)
	);
$numRows = $dbc->count; 
if ($numRows > 0) {
	$cur_m = false;
	foreach($rows as $row){
		if($row['id']==PAGE_ID){
			$tpl->assign("ROLE_M_CLASS", ' class="current"');
			$cur_m = true;
		}
		else{
			$tpl->assign("ROLE_M_CLASS", '');
		}
		$url = 'system.php?menu='.$row['id'];
		$tpl->assign("ROLE_M_URL", $url);
		$tpl->assign("ROLE_M_TITLE", $row['title']);
		
		if($cur_m){
			$tpl->assign("CUR_MM_SET", '');
			$tpl->assign("CUR_MM_STAT", '');
			$tpl->assign("CUR_MM_USERS", '  current');
			$tpl->assign("CUR_MM_MODULES", '');
		}
		else{
			$tpl->assign("CUR_MM_USERS", '');
		}
		
		$tpl->parse("ROLE_ROWS", ".".$moduleName."role_rows");
	}
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
	$tpl->assign(strtoupper($moduleName), "");
}






?>