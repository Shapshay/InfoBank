<?php 
/**
* 	Здесь функции для работы с правами
*/
class RFunc
{
	
	static $dbc;

	function __construct()
	{
		# code...
	}

	public function initialise()
	{
		$dbc = new BDFunc;
		//поля для таблицы пользователи
		$fields = array(
			"name"=>"varchar(150)",
			"login"=>"varchar(20)",
			"password"=>"varchar(50)",
			"root"=>"tinyint",
			"page_id"=>"int(11)"
			);
		$dbc->table_create("users",$fields);
		//echo $dbc->status;
		$dbc->table_create_uniq_index("users","login");
		//echo $dbc->status;
		//поля для таблицы роли
		$fields = array(
			"name"=>"varchar(150)",
			"description"=>"varchar(150)"
			);
		$dbc->table_create("r_role",$fields);
		//echo $dbc->status;
		$dbc->table_create_uniq_index("r_role","name");
		//echo $dbc->status;
		//поля для таблицы роли пользователей
		$fields = array(
			"user_id"=>"INT(11)",
			"role_id"=>"INT(11)"
			);
		$dbc->table_create("r_user_role",$fields);
		//echo $dbc->status;
		$dbc->table_create_uniq_index("r_user_role","user_role","user_id","role_id");
		//echo $dbc->status;
		//поля для таблицы роли страницы
		$fields = array(
			"page_id"=>"INT(11)",
			"role_id"=>"INT(11)"
			);
		$dbc->table_create("r_page_role",$fields);
		//echo $dbc->status;
		$dbc->table_create_uniq_index("r_page_role","page_role","page_id","role_id");
		//echo $dbc->status;
	}

	public function addUser($name,$login,$password,$root,$page_id)
	{
		$dbc = new BDFunc;
		//поля для таблицы пользователи
		$fields = array(
			"name"=>$name,
			"login"=>$login,
			"password"=>$password,
			"root"=>$root,
			"page_id"=>$page_id
			);
		$dbc->element_create("users",$fields);
		//echo $dbc->status;
	}

	public function delUser($login)
	{
		$dbc = new BDFunc;
		//поля для таблицы пользователи
		if ($element=$dbc->element_find_by_field("user","login",$login)) {
			$dbc->element_delete("users",$element["id"]);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}	
	}

	public function updateUser($name,$login,$password,$root,$page_id)
	{
		$dbc = new BDFunc;
		//поля для таблицы пользователи
		$fields = array(
			"name"=>$name,
			"login"=>$login,
			"password"=>$password,
			"root"=>$root,
			"page_id"=>$page_id
			);
		if ($element=$dbc->element_find_by_field("users","login",$login)) {
			$dbc->element_update("users",$element["id"],$fields);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}
	}

	public function addRole($name,$description)
	{
		$dbc = new BDFunc;
		$fields = array(
			"name"=>$name,
			"description"=>$description
			);
		$dbc->element_create("r_role",$fields);
		//echo $dbc->status;	
	}	

	public function delRole($name)
	{
		$dbc = new BDFunc;
		if ($element=$dbc->element_find_by_field("r_role","name",$name)) {
			$dbc->element_delete("r_role",$element["id"]);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}	
	}

	public function updateRole($name,$description)
	{
		$dbc = new BDFunc;

		$fields = array(
			"name"=>$name,
			"description"=>$description
			);
		if ($element=$dbc->element_find_by_field("r_role","name",$name)) {
			$dbc->element_update("r_role",$element["id"],$fields);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}
	}

	public function set_role_user($role_id,$user_id)
	{
		$dbc = new BDFunc;
		$fields = array(
			"role_id"=>$role_id,
			"user_id"=>$user_id
			);
		$dbc->element_create("r_user_role",$fields);
		//echo $dbc->status;		
	}

	public function drop_role_user($role_id,$user_id)
	{
		$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_user_role",
			"where"=>array(
				"role_id = ".$role_id,
				"user_id = ".$user_id
				)
			)
		);
		$element=array_shift($elements);

		if ($element) {
			$dbc->element_delete("r_user_role",$element["id"]);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}	
	}

	public function set_role_page($role_id,$page_id)
	{
		$dbc = new BDFunc;
		$fields = array(
			"role_id"=>$role_id,
			"page_id"=>$page_id
			);
		$dbc->element_create("r_page_role",$fields);
		//echo $dbc->status;		
	}

	public function drop_role_page($role_id,$page_id)
	{
		$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_page_role",
			"where"=>array(
				"role_id = ".$role_id,
				"page_id = ".$page_id
				)
			)
		);
		$element=array_shift($elements);

		if ($element) {
			$dbc->element_delete("r_page_role",$element["id"]);
			//echo $dbc->status;
		} else {
			//echo "Not found";
			//echo $dbc->status;
		}	
	}

	public function list_roles()
	{
		$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_role"
			)
		);		
		return $elements;
	}

public function list_pages_by_role($role_id)
{
	$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_page_role",
			"select"=>"pages.*",
			"where"=>"r_page_role.role_id=".$role_id,
			"joins"=>"LEFT OUTER JOIN pages on r_page_role.page_id=pages.id"
			)
		);		
		return $elements;
}

public function list_users_by_role($role_id)
{
	$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_user_role",
			"select"=>"users.*",
			"where"=>"r_user_role.role_id=".$role_id,
			"joins"=>"LEFT OUTER JOIN users on r_user_role.user_id=users.id"
			)
		);		
		return $elements;
}

public function is_permission($user_id,$page_id)
{
		$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"r_user_role",
			"select"=>"*",
			"where"=>"r_page_role.page_id=".$page_id." AND r_user_role.user_id=".$user_id,
			"joins"=>"INNER JOIN r_page_role ON r_user_role.role_id=r_page_role.role_id"
			)
		);		
		if ($dbc->count>0) {
			return true;
		} else {
			return false;
		}
}

public function get_start_page($user_id)
{
	$dbc = new BDFunc;
		$elements=$dbc->dbselect(array(
			"table"=>"users",
			"select"=>"page_id",
			"where"=>"user_id=".$user_id
			)
		);		
		return $elements;	
}

}

?>