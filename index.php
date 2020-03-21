<?php
//データベース接続
require_once('../config.php');
$dbh = connectDb();
$sql = 'SELECT * FROM memos WHERE deleted_at IS NULL';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//inputの値取得
	$memo = $_POST['memo'];
	if (empty($memo)) {
		$error = 'メモを入力して下さい。';
	}
	if (empty($error)) {
		//データ登録
		$dbh = connectDb();
		$sql = 'INSERT INTO memos (memo, created_at) VALUES (:memo, now())';
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
		$data = $stmt->execute();
		if ($result) {
			$message = '登録しました。';
		} else {
			$message = '登録出来ませんでした。';
		}
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
<h1>メモ</h1>
<?php if(!empty($message)) : ?>
	<p><?php echo $message; ?></p>
<?php endif; ?>
<form action="" method="POST">
<textarea name="memo" placeholder="メモを入力して下さい。"></textarea><br>
<?php if (!empty($error)) :?>
	<p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
<input type="submit" value="登録する">
</form>
<table border="1">
<tr>
<th>ID</th>
<th>内容</th>
<th>変更</th>
</tr>
<?php foreach ($result as $value) : ?>
<tr>
	<td><?php echo $value['id']; ?></td>
	<td><?php echo $value['memo']; ?></td>
	<td><a href="edit.php?id=<?php echo $value['id']; ?>">[編集]</a><a href="delete.php?id=<?php echo $value['id']; ?>">[削除]</a></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
