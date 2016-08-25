<?php
# SETTINGS #############################################################################
$moduleName = "tasks";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
			"table"=>"tasks",
			"select"=>"*"
			)
		);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		$tpl->assign("EDT_TITLE", $row['title']);
		$tpl->assign("EDT_TIME_ON_TASK", $row['time_on_task']);


		$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
	}
}
else{
	$tpl->assign("ITEM_ROWS", '');
}
$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

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