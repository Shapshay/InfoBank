<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->dbselect(array(
			"table"=>"msgs",
			"select"=>"msgs.*, users.name as sender",
			"joins"=>"LEFT OUTER JOIN users ON msgs.sender_id = users.id",
			"where"=>"msgs.id = ".$_POST['id'],
			"limit"=>1
		)
	);
	$row = $rows[0];
	$numRows = $dbc->count;
	//echo $dbc->outsql;
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['date'] = $row['date'];
		$out_row['sender_id'] = $row['sender'];
		$out_row['theme'] = $row['theme'];
		$out_row['content'] = $row['content'];
		$dbc->element_update('msgs',$_POST['id'],array("view" => 1));
	}
	else{
		$out_row['result'] = 'Err';
	}
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>