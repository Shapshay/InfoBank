<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;
if(isset($_POST['page_id'])&&isset($_POST['cut_page'])){
	//$dbc->element_delete('variables',$_POST['id']);
	$dbc->element_update('pages',$_POST['cut_page'],array(
			'parent_id' => $_POST['page_id']
		));
	$out_row['result'] = 'OK';
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>