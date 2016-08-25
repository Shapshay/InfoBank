<?php
	# SETTINGS #############################################################################
	$moduleName = "page_setings";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "tpl_row" => $prefix . "tpl_row.tpl",
			$moduleName . "content_row" => $prefix . "content_row.tpl",
	));
	
	# MAIN #################################################################################
	if(isset($_POST['edt_page'])){
		if(isset($_POST['view'])){
			$view = 1;
		}
		else{
			$view = 0;
		}
		if(isset($_POST['start'])){
			$start = 1;
			$dbc->element_fields_update('pages',
				" WHERE group_id = 1",
				array(
				'start' => 0
			));
		}
		else{
			$start = 0;
		}
		
		$dbc->element_update('pages',$_GET['ch'],array(
			'view' => $view,
			'title' => $_POST['title'],
			'chpu' => setPageCHPU($_POST['title'], $_GET['ch']),
			'description' => $_POST['description'],
			'content' => "{".strtoupper($_POST['content'])."}",
			'stemplate' => $_POST['stemplate'],
			'type' => $_POST['type'],
			'start' => $start
		));
		
		header("Location: system.php?menu=".$_GET['ch']);
		exit;
	}
	
	$row = $dbc->element_find('pages',$_GET['ch']);
	
	$tpl->assign("EDT_TITLE", $row['title']);
	$tpl->assign("EDT_DESC", $row['description']);
	if($row['view']==1){
		$tpl->assign("VIEW_CHECK", ' checked="checked"');
	}
	else{
		$tpl->assign("VIEW_CHECK", '');
	}
	if($row['start']==1){
		$tpl->assign("START_CHECK", ' checked="checked"');
	}
	else{
		$tpl->assign("START_CHECK", '');
	}
	
	$type_rows = $dbc->db_free_query("SHOW TABLES");
	$t_row = '';
	foreach($type_rows as $type_row){
		if($type_row[0]==$row['type']){
			$t_row.='<option value="'.$type_row[0].'" selected="selected">'.$type_row[0].'</option>';
		}
		else{
			$t_row.='<option value="'.$type_row[0].'">'.$type_row[0].'</option>';
		}
	}
	$tpl->assign("TABLE_ROWS", $t_row);
	
	
	$rows = $dbc->dbselect(array(
				"table"=>"site_setings",
				"select"=>"tpl_groups.tpl_folder AS tpl_folder",
				"joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
				"limit"=>"1"
				)
			);
	$row2 = $rows[0];
	
	$tpl_arr = array();
	$dir = opendir ("../templates/".$row2['tpl_folder']); 
	while (false !== ($file = readdir($dir))) { 
	    	if (strpos($file, '.tpl',1)) { 
				$tpl_arr[] = $file;
	    	}
	}
	sort($tpl_arr);
	$html = new simple_html_dom();
	foreach($tpl_arr as $template) {
		$url = 'http://'.$_SERVER['HTTP_HOST'].'/templates/'.$row2['tpl_folder'].'/'.$template;
		$result = get_web_page($url);
		$html2 = $result['content'];
		$html->load($html2); 
  		if($html->find("template", 0)){
			$element = $html->find("template", 0)->plaintext; 
			$tpl->assign("TPL_ID", $template);
			$tpl->assign("TPL_TITLE", $element.' ('.$template.')');
			if($template==$row['stemplate']){
				$tpl->assign("TPL_SEL", ' selected');
			}
			else{
				$tpl->assign("TPL_SEL", '');
			}
			
			$tpl->parse("TPL_ROWS", ".".$moduleName."tpl_row");
		}
	}
	
	$file_path = find("modules", "info.xml"); 
	if ($file_path){
    	foreach($file_path as $modul) {
			$xmlStr = file_get_contents($modul);
			$xml = new SimpleXMLElement($xmlStr);
			$tpl->assign("CONTENT_ID", $xml->modul);
			$tpl->assign("CONTENT_TITLE", $xml->title.' {'.strtoupper($xml->modul).'}');
			$tpl->assign("CONTENT_SEL", '');
			$page_tmp_modul = '{'.strtoupper($xml->modul).'}';
			if($page_tmp_modul==$row['content']){
				$tpl->assign("CONTENT_SEL", ' selected');
			}
			else{
				$tpl->assign("CONTENT_SEL", '');
			}
			
			$tpl->parse("CONTENT_ROWS", ".".$moduleName."content_row");
		}
	}
	
	
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>