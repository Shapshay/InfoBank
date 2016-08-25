<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 12.05.2016
 * Time: 16:42
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(!isset($_POST['comps'])){
    $_POST['comps'] = array(0);
}

$rows = $dbc->dbselect(array(
        "table"=>"complex",
        "select"=>"id, title"
    )
);
$u_rows = '';
foreach($rows as $row){
    $rows2 = $dbc->dbselect(array(
            "table"=>"user_comp",
            "select"=>"*",
            "where"=>"user_id = ".$_POST['u_id']." AND comp_id = ".$row['id'],
            "limit"=>1
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row2 = $rows2[0];

        if (!in_array($row['id'], $_POST['comps'])) {
            $dbc->element_delete("user_comp",$row2['id']);
            $comp_check = '';
            $date_sheduled = '';
        }
        else{
            $comp_check = ' checked="checked"';
            $date_sheduled = ' ('.$row2['date'].')';
        }
    }
    else{
        if (in_array($row['id'], $_POST['comps'])) {
            $dbc->element_create("user_comp",array(
                "comp_id" => $row['id'],
                "control_id" => $_POST['ROOT_ID'],
                "user_id" => $_POST['u_id'],
                "date" => 'NOW()'));
            $comp_check = ' checked="checked"';
            $date_sheduled = ' ('.date("Y-m-d H:i:s").')';
        }
        else{
            $comp_check = '';
            $date_sheduled = '';
        }
    }
    $u_rows.= '<p><input type="checkbox" id="'.$row['id'].'" class="cb-element"'.$comp_check.'> '.$row['title'].$date_sheduled.'</p>';
}

$out_row['result'] = 'OK';
$out_row['html'] = $u_rows;

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>