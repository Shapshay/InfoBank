<?php
# SETTINGS #############################################################################
$moduleName = "users";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
		$moduleName . "u_page_row" => $prefix . "u_page_row.tpl",
		$moduleName . "u_role_row" => $prefix . "u_role_row.tpl",
));
# MAIN #################################################################################

if(isset($_POST['u_r'])){
	if($_POST['item2_id']!=0){
		$sql = "DELETE FROM r_user_role WHERE user_id = ".$_POST['item2_id'];
		$dbc->db_free_del($sql);
		foreach($_POST['role'] as $key=>$val){
			$rfq->set_role_user($key,$_POST['item2_id']);
		}
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
}
else{
	if(isset($_POST['item_id'])){
		if(isset($_POST['prod'])){
			$prod = 1;
		}
		else{
			$prod = 0;
		}
		switch($_POST['item_id']){
			case 0:{
				$dbc->element_create("users",array(
					"name" => $_POST['name'],
					"login" => $_POST['login'],
					"password" => md5($_POST['password'].SECRET),
					"login_1C" => $_POST['login_1C'],
					"phone" => $_POST['phone'],
					"office_id" => $_POST['office_id'],
					"prod" => $prod,
					"page_id" => $_POST['page_id']));
				break;
			}
			default:{
				$dbc->element_update('users',$_POST['item_id'],array(
					"name" => $_POST['name'],
					"login" => $_POST['login'],
					"password" => md5($_POST['password'].SECRET),
					"login_1C" => $_POST['login_1C'],
					"phone" => $_POST['phone'],
					"office_id" => $_POST['office_id'],
					"prod" => $prod,
					"page_id" => $_POST['page_id']));
				break;
			}
		}
		//echo $dbc->outsql;
		header("Location: system.php?menu=".$_GET['menu']);
		exit;
	}
}



$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"users.*, 
			pages.title as title",
			"joins"=>"LEFT OUTER JOIN pages ON users.page_id = pages.id"
			)
		);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		$tpl->assign("EDT_NAME", $row['name']);
		$tpl->assign("EDT_LOGIN", $row['login']);
		$tpl->assign("EDT_PASSWORD", 123456);
		$tpl->assign("EDT_PAGE_ID", $row['title']);
		$tpl->assign("EDT_PHONE", $row['phone']);
		$tpl->assign("EDT_DATE_REG", $row['reg_date']);


		$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
	}
}
else{
	$tpl->assign("ITEM_ROWS", '');
}
$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

$rows = $dbc->dbselect(array(
	"table"=>"pages",
	"select"=>" id, title",
	"where"=>"parent_id<>0 AND group_id=1",
	"order"=>"sortfield"
	)
);
foreach($rows as $row){
	$tpl->assign("U_PAGE_ID", $row['id']);
	$tpl->assign("U_PAGE_NAME", $row['title']);

	$tpl->parse("U_PAGE_PAGE_ROWS", ".".$moduleName."u_page_row");
}


$rows = $dbc->dbselect(array(
	"table"=>"r_role",
	"select"=>"id, name"
	)
);
foreach($rows as $row){
	$tpl->assign("U_ROLE_ID", $row['id']);
	$tpl->assign("U_ROLE_NAME", $row['name']);

	$tpl->parse("USER_ROLES_ROWS", ".".$moduleName."u_role_row");
}

$rows2 = $dbc->dbselect(array(
		"table"=>"offices",
		"select"=>"*"
	)
);

$office_sel = '';
foreach($rows2 as $row2){
	$office_sel.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
}
$tpl->assign("USER_OFFICE_SEL", $office_sel);


$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>