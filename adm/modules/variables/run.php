<?php
	# SETTINGS #############################################################################
	$moduleName = "variables";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "var_row" => $prefix . "var_row.tpl",
	));
	# MAIN #################################################################################
	if(isset($_POST['val_id'])){
		switch($_POST['val_id']){
			case 0:{
				$dbc->element_create("variables",array(
					'name'=>$_POST['name'],
					'val'=>$_POST['val'],
					'page_id'=>$_GET['menu'])); 
				break;
			}
			default:{
				$dbc->element_update('variables',$_POST['val_id'],array(
					'name' => $_POST['name'],
					'val' => $_POST['val']));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"variables",
				"select"=>"*",
				"order"=>"name"
				)
			);
	foreach($rows as $row){
		$tpl->assign("VAR_ID", $row['id']);
		$tpl->assign("VAR_NAME", $row['name']);
		$tpl->assign("VAR_VAL", $row['val']);
		
		$tpl->parse("VAR_ROWS", ".".$moduleName."var_row");
	}
	
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>