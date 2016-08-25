<?php
# SETTINGS #############################################################################
$moduleName = "complex";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################


if(isset($_POST['item_id'])){
	if(isset($_POST['block'])){
		$block = 1;
	}
	else{
		$block = 0;
	}

	switch($_POST['item_id']){
		case 0:{
			$dbc->element_create("complex",array(
				"title" => $_POST['title'],
				"block" => $block,
				"dostup_start" => date("H:i",strtotime($_POST['dostup_start'])),
				"dostup_end" => date("H:i",strtotime($_POST['dostup_end']))));
			break;
		}
		default:{
			$dbc->element_update('complex',$_POST['item_id'],array(
				"title" => $_POST['title'],
				"block" => $block,
				"dostup_start" => date("H:i",strtotime($_POST['dostup_start'])),
				"dostup_end" => date("H:i",strtotime($_POST['dostup_end']))));
			break;
		}
	}
	header("Location: system.php?menu=".$_GET['menu']);
	exit;
}


$rows = $dbc->dbselect(array(
			"table"=>"complex",
			"select"=>"*"
			)
		);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		$tpl->assign("EDT_TITLE", $row['title']);
		if($row['block']==1){
				$tpl->assign("BLOCK_CHECK", ' checked="checked"');
			}
			else{
				$tpl->assign("BLOCK_CHECK", '');
			}
		$tpl->assign("EDT_DOSTUP_START", date("H:i",strtotime($row['dostup_start'])));
		$tpl->assign("EDT_DOSTUP_END", date("H:i",strtotime($row['dostup_end'])));


		$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
	}
}
else{
	$tpl->assign("ITEM_ROWS", '');
}
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