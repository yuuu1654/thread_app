<?php
	//管理者を登録・ログインする処理を行うクラス
	require_once "../dbconnect.php";
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	class AdministerLogic
	{
		/**
		 * [管理者を登録する]
		 * @param array $administerData
		 * @return bool $result
		 * 
		 */
		public static function createAdminister($administerData){
			$result = false;

			$sql = 'INSERT INTO administers(name, login_id, password, created_at) 
											VALUES(:name, :login_id, :password, now())';
			
			//すでに登録しているかチェック
			$admin = self::getAdminByName($administerData["name"]);

			if ( $admin ){  
				$result = true;
				return $result;
			}

			try {
				//データベースに接続する
				$stmt = connect()->prepare($sql);
				$stmt->bindValue(':name', $administerData["name"]);
				$stmt->bindValue(':login_id', $administerData["login_id"]);
				$stmt->bindValue(':password', $administerData["password"]);
				$result = $stmt->execute();
				return $result;
			} catch(\Exception $e) {
				echo $e;  //エラーを出力
				error_log($e, 3, "error.log");  //ログを出力する
				return $result;
			}
		}



		/**
		 * [nameから管理者が登録されているか検索]
		 * @param string $name
		 * @return array | bool  $admin | false (成功したら管理者の配列データ、失敗したらfalseを返す)
		 */
		public static function getAdminByName($name){
			
			$sql = 'SELECT * FROM administers WHERE name = ? AND deleted_at IS NULL';  
			//emailを配列に入れる
			$array = [];
			$array[] = $name;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$admin = $stmt->fetch();
				return $admin;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [ログイン処理]
		 * すべての条件を満たしたメンバーがいたらセッションに保存して結果をtrueで返す
		 * @param string $login_id
		 * @param string $password
		 * @return bool $result
		 */
		public static function login($login_id, $password){
			//結果
			$result = false;
			//メンバーをemailから検索して取得
			$admin = self::getAdminById($login_id);

			//メールアドレスで検索をかけてメンバーが見つからなかった時の処理
			if ( !$admin ){  
				$_SESSION["msg"] = "ログインIDもしくはパスワードが間違っています";
				return $result;
			}
			//パスワードの照会 //password_verify($password, $member["password"])
			if ($password == $admin["password"]){
				//ログイン成功
				session_regenerate_id(true);          //セッションハイジャック対策
				$_SESSION["login_admin"] = $admin;  //emailの照会で見つかり、パスワードも一致したメンバーをセッションに保存
				$result = true;
				return $result;
			}
			//パスワードの照会に失敗した時はエラーを返す
			$_SESSION["msg"] = "ログインIDもしくはパスワードが間違っています";
			return $result;
		}



		/**
		 * [login_idから管理者を取得]
		 * @param string $login_id
		 * @return array | bool  $admin | false (成功したら管理者の配列データ、失敗したらfalseを返す)
		 */
		public static function getAdminById($login_id){

			$sql = 'SELECT * FROM administers WHERE login_id = ? AND deleted_at IS NULL';  
			$array = [];
			$array[] = $login_id;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$admin = $stmt->fetch();
				return $admin;
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
			if (isset($_SESSION["login_admin"]) && $_SESSION["login_admin"]["id"] > 0){
				return $result = true;
			}
			return $result;
		}


		/**
		 * ログアウト処理
		 */
		public static function logout(){
			//セッション変数をすべて解除する
			$_SESSION["login_admin"] = array();  

			session_destroy();
		}

	}
?>