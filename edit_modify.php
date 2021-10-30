<?php  
	//前に戻るボタンから編集ページに来たときは編集した内容($_SESSIONの内容)を表示
	//リンクを押して編集ページに来た時はメンバーの詳細データを表示する
?>

<?php foreach( $gender as $i => $v ){ ?>
	<?php if( $_SESSION["gender"] == $i ){ ?>
		<label><input type="radio" name="gender" value="<?php echo $i ?>" checked><?php echo $v ?></label><br>
	<?php } else { ?>
		<label><input type="radio" name="gender" value="<?php echo $i ?>" ><?php echo $v ?></label><br>
	<?php } ?>
<?php } ?>




<?php if( isset($_POST["back"]) && $_POST["back"] ){ ?>
	<!-- 前に戻るボタンを押された時 -->
	<?php foreach( $gender as $i => $v ){ ?>
		<?php if( $_SESSION["gender"] == $i ){ ?>
			<label><input type="radio" name="gender" value="<?php echo $i ?>" checked><?php echo $v ?></label><br>
		<?php } else { ?>
			<label><input type="radio" name="gender" value="<?php echo $i ?>" ><?php echo $v ?></label><br>
		<?php } ?>
	<?php } ?>
<?php }else{ ?>
	<!-- それ以外の時はデフォルトのメンバー詳細の性別を表示する -->
	<?php foreach( $gender as $i => $v ){ ?>
		<?php if( $memberDetail["gender"] == $i ){ ?>
			<label><input type="radio" name="gender" value="<?php echo $i ?>" checked><?php echo $v ?></label><br>
		<?php } else { ?>
			<label><input type="radio" name="gender" value="<?php echo $i ?>" ><?php echo $v ?></label><br>
		<?php } ?>
	<?php } ?>
<?php } ?>




<?php 
	//TO DO
	/**
	 * 都道府県の初期値はmemberDetailのデータを表示する
	 * フォームの値が更新されたら$_SESSIONに値を格納してそれを表示する
	 * pref_numをどうやって上手い事表示するのかを考える
	 */
?>



<?php if( isset($_POST["back"]) && $_POST["back"] ){ ?>
	<!-- 前に戻るボタンを押された時 -->
	<select name="pref_name" class="form-control">
		<?php foreach( $kind as $i => $v ){ ?>
			<?php if( $_SESSION["pref_num"] == $i ) { ?>
				<option value="<?php echo $i ?>" selected><?php echo $v ?></option>
			<?php } else { ?>
				<option value="<?php echo $i ?>" ><?php echo $v ?></option>
			<?php } ?>
		<?php } ?>
	</select><br>
<?php }else{ ?>
	<!-- それ以外の時はデフォルトのメンバー詳細の性別を表示する -->
	<select name="pref_name" class="form-control">
		<?php foreach( $kind as $i => $v ){ ?>
			<?php if( $_SESSION["pref_num"] == $i ) { ?>
				<option value="<?php echo $i ?>" selected><?php echo $v ?></option>
			<?php } else { ?>
				<option value="<?php echo $i ?>" ><?php echo $v ?></option>
			<?php } ?>
		<?php } ?>
	</select><br>
<?php } ?>