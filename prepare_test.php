<?php
require_once('dbconnect.php');// データベースの設定を読み込む

//データベース内で検索したい条件の値を変数に代入
$main_id="61";

//クエリを変数に代入(プレースホルダ込み)
$stmt = mysqli_prepare($link, "SELECT * FROM hageblo WHERE id=?");

//マーカー(?)にパラメータ($main_id)をバインド(関連付ける)。"s"は変数の型。
mysqli_stmt_bind_param($stmt,"s",$main_id);

//クエリを実行
mysqli_stmt_execute($stmt);

//プリペアドステートメントから値をセットで取得し、$resultに代入
$result = mysqli_stmt_get_result($stmt);

//プリペアドステートメントを閉じる
mysqli_stmt_close($stmt);


?>

	
<html>
<head>
<meta charset="UTF-8">
<title>sample</title>
</head>
<body>



example

<?php
$row = mysqli_fetch_array($result, MYSQLI_ASSOC)
?>

<table width="100%">
	<tr>
		<td>投稿番号：<?php print(htmlspecialchars($main_id , ENT_QUOTES)); ?></td><td>書込日：<?php printf(" %s",htmlspecialchars($row['cat_id'],ENT_QUOTES)); ?></td>
		<td>タイトル：<?php print(htmlspecialchars($row["daimei"], ENT_QUOTES)); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="3"><?php print(htmlspecialchars($row['the_body'], ENT_QUOTES)); ?></td>
	</tr>
	<tr>
		<td colspan="3"><a href="session2.php?id=<?php print(htmlspecialchars($main_id['id']));?>">この記事をお気に入りに入れる</a></td>
	</tr>
</table>
<?php
//$resultに格納された結果セットを開放(メモリの解放)
mysqli_free_result($result);
//接続終了
mysqli_close($link);
?>
</body>
</html>
