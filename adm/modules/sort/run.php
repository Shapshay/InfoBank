<?php
	# SETTINGS #############################################################################
	$moduleName = "sort";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "sort_row" => $prefix . "sort_row.tpl",
	));
	# MAIN #################################################################################
	
	if(isset($_POST['ch'])){
		$parent = $_POST['ch'];
	}
	else{
		$parent = 0;
	}
	
	$menu_sort = '<ul id="sortable" class="sort_am">';
	$rows = $dbc->dbselect(array(
		"table"=>"pages",
		"select"=>" id, title, parent_id",
		"where"=>"parent_id=".$parent." AND group_id=1",
		"order"=>"sortfield"
		)
	);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		foreach($rows as $row){
			$menu_sort.= '<li id="note_'.$row['id'].'" class="editable"><span class="note" id="n_'.$row['id'].'">'.$row['title'].'</span></li>';
			$tpl->assign("SORT_ID", $row['id']);
			$tpl->assign("SORT_NAME", $row['title']);
			
			$tpl->parse("SORT_PAGE_ROWS", ".".$moduleName."sort_row");
		}
		$menu_sort.= '</ul>';
		$tpl->assign("SORT_MENU", $menu_sort);
	}
	else{
		$tpl->assign("SORT_MENU", '');
	}
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>