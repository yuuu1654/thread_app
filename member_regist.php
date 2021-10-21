<?php
	session_start();
	$mode = "input";
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	


	if( isset($_POST["back"]) && $_POST["back"] ){
		//何もしない
	}else if( isset($_POST["confirm"]) && $_POST["confirm"] ){
		/**
		 * 確認画面
		 */
		

		//氏名(姓)のバリデーション
		if( !$_POST["name_sei"] ){
			$errmessage[] = "氏名(姓)は入力必須です";
		}else if( mb_strlen($_POST["name_sei"]) > 20 ){
			$errmessage[] = "氏名(姓)は20文字以内で入力してください";
		}
		$_SESSION["name_sei"] = htmlspecialchars($_POST["name_sei"], ENT_QUOTES);  //無害化した文字列を代入


		//氏名(名)のバリデーション
		if( !$_POST["name_mei"] ){
			$errmessage[] = "氏名(名)は入力必須です";
		}else if( mb_strlen($_POST["name_mei"]) > 20 ){
			$errmessage[] = "氏名(名)は20文字以内で入力してください";
		}
		$_SESSION["name_mei"] = htmlspecialchars($_POST["name_mei"], ENT_QUOTES);  //無害化した文字列を代入


		//住所(それ以降の住所)のバリデーション (任意)
		if( mb_strlen($_POST["address"]) > 100 ){
			$errmessage[] = "住所(それ以降の住所)は100文字以内で入力してください";
		}
		$_SESSION["address"] = htmlspecialchars($_POST["address"], ENT_QUOTES);  //無害化した文字列を代入


		//パスワードのバリデーション
		/**
		 * 半角英数字8~20のリファクタリング実装
		 */
		if( !$_POST["password"] ){
			$errmessage[] = "パスワードは入力必須です";
		}else if( mb_strlen($_POST["password"]) > 20 || mb_strlen($_POST["password"]) < 8 ){
			$errmessage[] = "パスワードは半角英数字8～20文字以内で入力してください";
		}else if(preg_match("/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z\d]$/", $_POST["password"])){  //正規表現(半角英数字)
			$errmessage[] = "パスワードは半角英数字8～20文字以内で入力してください";
		}  
		$_SESSION["password"] = htmlspecialchars($_POST["password"], ENT_QUOTES);  //無害化した文字列を代入
		//$_SESSION["password_1"] = htmlspecialchars($_POST["password"], ENT_QUOTES);  //無害化した文字列を代入



		//パスワード確認のバリデーション
		if( !$_POST["password_confirmation"] ){
			$errmessage[] = "パスワード確認は入力必須です";
		}else if( mb_strlen($_POST["password_confirmation"]) > 20 || mb_strlen($_POST["password_confirmation"]) < 8 ){
			$errmessage[] = "パスワード確認は半角英数字8～20文字以内で入力してください";
		}else if( $_POST["password_confirmation"] !== $_POST["password"] ){ //データ型も比較
			$errmessage[] = "入力した文字がパスワードと一致しません";
		}
		$_SESSION["password_confirmation"] = htmlspecialchars($_POST["password_confirmation"], ENT_QUOTES);  //無害化した文字列を代入
		//$_SESSION["password_confirmation_1"] = htmlspecialchars($_POST["password_confirmation"], ENT_QUOTES);  //無害化した文字列を代入


		//メールアドレスのバリデーション
		/**
		 * 重複するメールアドレスの登録を防ぐバリデーション未実装
		 */
		if ( !$_POST["email"] ){
			$errmessage[] = "メールアドレスは入力必須です";
		}else if( mb_strlen($_POST["email"]) > 200 ){
			$errmessage[] = "メールアドレスは200文字以内で入力してください";
		}else if( !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) ){  //メールアドレス形式の文字列かどうかのチェック
			$errmessage[] = "不正なメールアドレスです";
		}
		$_SESSION["email"] = htmlspecialchars($_POST["email"], ENT_QUOTES);  //無害化した文字列を入力


		
		//バリデーションまだ未作成
		$_SESSION["gender"]                 = $_POST["gender"];
		$_SESSION["pref_name"]              = $_POST["pref_name"];

		
		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "input";
		}else{
			$mode = "confirm";
		}
	

	//確認画面から登録完了ボタンが押されたらDBに登録する
	}else if( isset($_POST["signup_done"]) && $_POST["signup_done"] ){
		$mode = "signup_done";
		$hasCreated = MemberLogic::createMember($_SESSION);  //MemberLogicのメソッドを呼び出す

		//$resultの結果がfalseで返ってきたらエラーメッセージを追加する
		if( !$hasCreated ){
			$errmessage[] = "重複したメールアドレスです";
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
			$mode = "signup_done";
		}


	}else{
		//セッションを初期化
		$_SESSION["name_sei"]               = "";
		$_SESSION["name_mei"]               = "";
		$_SESSION["gender"]                 = "";
		$_SESSION["pref_name"]              = "";
		$_SESSION["address"]                = "";
		$_SESSION["password"]               = "";
		$_SESSION["password_confirmation"]  = "";
		$_SESSION["email"]                  = "";
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員登録フォーム</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{
			padding: 10px;
			max-width: 600px;
			margin: 0px auto;
		}
		div.button{
			text-align: center;
		}
		.container{
			text-align: center;
			padding-top: 150px;
		}
		.done{
			padding-top: 50px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
		}
		h1{
			padding-bottom: 50px;
		}
		.conf_form{
			padding-left: 130px;
		}
	</style>
</head>
<body>
		<?php if( $mode == "input"){ ?>
			<!-- 入力フォーム画面 -->
			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>

			<h1>会員情報登録フォーム</h1>
			<form action="" method="POST">
				<!-- 氏名 -->
				氏名  姓<input type="text" class="form-control" name="name_sei" value="<?php echo $_SESSION["name_sei"] ?>">
							名<input type="text" class="form-control" name="name_mei" value="<?php echo $_SESSION["name_mei"] ?>"><br>
				<!-- 性別 -->
				性別　<input type="radio" name="gender" value="1" checked="checked">男性
						<input type="radio" name="gender" value="2">女性<br>
				<!-- 住所 -->
				住所　都道府県　
				<select name="pref_name" value="<?php echo $_SESSION["pref_name"] ?>">
					<option value="selected">選択して下さい</option>
					<option value="北海道">北海道</option>
					<option value="青森県">青森県</option>
					<option value="岩手県">岩手県</option>
					<option value="宮城県">宮城県</option>
					<option value="秋田県">秋田県</option>
					<option value="山形県">山形県</option>
					<option value="福島県">福島県</option>
					<option value="茨城県">茨城県</option>
					<option value="栃木県">栃木県</option>
					<option value="群馬県">群馬県</option>
					<option value="埼玉県">埼玉県</option>
					<option value="千葉県">千葉県</option>
					<option value="東京都">東京都</option>
					<option value="神奈川県">神奈川県</option>
					<option value="新潟県">新潟県</option>
					<option value="富山県">富山県</option>
					<option value="石川県">石川県</option>
					<option value="福井県">福井県</option>
					<option value="山梨県">山梨県</option>
					<option value="長野県">長野県</option>
					<option value="岐阜県">岐阜県</option>
					<option value="静岡県">静岡県</option>
					<option value="愛知県">愛知県</option>
					<option value="三重県">三重県</option>
					<option value="滋賀県">滋賀県</option>
					<option value="京都府">京都府</option>
					<option value="大阪府">大阪府</option>
					<option value="兵庫県">兵庫県</option>
					<option value="奈良県">奈良県</option>
					<option value="和歌山県">和歌山県</option>
					<option value="鳥取県">鳥取県</option>
					<option value="島根県">島根県</option>
					<option value="岡山県">岡山県</option>
					<option value="広島県">広島県</option>
					<option value="山口県">山口県</option>
					<option value="徳島県">徳島県</option>
					<option value="香川県">香川県</option>
					<option value="愛媛県">愛媛県</option>
					<option value="高知県">高知県</option>
					<option value="福岡県">福岡県</option>
					<option value="佐賀県">佐賀県</option>
					<option value="長崎県">長崎県</option>
					<option value="熊本県">熊本県</option>
					<option value="大分県">大分県</option>
					<option value="宮崎県">宮崎県</option>
					<option value="鹿児島県">鹿児島県</option>
					<option value="沖縄県">沖縄県</option>
				</select><br>
				　　　それ以降の住所<input type="text" class="form-control" name="address" value="<?php echo $_SESSION["address"] ?>"><br>
				<!-- パスワード -->
				パスワード　　　　<input type="password" class="form-control" name="password" value="<?php echo $_SESSION["password"] ?>"><br>
				<!-- パスワード確認 -->
				パスワード確認　　<input type="password" class="form-control" name="password_confirmation" value="<?php echo $_SESSION["password_confirmation"] ?>"><br>
				<!-- メールアドレス -->
				メールアドレス　　<input type="email" class="form-control" name="email" value="<?php echo $_SESSION["email"] ?>"><br><br>
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" name="confirm" value="確認画面へ"><br>
				</div>
			</form>
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
			</div>
			
		<?php } else if( $mode == "confirm"){ ?>
			<!-- 確認画面 -->
			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
				if($_SESSION["gender"] == 1){
					$gender = "男性";
				}else{
					$gender = "女性";
				}
			?>
			<div class="container">
				<h1>会員情報確認画面</h1>
			</div>
			<form action="" class="conf_form" method="post">
				氏名　　　　　　<?php echo $_SESSION["name_sei"] ?>　<?php echo $_SESSION["name_mei"] ?><br>
				性別　　　　　　<?php echo $gender ?><br>
				住所　　　　　　<?php echo $_SESSION["pref_name"] ?><?php echo $_SESSION["address"] ?><br>
				パスワード　　　セキュリティのため非表示<br>
				メールアドレス　<?php echo $_SESSION["email"] ?><br><br>
				<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" name="signup_done" value="登録完了"><br>
					<input type="submit" class="btn btn-secondary btn-lg" name="back" value="前に戻る">
				</div>
				<!-- <button type="button" onclick="history.back()">戻る</button> -->
			</form>
		<?php } else { ?>
			<!-- 完了画面 -->
			<div class="container">
				<h1>会員登録完了</h1>
				<p class="done">会員登録が完了しました。</p>
			</div>
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
			</div>
		<?php } ?>
</body>
</html>

