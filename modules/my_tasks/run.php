<?php
# SETTINGS #############################################################################
$moduleName = "my_tasks";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "tasks_rows" => $prefix . "tasks_rows.tpl",
	$moduleName . "ch_rows" => $prefix . "ch_rows.tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "html" => $prefix . "html.tpl",
	$moduleName . "item" => $prefix . "item.tpl",
	$moduleName . "item_html" => $prefix . "item_html.tpl",
	$moduleName . "dostup" => $prefix . "dostup.tpl",
	$moduleName . "dostup2" => $prefix . "dostup2.tpl",
	$moduleName . "q_row" => $prefix . "q_row.tpl",
	$moduleName . "a_row" => $prefix . "a_row.tpl",
));
# MAIN #################################################################################

if(!isset($_GET['comp'])){
	// список задач пользователя
	$rows = $dbc->dbselect(array(
			"table"=>"user_comp",
			"select"=>"user_comp.comp_id as comp_id,
			complex.title as comp,
			user_comp.date as date_naz,
			user_comp.percent as percent,
			user_comp.close as close,
			complex.block as block,
			SUM(questions.xp) as xp",
			"joins"=>"LEFT OUTER JOIN complex ON user_comp.comp_id = complex.id
			LEFT OUTER JOIN comp_task ON complex.id = comp_task.comp_id
			LEFT OUTER JOIN task_art ON comp_task.task_id = task_art.task_id
			LEFT OUTER JOIN questions ON task_art.art_id = questions.art_id",
			"where"=>"user_id = ".ROOT_ID." AND close = 0",
			"group"=>"user_comp.comp_id",
			"order"=>"block",
			"order_type"=>"DESC"
		)
	);
	$numRows = $dbc->count;
	$prog_anim = '';
	if ($numRows > 0) {
		$task1 = false;
		$task2 = false;
		foreach ($rows as $row) {
			$url = getCodeBaseURL("index.php?menu=".$_GET['menu'])."/?comp=".$row['comp_id'];
			$tpl->assign("COMPLEX_URL", $url);
			$tpl->assign("COMPLEX_ID", $row['comp_id']);
			$tpl->assign("COMPLEX_TITLE", $row['comp']);
			$tpl->assign("COMPLEX_XP", $row['xp']);
			$tpl->assign("COMPLEX_PERCENT", $row['percent']);
			if($row['block']==1){
				$tpl->parse("TASKS_ROWS", "." . $moduleName . "tasks_rows");
				$task1 = true;
			}
			else{
				$tpl->parse("TASKS_ROWS2", "." . $moduleName . "tasks_rows");
				$task2 = true;
			}
			$prog_anim.= 'ProgressAnimate('.$row['comp_id'].');';
		}
		if(!$task1){
			$tpl->assign("TASKS_ROWS", 'Нет задач');
		}
		if(!$task2){
			$tpl->assign("TASKS_ROWS2", 'Нет задач');
		}
	}
	else{
		$tpl->assign("TASKS_ROWS", 'Нет задач');
		$tpl->assign("TASKS_ROWS2", 'Нет задач');
	}
	$tpl->assign("PROGRES_ANIM", $prog_anim);
	$tpl->parse("META_LINK", ".".$moduleName."html");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
	// запуск прохождения комплекса
	$rows = $dbc->dbselect(array(
			"table"=>"complex",
			"select"=>"*",
			"where"=>"id = ".$_GET['comp'],
			"limit"=>1
		)
	);
	$row = $rows[0];
	$tpl->assign("COMP_TITLE", $row['title']);
	$rgTimes = array(
		new DateTime($row['dostup_start']), new DateTime($row['dostup_end'])
	);
	$fTime = new DateTime(date("H:i:s"));
	$dostup = $fTime > $rgTimes[0] && $fTime < $rgTimes[1] ? true : false;
	if(!$dostup){
		$tpl->assign("META_LINK", '');
		$tpl->assign("START_DOSTUP", date("H:i",strtotime($row['dostup_start'])));
		$tpl->assign("END_DOSTUP", date("H:i",strtotime($row['dostup_end'])));
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."dostup");
	}
	else{
		$cur_comp_rows = $dbc->dbselect(array(
				"table"=>"user_comp",
				"select"=>"date",
				"where"=>"comp_id = ".$_GET['comp']." AND user_id = ".ROOT_ID,
				"order"=>"date DESC",
				"limit"=>1
			)
		);
		$cur_comp_row = $cur_comp_rows[0];
		$nazCompDate = $cur_comp_row['date'];
		$rows = $dbc->dbselect(array(
				"table"=>"comp_task",
				"select"=>"comp_task.comp_id as comp,
					comp_task.after_time as after_time,
					comp_task.task_id as task,
					task_art.art_id",
				"joins"=>"LEFT OUTER JOIN task_art ON comp_task.task_id = task_art.task_id",
				"where"=>"comp_id = ".$_GET['comp'],
				"order"=>"c_sort, t_sort"
			)
		);
		$last_date = "20000101";
		foreach ($rows as $row){
			$log_rows = $dbc->dbselect(array(
					"table"=>"comp_log",
					"select"=>"*",
					"where"=>"u_id = ".ROOT_ID." AND 
						comp_id = ".$row['comp']." AND 
						task_id = ".$row['task']." AND 
						art_id = ".$row['art_id']." AND 
						DATE_FORMAT(date,'%Y%m%d%H%i') > '".date('YmdHi',strtotime($nazCompDate))."' AND	
						close_art = 1",
					"limit"=>"1"
				)
			);
			//echo $dbc->outsql;
			$numRows = $dbc->count;
			if ($numRows > 0) {
				$log_row = $log_rows[0];
				if($log_row['close_task']==1){
					$last_date = date("Ymd",strtotime($log_row['date']));
				}

			}
			else{
				//var_dump($row);
				$comp = $row['comp'];
				$task = $row['task'];
				$art_id = $row['art_id'];
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
		// проверка на период между задачами
		if($dostup_date<=date("Ymd")) {

			$tpl->assign("COMP_ID", $comp);
			$tpl->assign("TASK_ID", $task);
			$tpl->assign("ART_ID", $art_id);

			$task_rows = $dbc->dbselect(array(
					"table" => "tasks",
					"select" => "*",
					"where" => "id = " . $task,
					"limit" => 1
				)
			);
			$task_row = $task_rows[0];
			$tpl->assign("TASK_TITLE", $task_row['title']);
			//$task_timer = decoct($task_row['time_on_task'] * 60000);
			$task_timer = $task_row['time_on_task'] * 60000;
			$task_timer_text = $task_row['time_on_task'] * 60;
			$tpl->assign("TASK_TIMER", $task_timer);
			$tpl->assign("TASK_TIMER_TEXT", $task_timer_text);

			if (!isset($_SESSION['task'])) {
				$_SESSION['task'] = $task;
				$_SESSION['a'] = 0;
			} else {
				if ($_SESSION['task'] != $task) {
					$_SESSION['task'] = $task;
					$_SESSION['a'] = 0;
				}
			}


			$rows2 = $dbc->dbselect(array(
					"table" => "articles",
					"select" => "*",
					"where" => "id = " . $art_id,
					"limit" => 1
				)
			);
			$row2 = $rows2[0];
			$tpl->assign("ART_TITLE", $row2['title']);
			$tpl->assign("COMP_ART", $row2['content']);

			$q_rows = $dbc->dbselect(array(
					"table" => "questions",
					"select" => "*",
					"where" => "art_id = " . $art_id,
					"order" => "RAND()"
				)
			);
			foreach ($q_rows as $q_row) {
				$tpl->assign("Q_ID", $q_row['id']);
				$tpl->assign("Q_TITLE", $q_row['title']);

				$a_rows = $dbc->dbselect(array(
						"table" => "answers",
						"select" => "*",
						"where" => "q_id = " . $q_row['id'],
						"order" => "RAND()"
					)
				);
				foreach ($a_rows as $a_row) {
					$tpl->assign("A_ID", $a_row['id']);
					$tpl->assign("A_TITLE", $a_row['title']);

					$tpl->parse("ANSWERS", "." . $moduleName . "a_row");
				}

				$tpl->parse("QUESTIONS", "." . $moduleName . "q_row");
				$tpl->clear("ANSWERS");
			}


			$tpl->parse("META_LINK", "." . $moduleName . "item_html");
			$tpl->parse(strtoupper($moduleName), "." . $moduleName . "item");
		}
		else{
			$tpl->assign("META_LINK", '');
			$tpl->assign("START_DOSTUP", date("d-m-Y",strtotime($dostup_date)));
			$tpl->parse(strtoupper($moduleName), ".".$moduleName."dostup2");
		}
	}
}


?>