<?php
	$kind     = array();
	$kind[1] = "選択して下さい";
	$kind[2] = "北海道";
	$kind[3] = "青森県";
	$kind[4] = "岩手県";
	$kind[5] = "宮城県";
	$kind[6] = "秋田県";
	$kind[7] = "山形県";
	$kind[8] = "福島県";
	$kind[9] = "茨城県";
	$kind[10] = "栃木県";
	$kind[11] = "群馬県";
	$kind[12] = "埼玉県";
	$kind[13] = "千葉県";
	$kind[14] = "東京都";
	$kind[15] = "神奈川県";
	$kind[16] = "新潟県";
	$kind[17] = "富山県";
	$kind[18] = "石川県";
	$kind[19] = "福井県";
	$kind[20] = "山梨県";
	$kind[21] = "長野県";
	$kind[22] = "岐阜県";
	$kind[23] = "静岡県";
	$kind[24] = "愛知県";
	$kind[25] = "三重県";
	$kind[26] = "滋賀県";
	$kind[27] = "京都府";
	$kind[28] = "大阪府";
	$kind[29] = "兵庫県";
	$kind[30] = "奈良県";
	$kind[31] = "和歌山県";
	$kind[32] = "鳥取県";
	$kind[33] = "島根県";
	$kind[34] = "岡山県";
	$kind[35] = "広島県";
	$kind[36] = "山口県";
	$kind[37] = "徳島県";
	$kind[38] = "香川県";
	$kind[39] = "愛媛県";
	$kind[40] = "高知県";
	$kind[41] = "福岡県";
	$kind[42] = "佐賀県";
	$kind[43] = "長崎県";
	$kind[44] = "熊本県";
	$kind[45] = "大分県";
	$kind[46] = "宮崎県";
	$kind[47] = "鹿児島県";
	$kind[48] = "沖縄県";

	//性別のラジオボタン
	$gender = array();
	$gender[1] = "男性";
	$gender[2] = "女性";

	session_start();
	$mode = "input";
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	
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



		//性別のバリデーション
		if( !$_POST["gender"] ){
			$errmessage[] = "性別は入力必須です";
		}
		$_SESSION["gender"] = htmlspecialchars($_POST["gender"], ENT_QUOTES);  //無害化した文字列を代入

		
		//都道府県のバリデーション
		if( !$_POST["pref_name"] || $_POST["pref_name"] == 1 ){
			$errmessage[] = "都道府県は入力必須です";
		}
		$_SESSION["pref_num"]	= htmlspecialchars($_POST["pref_name"], ENT_QUOTES);
		$_SESSION["pref_name"] = htmlspecialchars($kind[ $_POST["pref_name"] ], ENT_QUOTES);  //無害化した文字列を代入
		


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
		}else if( !preg_match("/^[a-zA-Z0-9]+$/", $_POST["password"]) ){  //正規表現(半角英数字)
			$errmessage[] = "パスワードは半角英数字8～20文字以内で入力してください";
		}  
		$_SESSION["password"] = htmlspecialchars($_POST["password"], ENT_QUOTES);  //無害化した文字列を代入


		//パスワード確認のバリデーション
		if( !$_POST["password_confirmation"] ){
			$errmessage[] = "パスワード確認は入力必須です";
		}else if( mb_strlen($_POST["password_confirmation"]) > 20 || mb_strlen($_POST["password_confirmation"]) < 8 ){
			$errmessage[] = "パスワード確認は半角英数字8～20文字以内で入力してください";
		}else if( $_POST["password_confirmation"] !== $_POST["password"] ){ //データ型も比較
			$errmessage[] = "入力した文字がパスワードと一致しません";
		}
		$_SESSION["password_confirmation"] = htmlspecialchars($_POST["password_confirmation"], ENT_QUOTES);  //無害化した文字列を代入


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


		$result = MemberLogic::searchDupEmail($_SESSION);
		//var_dump($result);

		if ( !$result ){
			$errmessage[] = "すでに登録されているメールアドレスです";
		}


		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "input";
		}else{
			$mode = "confirm";
		}
	

	//確認画面から登録完了ボタンが押されたらDBに登録して会員一覧画面(/admin/member.php)に遷移する
	}else if( isset($_POST["members"]) && $_POST["members"] ){
		$mode = "";
		MemberLogic::createMember($_SESSION);  //MemberLogicのメソッドを呼び出す

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
			header("Location: member.php?signup=done");  //会員一覧ページに遷移する
		}


	}else{
		//セッションを初期化
		$_SESSION["name_sei"]               = "";
		$_SESSION["name_mei"]               = "";
		$_SESSION["gender"]                 = "";
		$_SESSION["pref_num"]								= "";
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
	<title>会員登録</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{

		}
		header{
			height: 80px;
			background-color: #FFCC99; 
		}
		.header-logo {
			float: left;
		}
		.header-logo h1 {
			line-height: 80px;
			padding-left: 40px;
		}
		.header-menus {
			float: right;
		}
		.header-menus .button {
			float: left;
			padding-right: 40px;
		}
		main{
			padding: 50px 10px 10px 10px;
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

		<header>
			<div class="header-logo">
				<h1>会員登録</h1>
			</div>
			<div class="header-menus">
				<!-- 会員一覧ページボタン -->
				<div class="button">
					<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='member.php'" value="一覧へ戻る">
				</div>
			</div>
		</header>
		<main>
			<form action="" method="POST">
				<!-- 氏名 -->
				氏名  姓<input type="text" class="form-control" name="name_sei" value="<?php echo $_SESSION["name_sei"] ?>">
							　　 名<input type="text" class="form-control" name="name_mei" value="<?php echo $_SESSION["name_mei"] ?>"><br>
				<!-- 性別 -->
				性別
				<?php foreach( $gender as $i => $v ){ ?>
					<?php if( $_SESSION["gender"] == $i ){ ?>
						<label><input type="radio" name="gender" value="<?php echo $i ?>" checked><?php echo $v ?></label><br>
					<?php } else { ?>
						<label><input type="radio" name="gender" value="<?php echo $i ?>" ><?php echo $v ?></label><br>
					<?php } ?>
				<?php } ?>
				<!-- 住所 -->
				住所　都道府県　
				<select name="pref_name" class="form-control">
					<?php foreach( $kind as $i => $v ){ ?>
						<?php if( $_SESSION["pref_num"] == $i ) { ?>
							<option value="<?php echo $i ?>" selected><?php echo $v ?></option>
						<?php } else { ?>
							<option value="<?php echo $i ?>" ><?php echo $v ?></option>
						<?php } ?>
					<?php } ?>
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
		</main>

	<?php } else if( $mode == "confirm"){ ?>
		<!-- 会員登録確認画面 -->
		<?php
			if($_SESSION["gender"] == 1){
				$gender = "男性";
			}else{
				$gender = "女性";
			}
		?>

		<header>
			<div class="header-logo">
				<h1>会員登録</h1>
			</div>
			<div class="header-menus">
				<!-- 会員一覧ページボタン -->
				<form action="" method="post">
					<div class="button">
						<input type="submit" class="btn btn-secondary btn-lg" name="back" value="前に戻る">
					</div>
				</form>
			</div>
		</header>
		<main>
			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>
			<form action="" method="post">
				ID　　　　　　　登録後に自動採番<br>
				氏名　　　　　　<?php echo $_SESSION["name_sei"] ?>　<?php echo $_SESSION["name_mei"] ?><br>
				性別　　　　　　<?php echo $gender ?><br>
				住所　　　　　　<?php echo $_SESSION["pref_name"] ?><?php echo $_SESSION["address"] ?><br>
				パスワード　　　セキュリティのため非表示<br>
				メールアドレス　<?php echo $_SESSION["email"] ?><br>
				<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" name="members" value="登録完了"><br>
				</div>
				<!-- <button type="button" onclick="history.back()">戻る</button> -->
			</form>
		</main>
	<?php } else { ?>
		
	<?php } ?>
</body>
</html>

