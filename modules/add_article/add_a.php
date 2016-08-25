<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 04.05.2016
 * Time: 11:07
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;

$q_id = $_POST['q_id'];

$dbc->element_create("answers",array(
    "q_id" => $q_id
));
$a_id = $dbc->ins_id;

$out_row['result'] = 'OK';


$a_div = '<div id="DivAforDel'.$a_id.'"><p><input type="button" value="Удалить ответ" class="button_del" onclick="DelA('.$a_id.');"></p>
                        <label>Ответ</label>
						<textarea name="answer['.$a_id.']" id="answer" class="answ_area"></textarea>
						<p>
						<input type="checkbox" name="correct['.$a_id.']" id="correct['.$a_id.']" value="1" /><label>Верный ответ</label>
						</p>
						<hr size="1" width="50%" align="left"></div>';

$out_row['html'] = $a_div;

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>