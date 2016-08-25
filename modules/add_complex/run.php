<?php
# SETTINGS #############################################################################
$moduleName = "add_complex";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################

$tpl->assign("DATE_NOW", date("H:i"));

$rows2 = $dbc->dbselect(array(
		"table"=>"tasks",
		"select"=>"*"
	)
);
$art_sel = '';
foreach($rows2 as $row2){
	$art_sel.= '<div id="'.$row2['id'].'" class="task task_div"><aside class="widget">'.$row2['title'].'</aside></div>';
}
$tpl->assign("TASKS_SEL", $art_sel);

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>