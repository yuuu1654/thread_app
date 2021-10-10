<?php
	session_start();
	$mode = "input";
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み


	
	if( isset($_POST["search"]) && $_POST["search"] ){
		/**
		 * スレッド検索結果一覧画面
		 */

		if( !$_POST["word"] ){
			$errmessage[] = "検索ワードを入力してください";
		}
		$_SESSION["search_word"] = htmlspecialchars($_POST["word"], ENT_QUOTES);  //無害化した文字列を代入


		//ThreadLogicのsearchThreadsメソッドを呼び出す
		$result = ThreadLogic::searchThreads($_SESSION);  
		var_dump($result);

		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "input";
		}else{
			$mode = "confirm";
		}
	
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
	<!-- step3　画面仕様書 -->
	<!-- 入力フォームと確認画面をモード変数で表示を切り替える -->
	<!-- スレッド作成確認画面に「スレッドタイトル」と「コメント」の2つを表示する -->
	<!-- スレッドを作成するボタン（）と前に戻るボタンを作成 -->


	<?php if( $mode == "input"){ ?>
		<!-- 入力フォーム画面 -->
		<?php
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>
		<header>
			<div class="header-logo">
				<?php var_dump($mode); ?>
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
				<form action=""　method="POST">
					<!-- 検索フォーム -->
					<input type="text" name="word">
					<!-- 検索ボタン -->
					<input type="submit" name="search" value="スレッド検索">
				</form><br>
			</div>
		</main>
		<footer>
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
			</div>
		</footer>
		
	<?php } else if( $mode == "confirm"){ ?>
		<!-- スレッド検索結果一覧画面 -->
		<header>
			<div class="header-logo">
				<?php var_dump($mode); ?>
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
				<form action="thread.php"　method="POST">
					<!-- 検索フォーム -->
					<input type="text" name="word">
					<!-- 検索ボタン -->
					<input type="submit" name="search" value="スレッド検索">
				</form><br>
			</div>
			<div class="container">
				<!-- スレッドテーブルからid/title/created_atを取得して表示する。その際、タイトルをリンクにしてクリックすると詳細ページに遷移する -->
				<?php if( $_POST["search"] ){ ?>
					<!-- 検索結果をidの小順でforeach文で一覧表示する -->
					<table>
						<?php foreach($result as $column): ?>
							<tr>
								<td>ID: <?php echo $column["id"] ?></td>
								<td><?php echo $column["title"] ?></td>
								<td><?php echo $column["cteated_at"] ?></td>
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
	<?php } ?>
</body>
</html>