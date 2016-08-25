<?php
	# SETTINGS #############################################################################
	
	$moduleName = "meta";
	$prefix = "./modules/".$moduleName."/";
	
	################################################################################################
	
	if($page_arr['seo_title']!=''){
		$page_seo_title = $page_arr['seo_title']." : ".$main_set['company'];
	}
	else{
		$page_seo_title = getPageTitle(PAGE_ID)." : ".$main_set['company'];
	}
	
	if($page_arr['seo_key']!=''){
		$page_seo_key = $page_arr['seo_key'];
	}
	else{
		$page_seo_key = $main_set['meta_key'];
	}
	
	if($page_arr['seo_desc']!=''){
		$page_seo_desc = $page_arr['seo_desc'];
	}
	else{
		$page_seo_desc = $main_set['meta_desc'];
	}
	
	if(isset($_GET['page'])&&$_GET['page']>1){
		$meta_title = 'Страница '.$_GET['page'].' - '.$page_seo_title;
		$meta_content = 'Страница '.$_GET['page'].' - '.$page_seo_desc;
	}
	else{
		$meta_title = $page_seo_title;
		$meta_content = $page_seo_desc;
	}
	$meta_key = $page_seo_key;
	
	if(isset($_GET['item'])){
		$item = $_GET['item'];
	}
	else{
		$item = 0;
	}
	
	$meta = '<title>'.$meta_title.'</title>
		<meta content="'.$meta_key.'" name="keywords">
		<meta content="'.$meta_content.'" name="description">
		';
	$stat_id = getIPtoSiteStatisticID($_SERVER['REMOTE_ADDR'], $_GET['menu'], $item);
	if($stat_id>0){
		$sql = "UPDATE stat 
				SET num = num+1
				WHERE id = ".$stat_id;
		$dbc->element_free_update($sql);
	}
	else{
		$dbc->element_create("stat",array(
				"date" => "NOW()", 
				"ip" => $_SERVER['REMOTE_ADDR'], 
				"menu" => $_GET['menu'], 
				"item_id" => $item)); 
	}
	
	$tpl->assign(strtoupper($moduleName), $meta);
?>