<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->element_find('complex',$_POST['id']);
	$row = $rows;
	$numRows = $dbc->count;
	//echo $dbc->outsql;
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['title'] = $row['title'];
		$out_row['block'] = $row['block'];
		$out_row['dostup_start'] = date("H:i",strtotime($row['dostup_start']));
		$out_row['dostup_end'] = date("H:i",strtotime($row['dostup_end']));

		$rows2 = $dbc->dbselect(array(
				"table"=>"comp_task",
				"select"=>"comp_task.*, tasks.title as title",
				"joins"=>"LEFT OUTER JOIN tasks ON comp_task.task_id = tasks.id",
				"where"=>"comp_id = ".$_POST['id'],
				"order"=>"c_sort"
			)
		);
		$art_sel = '';
		foreach($rows2 as $row2){
			if($row2['after_time']!=0){
				$art_sel.= '<div id="pause" class="task task_div2"><aside class="widget">Пауза дней: '.$row2['after_time'].'</aside></div>';
			}
			$art_sel.= '<div id="'.$row2['task_id'].'" class="task task_div2"><aside class="widget">'.$row2['title'].'</aside></div>';
		}
		$out_row['tasks'] = $art_sel;
								
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