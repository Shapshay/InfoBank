<?php
# SETTINGS #############################################################################
	$moduleName = "tree";
# FUNCTION #############################################################################
	function ShowTree($ParentID, $lvl) { 
		global $dbc;
		global $lvl; 
		global $tree; 
		$lvl++;
		
		$rows = $dbc->dbselect(array(
				"table"=>"pages",
				"select"=>"id, parent_id, title",
				"where"=>"parent_id = '".$ParentID."' AND group_id=1",
				"order"=>"sortfield"
				)
			);
		$numRows = $dbc->count; 
		if ($numRows > 0) {
			$tree.= "<ul>\n";
			foreach($rows as $row){
				$id = $row["id"];
				if($id==PAGE_ID){
					$curent = ' class="jstree-clicked" style="color:#000;"';
				}
				else{
					$curent = '';
				}
				if(getPageFirstChildrenID($id)>0){
					$tree.= "<li data-jstree='{\"icon\":\"inc/tree/images/tree.png\", \"href\":\"system.php?menu=".$id."\"}'><a href=\"system.php?menu=".$id."\"".$curent.">".$row["title"]."</a>\n";
				}
				else{
					$tree.= "<li data-jstree='{\"icon\":\"inc/tree/images/leaf.png\", \"href\":\"system.php?menu=".$id."\"}' class=\"jstree-checked\"><a href=\"system.php?menu=".$id."\"".$curent.">".$row["title"]."</a>\n";
				}
				ShowTree($id, $lvl); 
				
				$lvl--;
			}
			$tree.= "</ul>\n";
		}
	}
# MAIN #################################################################################
	$tree = '';
	ShowTree(0, 0); 
	//print_r($_SERVER);
	//echo $tree;
	$tpl->assign(strtoupper($moduleName), $tree);
?>