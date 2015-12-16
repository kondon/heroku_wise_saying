<?php

require('../vendor/autoload.php');

$url = parse_url(getenv('DATABASE_URL'));
/*
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));

$pdo = new PDO($dsn, $url['user'], $url['pass']);
var_dump($pdo->getAttribute(PDO::ATTR_SERVER_VERSION));

//debug用
echo($url['host']."</br>");
echo($url['port']."</br>");
echo($url['user']."</br>");
echo($url['pass']."</br>");

var_dump($url);
echo($url['path']."</br>");
echo("host=".$url['host']." port=".$url['port']." dbname=".substr($url['path'], 1)." user=".$url['user']." password=".$url['pass']."</br>");
*/



//DBに接続
//local用
//$conn = pg_connect("host=localhost port=5432 dbname=db_test01 user=postgres password=g1o3o5d7");
//heroku postgres用
$conn = pg_connect("host=".$url['host']." port=".$url['port']." dbname=".substr($url['path'], 1)." user=".$url['user']." password=".$url['pass']);
if (!$conn) {
    die('接続できませんでした');
}

//ランダムに出力するための、テーブルサイズ取得
$result = pg_query(
				"SELECT count(*)
				FROM kondo_local;"
				);

$rows = pg_fetch_array($result, NULL, PGSQL_NUM);

//echo $rows['0'];
$randam = rand(1,$rows['0']);

//CREATE文
/*
$result = pg_query(
				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
				);
*/

//SELECT文

//クエリ-の実行
$result = pg_query(
//				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
				"SELECT name,meigen FROM kondo_local where id = $randam;"
				);

//表示に使う変数格納用
$name;
$meigen;

for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    $name = $rows['name'];
    $meigen = $rows['meigen'];
}
/*
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}
	echo ('success');
*/
?>


<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
</head>

<body>
	<div class="container">
		<h3>
			<?php echo $name;?>より
		</h3>
	</div>
	
	<header class="jumbotron">
		<div class="container">
			<h1><?php echo $meigen;?></h1>
			<!--
			<p>あなたのクローゼットの中身を教えてください♡</p>
			<p><a class="btn btn-lg midashi-btn" role="button">もっと詳しく &raquo;</a></p>
			-->
		</div>
	</header>
	<div class = "container">
		<div class="panel-group">
		  <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#acc1">
						 新しい名言を
					</a>
				</h4>
			</div>
			<div id="acc1" class="panel-collapse collapse">
				<div class="panel-body">
					<table class = "table table-bordered table-striped" id="dataTable">
						<thead>
							<tr>
								<th class=" col-xs-3 col-sm-3 col-md-3 col-lg-3">名前</th>
								<th class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">名言</th>
								<th class=" col-xs-1 col-sm-1 col-md-1 col-lg-1">追加</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class=" col-xs-3 col-sm-3 col-md-3 col-lg-3"><input type="text" id="name" style = "width: 100%" value="" ></td>
								<td class=" col-xs-8 col-sm-8 col-md-8 col-lg-8"><input type="text" id="meigen" style = "width: 100%" value="" ></td>
								<td class=" col-xs-1 col-sm-1 col-md-1 col-lg-1"><button value="追加" id="insert" onClick="insert()">
										<span class="glyphicon glyphicon-ok"></span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		  </div>
		  <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#acc2" id="view">
						 一覧表示
					</a>
				</h4>
	    	</div>
	    	<div id="acc2" class="panel-collapse collapse">
	      		<div class="panel-body">
	      			<table class = "table table-bordered table-striped" id="dataTable">
						<thead>
							<tr>
								<th>NO</th>
								<th>名前</th>
								<th>名言</th>
								<th>更新</th
							</tr>
						</thead>
						<tbody id="meigen_table">
							<?php
      			
			      				$result_view = pg_query(
								//				"CREATE TABLE kondo_test_auau(id integer,name text,password text);"
												"SELECT id,name,meigen FROM kondo_local order by 1;"
												);
								$view_id;
								$view_name;
								$view_meigen;
								
								for ($i = 0 ; $i < pg_num_rows($result_view) ; $i++){
								    $rows_view = pg_fetch_array($result_view, NULL, PGSQL_ASSOC);
									$view_id = $rows_view['id'];
								    $view_name = $rows_view['name'];
								    $view_meigen = $rows_view['meigen'];
									echo "<tr>";
									echo "<td>$view_id</td>";
				      				echo "<td>$view_name</td>";
				      				echo "<td>$view_meigen</td>";
				      				echo '<td><button value="更新" onClick="update()">　<span class="glyphicon glyphicon-user"></span>　</button></td>';
				      				echo "</tr>";
								}
			      				
			      			?>
						</tbody>
					</table>
					<!--
					<ul class="pagination">
						<li class="disabled" id="head"><a href="#">&laquo;</a></li>
						<li class="active" id="1"><a href="#">1<span class="sr-only">(現位置)</span></a></li>
						<li id="2"><a href="#">2</a></li>
						<li id="3"><a href="#">3</a></li>
						<li id="4"><a href="#">4</a></li>
						<li id="5"><a href="#">5</a></li>
						<li id="back"><a href="#">&raquo;</a></li>
					</ul>
					-->
	      		</div>
	    	</div>
	      </div>
		</div>
	</div>
	<script src="js/function.js" type="text/javascript"></script>
</body>
