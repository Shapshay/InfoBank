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

if(!isset($_POST['title'])||$_POST['title']==''){
    $out_row['result'] = 'Err1';
}
else{
    if(!isset($_POST['time_on_task'])||$_POST['time_on_task']==''){
        $out_row['result'] = 'Err2';
    }
    else{
        if(!isset($_POST['order'])){
            $out_row['result'] = 'Err3';
        }
        else{
            if($_POST['id']==0){
                $dbc->element_create("tasks",array(
                    "title" => $_POST['title'],
                    "time_on_task" => $_POST['time_on_task']));
                $task_id = $dbc->ins_id;
                foreach($_POST['order'] as $order=>$art_id){
                    $dbc->element_create("task_art",array(
                        "task_id" => $task_id,
                        "art_id" => $art_id,
                        "t_sort" => $order));
                }
            }
            else{
                $dbc->element_update('tasks',$_POST['id'],array(
                    "title" => $_POST['title'],
                    "time_on_task" => $_POST['time_on_task']));
                $task_id = $_POST['id'];
                $sql = "DELETE FROM task_art WHERE task_id = ".$_POST['id'];
                $dbc->db_free_del($sql);
                foreach($_POST['order'] as $order=>$art_id){
                    $dbc->element_create("task_art",array(
                        "task_id" => $_POST['id'],
                        "art_id" => $art_id,
                        "t_sort" => $order));
                }
            }
            $out_row['result'] = 'OK';
            $out_row['id'] = $task_id;
        }
    }
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
?>