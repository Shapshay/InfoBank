<?php
# SETTINGS #############################################################################
$moduleName = "pages_role";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "page_row" => $prefix . "page_row.tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# FUNCTION #############################################################################
	function ShowRoleTree($ParentID, $lvl, $role_id) { 
		global $dbc;
		global $lvl; 
		global $role_id; 
		global $treeRole; 
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
			$treeRole.= "<ul>\n";
			foreach($rows as $row){
				$id = $row["id"];
				if(isRolePage($role_id,$id)>0){
					$sel_check = ",\"selected\":true";
				}
				else{
					$sel_check = "";
				}
				if(getPageFirstChildrenID($id)>0){
					$treeRole.= "<li data-jstree='{\"icon\":\"inc/tree/images/tree.png\", \"href\":\"system.php?menu=".$id."\"".$sel_check."}'><a href='".$id."'>".$row["title"]."</a>\n";
				}
				else{
					$treeRole.= "<li data-jstree='{\"icon\":\"inc/tree/images/leaf.png\", \"href\":\"system.php?menu=".$id."\"".$sel_check."}'><a href='".$id."'>".$row["title"]."</a>\n";
				}
				ShowRoleTree($id, $lvl, $role_id); 
				
				$lvl--;
			}
			$treeRole.= "</ul>\n";
		}
	}
# MAIN #################################################################################
$tpl->assign("META_LINK", "");
$treeRole = '';
if(isset($_POST['inp_role_id'])){
	$dbc->db_free_del("DELETE FROM r_page_role WHERE role_id = ".$_POST['inp_role_id']);
	if(isset($_POST['r_p'])){
		foreach($_POST['r_p'] as $p_id){
			$dbc->element_create("r_page_role",array(
				"role_id" => $_POST['inp_role_id'], 
				"page_id" => $p_id)); 
		}
	}
	$role_id = $_POST['inp_role_id'];
}
else{
	$role_id = 1;
}

if(isset($_POST['role_id'])){
	$role_id = $_POST['role_id'];
}

ShowRoleTree(0, 0,$role_id); 


$rows = $dbc->dbselect(array(
	"table"=>"r_role",
	"select"=>"*"
	)
);
$numRows = $dbc->count; 
if ($numRows > 0) {
	foreach($rows as $row){
		if($role_id==$row['id']){
			$role_id_sel = ' selected="selected"';
		}
		else{
			$role_id_sel = '';
		}
		$tpl->assign("ROLE_ID_SEL", $role_id_sel);
		$tpl->assign("ROLE_ID", $row['id']);
		$tpl->assign("ROLE_NAME", $row['name']);
		
		$tpl->parse("ROLE_ROWS", ".".$moduleName."page_row");
	}
}
else{
	$tpl->assign("ROLE_ROWS", '');
}

$tpl->assign("TREE_ROLE_ID", $role_id);


$tpl->assign("ROLE_PAGES", $treeRole);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>