<?php
# SETTINGS #############################################################################
$moduleName = "comp_user";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "html" => $prefix . "html.tpl",
	$moduleName . "user_rows" => $prefix . "user_rows.tpl",
));

# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."html");

$rows = $dbc->dbselect(array(
		"table"=>"complex",
		"select"=>"id, title"
	)
);
$sel_comp = '';
$curent_comp = 0;
$i = 0;
foreach($rows as $row){
    if($i == 0){
        $curent_comp = $row['id'];
    }
	$sel_comp.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
    $i++;
}
$tpl->assign("COMP_SEL", $sel_comp);

$rows = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"*"
	)
);
foreach($rows as $row){
    $rows2 = $dbc->dbselect(array(
            "table"=>"user_comp",
            "select"=>"*",
            "where"=>"user_id = ".$row['id']." AND comp_id = ".$curent_comp,
            "limit"=>1
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row2 = $rows2[0];
        $comp_check = ' checked="checked"';
        $date_sheduled = $row2['date'];
    }
    else{
        $comp_check = '';
        $date_sheduled = '---';
    }
    
    $tpl->assign("ITEM_ID", $row['id']);
    $tpl->assign("EDT_NAME", $row['name']);
    $tpl->assign("EDT_LOGIN", $row['login']);
    $tpl->assign("EDT_CHECK", $comp_check);
    $tpl->assign("EDT_DATE_SHEDULED", $date_sheduled);

	$tpl->parse("USER_ROWS", ".".$moduleName."user_rows");
}
$tpl->parse(strtoupper($moduleName), ".".$moduleName);







?>