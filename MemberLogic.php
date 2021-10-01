<?php
	//メンバーを登録する処理を行うクラス
	require_once "dbconnect.php";

	class MemberLogic
	{
		/**
		 * メンバーを登録する
		 * @param array $memberData
		 * @return bool $result
		 */
		public static function createMember($memberData){
			$result = false;

			$sql = 'INSERT INTO members(name_sei, name_mei, gender, pref_name, address, password, email) VALUES(?, ?, ?, ?, ?, ?, ?)';

			//会員データを配列に入れる
			$array = [];
			$array[] = $memberData["name_sei"]; //name_sei
			$array[] = $memberData["name_mei"]; //name_mei
			$array[] = $memberData["gender"]; //gender
			$array[] = $memberData["pref_name"]; //pref_name
			$array[] = $memberData["address"]; //address
			$array[] = $memberData["password"]; //password
			$array[] = $memberData["email"]; //email

			try {
				//データベースに接続する
				$stmt = connect()->prepare($sql);
				$result = $stmt->execute($array);
				return $result;
			} catch(\Exception $e) {
				echo $e;  //エラーを出力
				error_log($e, 3, "error.log");  //ログを出力する
				return $result;
			}

		}
	}
?>