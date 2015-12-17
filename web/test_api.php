<?php

require('../vendor/autoload.php');

include('./DB_config.php');
//$url = parse_url(getenv('DATABASE_URL'));
$response = array();
$id = NULL;
$name = NULL;

$search;	//searchが1の場合はid検索。2の場合はname検索

/**
 * empty関数は数字の0,文字の0も値がないと判定するっぽい。
 * 仕様によって、isset,empty,!,is_nullとかは使い分けが必要そう。
 */
if($_SERVER["REQUEST_METHOD"] != "POST"){
    //echo"a";
	/*
	 * このやり方だとパラメータで0が入ってくるとどうしてもNULLと同じ扱いになる。
	 * phpでこれを回避している人・・・
	 */
	/*
    // パラメータの取得
    if( isset($_GET["id"]) ){
		$id = $_GET["id"];
		echo"b";
	}
	if( isset($_GET["name"]) ){
		$name = $_GET["name"];
		echo"c";
	}
	//パラメータチェック
	if (compact('id') && !$id){
//	if( empty($id) ){
		if (compact('name') && !$name){
			//両方がない場合、エラーになる。
			echo"d";
			$response['status'] = "Not Param";
			response_json();

		}else{
			echo"e";
			search_check(1,$name);
			$search = 2;

		}
	}else{
		search_check(0,$id);
		$search = 1;
		echo"f";
		if (compact('name') && $name ){
			echo"h";
		}else{
			search_check(1,$name);
			echo"i";
		}
	}
	*/
	//もう値の有無は無視。GETパラメータが存在するかということにする。
	if( isset($_GET["id"]) ){
		//idに値があれば、id検索を優先。
		$id=$_GET["id"];
		search_check(0,$id);
		$search = 1;
		//echo"b";
		if( isset($_GET["name"]) ){
			$name = $_GET["name"];
			search_check(1,$name);
			//echo"c";
		}
	}else{
		if( isset($_GET["name"]) ){
			//idに値がなく、nameni値がある。
			//echo"d";
			$name = $_GET["name"];
			search_check(1,$name);
			$search = 2;
		}else{
			//両方ないパターン
			//echo"e";
			$response['status'] = "Not Param";
			response_json();;
		}
	}


}else{
    // フォームからPOSTによって要求された場合
    $resonse['status'] = "Only Get Method";
	response_json();
}

//header取得
if(!function_exists('getallheaders')) {
	$request_header = getallheaders_tmp();
}else{
	$request_header = getallheaders();
}

//DBに接続
//local用
//$conn = pg_connect("host=localhost port=5432 dbname=db_test01 user=postgres password=g1o3o5d7");
//heroku postgres用
$conn = pg_connect(DEF_CONNECT_PARAM);
if (!$conn) {
    die('接続できませんでした');
}

$query ="";
//検索方法によって、queryを変更する
if($search == 1){				//id検索
	$query = "SELECT id,name,meigen FROM kondo_local where id = '$id';";
}else if($search == 2){			//name検索
	$query = "SELECT id,name,meigen FROM kondo_local where name = '$name';";
}else{
	$response['status'] = "Server Error";
}

//CREATE文
/*
$result = pg_query(
				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
				);
*/

//SELECT文

//クエリ-の実行
$result = pg_query(
				$query
				);

//表示に使う変数格納用
$select_id;
$select_name;
$select_meigen;

if(pg_num_rows($result) == 0){
	$response['status'] = "No Hits";
	response_json();
}else{
	for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
	    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
	    $select_id = $rows['id'];
	    $select_name = $rows['name'];
	    $select_meigen = $rows['meigen'];
		$response[$i] = array('id' => $select_id,
								'name' => $select_name,
								'meigen' => $select_meigen);
	}
	//var_dump($response);
	response_json();
}
/*
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}
	echo ('success');
*/

function getallheaders_tmp()
{
      $headers = '';
   foreach ($_SERVER as $name => $value)
   {
       if (substr($name, 0, 5) == 'HTTP_')
       {
           $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
       }
   }
   return $headers;
}

function search_check($flag,$var){
	global $response;

	if($flag == 0){	//idのチェック
		if($var < 0){
			$response["status"] = "Invalid Request ID";
			response_json();
		}else{

		}
	}else{			//nameのチェック

	}
}

function response_json(){
	global $response;

	$response = json_encode($response, JSON_UNESCAPED_UNICODE);
	header('Content-type: application/json; charset = UTF-8');
	echo $response;
	exit();
}
?>
