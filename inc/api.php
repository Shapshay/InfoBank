<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 20.05.2016
 * Time: 14:44
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
$dbc = new BDFunc;
//$_POST['u_lgn'] = 'oper';

if(isset($_POST['u_lgn'])){
    $user = $dbc->element_find_by_field('users','login',$_POST['u_lgn']);
    $rows = $dbc->dbselect(array(
            "table"=>"user_comp",
            "select"=>"user_comp.comp_id as comp_id,
			complex.title as comp,
			complex.dostup_start as dostup_start,
			complex.dostup_end as dostup_end,
			complex.block as block",
            "joins"=>"LEFT OUTER JOIN complex ON user_comp.comp_id = complex.id
			LEFT OUTER JOIN users ON user_comp.user_id = users.id",
            "where"=>"users.login = '".$_POST['u_lgn']."' AND block = 1 AND close = 0"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $block_user = false;
        foreach ($rows as $row) {
            // проверка на период доступности комплекса
            $rgTimes = array(
                new DateTime($row['dostup_start']), new DateTime($row['dostup_end'])
            );
            $fTime = new DateTime(date("H:i:s"));
            $dostup = $fTime > $rgTimes[0] && $fTime < $rgTimes[1] ? true : false;
            if($dostup){
                // проверка на период между задачами
                $d_rows = $dbc->dbselect(array(
                        "table"=>"comp_task",
                        "select"=>"comp_task.comp_id as comp,
					comp_task.after_time as after_time,
					comp_task.task_id as task,
					task_art.art_id",
                        "joins"=>"LEFT OUTER JOIN task_art ON comp_task.task_id = task_art.task_id",
                        "where"=>"comp_id = ".$row['comp_id'],
                        "order"=>"c_sort, t_sort"
                    )
                );
                $last_date = "20000101";
                foreach ($d_rows as $d_row){
                    $log_rows = $dbc->dbselect(array(
                            "table"=>"comp_log",
                            "select"=>"*",
                            "where"=>"u_id = ".$user['id']." AND comp_id = ".$d_row['comp']." AND task_id = ".$d_row['task']." AND art_id = ".$d_row['art_id']." AND close_art = 1",
                            "limit"=>"1"
                        )
                    );
                    $numRows = $dbc->count;
                    if ($numRows > 0) {
                        $log_row = $log_rows[0];
                        if($log_row['close_task']==1){
                            $last_date = date("Ymd",strtotime($log_row['date']));
                        }

                    }
                    else{
                        //var_dump($row);
                        $comp = $d_row['comp'];
                        $task = $d_row['task'];
                        $art_id = $d_row['art_id'];
                        break;
                    }
                }

                $dostup_rows = $dbc->dbselect(array(
                        "table"=>"comp_task",
                        "select"=>"*",
                        "where"=>"comp_id = ".$comp." AND task_id = ".$task,
                        "limit"=>"1"
                    )
                );
                $dostup_row = $dostup_rows[0];
                $dostup_date = $last_date+$dostup_row['after_time'];
                if($dostup_date<=date("Ymd")) {
                    $block_user = true;
                    $comp_title = $row['comp'];
                    $comp_id= $comp;
                    break;
                }
                // конец проверки на период между задачами
            }
        }
        if($block_user){
            $out_row['result'] = 'OK';
            $out_row['id'] = $comp_id;
            $out_row['c_title'] = $comp_title;
        }
        else{
            $out_row['result'] = 'NO';
        }
    }
    else{
        $out_row['result'] = 'NO';
    }
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;