<?php
	# SETTINGS #############################################################################
	$moduleName = "roots";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "item_row" => $prefix . "item_row.tpl",
	));
	# MAIN #################################################################################
	
	
	if(isset($_POST['item_id'])){
		$secret = "IIib@v~X"; // Секретное слово
		switch($_POST['item_id']){
			case 0:{
				$dbc->element_create("roots",array(
					"reg_date" => date("Y-m-d H:i",strtotime($_POST['reg_date'])), 
								"name" => $_POST['name'], 
								"login" => $_POST['login'], 
								"password" => md5($_POST['password'].$secret))); 
				break;
			}
			default:{
				$dbc->element_update('roots',$_POST['item_id'],array(
					"reg_date" => date("Y-m-d H:i",strtotime($_POST['reg_date'])), 
								"name" => $_POST['name'], 
								"login" => $_POST['login'], 
								"password" => md5($_POST['password'].$secret)));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"roots",
				"select"=>"*"
				)
			);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$tpl->assign("ITEM_ID", $row['id']);
			$tpl->assign("EDT_REG_DATE", $row['reg_date']);
									$tpl->assign("EDT_NAME", $row['name']);
									$tpl->assign("EDT_LOGIN", $row['login']);
									
			
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