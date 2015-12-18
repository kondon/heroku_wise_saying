<?php

require('../vendor/autoload.php');

include('./DB_config.php');
//$url = parse_url(getenv('DATABASE_URL'));

//リクエストの取得
$id = $_POST['id'];
$meigen = $_POST['meigen'];

//DBに接続
//local用
//$conn = pg_connect("host=localhost port=5432 dbname=db_test01 user=postgres password=g1o3o5d7");
//heroku postgres用
$conn = pg_connect(DEF_CONNECT_PARAM);
if (!$conn) {
    die('接続できませんでした');
}

//SELECT文
//クエリ-の実行

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



/*
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}
	echo ('success');
*/
?>
