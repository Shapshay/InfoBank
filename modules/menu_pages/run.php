<?php
# SETTINGS #############################################################################
$moduleName = "menu_pages";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "page_rows" => $prefix . "page_rows.tpl",
		$moduleName . "ch_rows" => $prefix . "ch_rows.tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
		"table"=>"pages",
		"select"=>"id, title",
		"where"=>"parent_id=2 AND view=1",
		"order"=>"sortfield"
		)
	);
$numRows = $dbc->count; 
if ($numRows > 0) {
	foreach($rows as $row){
		if($rfq->is_permission(ROOT_ID,$row['id'])){
			$cur_m = false;
			$tpl->assign("MM_CH_TITLE", $row['title']);
			if($row['id']==1){
				$url =  'onclick="window.location=\'/'.getItemCHPU($row['id'],'pages').'\';"';
				$tpl->assign("MM_CH_URL", $url);
				if($row['id']==PAGE_ID){
					$cur_m = true;
				}
			}
			else{
				$tpl->assign("MM_CH_URL", '');
			}
			$rows2 = $dbc->dbselect(array(
					"table"=>"pages",
					"select"=>"id, title",
					"where"=>"parent_id=".$row['id']." AND view=1",
					"order"=>"sortfield"
				)
			);
			$numRows = $dbc->count;
			if ($numRows > 0) {
				foreach ($rows2 as $row2) {
					if($rfq->is_permission(ROOT_ID,$row2['id'])) {
						if ($row2['id'] == PAGE_ID) {
							$tpl->assign("PAGE_M_CLASS", ' class="current"');
							$cur_m = true;
						} else {
							$tpl->assign("PAGE_M_CLASS", '');
						}
						$url = '/' . getItemCHPU($row2['id'], 'pages');
						$tpl->assign("PAGE_M_URL", $url);
						$tpl->assign("PAGE_M_TITLE", $row2['title']);

						$tpl->parse("PAGE_ROWS", "." . $moduleName . "page_rows");
					}
				}
			}
			else{
				$tpl->assign("PAGE_ROWS", '');
			}
			if($cur_m){
				$tpl->assign("CUR_MM", '  current');
			}
			else{
				$tpl->assign("CUR_MM", '');
			}
			$tpl->parse("MM_CH_ROWS", ".".$moduleName."ch_rows");
			$tpl->clear("PAGE_ROWS");
		}
	}
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
	$tpl->assign(strtoupper($moduleName), "");
}

?>