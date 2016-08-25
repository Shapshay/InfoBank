<?php
# SETTINGS #############################################################################
$moduleName = "add_article";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
		$moduleName . "ch_row" => $prefix . "ch_row.tpl",
));
# MAIN #################################################################################


if(isset($_POST['item_id'])){
	if(isset($_POST['view'])){
		$view = 1;
	}
	else{
		$view = 0;
	}
	//print_r($_POST);
	$dbc->element_update('articles',$_POST['item_id'],array(
				"title" => $_POST['title'],
				"ch_id" => $_POST['ch_id'],
				"date" => 'NOW()',
				"chpu" => setItemCHPU($_POST['title'], 'articles', $_POST['item_id']),
				"content" => addslashes($_POST['content'])));
	//echo "<p>".$dbc->outsql;
	foreach ($_POST['question'] as $key=>$v){
		$dbc->element_update('questions',$key,array(
			"title" => $v,
			"hint" => $_POST['hint'][$key],
			"xp" => $_POST['xp'][$key]));
	}

	foreach ($_POST['answer'] as $key=>$v){
		if(isset($_POST['correct'][$key])){
			$correct = 1;
		}
		else{
			$correct = 0;
		}
		$dbc->element_update('answers',$key,array(
			"title" => $v,
			"correct" => $correct));
	}
	//header("Location: /".getItemCHPU($_GET['menu'],'pages'));
	//exit;

	// article
	$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

	$rows2 = $dbc->dbselect(array(
			"table"=>"chapters",
			"select"=>"*"
		)
	);
	$ch_sel = '';
	foreach($rows2 as $row2){
		if($row2['id']==$_POST['ch_id']){
			$ch_select = ' selected="selected"';
		}
		else{
			$ch_select = '';
		}
		$ch_sel.= '<option value="'.$row2['id'].'"'.$ch_select.'>'.$row2['title'].'</option>';
	}
	$tpl->assign("ART_CH", $ch_sel);

	$tpl->assign("ITEM_ID", $_POST['item_id']);
	$tpl->assign("ITEM_TITLE", $_POST['title']);
	$tpl->assign("ITEM_CONT", $_POST['content']);


	$questions = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"questions",
		"select"=>"*",
		"where"=>"art_id = ".$_POST['item_id']
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
					$answers .= '<p><input type="button" value="Удалить ответ" class="button_del" onclick="DelA('.$row3['id'].');"></p>
						<label>Ответ</label>
						<textarea name="answer['.$row3['id'].']" id="answer" class="answ_area">'.$row3['title'].'</textarea>
						<p>
						<input type="checkbox" name="correct['.$row3['id'].']" id="correct['.$row3['id'].']" value="1"'.$correct.' /><label>Верный ответ</label>
						</p>
						<hr size="1" width="50%" align="left">';
				}
			}
			$questions.= '<hr size="1" width="80%" align="left">
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
					<div id="AnswDiv'.$row2['id'].'">
					    '.$answers.'
					</div>
						<p><input type="button" value="Добавить ответ" class="button" onclick="AddAnswer('.$row2['id'].');"></p>
					</div>';
		}
	}



	$tpl->assign("ITEM_Q_DIV", $questions);
	

	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName);
}
else{
	$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

	$tpl->assign("ITEM_ID", 0);
	$tpl->assign("ITEM_TITLE", '');
	$tpl->assign("ITEM_Q_DIV", '');
	$tpl->assign("ITEM_CONT", '');

	$rows2 = $dbc->dbselect(array(
			"table"=>"chapters",
			"select"=>"*"
		)
	);
	$ch_sel = '';
	foreach($rows2 as $row2){
		$ch_sel.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
	}
	$tpl->assign("ART_CH", $ch_sel);

	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName);
}



?>