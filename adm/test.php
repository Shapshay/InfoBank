<?php 
/*
  require "BDFunc.php";

  $resp = new BDFunc;

  //print_r($resp->conn);

  //$resp->table_delete("dima");
  //$columns = array('name' => 'CHAR(100)' );
  //$resp->table_create("dima",$columns);

  //$values = array('name' => 'Dima' );
  //$resp->element_create("dima",$values);
  //print_r($values);
  //$resp->element_update("dima",1,$values);
  //$resp->element_delete("dima",2);
  //$el = $resp->element_find("dima",2);
  //print_r($el);

  //$arr= array("id=1","name='Dima'");
  //$arr = array("id=1"."name='Dima'");
  //print_r(implode(",",$arr));

  $sql = $resp->dbselect(array(
      "table"=>"dima",
      "where"=>"name = 'Dima'"
          ));
  foreach ($sql as $value) {
    print_r($value);
  }
  //print_r ($sql);
  
  $secret = "IIib@v~X"; // Секретное слово
  $password = "akk2016"; // Пароль
  echo md5($password.$secret); // Результат хэширования
  */
  phpinfo();
?>