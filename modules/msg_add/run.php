<?php
# SETTINGS #############################################################################
$moduleName = "msg_add";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################

if(isset($_POST['item_id'])){
	$dbc->element_create("msgs",array(
			"sender_id" => ROOT_ID,
			"from_id" => $_POST['from_id'],
			"date" => 'NOW()',
			"theme" => $_POST['theme'],
			"content" => $_POST['content']));
	header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
	exit;
}


$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"*",
			"order"=>"name"
			)
		);
$numRows = $dbc->count;
foreach($rows as $row){
	$tpl->assign("ITEM_ID", $row['id']);
	$tpl->assign("ITEM_TITLE", $row['name']);

	$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
}


$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>