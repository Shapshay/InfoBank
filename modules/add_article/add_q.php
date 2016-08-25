<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 04.05.2016
 * Time: 9:59
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

$art_id = $_POST['art_id'];

$dbc->element_create("questions",array(
    "art_id" => $art_id
));
$q_id = $dbc->ins_id;

$dbc->element_create("answers",array(
    "q_id" => $q_id
));
$a_id = $dbc->ins_id;

$out_row['result'] = 'OK';


$q_div = '<div id="DivQforDel'.$q_id.'"> <hr size="1" width="80%" align="left">
                    <button type="button" class="acord_btn2" onclick="ShowQ('.$q_id.');">Вопрос</button>
					<div id="Q'.$q_id.'">
					<p><input type="button" value="Удалить вопрос" class="button_del" onclick="DelQ('.$q_id.');"></p>
					<label>Вопрос</label>
					<textarea name="question['.$q_id.']" id="question" class="quest_area"></textarea>
					<p>
					<label>Подсказка</label>
					<textarea name="hint['.$q_id.']" id="hint" class="quest_area"></textarea>
					</p>
					<p>
                    <label>Количество опыта</label>
                    <input class="text-input medium-input" type="text" id="title" name="xp['.$q_id.']" value="0" />
                    </p>
					<hr size="1" width="50%" align="left">
					<div id="AnswDiv'.$q_id.'" class="AnswDiv">
					    <div id="DivAforDel'.$a_id.'">
					    <p><input type="button" value="Удалить ответ" class="button_del" onclick="DelA('.$a_id.');"></p>
					    <label>Ответ</label>
						<textarea name="answer['.$a_id.']" id="answer" class="answ_area"></textarea>
						<p>
						<input type="checkbox" name="correct['.$a_id.']" id="correct['.$a_id.']" value="1" /><label>Верный ответ</label>
						</p>
						<hr size="1" width="50%" align="left">
						</div>
					</div>
						<p><input type="button" value="Добавить ответ" class="button" onclick="AddAnswer('.$q_id.');"></p>
					</div>
					</div>';

$out_row['html'] = $q_div;

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>