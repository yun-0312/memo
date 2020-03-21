<?php
$page_flg = 0; //表示切り替え用flag
$id = $_GET['id']; //id取得
//データベース接続
require_once('../config.php');
$dbh = connectDb();
//データ取得
$sql = 'SELECT * FROM memos WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();
if (!$result) {
	header('Location: index.php');
	exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$memo = $_POST['memo'];
	//未入力チェック
	if (empty($memo)) {
		$error = 'メモが未入力です。';
	}
	//エラーがなかったらデータベースに登録
	if (empty($error)) {
		$sql = 'UPDATE memos SET memo = :memo, updated_at = now() WHERE id = :id';
		$stmt = $dbh->prepare($sql);
		$params = array(':memo' => $memo, ':id' => $id);
		$stmt->execute($params);
		$message = '内容が変更されました。';
		$page_flg = 1;
	}
}
$dbh = null;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>メモ</title>
</head>
<body>
<h1>編集</h1>
<?php if ($page_flg === 0) : ?>
	<form action="" method="post">
	<p>メモ</p>
	<textarea type="text" name="memo"><?php echo htmlspecialchars($result['memo'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>
	<?php if (!empty($error)) : ?>
		<p style="color:red;"><?php echo $error; ?></p>
	<?php endif; ?>
	<input type="submit" value="変更する">
	</form>
<?php else : ?>
	<p><?php echo $message; ?></p>
<?php endif; ?>
<a href="index.php">メモ一覧へ戻る</a>
</body>
</html>
