<!-- フォームテンプレート -->
<?php
	//session_start();
	//配列変数が反映されない時は追加する
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if( $_SESSION["form"] == 1 ){ ?>
		<title>会員登録</title>
	<?php }else{ ?>
		<title>会員編集</title>
	<?php } ?>
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
	<?php if( $_SESSION["form"] == 1 ){ ?>
		<!-- 登録用のフォーム -->
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
			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>
			<form action="member_regist2.php" method="POST">
				ID　　登録後に自動採番<br><br>
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
	<?php }else{ ?>
		<!-- 編集用のフォーム -->
		
	<?php } ?>


	
</body>
</html>



