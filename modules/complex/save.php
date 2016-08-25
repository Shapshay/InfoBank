<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 11.05.2016
 * Time: 11:58
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(!isset($_POST['title'])||$_POST['title']==''){
    $out_row['result'] = 'Err1';
}
else{
    if(!isset($_POST['order'])){
        $out_row['result'] = 'Err3';
    }
    else{
        if($_POST['id']==0){
            $dbc->element_create("complex",array(
                "title" => $_POST['title'],
                "block" => $_POST['block'],
                "dostup_start" => $_POST['dostup_start'],
                "dostup_end" => $_POST['dostup_end']));
            $comp_id = $dbc->ins_id;
            $prev_pause = 0;
            $i = 0;
            foreach($_POST['order'] as $order=>$task_id){
                if($task_id!='pause'){
                    $dbc->element_create("comp_task",array(
                        "comp_id" => $comp_id,
                        "task_id" => $task_id,
                        "after_time" => $prev_pause,
                        "c_sort" => $order));
                    $prev_pause = 0;
                }
                else{
                    $prev_pause = $_POST['pause'][$i];
                    $i++;
                }

            }
        }
        else{
            $dbc->element_update('complex',$_POST['id'],array(
                "title" => $_POST['title'],
                "block" => $_POST['block'],
                "dostup_start" => $_POST['dostup_start'],
                "dostup_end" => $_POST['dostup_end']));
            $comp_id = $_POST['id'];
            $sql = "DELETE FROM comp_task WHERE comp_id = ".$_POST['id'];
            $dbc->db_free_del($sql);
            $prev_pause = 0;
            $i = 0;
            foreach($_POST['order'] as $order=>$task_id){
                if($task_id!='pause'){
                    $dbc->element_create("comp_task",array(
                        "comp_id" => $comp_id,
                        "task_id" => $task_id,
                        "after_time" => $prev_pause,
                        "c_sort" => $order));
                    $prev_pause = 0;
                }
                else{
                    $prev_pause = $_POST['pause'][$i];
                    $i++;
                }
            }
        }
        $out_row['result'] = 'OK';
        $out_row['id'] = $comp_id;
    }
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>