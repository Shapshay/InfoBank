<?php
# SETTINGS #############################################################################
$moduleName = "stat1";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "html" => $prefix . "html.tpl",
	$moduleName . "user_rows" => $prefix . "user_rows.tpl",
));

# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."html");


$rows = $dbc->dbselect(array(
		"table"=>"comp_log",
		"select"=>"users.name as oper,
            complex.title as comp,
            tasks.title as task,
            articles.title as art,
            questions.title as question,
            COUNT(comp_log.q_id) as answer_count",
        "joins"=>"LEFT OUTER JOIN users ON comp_log.u_id = users.id
            LEFT OUTER JOIN complex ON comp_log.comp_id = complex.id
            LEFT OUTER JOIN tasks ON comp_log.task_id = tasks.id
            LEFT OUTER JOIN articles ON comp_log.art_id = articles.id
            LEFT OUTER JOIN questions ON comp_log.q_id = questions.id",
        "where"=>"articles.title IS NOT NULL AND questions.title IS NOT NULL",
        "group"=>"comp_log.u_id, comp_log.q_id",
        "order"=>"comp_log.date"
	)
);
foreach($rows as $row){

    $tpl->assign("STAT_NAME", $row['oper']);
    $tpl->assign("STAT_COMP", $row['comp']);
    $tpl->assign("STAT_TASK", $row['task']);
    $tpl->assign("STAT_ART", $row['art']);
    $tpl->assign("STAT_QUESTION", $row['question']);
    $tpl->assign("STAT_ANSW_COUNT", $row['answer_count']);


	$tpl->parse("USER_ROWS", ".".$moduleName."user_rows");
}
$tpl->parse(strtoupper($moduleName), ".".$moduleName);







?>