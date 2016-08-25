<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->element_find('tasks',$_POST['id']);
	$row = $rows;
	$numRows = $dbc->count;
	//echo $dbc->outsql;
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['title'] = $row['title'];
		$out_row['time_on_task'] = $row['time_on_task'];

		$rows2 = $dbc->dbselect(array(
				"table"=>"task_art",
				"select"=>"task_art.*, articles.title as title",
				"joins"=>"LEFT OUTER JOIN articles ON task_art.art_id = articles.id",
				"where"=>"task_id = ".$_POST['id'],
				"order"=>"t_sort"
			)
		);
		$art_sel = '';
		foreach($rows2 as $row2){
			$art_sel.= '<div id="'.$row2['art_id'].'" class="task task_div2"><aside class="widget">'.$row2['title'].'</aside></div>';
		}
		$out_row['arts'] = $art_sel;
								
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