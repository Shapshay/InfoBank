<?php
	# SETTINGS #############################################################################
	$moduleName = "site_setings";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "theme_row" => $prefix . "theme_row.tpl",
	));
	# MAIN #################################################################################
	if(isset($_POST['edt_s_s'])){
		$dbc->element_update('site_setings',1,array(
			'company' => $_POST['company'],
			'slogan' => $_POST['slogan'],
			'email' => $_POST['email'],
			'meta_desc' => $_POST['meta_desc'],
			'meta_key' => $_POST['meta_key'],
			'adress' => $_POST['adress'],
			'jtime' => $_POST['jtime'],
			'phone' => $_POST['phone'],
			'tpl_group_id' => $_POST['tpl_group_id']
		));
		
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	$rows = $dbc->dbselect(array(
				"table"=>"site_setings",
				"select"=>"*",
				"limit"=>"1"
				)
			);
	$row = $rows[0];
	$tpl->assign("EDT_COMPANY", $row['company']);
	$tpl->assign("EDT_LOGO", $row['logo']);
	$tpl->assign("EDT_SLOGAN", $row['slogan']);
	$tpl->assign("EDT_EMAIL", $row['email']);
	$tpl->assign("EDT_DESC", $row['meta_desc']);
	$tpl->assign("EDT_KEYS", $row['meta_key']);
	$tpl->assign("EDT_ADRESS", $row['adress']);
	$tpl->assign("EDT_JTIME", $row['jtime']);
	$tpl->assign("EDT_PHONE", $row['phone']);
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"tpl_groups",
				"select"=>"*",
				"order"=>"title"
				)
			);
	
	foreach($rows as $row2){
		$tpl->assign("THEME_ID", $row2['id']);
		$tpl->assign("THEME_TITLE", $row2['title']);
		if($row2['id']==$row['tpl_group_id']){
			$tpl->assign("THEME_SEL", ' selected');
		}
		else{
			$tpl->assign("THEME_SEL", '');
		}
		$tpl->parse("THEME_ROWS", ".".$moduleName."theme_row");
	}
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>