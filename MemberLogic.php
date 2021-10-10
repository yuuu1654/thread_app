<?php
	//メンバーを登録する&検索する処理を行うクラス

	require_once "dbconnect.php";

	class MemberLogic
	{
		/**
		 * [メンバーを登録する]
		 * @param array $memberData
		 * @return bool $result
		 */
		public static function createMember($memberData){
			$result = false;

			//フォームに入力されたmailがすでに登録されていないかチェック
			$sql1 = "SELECT * FROM members WHERE email = :email";
			$stmt = connect()->prepare($sql1);
			$stmt->bindValue(':email', $memberData["email"]);
			$stmt->execute();
			$member = $stmt->fetch();

			if ($member['email'] === $memberData["email"]) {
				//$msg = '同じメールアドレスが存在します。';
				return $result;  //処理を止める
			} else {
				//登録されていなければinsert (DBに登録する処理)
				$sql2 = 'INSERT INTO members(name_sei, name_mei, gender, pref_name, address, password, email, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
				//会員データを配列に入れる
				$array = [];
				$array[] = $memberData["name_sei"];  //name_sei
				$array[] = $memberData["name_mei"];  //name_mei
				$array[] = $memberData["gender"];    //gender
				$array[] = $memberData["pref_name"]; //pref_name
				$array[] = $memberData["address"];   //address

				//$array[] = $memberData["password"];  //password
				$array[] = password_hash($memberData["password"], PASSWORD_DEFAULT);  //ハッシュ化したパスワード

				$array[] = $memberData["email"];     //email
				$array[] = $memberData["created_at"];     //created_at
				try {
					//データベースに接続する
					$stmt = connect()->prepare($sql2);
					$result = $stmt->execute($array);
					return $result;
				} catch(\Exception $e) {
					echo $e;  //エラーを出力
					error_log($e, 3, "error.log");  //ログを出力する
					return $result;
				}
			}
		}



		/**
		 * [ログイン処理]
		 * すべての条件を満たしたメンバーがいたらセッションに保存して結果をtrueで返す
		 * @param string $email
		 * @param string $password
		 * @return bool $result
		 */
		public static function login($email, $password){
			//結果
			$result = false;
			//メンバーをemailから検索して取得
			$member = self::getMemberByEmail($email);

			//メールアドレスで検索をかけてメンバーが見つからなかった時の処理
			if ( !$member ){  
				$_SESSION["msg"] = "IDもしくはパスワードが間違っています";
				return $result;
			}
			//パスワードの照会
			if (password_verify($password, $member["password"])){
				//ログイン成功
				session_regenerate_id(true);          //セッションハイジャック対策
				$_SESSION["login_member"] = $member;  //emailの照会で見つかり、パスワードも一致したメンバーをセッションに保存
				$result = true;
				return $result;
			}
			//パスワードの照会に失敗した時はエラーを返す
			$_SESSION["msg"] = "IDもしくはパスワードが間違っています";
			return $result;
		}



		/**
		 * [emailからメンバーを取得]
		 * @param string $email
		 * @return array | bool  $member | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getMemberByEmail($email){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = 'SELECT * FROM members WHERE email = ?';

			//emailを配列に入れる
			$array = [];
			$array[] = $email;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				
				//SQLの結果を返す
				$member = $stmt->fetch();
				return $member;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [ログインチェック]
		 * @param void
		 * @return bool $result
		 */
		public static function checkLogin(){
			$result = false;

			//セッションにログインユーザーが入っていなかったらfalse
			if (isset($_SESSION["login_member"]) && $_SESSION["login_member"]["id"] > 0){
				return $result = true;
			}
			return $result;
		}


		/**
		 * ログアウト処理
		 */
		public static function logout(){
			//セッション変数をすべて解除する
			$_SESSION = array();  

			session_destroy();
		}
	}
?>