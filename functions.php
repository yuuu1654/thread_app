<?php
	/**
	 * XSS対策：エスケープ処理
	 * 
	 * @param string $str 対象の文字列
	 * @return string 処理された文字列
	 */
	function h($str){
		return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
	}

	/**
	 * 2重送信防止・CSRF対策
	 * @param void (引数なし)
	 * @return string $csrf_token
	 */

	function setToken(){
		//トークンを生成
		//フォームからそのトークンを送信
		//送信後の画面でそのトークンを照会
		//処理が完了したらトークンを削除
		session_start();
		$csrf_token = bin2hex(random_bytes(32));  //暗号を生成
		$_SESSION["csrf_token"] = $csrf_token;

		return $csrf_token;
	}
?>