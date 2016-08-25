<?php
# SETTINGS #############################################################################
$moduleName = "articles";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
		$moduleName . "ch_row" => $prefix . "ch_row.tpl",
));
# MAIN #################################################################################


if(isset($_POST['item_id'])){
	if(isset($_POST['view'])){
		$view = 1;
	}
	else{
		$view = 0;
	}
	//print_r($_POST);
	if($_POST['item_id']==0){
			$dbc->element_create("articles",array(
				"date" => 'NOW()',
				"page_id" => $_GET['menu'],
				"title" => $_POST['title'],
				"ch_id" => $_POST['ch_id'],
				"chpu" => setItemCHPU($_POST['title'], 'articles'),
				"content" => addslashes($_POST['content'])));
	}
	else{
		$dbc->element_update('articles',$_POST['item_id'],array(
			"title" => $_POST['title'],
			"ch_id" => $_POST['ch_id'],
			"date" => 'NOW()',
			"chpu" => setItemCHPU($_POST['title'], 'articles', $_POST['item_id']),
			"content" => addslashes($_POST['content'])));
	}

	foreach ($_POST['question'] as $key=>$v){
		$dbc->element_update('questions',$key,array(
			"title" => $v,
			"hint" => $_POST['hint'][$key],
			"xp" => $_POST['xp'][$key]));
	}

	foreach ($_POST['answer'] as $key=>$v){
		if(isset($_POST['correct'][$key])){
			$correct = 1;
		}
		else{
			$correct = 0;
		}
		$dbc->element_update('answers',$key,array(
			"title" => $v,
			"correct" => $correct));
	}
	header("Location: /".getItemCHPU($_GET['menu'],'pages'));
	exit;
}


$rows = $dbc->dbselect(array(
			"table"=>"articles",
			"select"=>"articles.*, chapters.title as ch",
			"joins"=>"LEFT OUTER JOIN chapters ON articles.ch_id = chapters.id",
			"where"=>"page_id = ".$_GET['menu']." AND ch_id<>0"
			)
		);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		$tpl->assign("EDT_DATE", $row['date']);
		$tpl->assign("EDT_TITLE", $row['title']);
		$tpl->assign("EDT_CONTENT", $row['content']);
		$tpl->assign("EDT_CH", $row['ch']);

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
	)
);
$ch_sel = '';
foreach($rows2 as $row2){
	$ch_sel.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
}
$tpl->assign("ART_CH", $ch_sel);

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);

?>