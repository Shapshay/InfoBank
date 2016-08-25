<?php
# SETTINGS #############################################################################
$moduleName = "start";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "result" => $prefix . "result.tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################

$tpl->assign("TITLE", "Framework");

$tpl->parse("RESULT", ".".$moduleName."result");


$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>