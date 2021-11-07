<?php
	//会員一覧画面をnormalモードとsearchモードの二つの画面で切り替えて情報を表示する
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


	session_start();
	$mode = "normal";  //デフォルトモード
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	if( isset($_POST["search"]) && $_POST["search"] ){
		//serchMemberメソッドで検索条件にあったメンバー一覧を表示する
		//ポストの値をバリデーションにかけて通ったらセッションに値を保存する
		//エラーメッセージの有無でモードを切り替える
		//メソッドを呼び出す

		//IDのバリデーション
		if( isset($_POST["id"]) && $_POST["id"] ){
			$_SESSION["id"] = htmlspecialchars($_POST["id"], ENT_QUOTES); 
			var_dump($_SESSION["id"]);
		}
		


		//性別のバリデーション
		if( isset($_POST["gender"]) && $_POST["gender"] ){
			$_SESSION["gender"] = htmlspecialchars($_POST["gender"], ENT_QUOTES);  
		}
		

		//都道府県のバリデーション
		if( isset($_POST["pref_name"]) && $_POST["pref_name"] ){
			$_SESSION["pref_num"]	= htmlspecialchars($_POST["pref_name"], ENT_QUOTES);
			$_SESSION["pref_name"] = htmlspecialchars($kind[ $_POST["pref_name"] ], ENT_QUOTES);  //無害化した文字列を代入
		}
		
		//フリーワードのバリデーション
		if( isset($_POST["word"]) && $_POST["word"] ){
			$_SESSION["word"] = htmlspecialchars($_POST["word"], ENT_QUOTES); 
		}
		

		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "normal";
		}else{
			$mode = "search";
		}

		//キーワードからメンバー検索して一覧データを取得
		$result = MemberLogic::searchMembers($_SESSION); 
		var_dump($result);

		$_SESSION["id"]               = "";
		$_SESSION["gender"]           = "";
		$_SESSION["pref_num"]					= "";
		$_SESSION["pref_name"]        = "";
		$_SESSION["word"]             = "";

	}else{
		//セッションを初期化
		$_SESSION["id"]               = "";
		$_SESSION["gender"]           = "";
		$_SESSION["pref_num"]					= "";
		$_SESSION["pref_name"]        = "";
		$_SESSION["word"]             = "";
		
		//デフォルトでは全てのメンバーを表示する
		$allMembers = MemberLogic::getAllMembers();
		
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員一覧ページ</title>
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
		.table{
			text-align: center;
		}
		div.button{
			text-align: center;
		}
		.container{
			text-align: center;
			padding-top: 10px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
		}
	</style>
</head>
<body>

	<?php if( $mode == "normal" ){ ?>
		<!-- ノーマルモードの処理 -->
		<header>
			<div class="header-logo">
				<h1>会員一覧</h1>
			</div>
			<div class="header-menus">
				<!-- 会員一覧ページボタン -->
				<div class="button">
					<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='login.php'" value="トップへ戻る">
				</div>
			</div>
		</header>
		<main>
			<div class="container">
				<!-- 会員登録ボタン -->
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='../member_regist.php'" value="会員登録">
				</div>
			</div>
			<?php
				//エラーメッセージがあれば表示する
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>
			<div class="container">
				<!-- 検索フォーム -->
				<form action="" method="post">
					<table class="table">
						<tr>
							<th>ID</th>
							<td><input type="text" class="form-control" name="id" value=""></td><br>
						</tr>
						<tr>
							<th>性別</th>
							<td>
								<input type="radio" name="gender" value="1">男性
								<input type="radio" name="gender" value="2">女性<br>
							</td>
						</tr>
						<tr>
							<th>都道府県</th>
							<td>
								<select name="pref_name" class="form-control">
									<?php foreach( $kind as $i => $v ){ ?>
										<?php if( $_SESSION["pref_num"] == $i ) { ?>
											<option value="" selected><?php echo $v ?></option>
										<?php } else { ?>
											<option value="" ><?php echo $v ?></option>
										<?php } ?>
									<?php } ?>
								</select><br>
							</td>
						</tr>
						<tr>
							<th>フリーワード</th>
							<td><input type="text" class="form-control" name="word" value=""></td>
						</tr>
						
					</table>
					<!-- 検索ボタン -->
					<div class="button">
						<input type="submit" class="btn btn-secondary btn-lg" name="search" value="検索する"><br>
					</div>
				</form>
			</div>
			<div class="container">
				<!-- デフォルトではメンバー一覧を表示する -->
				<table class="table">
					<tr>
						<th>ID</th>
						<th>氏名</th>
						<th>性別</th>
						<th>住所</th>
						<th>登録日時</th>
						<th>編集</th>
						<th>詳細</th>
					</tr>
					<?php foreach($allMembers as $member): ?>	
						<?php
							if(h($member["gender"]) == "1"){
								$gender = "男性";
							}else{
								$gender = "女性";
							}
						?>
						<tr>
							<td><?php echo h($member["id"]) ?></td>
							<td><?php echo h($member["name_sei"]) ?><?php echo h($member["name_mei"]) ?></td>
							<td><?php echo $gender ?></td>
							<td><?php echo h($member["pref_name"]) ?><?php echo h($member["address"]) ?></td>
							<td><?php echo h($member["created_at"]) ?></td>
							<td><a href="member_edit.php?id=<?php echo h($member["id"]) ?>">編集</a></td>
							<td><a href="member_detail.php?id=<?php echo h($member["id"]) ?>">詳細</a></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</main>
	




	<?php }else if( $mode == "search" ){ ?>
		<!-- 検索モードの処理 -->
		<header>
			<div class="header-logo">
				<h1>会員一覧</h1>
			</div>
			<div class="header-menus">
				<!-- 会員一覧ページボタン -->
				<div class="button">
					<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='login.php'" value="トップへ戻る">
				</div>
			</div>
		</header>

		<main>
			<div class="container">
				<!-- 会員登録ボタン -->
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='../member_regist.php'" value="会員登録">
				</div>
			</div>
			<?php
				//エラーメッセージがあれば表示する
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>
			<div class="container">
				<!-- 検索フォーム -->
				<form action="" method="post">
					<table class="table">
						<tr>
							<th>ID</th>
							<td><input type="text" class="form-control" name="id" value=""></td><br>
						</tr>
						<tr>
							<th>性別</th>
							<td>
								<input type="radio" name="gender" value="1">男性
								<input type="radio" name="gender" value="2">女性<br>
							</td>
						</tr>
						<tr>
							<th>都道府県</th>
							<td>
								<select name="pref_name" class="form-control">
									<?php foreach( $kind as $i => $v ){ ?>
										<?php if( $_SESSION["pref_num"] == $i ) { ?>
											<option value="" selected><?php echo $v ?></option>
										<?php } else { ?>
											<option value="" ><?php echo $v ?></option>
										<?php } ?>
									<?php } ?>
								</select><br>
							</td>
						</tr>
						<tr>
							<th>フリーワード</th>
							<td><input type="text" class="form-control" name="word" value=""></td>
						</tr>
						
					</table>
					<!-- 検索ボタン -->
					<div class="button">
						<input type="submit" class="btn btn-secondary btn-lg" name="search" value="検索する"><br>
					</div>
				</form>
			</div>
			<div class="container">
				<!-- 検索ボタンが押されたら、一覧ページャーに会員のデータを表示する -->
				<table class="table">
					<?php foreach($result as $member): ?>
						<?php
							if(h($member["gender"]) == "1"){
								$gender = "男性";
							}else{
								$gender = "女性";
							}
						?>
						<tr>
							<th>ID</th>
							<th>氏名</th>
							<th>性別</th>
							<th>住所</th>
							<th>登録日時</th>
							<th>編集</th>
							<th>詳細</th>
						</tr>
						<tr>
							<td><?php echo h($member["id"]) ?></td>
							<td><?php echo h($member["name_sei"]) ?><?php echo h($member["name_mei"]) ?></td>
							<td><?php echo $gender ?></td>
							<td><?php echo h($member["pref_name"]) ?><?php echo h($member["address"]) ?></td>
							<td><?php echo h($member["created_at"]) ?></td>
							<td><a href="member_edit.php?id=<?php echo h($member["id"]) ?>">編集</a></td>
							<td><a href="member_detail.php?id=<?php echo h($member["id"]) ?>">詳細</a></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</main>
	<?php } ?>
</body>
</html>