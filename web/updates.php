<?php

require('../vendor/autoload.php');

include('./DB_config.php');
//$url = parse_url(getenv('DATABASE_URL'));

//リクエストの取得
$name = $_POST['name'];
$meigen = $_POST['meigen'];

//DBに接続
//local用
//$conn = pg_connect("host=localhost port=5432 dbname=db_test01 user=postgres password=g1o3o5d7");
//heroku postgres用
$conn = pg_connect(DEF_CONNECT_PARAM);
if (!$conn) {
    die('接続できませんでした');
}

//CREATE文
/*
$result = pg_query(
				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
				);
*/

//SELECT文
//$test = 'ギタ・ベリン';
//クエリ-の実行
$result = pg_query(
//				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
//				"SELECT name,meigen FROM kondo_local where name = $name;"
				"SELECT name,meigen FROM kondo_local where name = '$name';"
				);

//すでに同じものがあればflagを立てる
$flag=0;

//echo pg_num_rows($result);

if(pg_num_rows($result) != 0){
	for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
	    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
	    //$name = $rows['name'];
	    //$meigen = $rows['meigen'];
	    //DBから引っ張ってきた名言を整形
	    $query_meigen = $rows['meigen'];
	    $query_meigen1 = str_replace('。', '',$query_meigen);
	    $query_meigen2 = str_replace('、', '',$query_meigen1);
		/*
		echo $query_meigen2;
		echo strlen($query_meigen2);
		echo mb_strlen($query_meigen2);
		*/
		//入力された名言を整形
		$meigen_tmp1 = str_replace('。', '',$meigen);
		$meigen_tmp2 = str_replace('、', '',$meigen_tmp1);
		/*
		echo 'aaaaa';
		echo strlen($meigen_tmp2);
		echo mb_strlen($meigen_tmp2);
		*/
		//echo mb_strlen($query_meigen2);
		//echo 'aaaaa';
		//echo mb_strlen($meigen_tmp2);
		if(mb_strlen($query_meigen2) == mb_strlen($meigen_tmp2)){
			$flag=1;
			break;
		}
	}
}

//flagが1ならばDB内に、入力した名言と同じものがある(文字数が一緒なだけ。)
if($flag == 1){
	echo 'exist';
}else{
	//DBにないので、Insertする。
	$insert_result = pg_query(
//				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
//				"SELECT name,meigen FROM kondo_local where name = $name;"
				"insert into kondo_local (id, name, meigen)
					values (nextval('id_seq'), '$name', '$meigen');"
				);
	if (!$insert_result) {
		//失敗した際にseqの値を戻す必要もありそうだが。。。？
	    die('クエリーが失敗しました。'.pg_last_error());
	}
	echo ('success');
}


/*
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}
	echo ('success');
*/
?>
