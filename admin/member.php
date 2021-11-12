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
			//var_dump($_SESSION["id"]);
		}
		//性別のバリデーション
		if( isset($_POST["gender"]) && $_POST["gender"] ){
			$_SESSION["gender"] = htmlspecialchars($_POST["gender"], ENT_QUOTES);  
			var_dump($_SESSION["gender"]);
		}
		//都道府県のバリデーション
		if( isset($_POST["pref_name"]) && $_POST["pref_name"] && $_POST["pref_name"] != 1 ){
			
			$_SESSION["pref_num"]	= htmlspecialchars($_POST["pref_name"], ENT_QUOTES);
			$_SESSION["pref_name"] = htmlspecialchars($kind[ $_POST["pref_name"] ], ENT_QUOTES);  //無害化した文字列を代入
			//var_dump($_SESSION["pref_name"]);
		}
		//フリーワードのバリデーション
		if( isset($_POST["word"]) && $_POST["word"] ){
			$_SESSION["word"] = htmlspecialchars($_POST["word"], ENT_QUOTES); 
			var_dump($_SESSION["word"]);
		}
		

		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "normal";
		}else{
			$mode = "search";
		}

		

		//キーワードからメンバー検索して一覧データを取得
		$result = MemberLogic::searchMembers3($_SESSION); 
		//var_dump($searchMembers);

		$searchMembers = MemberLogic::sortByKey('id', SORT_DESC, $result);
		//var_dump($searchMembers);

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
		
		//デフォルトは全てのメンバー表示
		$allMembers = MemberLogic::getAllMembersDesc();

		$max = 10; // 1ページの記事の表示数
		$allMembers_num = count($allMembers);  //トータルデータ件数
		var_dump($allMembers_num);

		$max_page = ceil($allMembers_num / $max);
		$max_page = (int)$max_page;
		var_dump($max_page);

		if( isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $max_page ){
			$page = $_GET["page"];
		}else{
			$page = "1";
		}

		$page = (int)$page;
		var_dump($page);

		$start = ($page - 1) * $max; // 配列の何番目から取得すればよいか

		// array_sliceは、配列の何番目($start)から何番目(MAX)まで切り取る関数
		$disp_allMembers = array_slice($allMembers, $start, $max, true);
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
											<option value="<?php echo $i ?>" selected><?php echo $v ?></option>
										<?php } else { ?>
											<option value="<?php echo $i ?>" ><?php echo $v ?></option>
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
				<?php
					//idの昇順かどうかを判別して切り替える/デフォルトは降順(ノーマルモード)
					if( isset($_POST["id_sort"]) && $_POST["id_sort"] ){
						//一覧の一番下のメンバーのidを取得
						$value = end($disp_allMembers);
						var_dump($value["id"]);  //15
						if( $_SESSION["asc_id"] != $value["id"] ){

							$_SESSION["asc_id"] = "";
							$disp_allMembers = MemberLogic::sortByKey('id', SORT_ASC, $disp_allMembers);  //昇順にする
							$_SESSION["asc_id"] = $disp_allMembers[0]["id"];

						}else{

							$_SESSION["asc_id"] = "";
							$disp_allMembers = MemberLogic::sortByKey('id', SORT_DESC, $disp_allMembers);  //降順にする
							$_SESSION["asc_id"] = $disp_allMembers[0]["id"];
						}
					}
					//created_atの昇順かどうかを判別して切り替える
					if( isset($_POST["created_at_sort"]) && $_POST["created_at_sort"] ){
						//一覧の一番下のメンバーのcreated_atを取得
						$value = end($disp_allMembers);
						//var_dump($value["created_at"]);  
						if( $_SESSION["asc_created_at"] != $value["created_at"] ){

							$_SESSION["asc_created_at"] = "";
							$disp_allMembers = MemberLogic::sortByKey('created_at', SORT_ASC, $disp_allMembers);  //昇順にする
							$_SESSION["asc_created_at"] = $disp_allMembers[0]["created_at"];

						}else{

							$_SESSION["asc_created_at"] = "";
							$disp_allMembers = MemberLogic::sortByKey('created_at', SORT_DESC, $disp_allMembers); //降順にする
							$_SESSION["asc_created_at"] = $disp_allMembers[0]["created_at"];

						}
					}
				?>
				<table class="table">
					<tr>
						<th>
							<form method="POST" name="id_sort" action="">
								<input type="hidden" name="id_sort" value="sort">
								<a href="#" onclick="document.forms.id_sort.submit();"><span class="sort">ID▼</span></a>
							</form>
						</th>
						<th>氏名</th>
						<th>性別</th>
						<th>住所</th>
						<th>
							<form method="POST" name="created_at_sort" action="">
								<input type="hidden" name="created_at_sort" value="sort">
								<a href="#" onclick="document.forms.created_at_sort.submit();"><span class="sort">登録日時▼</span></a>
							</form>
						</th>
						<th>編集</th>
						<th>詳細</th>
					</tr>
					<?php foreach($disp_allMembers as $member): ?>	
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

			<div class="container">
				<!-- デフォルトページネーションのリンク実装 -->
				<!-- ページ切り替えリンクの表示条件 -->
				<?php
					//var_dump($max_page);
					//var_dump($page);
				?>
				<?php if( $page == 1 ){ ?>
					<nav>
						<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page ?></a>
						　<a href="member.php?page=<?php echo ($page + 1) ?>" ><?php echo ($page + 1) ?></a>
						　　<a href="member.php?page=<?php echo ($page + 1) ?>">次へ></a>
					</nav>
				<?php } ?>
				<?php if( $page != 1 && $page != $max_page ){ ?>
					<nav>
					<a href="member.php?page=<?php echo ($page - 1) ?>">前へ></a>
					　　<a href="member.php?page=<?php echo ($page - 1) ?>" ><?php echo ($page - 1) ?></a>
					　　　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page ?></a>
					　　　　<a href="member.php?page=<?php echo ($page + 1) ?>" ><?php echo ($page + 1) ?></a>
					　　　　　　<a href="member.php?page=<?php echo ($page + 1) ?>">次へ></a>
					</nav>
				<?php } ?>
				<?php if( $page == $max_page ){ ?>
					<nav>
					<a href="member.php?page=<?php echo ($page - 1) ?>">前へ></a>
					　　<a href="member.php?page=<?php echo ($page - 1) ?>" ><?php echo ($page - 1) ?></a>
					　　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page ?></a>
					</nav>
				<?php } ?>
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
											<option value="<?php echo $i ?>" selected><?php echo $v ?></option>
										<?php } else { ?>
											<option value="<?php echo $i ?>" ><?php echo $v ?></option>
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
				<?php
					//idの昇順かどうかを判別して切り替える/デフォルトは降順(検索モード)
					if( isset($_POST["id_sort2"]) && $_POST["id_sort2"] ){
						$mode = "search";
						//$searchMembers = MemberLogic::sortByKey('id', SORT_DESC, $result);
						var_dump($searchMembers);

						//一覧の一番下のメンバーのidを取得
						$value = end($searchMembers);
						var_dump($value["id"]);  //19

						if( $_SESSION["asc_id2"] != $value["id"] ){
							$_SESSION["asc_id2"] = "";
							//昇順にする
							$searchMembers = MemberLogic::sortByKey('id', SORT_ASC, $searchMembers);
							$_SESSION["asc_id2"] = $searchMembers[0]["id"];  //19
						}else{
							$_SESSION["asc_id2"] = "";
							//降順にする
							$searchMembers = MemberLogic::sortByKey('id', SORT_DESC, $searchMembers);
							$_SESSION["asc_id2"] = $searchMembers[0]["id"];  //37
						}
					}
					//created_atの昇順かどうかを判別して切り替える
					if( isset($_POST["created_at_sort2"]) && $_POST["created_at_sort2"] ){
						

						//一覧の一番下のメンバーのcreated_atを取得
						$value = end($searchMembers);
						//var_dump($value["created_at"]);  

						if( $_SESSION["asc_created_at2"] != $value["created_at"] ){
							$_SESSION["asc_created_at2"] = "";
							//$allMembers = MemberLogic::createdAtAsc(); //昇順にする
							$searchMembers = MemberLogic::sortByKey('id', SORT_ASC, $searchMembers);
							$_SESSION["asc_created_at2"] = $searchMembers[0]["created_at"];

						}else{
							$_SESSION["asc_created_at2"] = "";
							//$allMembers = MemberLogic::createdAtDesc(); //降順にする
							$_SESSION["asc_created_at2"] = $searchMembers[0]["created_at"];
						}
					}
				?>
				<table class="table">
					<tr>
						<th>
							<form method="POST" name="id_sort2" action="">
								<input type="hidden" name="id_sort2" value="sort2">
								<a href="#" onclick="document.forms.id_sort2.submit();"><span class="sort2">ID▼</span></a>
							</form>
						</th>
						<th>氏名</th>
						<th>性別</th>
						<th>住所</th>
						<th>
							<form method="POST" name="created_at_sort2" action="">
								<input type="hidden" name="created_at_sort2" value="sort2">
								<a href="#" onclick="document.forms.created_at_sort2.submit();"><span class="sort2">登録日時▼</span></a>
							</form>
						</th>
						<th>編集</th>
						<th>詳細</th>
					</tr>
					<?php foreach($searchMembers as $member): ?>
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
	<?php } ?>
</body>
</html>