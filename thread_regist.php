<?php
	session_start();
	$mode = "input";
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み


	if( isset($_POST["back"]) && $_POST["back"] ){
		//何もしない
	}else if( isset($_POST["confirm"]) && $_POST["confirm"] ){
		/**
		 * スレッド確認確認画面
		 */
		

		//スレッドタイトルのバリデーション
		if( !$_POST["title"] ){
			$errmessage[] = "タイトルを入力して下さい";
		}else if( mb_strlen($_POST["title"]) > 100 ){
			$errmessage[] = "タイトルは100文字以内で入力してください";
		}
		$_SESSION["title"] = htmlspecialchars($_POST["title"], ENT_QUOTES);  //無害化した文字列を代入


		//スレッドコメントのバリデーション
		if( !$_POST["content"] ){
			$errmessage[] = "コメントを入力して下さい";
		}else if( mb_strlen($_POST["content"]) > 500 ){
			$errmessage[] = "コメントは500文字以内で入力してください";
		}
		$_SESSION["content"] = htmlspecialchars($_POST["content"], ENT_QUOTES);  //無害化した文字列を代入


		
		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "input";
		}else{
			$mode = "confirm";
		}
	

	//確認画面からスレッド作成ボタンが押されたらDBに登録してスレッド一覧画面(thread.php)に遷移する
	}else if( isset($_POST["create_thread"]) && $_POST["create_thread"] ){
		//セッションにあるログインユーザーの連想配列データを変数に格納
		$login_member = $_SESSION["login_member"];

		$_SESSION["member_id"] = $login_member["id"];        //スレッドの投稿者のidをセッションに保存
		$hasCreated = ThreadLogic::createThread($_SESSION);  //ThreadLogicのスレッド作成メソッドを呼び出す

		//$resultの結果がfalseで返ってきたらエラーメッセージを追加する
		if( !$hasCreated ){
			$errmessage[] = "スレッド作成に失敗しました";
		}

		/**
		 * 完了画面がうまく表示されない為、一旦コメントアウトしました。
		 */
		// //トークンを受け取る
		// $token = filter_input(INPUT_POST, "csrf_token");
		// //トークンがない、もしくは一致しない場合に処理を中止
		// if ( !isset($_SESSION["csrf_token"]) || $token !== $_SESSION["csrf_token"]){
		// 	exit("不正なリクエスト");
		// }
		// unset($_SESSION["csrf_token"]);  //セッションを削除する

		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "confirm";
		}else{
			header("Location: top.php");  //スレッド一覧ページに遷移する
		}


	}else{
		//ログインしているか判定して、していなかったらlogout.phpに遷移する
		$result = MemberLogic::checkLogin();
		if ( !$result ){
			$_SESSION["login_err"] = "会員登録してログインしてください！";
			header("Location: logout.php");
			return;
		}
		//セッションを初期化
		$_SESSION["title"]               = "";
		$_SESSION["content"]             = "";
		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>スレッド作成フォーム</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{
			padding: 10px;
			max-width: 600px;
			margin: 0px auto;
			background-color: #CCFFFF;
		}
		div.button{
			text-align: center;
		}
		.container{
			text-align: center;
			padding-top: 150px;
		}
		h1{
			padding-bottom: 50px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
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

		<h1>スレッド作成フォーム</h1>
		<form action="" method="POST">
			<!-- スレッドタイトル・コメント欄作成 -->
			スレッドタイトル　<input type="text" class="form-control"　placeholder="Title" name="title" value="<?php echo $_SESSION["title"] ?>"><br>
			コメント　　　　　<textarea class="form-control" placeholder="Content" cols="40" rows="8" name="content" value=""><?php echo $_SESSION["content"] ?></textarea>
			
			
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" name="confirm" value="確認画面へ"><br>
			</div>
			
		</form>
		<div class="button">
			<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
		</div>
		
	<?php } else if( $mode == "confirm"){ ?>
		<!-- 確認画面 -->
		<div class="container">
			<h1>スレッド作成確認画面</h1>
		</div>
		
		<?php
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>

		<form action="" method="post">
			スレッドタイトル： <br>
			<?php echo $_SESSION["title"] ?><br><br>
			コメント： <br>
			<?php echo nl2br($_SESSION["content"]) ?><br>
			<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" name="create_thread" value="スレッドを作成する"><br>
				<input type="submit" class="btn btn-secondary btn-lg" name="back" value="前に戻る">
			</div>
		</form>
	<?php } ?>
</body>
</html>