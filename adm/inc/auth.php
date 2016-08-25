<?php
session_name('ROOT');
@session_start('ROOT');
function checkAuth() {
	global $dbc;
	if (!$_SESSION['lgn'] || !$_SESSION['psw']) return false;
	$resp = $dbc->element_find_by_field('site_setings','id',1);
	$secret = $resp['secret'];
	$rows = $dbc->dbselect(array(
			"table"=>"roots",
			"select"=>"id",
			"where"=>"login = '".$_SESSION['lgn']."' AND password = MD5('".$_SESSION['psw'].$secret."')",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return true;
	}
	else{
		return false;
	}
}

if (!checkAuth()) {
	echo '<script>window.location = "index.php"; </script>';
	exit;
}
?>