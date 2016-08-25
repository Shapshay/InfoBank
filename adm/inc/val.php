<?php
function getval($var_name, $lang_id = 0, $var_id = 0) {
	global $dbc;
	$resultValue = '';
	$sort = " ORDER BY id";
	
	if ($lang_id != 0) $langFilter = " AND lang = ".$lang_id; else $langFilter = "";
	if ($var_id != 0 && $var_name == '') $rows = $dbc->dbselect(array(
		"table"=>"variables",
		"select"=>"val",
		"where"=>"id = ".$var_id.$langFilter.$sort
		)
	);
	if ($var_name != '' && $var_id == 0) $rows = $dbc->dbselect(array(
		"table"=>"variables",
		"select"=>"val",
		"where"=>"name = '".$var_name."'".$langFilter.$sort
		)
	);
	if ($var_name != '' && $var_id != 0) $rows = $dbc->dbselect(array(
		"table"=>"variables",
		"select"=>"val",
		"where"=>"id = ".$var_id." AND name = '".$var_name."'".$langFilter.$sort
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		if ($numRows == 1) {
			$resultValue = $rows[0]['val'];
		} else {
			$resultValue = array();
			$i = 0;
			foreach($rows as $row){
				$resultValue[$i] = $row['val'];
				$i++;
			}
		}
	}
	
	return $resultValue;
}

function get_values($valuesArray) {
	global $dbc;
	if (!is_array($valuesArray)) return -1;
	
	$resultArray = array();
	if ($lang_id != 0) $langFilter = " AND lang = ".$lang_id; else $langFilter = "";
	
	while (list($varIndex, $varName) = each($valuesArray)) {
		$rows = $dbc->dbselect(array(
				"table"=>"variables",
				"select"=>"val",
				"where"=>"name = '".$var_name."'".$langFilter,
				"order"=>"name"
				)
			);
		$numRows = $dbc->count;
		
		if ($numRows > 0) {
			if ($numRows == 1) {
				$resultArray[$varName] = $rows[0]['val'];
			} else {
				$k = 0;
				foreach($rows as $row){
					$resultArray[$varName][$k] = $row['val'];
					$k++;
				}
			}
		}
	}
	
	return $resultArray;
}

function remove_brakes(&$item, $key) {
   	$item = str_replace("{", '', $item);
	$item = str_replace("}", '', $item);
	$item = "name = '".$item."'";
}

function parse_values($content) {
	global $dbc;
	preg_match_all("/{([A-Z0-9_]*)}/e", $content, $values);
	$tags = array_unique($values[0]);
	array_walk($tags, 'remove_brakes');
	$sql_where = string_build($tags, "OR");
	$values = array();
	if($sql_where!=''){
		$rows = $dbc->dbselect(array(
				"table"=>"variables",
				"select"=>"name, val",
				"joins"=>"",
				"where"=>"(".$sql_where.")"
				)
			);
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows as $row){
				 $values["{".$row['name']."}"] = $row['val'];
			}
		}
	}
	return strtr($content, $values);
}
?>
