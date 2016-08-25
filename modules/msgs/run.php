<?php
# SETTINGS #############################################################################
$moduleName = "msgs";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
			"table"=>"msgs",
			"select"=>"msgs.*, users.name as sender",
			"joins"=>"LEFT OUTER JOIN users ON msgs.sender_id = users.id",
			"where"=>"from_id = ".ROOT_ID,
			"order"=>"msgs.date",
			"order_type"=>"DESC"
			)
		);
//echo $dbc->outsql;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		$tpl->assign("EDT_DATE", $row['date']);
		$tpl->assign("EDT_SENDER_ID", $row['sender']);
		$tpl->assign("EDT_THEME", $row['theme']);
		$tpl->assign("EDT_CONTENT", $row['content']);

		if($row['view']==0){
			$tpl->assign("MSG_READ", ' class="msg_no_read"');
		}
		else{
			$tpl->assign("MSG_READ", '');
		}


		$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
	}
}
else{
	$tpl->assign("ITEM_ROWS", '');
}
$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>