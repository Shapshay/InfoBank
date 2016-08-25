<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 04.05.2016
 * Time: 17:56
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
if(isset($_POST['id'])){
    $dbc->element_delete('questions',$_POST['id']);
    $sql = "DELETE FROM answers WHERE q_id = ".$row['id'];
    $dbc->db_free_del($sql);

    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>