<?php
# SETTINGS #############################################################################
$moduleName = "mac_os";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
));

# MAIN #################################################################################

if(in_array(8,$USER_ROLE)||in_array(9,$USER_ROLE)){
	//$tpl->parse(strtoupper($moduleName), ".".$moduleName);
	$tpl->assign(strtoupper($moduleName), "");
}
else{
	$tpl->assign(strtoupper($moduleName), "");
}


?>