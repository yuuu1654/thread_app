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


	session_start();
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み


	//セッションを初期化
	$_SESSION["id"]               = "";
	$_SESSION["gender"]           = "";
	$_SESSION["pref_num"]					= "";
	$_SESSION["pref_name"]        = "";
	$_SESSION["word"]             = "";
	
	
	
	

	if( isset($_POST["search"]) && $_POST["search"] ){

		// $page = "1";
		// var_dump($page);

		$_SESSION["allMembers"]       = "";

		//IDのバリデーション
		if( isset($_POST["id"]) && $_POST["id"] ){
			$_SESSION["id"] = htmlspecialchars($_POST["id"], ENT_QUOTES); 
		}


		//性別のバリデーション
		if( isset($_POST["gender1"]) && $_POST["gender1"] ){
			$_SESSION["gender1"] = htmlspecialchars($_POST["gender1"], ENT_QUOTES);  
		}
		if( isset($_POST["gender2"]) && $_POST["gender2"] ){
			$_SESSION["gender2"] = htmlspecialchars($_POST["gender2"], ENT_QUOTES);  
		}


		//都道府県のバリデーション
		if( isset($_POST["pref_name"]) && $_POST["pref_name"] && $_POST["pref_name"] != 1 ){
			
			$_SESSION["pref_num"]	= htmlspecialchars($_POST["pref_name"], ENT_QUOTES);
			$_SESSION["pref_name"] = htmlspecialchars($kind[ $_POST["pref_name"] ], ENT_QUOTES);  //無害化した文字列を代入
		}
		//フリーワードのバリデーション
		if( isset($_POST["word"]) && $_POST["word"] ){
			$_SESSION["word"] = htmlspecialchars($_POST["word"], ENT_QUOTES); 
		}
		//キーワードからメンバー検索して一覧データを取得
		$result = MemberLogic::searchMembers3($_SESSION); 
		$allMembers = MemberLogic::sortByKey('id', SORT_DESC, $result);
		$_SESSION["allMembers"] = $allMembers;

	}else{
		//デフォルトは全てのメンバー表示
		$allMembers = MemberLogic::getAllMembersDesc();

		if( isset($_GET["signup"]) && $_GET["signup"] ){
			$allMembers = MemberLogic::getAllMembersDesc();  //再度検索し直す
			$_SESSION["allMembers"] = $allMembers;
		}

		if( isset($_GET["update"]) && $_GET["update"] ){
			$allMembers = MemberLogic::getAllMembersDesc();  //再度検索し直す
			$_SESSION["allMembers"] = $allMembers;
		}

		if( isset($_GET["delete"]) && $_GET["delete"] ){
			$allMembers = MemberLogic::getAllMembersDesc();  //再度検索し直す
			$_SESSION["allMembers"] = $allMembers;
		}

		if (isset($_SESSION["allMembers"]) && $_SESSION["allMembers"] != ""){     //セッションにメンバーのデータを格納
			$allMembers = $_SESSION["allMembers"];
		}
	}



	//idの昇順かどうかを判別して切り替える/デフォルトは降順(ノーマルモード)
	if( isset($_POST["id_sort"]) && $_POST["id_sort"] ){
		
		$lastMember = end($allMembers);  //全メンバー配列の最後の要素
		//var_dump($lastMember);
		//var_dump($_SESSION["asc_id"]);

		if( isset($_SESSION["asc_id"]) && $_SESSION["asc_id"] > $lastMember["id"] ){
			
			$_SESSION["asc_id"] =       "";
			$_SESSION["allMembers"] =   "";

			$allMembers = MemberLogic::sortByKey('id', SORT_ASC, $allMembers);  //昇順にする
			$_SESSION["allMembers"] = $allMembers;                              //sortしたメンバーをセッションに格納
			$_SESSION["asc_id"] = $allMembers[0]["id"];              //全メンバー配列の最初の要素のidをセッションに格納

		}else{

			$_SESSION["asc_id"] =       "";
			$_SESSION["allMembers"] =   "";

			$allMembers = MemberLogic::sortByKey('id', SORT_DESC, $allMembers);  //降順にする
			$_SESSION["allMembers"] = $allMembers;  //sortしたメンバーをセッションに格納
			$_SESSION["asc_id"] = $allMembers[0]["id"];
		}
	}
	//created_atの昇順かどうかを判別して切り替える
	if( isset($_POST["created_at_sort"]) && $_POST["created_at_sort"] ){
		//一覧の一番下のメンバーのcreated_atを取得
		$lastMember = end($allMembers);
		
		if( isset($_SESSION["asc_created_at"]) && $_SESSION["asc_created_at"] > $lastMember["created_at"] ){

			$_SESSION["asc_created_at"] = "";
			$_SESSION["allMembers"] =     "";

			$allMembers = MemberLogic::sortByKey('created_at', SORT_ASC, $allMembers);  //昇順にする
			$_SESSION["allMembers"] = $allMembers;  //sortしたメンバーをセッションに格納

			$_SESSION["asc_created_at"] = $allMembers[0]["created_at"];

		}else{

			$_SESSION["asc_created_at"] = "";
			$_SESSION["allMembers"] =     "";

			$allMembers = MemberLogic::sortByKey('created_at', SORT_DESC, $allMembers); //降順にする
			$_SESSION["allMembers"] = $allMembers;  //sortしたメンバーをセッションに格納

			$_SESSION["asc_created_at"] = $allMembers[0]["created_at"];

		}
	}



	$max = 10; // 1ページの記事の表示数
	$allMembers_num = count($allMembers);  //トータルデータ件数
	//var_dump($allMembers_num);

	$max_page = ceil($allMembers_num / $max);
	$max_page = (int)$max_page;
	//var_dump($max_page);

	if( isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $max_page ){
		$page = $_GET["page"];
	}else{
		$page = "1";
	}

	$page = (int)$page;
	//var_dump($page);
	$start = ($page - 1) * $max; // 配列の何番目から取得すればよいか

	// array_sliceは、配列の何番目($start)から何番目(MAX)まで切り取る関数
	$disp_allMembers = array_slice($allMembers, $start, $max, true);

	//var_dump($disp_allMembers);
	

	$_SESSION["id"]               = "";
	$_SESSION["gender1"]          = "";
	$_SESSION["gender2"]          = "";
	$_SESSION["pref_num"]					= "";
	$_SESSION["pref_name"]        = "";
	$_SESSION["word"]             = "";
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員一覧ページ</title>
</head>
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
<body>
	<header>
		<div class="header-logo">
			<h1>会員一覧</h1>
		</div>
		<div class="header-menus">
			<!-- 会員一覧ページボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='../top.php'" value="トップへ戻る">
			</div>
		</div>
	</header>
	<main>
		<div class="container">
			<!-- 会員登録ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='member_regist.php'" value="会員登録">
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
							<input type="checkbox" name="gender1" value="1">男性
							<input type="checkbox" name="gender2" value="2">女性<br>
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
			<?php
				
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
						<td><a href="member_detail.php?id=<?php echo h($member["id"]) ?>"><?php echo h($member["name_sei"]) ?><?php echo h($member["name_mei"]) ?></a></td>
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
			<?php
				//var_dump($max_page);
				//var_dump($page);
			?>
			<?php if( count($disp_allMembers) == 0 ){ ?>
				<nav>
					<?php echo "検索結果に該当する会員が見つかりませんでした" ?>
				</nav>
			<?php } ?>
			<?php if( $page == 1 && $max_page == 1 && count($disp_allMembers) != 0 ){ ?>
				<nav>
					<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
					　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page + 1 ?></a>
					　　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page + 2 ?></a>
				</nav>
			<?php } ?>
			<?php if( $page == 1 && $max_page != 1 && count($disp_allMembers) != 0 ){ ?>
				

				<?php if( $max_page == 2 ){ ?>
					<nav>
						<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
						　<a href="member.php?page=<?php echo ($page + 1) ?>"><?php echo $page + 1 ?></a>
						　　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page + 2 ?></a>
						　　　<a href="member.php?page=<?php echo ($page + 1) ?>">次へ></a>
					</nav>
				<?php }else{ ?>
					<nav>
						<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
						　<a href="member.php?page=<?php echo ($page + 1) ?>"><?php echo $page + 1 ?></a>
						　　<a href="member.php?page=<?php echo ($page + 2) ?>"><?php echo $page + 2 ?></a>
						　　　<a href="member.php?page=<?php echo ($page + 1) ?>">次へ></a>
					</nav>
				<?php } ?>
			<?php } ?>
			<?php if( $page != 1 && $page != $max_page && count($disp_allMembers) != 0 ){ //ページが中間地点の場合 ?>
				<nav>
					<a href="member.php?page=<?php echo ($page - 1) ?>">前へ></a>
					　　<a href="member.php?page=<?php echo ($page - 1) ?>"><?php echo ($page - 1) ?></a>
					　　　<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
					　　　　<a href="member.php?page=<?php echo ($page + 1) ?>"><?php echo ($page + 1) ?></a>
					　　　　　　<a href="member.php?page=<?php echo ($page + 1) ?>">次へ></a>
				</nav>


				
			<?php } ?>
			<?php if( $page == $max_page && $page != 1 && count($disp_allMembers) != 0 ){ //ページが最終ページの時 ?>
				<?php if( $max_page == 2 ){ ?>
					<nav>
						<a href="member.php?page=<?php echo ($page - 1) ?>">前へ></a>
						　<a href="member.php?page=<?php echo ($page - 1) ?>"><?php echo $page - 1 ?></a>
						　　<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
						　　　<a href="#" style="pointer-events: none; color: #344853;"><?php echo $page + 1 ?></a>
					</nav>
				<?php }else{ ?>
					<nav>
						<a href="member.php?page=<?php echo ($page - 1) ?>">前へ></a>
						　<a href="member.php?page=<?php echo ($page - 2) ?>"><?php echo $page - 2 ?></a>
						　　<a href="member.php?page=<?php echo ($page - 1) ?>"><?php echo ($page - 1) ?></a>
						　　　<a href="#" style="pointer-events: none; color: white; background-color: gray;"><?php echo $page ?></a>
					</nav>
				<?php } ?>
			<?php } ?><br><br>
		</div>
	</main>
</body>
</html>