<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("adm/inc/BDFunc.php");
require_once("adm/inc/RFunc.php");
$dbc = new BDFunc;
$rfq = new RFunc;
require_once('adm/inc/simple_html_dom.php');

//############### USERS #############################################

function getRootID($usrname) {
	global $dbc;
	$resp = $dbc->element_find_by_field('roots','login',$usrname);
	return $resp['id'];
}

function getRootName($usrname) {
	global $dbc;
	$resp = $dbc->element_find_by_field('roots','login',$usrname);
	return $resp['name'];
}

function isRolePage($role_id,$page_id) {
	global $dbc;
	$first = true;
	$values = '';
	if (is_array($role_id)) {
		foreach ($role_id as $value) {
			if ($first) {
				$values.= 'role_id='.$value;
				$first = false;
			} else {
				$values.=	" OR role_id=".$value;
			}
		}
	}
	else{
		$values.= 'role_id='.$role_id;
	}
	$rows = $dbc->dbselect(array(
			"table"=>"r_page_role",
			"select"=>"id",
			"where"=>"page_id = '".$page_id."' AND (".$values.")",
			"limit"=>"1"
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

// ID последнего лога пользователя
function getOperCurentMaxLog($oper_id){
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"calls_log",
			"select"=>"MAX(id) AS num",
			"where"=>"oper_id = ".$oper_id,
			"limit"=>"1"
		)
	);
	$row = $rows[0];
	return $row['num'];
}

// ID клиента по коду 1С
function getOperCode1CId($code) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"id",
			"where"=>"login_1C = '".$code."'",
			"limit"=>"1"
		)
	);
	$row = $rows[0];
	return $row['id'];
}

// проверяет наличие счетчика статистики оператора
function setDateCounter($oper_id, $count_type, $gn) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"oper_counter_log",
			"select"=>"id",
			"where"=>"oper_id = '".$oper_id."' AND gn = '".$gn."' AND count_type = ".$count_type." AND NOW() < ADDDATE(date, INTERVAL 12 HOUR)",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return false;
	}
	else{
		return true;
	}
}

// возвращает ID счетчика статистики
function setOperDateCounter($oper_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"oper_counter",
			"select"=>"id",
			"where"=>"oper_id = '".$oper_id."' AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

// подсчет количества по фродо
function OperCallFieldCount($field, $oper_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"frodo2",
			"select"=>"COUNT(".$field.") AS num",
			"where"=>"DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd")." AND ".$field."<>0 AND oper_id = ".$oper_id,
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['num'];
	}
	else{
		return 0;
	}
}
//############### ROUTING ###########################################

// получение настроек страницы
function getPageMenuTpl($menu_id = 0, $type='') {
	global $rfq;
	global $dbc;
	$page_arr = array();
	if(isset($_SESSION['lgn'])){
		$root = $rfq->get_start_page(ROOT_ID);
		$menu_parent = $root['page_id'];
	}
	else{
		$menu_parent = 0;
	}
	if($menu_id == 0){
		if($menu_parent==0){
			$row = $dbc->element_find_by_field('pages','start',1);
			$page_id = $row['id'];
			$page_tpl = $row[$type.'template'];
			$page_content = $row['content'];
			$page_title = $row['title'];
			$page_menu = $row['group_id'];
		}
		else{
			$row = $dbc->element_find('pages',$menu_parent);
			$page_id = $row['id'];
			$page_tpl = $row[$type.'template'];
			$page_content = $row['content'];
			$page_title = $row['title'];
			$page_menu = $row['group_id'];
		}
	}
	else{
		$row = $dbc->element_find('pages',$menu_id);
		$page_id = $row['id'];
		$page_tpl = $row[$type.'template'];
		$page_content = $row['content'];
		$page_title = $row['title'];
		$page_menu = $row['group_id'];
	}
	$page_arr['id'] = $page_id;
	$page_arr[$type.'template'] = $page_tpl;
	$page_arr['content'] = $page_content;
	$page_arr['title'] = $page_title;
	$page_arr['group_id'] = $page_menu;
	$page_arr['seo_title'] = $row['seo_title'];
	$page_arr['seo_key'] = $row['seo_key'];
	$page_arr['seo_desc'] = $row['seo_desc'];
	$page_arr['parent_id'] = $row['parent_id'];
	return $page_arr;
}

// получение ID первой вложеной страницы (проверка ее существования)
function getPageFirstChildrenID($page_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"pages",
			"select"=>"id",
			"where"=>"parent_id = '".$page_id."'",
			"order"=>"sortfield",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return $rows[0]['id'];
	}
	return 0;
}

// Возвращает ID родительской машины
function getPageParentID($page_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"pages",
			"select"=>"parent_id",
			"where"=>"id = '".$page_id."'",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return $rows[0]['parent_id'];
	}
	return 0;
}

// получение заголовка страницы
function getPageTitle($page_id) {
	global $dbc;
	$resp = $dbc->element_find('pages',$page_id);
	return $resp['title'];
}

// получаем номер сортировки
function getNewSortfield(){
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"pages",
			"select"=>"MAX(sortfield) AS num"
			)
		);
	$row = $rows[0];
	$cur_max = $row['num'];
	$newSort = $cur_max + 1;
	return $newSort;
}

// возражает ссылку на страницу
function getPageTitleLink($title, $page_id, $class) {
	$baseURL = 'index.php?menu_id='.$page_id;
	$url = getCodeBaseURL($baseURL);
	$link = '<a href="'.$url.'" class="'.$class.'">'.$title.'</a>';
	return $link;
}

// Цепочка ссылок для страницы PARENT / CHILD / .. / SUBCHILD
function getPagesChains($page_id, $delimiter, $css_class, $uri_params) {
	global $dbc;
	$items = array(1=> array("title" => "", "url" => ""));
	$items[1]['title'] = '<strong>'.getPageTitle($page_id).'</strong>';
	$items[1]['url'] = '';
	$parent_id = getPageParentID($page_id);
	$i = 1;
	while ($parent_id > 0) {
		$row = $dbc->element_find('pages',$parent_id);
		$numRows = $dbc->count; 
		if ($numRows > 0) {
			$row2 = $dbc->element_find('pages',$row['id']);
			$numRows = $dbc->count; 
			if ($numRows > 0) {
				$i++;
				$parent_id = $row2['parent_id'];
				$items[$i]['title'] = $row['title'];
				$url = 'index.php?menu_id='.$row2['id'];
				//if (!empty($uri_params)) $url.= '&'.$uri_params;
				$url = getCodeBaseURL($url);
				$items[$i]['url'] = $url;
			} else { $parent_id = 0; }
		} else { $parent_id = 0; }
	}
	$str = '';
	$items_count = count($items);
	if ($items_count > 1) $items_count = $items_count - 1;
	for ($i = $items_count; $i > 0; $i--) {
		$title = $items[$i]['title'];
		
		if ($i != 1) $str.= '<a href="'.$items[$i]['url'].'" class="'.$css_class.'">'.$title.'</a>';
			else $str.= $title;
		
		if ($i != 1) $str.= '<span class="'.$css_class.'">'.$delimiter.'</span>';
	}
	return $str;
}

// заголовок элемента
function getItemTitle($table, $item_id) {
	global $dbc;
	$row = $dbc->element_find($table,$item_id);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		if ($row['title'] != '') return $row['title'];
		else return '';
	}
	return '';
}

//############### VALUES ###########################################

// модернизированный implode
function string_build($param_array, $operand) {
	$condition_array = array();
	foreach ($param_array as $param) {
		if ($param != '') $condition_array[] = $param;
	}
	$res = implode(" ".$operand." ", $condition_array);
	return $res;
}

// чистим текстовую $_GET[]
function SuperSaveGETStr($name) {
	//$name = preg_replace("/[^a-zA-ZА-Яа-я0-9_]/","",$name);
	$name = preg_replace("/[^a-zA-Z0-9_]/","",$name);
	return $name;
}

// Чистим числовую переменную
function SuperSaveInt($name) {
	$name = strip_tags($name);
	$name = trim($name);
	$name = iconv("utf-8", "windows-1251", $name);
	$name = preg_replace("/[^0-9]/i", "", $name);
	$name = iconv("windows-1251", "utf-8", $name);
	return $name;
}

// чистим текст
function SuperSaveStr($name) {
	$name = strip_tags($name);
	$name = trim($name);
	$name = preg_replace("/[^\x20-\xFF]/","",@strval($name));
	return $name;
}

//############### CURL ###########################################

// поиск файлов в папках на сервере
function find($dir, $tosearch) { 
	global $file_arr;
	$files = array_diff( scandir( $dir ), Array( ".", ".." ) );     
	foreach( $files as $d ) { 
	    if( !is_dir($dir."/".$d) ) { 
	         if ($d == $tosearch) 
	             $file_arr[] = $dir."/".$d; 
	     } else { 
	         $res = find($dir."/".$d, $tosearch); 
	     } 
	} 
	return $file_arr; 
}

// получение страницы через GET
function get_web_page( $url ){
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
	
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");
	
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );
	
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	
	return $header;
}

// получение страницы через POST
function post_content ($url,$postdata) {
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
	
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");
	
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );
	
	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	
	return $header;
}

//############### STATISTIC ###########################################

// возращает было ли посещение страницы сайта сегодня с данного IP
function getIPtoSiteStatisticID($ip, $menu, $item_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"stat",
			"select"=>"id",
			"where"=>"ip = '".$ip."' AND menu = '".$menu."' AND item_id = '".$item_id."' AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"limit"=>"1"
			)
		);
	$numRows = $dbc->count; 
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

//############### SOAP ###################################################

// SOAP объект в массив
function objectToArray($d) {
	if (is_object($d)) {
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		return array_map(__FUNCTION__, $d);
	}
	else {
		return $d;
	}
}

// SOAP std в массив
function stdToArray($obj){
	$rc = (array)$obj;
	foreach($rc as $key => &$field){
		if(is_object($field))$field = $this->stdToArray($field);
	}
	return $rc;
}

// SOAP штрафов по Алматы
function sendSoap($name, $phone, $gn, $pn, $email = null){
	//	$name = 'Alexey';
	//	$phone = '87078071312';
	//	$gn = 'A375YHN';
	//	$pn = 'AV00138647';
	//	$email = 'Null';

	$client = new SoapClient("http://89.218.11.74/soap_server/wsdl");
	$answer = $client->get_info1($name, $phone, $gn, $pn, $email);

	return base64_decode($answer['return']);
}

// SOAP штрафов с городом
function sendSoapFull($name, $phone, $gn, $pn, $email = null, $city){
	//	$name = 'Alexey';
	//	$phone = '87078071312';
	//	$gn = 'A375YHN';
	//	$pn = 'AV00138647';
	//	$email = 'Null';

	$client = new SoapClient("http://89.218.11.74/soap_server/wsdl");
	$answer = $client->get_info2($name, $phone, $gn, $pn, $email, $city);

	return base64_decode($answer['return']);
}

//############### Работа с клиентами ###################################################

// ID клиента по коду 1С
function getClientID($code_1C) {
	global $dbc;
	$row = $dbc->element_find_by_field('clients','code_1C',$code_1C);
	return $row['id'];
}

// ID телефона клиента по номеру
function getClientPhoneID($c_id, $phone) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"phones",
			"select"=>"id",
			"where"=>"client_id = '".$c_id."' AND phone = '".$phone."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

//############### Работа с катинками ###################################################

// валидация типа файла
function validateFileType($type) {
	switch ($type) {
		case 'image/gif': return 'GIF'; break;
		case 'image/bmp': return 'BMP'; break;
		case 'image/pjpeg': return 'JPG'; break;
		case 'image/jpeg': return 'JPG'; break;
		case 'image/x-png': return 'PNG'; break;
		case 'application/x-shockwave-flash': return 'SWF'; break;
	}
	return false;
}

// получение нового имени для файла
function getFilename($fname, $ext = '', $folder) {
	if ($ext == '') $extension = getFileExt($fname); else $extension = $ext;
	$i = 1;
	$newFileName = $i.".".$extension;
	while (is_file($folder.$i.".".strtolower($extension)) || is_file($folder.$i.".".strtoupper($extension))) {
		$i++;
		$newFileName = strtolower($i.".".$extension);
	}
	return $newFileName;
}

// получение расширения файла
function getFileExt($fname) {
	$path_parts = pathinfo($fname);
	if (is_array($path_parts)) {
		return strtolower($path_parts["extension"]);
	}
}

// вычисление пропорциональных размеров
function resizeProportional($srcW, $srcH, $dstW, $dstH) {
	$d = max($srcW/$dstW, $srcH/$dstH);
	$result[] = round($srcW/$d);
	$result[] = round($srcH/$d);
	return $result;
}

// создание изображения
function ImageCreateTrue($width, $height, $type) {
	switch ($type) {
		case 1: return ImageCreate($width, $height); break;
		case 2: return ImageCreateTrueColor($width, $height); break;
		case 3: return ImageCreateTrueColor($width, $height); break;
	}
}

// инициализация типа изображения
function ImageCreateFrom($filename, $type) {
	switch ($type) {
		case 1: return imagecreatefromgif($filename); break;
		case 2: return imagecreatefromjpeg($filename); break;
		case 3: return imagecreatefrompng($filename); break;
	}
}

// финальное сохранение картинки
function Image($src, $file, $type) {
	switch ($type) {
		case 1: return ImageGif($src, $file); break;
		case 2: return ImageJPEG($src, $file, 88); break;
		case 3: return ImagePNG($src, $file); break;
	}
}

//############### Работа с тестами ###################################################

// проверка пройдености задачи пользователем
function setUserTaskClose($u_id, $comp, $task, $art_id){
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"comp_log",
			"select"=>"id",
			"where"=>"u_id = ".$u_id." AND comp_id = ".$comp." AND task_id = ".$task." AND art_id = ".$art_id,
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

?>