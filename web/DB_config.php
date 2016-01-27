<?php

/****
DB情報を取得する。
****/

//heroku上の環境変数からpostgresの接続パラメータを取得

$url = parse_url(getenv('DATABASE_URL'));
$param = "host=".$url['host']." port=".$url['port']." dbname=".substr($url['path'], 1)." user=".$url['user']." password=".$url['pass'];


//local環境でのpostgresの接続パラメータを取得

//$param = "host=localhost port=5432 dbname=db_test01 user=postgres password=g1o3o5d7";

define("DEF_CONNECT_PARAM", $param);
?>
