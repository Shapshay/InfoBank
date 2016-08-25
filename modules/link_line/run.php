<?php
	# SETTINGS #############################################################################
	$moduleName = "link_line";
	# MAIN #################################################################################
	$row = $dbc->element_find('pages',PAGE_ID);
	$url = "index.php?menu=".$row['id'];
	$url = getCodeBaseURL($url);
	$link_line = '<i class="link_line">›</i> <a href="'.$url.'"><span class="link_line">'.$row['title'].'</span></a>';
	$i = 1;
	$parent_page = $row['parent_id'];
	while ($parent_page!= 0){
			$row2 = $dbc->element_find('pages',$parent_page);
			$url = "index.php?menu=".$row2['id'];
			$url = getCodeBaseURL($url);
			$link_line = '<i class="link_line">›</i> <a href="'.$url.'"><span class="link_line">'.$row2['title'].'</span></a> '.$link_line;
			$parent_page = $row2['parent_id'];
			
			$i++;
	}
	$tpl->assign(strtoupper($moduleName), $link_line);
	
?>