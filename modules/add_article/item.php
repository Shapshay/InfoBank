<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['id'])){
	$rows = $dbc->element_find('articles',$_POST['id']);
	$row = $rows;
	$numRows = $dbc->count;
	//echo $dbc->outsql;
	$questions = '';
	if ($numRows > 0) {
		$out_row['result'] = 'OK';
		$out_row['date'] = date("d-m-Y H:i",strtotime($row['date']));
		$out_row['title'] = $row['title'];
		$out_row['content'] = $row['content'];
		$out_row['ch_id'] = $row['ch_id'];

		$rows2 = $dbc->dbselect(array(
				"table"=>"questions",
				"select"=>"*",
				"where"=>"art_id = ".$_POST['id']
			));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$answers = '';
				$rows3 = $dbc->dbselect(array(
					"table"=>"answers",
					"select"=>"*",
					"where"=>"q_id = ".$row2['id']
				));
				$numRows = $dbc->count;
				if ($numRows > 0) {
					foreach($rows3 as $row3) {
						if($row3['correct']==1){
							$correct = ' checked="checked"';
						}
						else{
							$correct = '';
						}
						$answers .= '<div id="DivAforDel'.$row3['id'].'"><p><input type="button" value="Удалить ответ" class="button_del" onclick="DelA('.$row3['id'].');"></p>
						<label>Ответ</label>
						<textarea name="answer['.$row3['id'].']" id="answer" class="answ_area">'.$row3['title'].'</textarea>
						<p>
						<input type="checkbox" name="correct['.$row3['id'].']" id="correct['.$row3['id'].']" value="1"'.$correct.' /><label>Верный ответ</label>
						</p>
						<hr size="1" width="50%" align="left"></div>';
					}
				}
				$questions.= '<div id="DivQforDel'.$row2['id'].'"><hr size="1" width="80%" align="left">
                    <button type="button" class="acord_btn2" onclick="ShowQ('.$row2['id'].');">Вопрос</button>
					<div id="Q'.$row2['id'].'">
					<p><input type="button" value="Удалить вопрос" class="button_del" onclick="DelQ('.$row2['id'].');"></p>
					<label>Вопрос</label>
					<textarea name="question['.$row2['id'].']" id="question" class="quest_area">'.$row2['title'].'</textarea>
					<p>
					<label>Подсказка</label>
					<textarea name="hint['.$row2['id'].']" id="hint" class="quest_area">'.$row2['hint'].'</textarea>
					</p>
					<p>
                    <label>Количество опыта</label>
                    <input class="text-input medium-input" type="text" id="title" name="xp['.$row2['id'].']" value="'.$row2['xp'].'" />
                    </p>
					<hr size="1" width="50%" align="left">
					<div id="AnswDiv'.$row2['id'].'" class="AnswDiv">
					    '.$answers.'
					</div>
						<p><input type="button" value="Добавить ответ" class="button" onclick="AddAnswer('.$row2['id'].');"></p>
					</div>
					</div>';
			}
		}
		$out_row['html'] = $questions;

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