<?php
	# SETTINGS #############################################################################
	$moduleName = "{MODUL_NAME}";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "item_row" => $prefix . "item_row.tpl",
	));
	# MAIN #################################################################################
	
	
	if(isset($_POST['item_id'])){
		{MODUL_CHEKBOX_UPDATE}
		switch($_POST['item_id']){
			case 0:{
				$dbc->element_create("{MODUL_TABLE_NAME}",array(
					{MODUL_TABLE_FIELDS})); 
				break;
			}
			default:{
				$dbc->element_update('{MODUL_TABLE_NAME}',$_POST['item_id'],array(
					{MODUL_TABLE_FIELDS}));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"{MODUL_TABLE_NAME}",
				"select"=>"*"
				)
			);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$tpl->assign("ITEM_ID", $row['id']);
			{MODUL_EDT_FIELDS_VALUES}
			
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