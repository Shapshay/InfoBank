<?php
	# SETTINGS #############################################################################
	$moduleName = "chapters";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "item_row" => $prefix . "item_row.tpl",
	));
	# MAIN #################################################################################
	
	
	if(isset($_POST['item_id'])){
		
		switch($_POST['item_id']){
			case 0:{
				$dbc->element_create("chapters",array(
					"title" => $_POST['title'])); 
				break;
			}
			default:{
				$dbc->element_update('chapters',$_POST['item_id'],array(
					"title" => $_POST['title']));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"chapters",
				"select"=>"*"
				)
			);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$tpl->assign("ITEM_ID", $row['id']);
			$tpl->assign("EDT_TITLE", $row['title']);
									
			
			$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
		}
	}
	else{
		$tpl->assign("ITEM_ROWS", '');
	}
	$tpl->assign("DATE_NOW", date("d-m-Y H:i"));
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>