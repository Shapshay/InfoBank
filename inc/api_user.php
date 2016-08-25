<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 25.05.2016
 * Time: 11:08
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
require_once("../adm/inc/RFunc.php");
$dbc = new BDFunc;
$rfq = new RFunc;

if(isset($_POST['login'])){
    $main_set=$dbc->dbselect(array(
            "table"=>"site_setings",
            "select"=>"site_setings.*, tpl_groups.tpl_folder AS tpl_folder",
            "joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
            "limit"=>1
        )
    );
    $main_set = $main_set[0];
    define("SECRET", $main_set['secret']);
    $dbc->element_create("users",array(
        "name" => $_POST['name'],
        "login" => $_POST['login'],
        "password" => md5($_POST['password'].SECRET),
        "login_1C" => $_POST['login_1C'],
        "phone" => $_POST['phone'],
        "office_id" => $_POST['office_id'],
        "prod" => $_POST['prod'],
        "page_id" => 1));

    $out_row['result'] = 'OK';
}
else{
    if(isset($_POST['u_id'])){
        $u_set=$dbc->element_find_by_field('users','login',$_POST['u_id']);
        $u_id = $u_set['id'];
        $out_row['u_id'] = $u_id;
        if(isset($_POST['r_prod'])&&$_POST['r_prod']==1){
            $rfq->set_role_user(2,$u_id);
            $out_row['result'] = 'OK';
        }
        elseif (isset($_POST['r_td'])&&$_POST['r_td']==1){
            $rfq->set_role_user(1,$u_id);
            $out_row['result'] = 'OK';
        }
        else{
            $out_row['result'] = 'Err';
        }

    }
    else{
        $out_row['result'] = 'Err';
    }
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;