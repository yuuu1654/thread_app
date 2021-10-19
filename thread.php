<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	//空でスレッド検索ボタンを押したらエラーを返す
	// if( !$_POST["word"] ){
	// 	$errmessage[] = "キーワードを入力して下さい";
	// }

	if( isset($_POST["word"]) && $_POST["word"] ){
		//$_SESSION["search_word"]= $_POST["word"];
		$word = $_POST["word"];
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>スレッド一覧ページ</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{
			background-color: #CCFFFF;
		}
		header{
			height: 80px;
			background-color: #FFCC99; 
		}
		.header-logo {
			float: left;
		}
		.header-menus {
			float: right;
		}
		.header-menus .button {
			float: left;
			padding: 20px 20px 0 0;
		}
		div.button{
			text-align: center;
		}
		main{
			background-color: #CCFFFF;
		}
		.container{
			text-align: center;
			padding-top: 200px;
		}
		footer{
			padding-top: 150px;
			padding-bottom: 30px;
			background-color: #CCFFFF;
		}
	</style>
</head>
<body>
	<!-- step4 画面仕様書 -->
	<!-- スレッド検索フォーム（1行）とスレッド検索ボタン -->
	<!-- スレッド一覧表示 -->
	<!-- 「スレッドタイトル」をクリックするとスレッド詳細ページに遷移する -->
	<!-- トップに戻るボタン作成 -->

	<!-- step5 画面仕様書/スレッド詳細ページ -->
	<!-- ①スレッドタイトルと登録日時を表示 -->
	<!-- ②スレッド作者名・作成者コメント・登録日時を表示 -->
	<!-- ログイン時のみコメント投稿フォーム・コメントするボタンを表示 -->

	<header>
		<div class="header-logo">

		</div>
		<div class="header-menus">
			<!-- 新規スレッド作成 -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread_regist.php'" value="新規スレッド作成">
			</div>
		</div>
	</header>
	
	<main>
		<div class="container">
			<form action="" method="post">
				<input type="text" name="word">
				<input type="submit" name="search" value="スレッド検索">
			</form>
		</div>
		<div class="container">
			<!-- スレッドテーブルからid/title/created_atを取得して表示する。その際、タイトルをリンクにしてクリックすると詳細ページに遷移する -->
			<?php if( isset($_POST["search"]) && $_POST["search"] ){ ?>
				<?php 
					//ThreadLogicのsearchThreadsメソッドであいまい検索をかけて結果を取得する
					$result = ThreadLogic::searchThreads($word);  
					var_dump($result);
				?>
				<!-- 検索結果を登録した日付の降順でforeach文で一覧表示する -->
				<table>
					<?php foreach($result as $column): ?>
						<tr>
							<td>ID: <?php echo h($column["id"]) ?></td>
							<td><a href="thread_detail.php?id=<?php echo h($column["id"]) ?>"><?php echo h($column["title"]) ?></a></td>
							<td><?php echo h($column["created_at"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php } ?>

		</div>
	</main>
	<footer>
		<div class="button">
			<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
		</div>
	</footer>
</body>
</html>