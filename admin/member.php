<?php
	session_start();
	
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	//全てのメンバー情報を取得する
	$allMembers = MemberLogic::getAllMembers();
	

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
				<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='member.php'" value="会員登録">
			</div>
		</div>
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
			<?php if( isset($_POST["search"]) && $_POST["search"] ):?>
				<?php 
					//ThreadLogicのsearchThreadsメソッドを呼び出す
					$result = MemberLogic::searchMembers($_SESSION);  
					var_dump($result);
				?>
				<!-- 検索結果を登録した日付の降順でforeach文で一覧表示する -->
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
							<td><a href="thread_detail.php?id=<?php echo h($member["id"]) ?>"><?php echo h($member["title"]) ?></a></td>
							<td><?php echo h($member["created_at"]) ?></td>
							<td></td>
							<td></td>
							<td><a href="">編集</a></td>
							<td><a href="">詳細</a></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: //デフォルトでは会員一覧を表示する ?>
				<?php 
					//MemberLogicのgetAllMembers()メソッドを呼び出す
					$allMembers = MemberLogic::getAllMembers();  
				?>
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
			<?php endif; ?>
		</div>
	</main>
</body>
</html>