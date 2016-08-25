<?php 
//заполнение настроек программы, создание бзы данных, создание пользователя по умолчанию
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once "inc/BDFunc.php";
$dbc = new BDFunc;

$form_string='	<form action="setup.php" method="POST">
		<label>Имя сервера</label></br>
		<input type="text" name="servername" placeholder="Введите адрес сервера БД"/></br>
		<label>Имя пользователя</label></br>
		<input type="text" name="username" placeholder="Введите имя пользователя сервера БД"/></br>
		<label>Пароль</label></br>
		<input type="text" name="password" placeholder="Введите пароль пользователя"/></br>
		<label>Имя базы данных</label></br>
		<input type="text" name="bdname" placeholder="Введите имя БД"/></br>
		<input type="checkbox" name="createdb"/>
		<label>Создавать БД</label></br>
		<input type="submit" value="Записать">
	</form>
';

function createTable()
{
	//создадим основные таблицы
	/*
	*acts
	*articles
	*galery
	*page_group
	*pages
	*r_log
	*roots
	*site_setings
	*tpl_groups
	*variables

	еще необходимо чтоб при установке пользователь заполнял таблицу 
	site_setings
	*/
global $dbc;
	echo "Creating acts </br>";
	$fields = array(
		"title"=>"varchar(255)"
		);
	$dbc->table_create("acts",$fields);
	echo $dbc->status." </br>";
	
	echo "Creating articles </br>";
	$fields = array(
		'page_id'=> 'int(10)',
		'date' => 'datetime',
		'title'=> 'varchar(255)',
		'icon' => 'varchar(255)',
		'description' =>'text',
		'content'=> 'text',
		'meta_key'=> 'varchar(255)',
		'view' =>"tinyint(2) unsigned DEFAULT '1'",
		'chpu' =>"varchar(255) DEFAULT ''"
		);
	$dbc->table_create("articles",$fields);
	echo $dbc->status." </br>";

	echo "Creating galery </br>";
	$fields = array(
		'page_id'=> "int(10) unsigned DEFAULT '0'",
		'date' =>"datetime DEFAULT '0000-00-00 00:00:00'",
		'title'=>"varchar(255) NOT NULL",
		'small_icon' =>"varchar(255) DEFAULT ''",
		'big_icon' =>"varchar(255) DEFAULT ''",
		'description' =>"text",
		'view' =>"tinyint(2) unsigned DEFAULT '1'"
		);
	$dbc->table_create("galery",$fields);
	echo $dbc->status." </br>";

	echo "Creating page_group </br>";
	$fields = array(
		"title"=>"varchar(255)"
		);
	$dbc->table_create("page_group",$fields);
	echo $dbc->status." </br>";

	echo "Creating pages </br>";
	$fields = array(
		'parent_id' =>"int(10) unsigned DEFAULT '0'",
		'group_id' =>"int(10) unsigned DEFAULT '1'",
		'title' =>"varchar(255) DEFAULT 'New'",
		'icon' =>"varchar(255) DEFAULT ''",
		'icon2' =>"varchar(255) DEFAULT ''",
		'content' =>"varchar(255) DEFAULT ''",
		'template' =>"varchar(100) DEFAULT 'default.tpl'",
		'stemplate' =>"varchar(100) DEFAULT 'default.tpl'",
		'chpu' =>"varchar(255) DEFAULT ''",
		'sortfield' =>"int(10) unsigned DEFAULT '0'",
		'view' =>"tinyint(2) unsigned DEFAULT '1'",
		'start' =>"tinyint(2) unsigned DEFAULT '0'",
		'description' =>"text",
		'rights' =>"tinyint(2) unsigned DEFAULT '0'",
		'seo_title' =>"varchar(255) DEFAULT ''",
		'seo_key' =>"varchar(255) DEFAULT ''",
		'seo_desc' =>"varchar(255) DEFAULT ''"
		);
	$dbc->table_create("pages",$fields);
	echo $dbc->status." </br>";

	echo "Creating r_log </br>";
	$fields = array(
		'r_id' =>"int(10) unsigned DEFAULT '1'",
		'act_id' =>"int(10) unsigned DEFAULT '0'",
		'date_log' =>"datetime DEFAULT '0000-00-00 00:00:00'",
		'koment' =>"varchar(255) DEFAULT ''"
		);
	$dbc->table_create("r_log",$fields);
	echo $dbc->status." </br>";

	echo "Creating roots </br>";
	$fields = array(
		'reg_date' =>"datetime DEFAULT '0000-00-00 00:00:00'",
		'login' =>"varchar(20) NOT NULL DEFAULT ''",
		'password' =>"varchar(50) NOT NULL DEFAULT ''",
		'rights' =>"int(2) unsigned DEFAULT '0'"
		);
	$dbc->table_create("roots",$fields);
	echo $dbc->status." </br>";

	echo "Creating site_setings </br>";
	$fields = array(
		'company' =>"varchar(255) DEFAULT ''",
		'logo' =>"varchar(255) DEFAULT ''",
		'slogan' =>"varchar(255) DEFAULT ''",
		'tpl_group_id' =>"int(10) unsigned DEFAULT '1'",
		'email' =>"varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT ''",
		'meta_desc' =>"varchar(255) DEFAULT ''",
		'meta_key' =>"varchar(255) DEFAULT ''",
		'adress' =>"varchar(255) DEFAULT ''",
		'jtime' =>"varchar(255) DEFAULT ''",
		'phone' =>"varchar(255) DEFAULT ''"
		);
	$dbc->table_create("site_setings",$fields);
	echo $dbc->status." </br>";
	
	echo "Creating tpl_groups </br>";
	$fields = array(
		'title' =>"varchar(255) DEFAULT ''",
		'tpl_folder' =>"varchar(100) DEFAULT ''"
		);
	$dbc->table_create("tpl_groups",$fields);
	echo $dbc->status." </br>";
	
	echo "Creating variables </br>";
	$fields = array(
		'page_id' =>"int(10) unsigned DEFAULT '0'",
		'name' =>"varchar(255) DEFAULT ''",
		'val' =>"text",
		'description' =>"varchar(255) DEFAULT ''"
		);
	$dbc->table_create("variables",$fields);
	echo $dbc->status." </br>";

}

?>
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="Content-Language" content="ru"/>
</head>
<body>
	<div><h1>Первоначальные настройки программы</h1></div>
	<?php 
	if (isset($_POST["servername"])) {
		$server_name=$_POST["servername"];
		$user_name=$_POST["username"];
		$password=$_POST["password"];
		$bd_name=$_POST["bdname"];
		$conn = new mysqli($server_name, $user_name, $password);

		// Oh no! A connect_errno exists so the connection attempt failed!
		if ($conn->connect_errno) {
		    // The connection failed. What do you want to do? 

		    // Let's try this:
			echo "Sorry, this website is experiencing problems.";

		    // Something you should not do on a public site, but this example will show you
		    // anyways, is print out MySQL error related information -- you might log this
			echo "Error: Failed to make a MySQL connection, here is why: \n";
			echo "Errno: " . $conn->connect_errno . "\n";
			echo "Error: " . $conn->connect_error . "\n </br>";

		    // You might want to show them something nice, but we will simply exit
			exit;
		} else {
			echo 'Connected to server </br>';
		}
		//create bd
		//print_r($_POST);
		if (isset($_POST["createdb"])) {
			
			$sql = "CREATE DATABASE ".$bd_name;
			//echo $sql;
			if ($conn->query($sql)) {
				echo "Database created successfully </br>";
			} else {
				echo sprintf("Error creating database - SQLSTATE %s.\n </br>", $conn->sqlstate);
			}
		}
		$dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
		$dom->formatOutput=true;
		$config = $dom->createElement("config"); 
		$dom->appendChild($config);
		$bdconfig = $dom->createElement("bd_config");
		$config->appendChild($bdconfig);

		$server_name1 = $dom->createElement("server_name", $server_name); 
		$user_name1 = $dom->createElement("user_name", $user_name); 
		$password1 = $dom->createElement("password", $password); 
		$bd_name1 = $dom->createElement("bd_name", $bd_name); 

		$bdconfig->appendChild($server_name1); 
		$bdconfig->appendChild($user_name1); 
		$bdconfig->appendChild($password1); 
		$bdconfig->appendChild($bd_name1); 
  	$dom->save("config.xml"); // Сохраняем полученный XML-документ в файл
  	createTable();
  	
  	} else {
  		echo $form_string; 
  	}

  	?>
  </body>
  </html>
