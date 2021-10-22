<?php
	//会員一覧画面をnormalモードとsearchモードの二つの画面で切り替えて情報を表示する
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
		if( !$_POST["id"] ){
			$errmessage[] = "ID覧を入力して下さい";
		}
		$_SESSION["id"] = htmlspecialchars($_POST["id"], ENT_QUOTES);  


		//性別のバリデーション
		if( !$_POST["gender"] ){
			$errmessage[] = "性別を選択して下さい";
		}
		$_SESSION["gender"] = htmlspecialchars($_POST["gender"], ENT_QUOTES);  

		//都道府県のバリデーション
		if( !$_POST["pref_name"] ){
			$errmessage[] = "都道府県を選択して下さい";
		}
		$_SESSION["pref_name"] = htmlspecialchars($_POST["pref_name"], ENT_QUOTES);  

		//フリーワードのバリデーション
		if( !$_POST["word"] ){
			$errmessage[] = "フリーワードを入力して下さい";
		}
		$_SESSION["word"] = htmlspecialchars($_POST["word"], ENT_QUOTES); 

		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "normal";
		}else{
			$mode = "search";
		}

		//キーワードからメンバー検索して一覧データを取得
		$result = MemberLogic::searchMembers($_SESSION); 
		var_dump($result);

	}else{
		//セッションを初期化
		$_SESSION["id"]               = "";
		$_SESSION["gender"]           = "";
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
							<td><input type="text" class="form-control" name="id" value="<?php echo $_SESSION["id"] ?>"></td><br>
						</tr>
						<tr>
							<th>性別</th>
							<td>
								<input type="radio" name="gender" value="1" checked="checked">男性
								<input type="radio" name="gender" value="2">女性<br/>
							</td>
						</tr>
						<tr>
							<th>都道府県</th>
							<td>
								<select name="pref_name" value="">
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
							</td>
						</tr>
						<tr>
							<th>フリーワード</th>
							<td><input type="text" class="form-control" name="word" value="<?php echo $_SESSION["word"] ?>"></td>
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
							if(h($member["id"]) == "1"){
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
							<td><input type="text" class="form-control" name="id" value="<?php echo $_SESSION["id"] ?>"></td><br>
						</tr>
						<tr>
							<th>性別</th>
							<td>
								<input type="radio" name="gender" value="1" checked="checked">男性
								<input type="radio" name="gender" value="2">女性<br/>
							</td>
						</tr>
						<tr>
							<th>都道府県</th>
							<td>
								<select name="pref_name" value="">
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
							</td>
						</tr>
						<tr>
							<th>フリーワード</th>
							<td><input type="text" class="form-control" name="word" value="<?php echo $_SESSION["word"] ?>"></td>
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