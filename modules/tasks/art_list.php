<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 05.05.2016
 * Time: 11:22
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if($_POST['ch_id']==0){
    $ch = 'ch_id<>0';
}
else{
    $ch = 'ch_id = '.$_POST['ch_id'];
}

$rows2 = $dbc->dbselect(array(
        "table"=>"articles",
        "select"=>"*",
        "where"=>"page_id = 2181 AND ".$ch
    )
);
$art_sel = '';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach ($rows2 as $row2) {
        $art_sel .= '<div id="' . $row2['id'] . '" class="task task_div"><aside class="widget">' . $row2['title'] . '</aside></div>';
    }
}

$out_row['result'] = 'OK';
$out_row['html'] = $art_sel;

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>