<?php
# SETTINGS #############################################################################
$moduleName = "add_tasks";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################

$rows2 = $dbc->dbselect(array(
	"table"=>"chapters",
	"select"=>"*"
));
$ch_sel = '';
foreach($rows2 as $row2){
	$ch_sel.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
}
$tpl->assign("ART_CH", $ch_sel);

$rows2 = $dbc->dbselect(array(
		"table"=>"articles",
		"select"=>"*",
		"where"=>"page_id = 2181 AND ch_id<>0"
	)
);
$art_sel = '';
foreach($rows2 as $row2){
	$art_sel.= '<div id="'.$row2['id'].'" class="task task_div"><aside class="widget">'.$row2['title'].'</aside></div>';
}
$tpl->assign("ART_SEL", $art_sel);

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>