<!-- サンプルコード -->
<?php
	//テンプレート表示用の関数
	function display_template($tplFile, $param) {
		### テンプレートファイルを読み込み
		$html = file_get_contents($tplFile);

		### テンプレートファイルの内容を置換
		//テンプレートのブレース _%・・・%_
		// .*は全ての文字を意味する .は任意の1字で*は0個以上
		//パターン修飾子eは大文字小文字区別なし指定
		$pattern = '/_%(.*)%_/e';

		//置換内容の指定
		//$n 形式 参照を指定することができます。
		//詳細 http://php.net/manual/ja/function.preg-replace.php
		$replacement = '$param[\'$1\']';

		//置換実行
		$html = preg_replace_callback($pattern, $replacement, $html);
		
		### リターン処理
		return $html;
	}
?>