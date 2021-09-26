<?php
	session_start();
	$mode = "input";
	if( isset($_POST["back"]) && $_POST["back"] ){
		//何もしない
	}else if( isset($_POST["confirm"]) && $_POST["confirm"] ){
		$_SESSION["name_sei"]  		          = $_POST["name_sei"];
		$_SESSION["name_mei"]               = $_POST["name_mei"];
		$_SESSION["gender"]                 = $_POST["gender"];
		$_SESSION["pref_name"]              = $_POST["pref_name"];
		$_SESSION["address"]                = $_POST["address"];
		$_SESSION["password"]               = $_POST["password"];
		$_SESSION["password_confirmation"]  = $_POST["password_confirmation"];
		$_SESSION["email"]                  = $_POST["email"];
		$mode = "confirm";
	}else if( isset($_POST["signup_done"]) && $_POST["signup_done"] ){
		$mode = "signup_done";
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
	<title>Document</title>
	<style>
		.confirm{
			color: white;
			background-color: skyblue;
			font-weight: bold;
		}
		.btn{
			color: skyblue;
			background-color: white;
			padding: 8px 24px;
			display: inline-block;
			opacity: 0.8;
			border-radius: 4px;
		}
	</style>
</head>
<body>
		<?php if( $mode == "input"){ ?>
			<!-- 入力フォーム画面 -->
			<h1>会員情報登録フォーム</h1>
			<form action="" method="POST">
				<!-- 氏名 -->
				氏名  姓<input type="text" name="name_sei" value="<?php echo $_SESSION["name_sei"] ?>">
							名<input type="text" name="name_mei" value="<?php echo $_SESSION["name_mei"] ?>"><br>
				<!-- 性別 -->
				性別<input type="radio" name="gender" value="男性" checked="checked">男性
						<input type="radio" name="gender" value="女性">女性<br>
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
				　　　それ以降の住所<input type="text" name="address" value="<?php echo $_SESSION["address"] ?>"><br>
				<!-- パスワード -->
				パスワード　　　　<input type="password" name="password" value="<?php echo $_SESSION["password"] ?>"><br>
				<!-- パスワード確認 -->
				パスワード確認　　<input type="password" name="password_confirmation" value="<?php echo $_SESSION["password_confirmation"] ?>"><br>
				<!-- メールアドレス -->
				メールアドレス　　<input type="email" name="email" value="<?php echo $_SESSION["email"] ?>"><br><br>
				<input class="confirm" type="submit" name="confirm" value="確認画面へ">
			</form>
			<input type="button" onclick="location.href='top.php'" value="トップに戻る">
		<?php } else if( $mode == "confirm"){ ?>
			<!-- 確認画面 -->
			<h1>会員情報確認画面</h1>
			<!-- 連想配列の中身を表示 -->
			<?php print_r($_POST); ?>

			<form action="" method="post">
				氏名　　　　　　<?php echo $_SESSION["name_sei"] ?>　<?php echo $_SESSION["name_mei"] ?><br>
				性別　　　　　　<?php echo $_SESSION["gender"] ?><br>
				住所　　　　　　<?php echo $_SESSION["pref_name"] ?><?php echo $_SESSION["address"] ?><br>
				パスワード　　　<?php echo $_SESSION["password"] ?><br>
				メールアドレス　<?php echo $_SESSION["email"] ?><br>
				<input type="submit" name="signup_done" value="登録完了"><br>
				<input type="submit" name="back" value="前に戻る">
				<!-- <button type="button" onclick="history.back()">戻る</button> -->
			</form>
		<?php } else { ?>
			<!-- 完了画面 -->
			<h1>会員登録完了</h1>
			<p>会員登録が完了しました。</p>
		<?php } ?>
</body>
</html>

