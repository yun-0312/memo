<?php
$id = $_GET['id']; //id取得
//データベース接続
require_once('../config.php');
$dbh = connectDb();
//データ照合
$sql = 'SELECT * FROM memos WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$result = $stmt->fetch();
if (!$result) {
	header('Location: index.php');
	exit;
} else {
	$sql = 'UPDATE memos SET deleted_at = now() WHERE id = :id';
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$message = 'メモを削除しました。';
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
<p><?php echo $message; ?></p>
<a href="index.php">メモ一覧</a>
</body>
</html>
