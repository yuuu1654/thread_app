<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "CommentLogic.php";  //コメント登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み
	require_once "LikeLogic.php";

	//スレッドのid
	$id = $_SESSION["thread_id"];


	//リダイレクトしてきたらいいね作成を行ってセッションを初期化して再度スレッド詳細ページにリダイレクトする
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		$member_id = $_SESSION["member_id"];
		var_dump($member_id);
		
		$comment_id = $_SESSION["comment_id"];
		var_dump($comment_id);

		$page = $_SESSION["page"];  //ページ情報
		var_dump($page);

		//いいね取り消し
		LikeLogic::destroyLike($member_id, $comment_id);
		
		//セッションを初期化
		$_SESSION["member_id"] = "";
		$_SESSION["comment_id"] = "";
		$_SESSION["page"] = "";

		//リダイレクト
		header("Location: thread_detail.php?id=$id&page=$page");
		return;
	}else{
		// フォームからPOSTによって要求された場合
	}
?>