<?php
	# SETTINGS #############################################################################
	$moduleName = "r_role";
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
				$dbc->element_create("r_role",array(
					"name" => $_POST['name'], 
								"description" => $_POST['description'])); 
				break;
			}
			default:{
				$dbc->element_update('r_role',$_POST['item_id'],array(
					"name" => $_POST['name'], 
								"description" => $_POST['description']));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"r_role",
				"select"=>"*"
				)
			);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$tpl->assign("ITEM_ID", $row['id']);
			$tpl->assign("EDT_NAME", $row['name']);
			$tpl->assign("EDT_DESCRIPTION", $row['description']);
									
			
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