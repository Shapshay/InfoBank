<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 25.05.2016
 * Time: 12:14
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
require_once("../adm/inc/RFunc.php");
$dbc = new BDFunc;
$rfq = new RFunc;

if(isset($_POST['u_id'])){
    $u_set=$dbc->element_find_by_field('users','login',$_POST['u_id']);
    $u_id = $u_set['id'];
    $out_row['u_id'] = $u_id;
    $dbc->element_delete('users',$u_id);
    $sql = "DELETE FROM r_user_role WHERE user_id = ".$u_id;
    $dbc->db_free_del($sql);
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;