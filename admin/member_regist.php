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
		if( !isset($_POST["gender"]) || !$_POST["gender"] ){
			$errmessage[] = "性別は入力必須です";
		}else if( $_POST["gender"] <= 0 || $_POST["gender"] >= 3 ){
			$errmessage[] = "不正な入力です";
		}
		if( isset($_POST["gender"]) && $_POST["gender"] ){
			$_SESSION["gender"] = htmlspecialchars($_POST["gender"], ENT_QUOTES);  //無害化した文字列を代入
		}

		
		//都道府県のバリデーション
		if( !$_POST["pref_name"] || $_POST["pref_name"] == 1 ){
			$errmessage[] = "都道府県は入力必須です";
		}else if( $_POST["pref_name"] <= 0 || $_POST["pref_name"] >= 49 ){
			$errmessage[] = "不正な入力です";
		}
		$_SESSION["pref_num"]	= htmlspecialchars($_POST["pref_name"], ENT_QUOTES);
		$_SESSION["pref_name"] = htmlspecialchars($kind[ $_POST["pref_name"] ], ENT_QUOTES);  //無害化した文字列を代入
		


		//住所(それ以降の住所)のバリデーション (任意)
		if( mb_strlen($_POST["address"]) > 100 ){
			$errmessage[] = "住所(それ以降の住所)は100文字以内で入力してください";
		}
		$_SESSION["address"] = htmlspecialchars($_POST["address"], ENT_QUOTES);  //無害化した文字列を代入


		//パスワードのバリデーション
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
		$_SESSION["form"]                   = "";
		$_SESSION["confirm"]                = "";
	}



	if( $mode == "input" ){
		//フォームテンプレート表示
		$_SESSION["form"] = 1; //登録か編集の判別
		include "inc/form_tpl.php";
	}else if( $mode == "confirm" ){
		//確認画面テンプレート表示
		$_SESSION["confirm"] = 1;	//登録か編集の判別
		include "inc/confirm_tpl.php";
	}else{
		//何もしない
	}
	
?>

