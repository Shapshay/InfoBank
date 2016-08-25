<?php
	# SETTINGS #############################################################################
	$moduleName = "page_main_add";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "tpl_row" => $prefix . "tpl_row.tpl",
			$moduleName . "content_row" => $prefix . "content_row.tpl",
	));
	
	# MAIN #################################################################################
	if(isset($_POST['add_page'])){
		$dbc->element_create("pages",array(
					'view' => 0,
					'parent_id' => 0,
					'title' => $_POST['title'],
					'description' => $_POST['description'],
					'content' => '{'.strtoupper($_POST['content']).'}',
					'stemplate' => $_POST['stemplate'],
					'chpu' => setPageCHPU($_POST['title']),
					'sortfield' => getNewSortfield()
				));
		$new_page_id = $dbc->ins_id;
		header("Location: system.php?menu=9&ch=".$new_page_id);
		exit;
	}
	
	$rows = $dbc->dbselect(array(
				"table"=>"site_setings",
				"select"=>"tpl_groups.tpl_folder AS tpl_folder",
				"joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
				"limit"=>"1"
				)
			);
	$row = $rows[0];
	$tpl_arr = array();
	$dir = opendir ("../templates/".$row['tpl_folder']); 
	while (false !== ($file = readdir($dir))) { 
    	if (strpos($file, '.tpl',1)) { 
			$tpl_arr[] = $file;
    	}
	}
	sort($tpl_arr);
	$html = new simple_html_dom();
	foreach($tpl_arr as $template) {
		$url = 'http://'.$_SERVER['HTTP_HOST'].'/templates/'.$row['tpl_folder'].'/'.$template;
		$postdata = '';
		$result = get_web_page($url);
		$html2 = $result['content'];
		$html->load($html2); //砣��栥절TML-ꮤ
  		if($html->find("template", 0)){
			$element = $html->find("template", 0)->plaintext; // ���젢�堽륬孲� � ꫠ���books�
			$tpl->assign("TPL_ID", $template);
			$tpl->assign("TPL_TITLE", $element.' ('.$template.')');
			$tpl->assign("TPL_SEL", '');
			
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
			
			$tpl->parse("CONTENT_ROWS", ".".$moduleName."content_row");
		}
	}
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>