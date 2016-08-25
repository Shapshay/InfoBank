<?php
$moduleName = "page_title";

# SETTINGS ##############################################################################

// Типы заголовков
// 1: Только название страницы
// 2: С сылкой на родительскую
// 3: Полный путь от корня сайта
$_titleMode = 1;

//	CSS-класс для ссылок
$_cssLinkClass = 'page_title';

// разделитель вложений
$_pageSplitter = ' / ';

# MAIN ##################################################################################

$title = '';

switch ($_titleMode) {
	case 1: { 
		$title = $page_arr['title'];
		break;
	}
	case 2: {
		$title = getPageTitle($page_arr['parent_id']);
		if ($title) {
			$title = getPageTitleLink($title, $page_arr['parent_id'], $_cssLinkClass);
			$title.= $_pageSplitter.$page_arr['title'];
		} else {
			$title = $page_arr['title'];
		}
		break;
	}
	case 3: {
		$title = getPagesChains(PAGE_ID, $_pageSplitter, $_cssLinkClass, '');
		break;
	}
}

$tpl->assign(strtoupper($moduleName), $title);
?>