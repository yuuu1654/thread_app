<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "CommentLogic.php";  //コメント登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み
	require_once "LikeLogic.php";

	//スレッドのid
	var_dump($_SESSION["thread_id"]);
	$id = $_SESSION["thread_id"];
	var_dump($id);

	$member_id = $_SESSION["member_id"];
	var_dump($member_id);
	

	$comment_id = $_SESSION["comment_id"];
	var_dump($comment_id);
	

	//リダイレクトしてきたらいいね作成を行ってセッションを初期化して再度スレッド詳細ページにリダイレクトする
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		//いいね取り消し
		LikeLogic::destroyLike($member_id, $comment_id);
		
		//セッションを初期化
		$_SESSION["member_id"] = "";
		$_SESSION["comment_id"] = "";

		//リダイレクト
		header("Location: thread_detail.php?id=$id");
		return;
	}else{
		// フォームからPOSTによって要求された場合
	}
?>