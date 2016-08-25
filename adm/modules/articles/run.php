<?php
	# SETTINGS #############################################################################
	$moduleName = "articles";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "item_row" => $prefix . "item_row.tpl",
	));
	# MAIN #################################################################################
	
	
	if(isset($_POST['item_id'])){
		if(isset($_POST['view'])){
										$view = 1;
									}
									else{
										$view = 0;
									}
									
		switch($_POST['item_id']){
			case 0:{
				$dbc->element_create("articles",array(
					"date" => date("Y-m-d H:i",strtotime($_POST['date'])), 
					"page_id" => $_GET['menu'],
					"title" => $_POST['title'], 
					"chpu" => setItemCHPU($_POST['title'], 'articles'),
					"icon" => $_POST['icon'], 
					"description" => $_POST['description'], 
					"content" => $_POST['content'], 
					"meta_key" => $_POST['meta_key'], 
					"view" => $view)); 
				break;
			}
			default:{
				$dbc->element_update('articles',$_POST['item_id'],array(
					"date" => date("Y-m-d H:i",strtotime($_POST['date'])), 
								"title" => $_POST['title'], 
								"chpu" => setItemCHPU($_POST['title'], 'articles', $_POST['item_id']),
								"icon" => $_POST['icon'], 
								"description" => $_POST['description'], 
								"content" => $_POST['content'], 
								"meta_key" => $_POST['meta_key'], 
								"view" => $view));
				break;
			}
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"articles",
				"select"=>"*",
				"where"=>"page_id = ".$_GET['menu']
				)
			);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$tpl->assign("ITEM_ID", $row['id']);
			$tpl->assign("EDT_DATE", $row['date']);
			$tpl->assign("EDT_TITLE", $row['title']);
			$tpl->assign("EDT_ICON", $row['icon']);
			$tpl->assign("EDT_DESCRIPTION", $row['description']);
			$tpl->assign("EDT_CONTENT", $row['content']);
			$tpl->assign("EDT_META_KEY", $row['meta_key']);
			if($row['view']==1){
					$tpl->assign("VIEW_CHECK", ' checked="checked"');
				}
				else{
					$tpl->assign("VIEW_CHECK", '');
				}
			$tpl->assign("EDT_CHPU", $row['chpu']);
									
			
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