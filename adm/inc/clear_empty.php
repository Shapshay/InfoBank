<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 24.05.2016
 * Time: 11:02
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("BDFunc.php");
$dbc = new BDFunc;

///////////////////////////////////////////////////////////////////

$rows = $dbc->dbselect(array(
        "table"=>"articles",
        "select"=>"id",
        "where"=>"title IS NULL"
    )
);
foreach($rows as $row){
    $rows2 = $dbc->dbselect(array(
            "table"=>"questions",
            "select"=>"id",
            "where"=>"art_id = ".$row['id']
        )
    );
    foreach($rows2 as $row2){
        $dbc->element_delete('questions',$row2['id']);
        $del_sql = "DELETE FROM answers WHERE q_id = ".$row2['id'];
        $dbc->db_free_del($del_sql);
    }
    $dbc->element_delete('articles',$row['id']);
}