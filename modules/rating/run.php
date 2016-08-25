<?php
# SETTINGS #############################################################################
$moduleName = "rating";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "html" => $prefix . "html.tpl",
	$moduleName . "user_rows" => $prefix . "user_rows.tpl",
	$moduleName . "tasks_rows" => $prefix . "tasks_rows.tpl",
));
# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."html");

$sql = 'SELECT *
	  FROM (
			SELECT id, xp, @i:=@i+1 AS rating
			FROM users, (SELECT @i:=0) AS rating
			ORDER BY xp DESC
		) x
	  WHERE id ='.ROOT_ID;
$ur_rows = $dbc->db_free_query($sql);
$ur_row = $ur_rows[0];

$tpl->assign("EDT_XP", $ur_row['xp']);
$tpl->assign("EDT_RATING", $ur_row['rating']);

$rows = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"*",
		"order"=>"xp DESC",
		"limit"=>3
	)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("LIDER_XP", $row['xp']);
		$tpl->assign("LIDER_NAME", $row['name']);
		$folder = 'uploads/avatars/full/';
		if($row['av']==''||!is_file($folder.$row['av'])){
			$avatar='<img src="images/gollum.jpg" width="100">';
		}
		else{
			$avatar='<img src="uploads/avatars/full/'.$row['av'].'" width="100">';
		}
		$tpl->assign("LIDER_AV", $avatar);

		$tpl->parse("LIDER_ROWS", ".".$moduleName."user_rows");
	}
}
else{
	$tpl->assign("LIDER_ROWS", '');
}


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
		"where"=>"user_id = ".ROOT_ID,
		"group"=>"user_comp.comp_id",
		"order"=>"close",
		"order_type"=>"DESC"
	)
);
$numRows = $dbc->count;
$prog_anim = '';
if ($numRows > 0) {
	foreach ($rows as $row) {
		$url = "/moi_zadachi/?comp=".$row['comp_id'];
		$tpl->assign("COMPLEX_URL", $url);
		$tpl->assign("COMPLEX_ID", $row['comp_id']);
		$tpl->assign("COMPLEX_TITLE", $row['comp']);
		$tpl->assign("COMPLEX_XP", $row['xp']);
		$tpl->assign("COMPLEX_PERCENT", $row['percent']);

		$tpl->parse("COMPLEX_ROWS", "." . $moduleName . "tasks_rows");

		$prog_anim.= 'ProgressAnimate('.$row['comp_id'].');';
	}
}
else{
	$tpl->assign("COMPLEX_ROWS", 'Нет задач');
}
$tpl->assign("PROGRES_ANIM", $prog_anim);





$tpl->parse(strtoupper($moduleName), ".".$moduleName);







?>