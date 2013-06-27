<?php
require_once('dbconnect.php');// データベースの設定を読み込む
session_start();

$id = 61;// 前ページから、ＧＥＴ経由で主キーを持ってくる
$sql = sprintf("SELECT * FROM hageblo WHERE id=%d",
	mysql_real_escape_string($id)
);
$recordSet = mysql_query($sql);
$data = mysql_fetch_assoc($recordSet);

// 上記のＧＥＴ経由で得たＩＤ番号を利用して、ＩＤに関連したコメントのみ読み込む
$query = sprintf("SELECT * FROM comment WHERE connect_id='%s' ",
mysql_real_escape_string($id));
$comeSet = mysql_query($query);


// 本文内のURLにリンクを設定
function makeLink($value) {return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>掲示板内容を編集画面</title>
</head>

<body>
<div id="wrap">
<div id="head">
<h1>掲示板内容を編集画面</h1>
</div>

<div id="content">
<p><a href="index.php">ブログに戻る</a></p>
<p>この記事にコメントをする</p>

<table width="100%">
	<tr>
		<td>投稿番号：<?php print(htmlspecialchars($id , ENT_QUOTES)); ?></td><td>書込日：<?php print(htmlspecialchars($data['created'], ENT_QUOTES)); ?></td>
		<td>タイトル：<?php print(htmlspecialchars($data['daimei'], ENT_QUOTES)); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="3"><?php print(htmlspecialchars($data['the_body'], ENT_QUOTES)); ?></td><!--本文を表示printの後にmakeLink自作関数を入れる事で本文中のＵＲＬを発見する -->
	</tr>
	<tr>
		<td colspan="3"><a href="session2.php?id=<?php print(htmlspecialchars($data['id']));?>">この記事をお気に入りに入れる</a></td>
	</tr>
</table>

<hr>

<?php
while ($table = mysql_fetch_assoc($comeSet)) {
?>
<table width="100%">
<tr>
		<td>削除番号：<?php print(htmlspecialchars($table['come_id'], ENT_QUOTES)); ?></td>
		<td>書込日：<?php print(htmlspecialchars($table['come_modified'], ENT_QUOTES)); ?></td>
		<td>名前：<?php if (!empty($table["come_mail"])) {echo "<a href=\"mailto:" . $table["come_mail"] . "\">". $table['come_nama'] . "</a>"; }else{ echo $table['come_nama']; }?></td>
	</tr>
	<tr>
		<td colspan="3"><?php print makeLink(htmlspecialchars($table['come_body'], ENT_QUOTES)); ?></td><!--本文を表示printの後にmakeLink自作関数を入れる事で本文中のＵＲＬを発見する -->
	</tr>
</table>
<hr>

<?php
}
?>

</div>

<div id="combox">
<p>コメント内容を記載してください。</p>
<form id="comeinput" name="comeinput" method="post" action="comeinput_do.php">
  <dt>
    <label for="come_body">コメント内容</label>
  </dt>
  <dd>
    <textarea name="come_body" cols="50" rows="10"></textarea>
  </dd>
  <dt>
    <label for="come_nama">名前</label>
  </dt>
  <dd>
    <input name="come_nama" type="text" id="come_nama" value="<?php if (isset($_COOKIE["string"])) {echo $_COOKIE["string"];} ?>" size="25" maxlength="25" />
  </dd>

  <dt>
    <label for="come_pass">パスワード</label>
  </dt>
  <dd>
    <input name="come_pass" type="text" id="come_pass" value="" size="25" maxlength="25" />
  </dd>


  <input type="submit" value="書き込む" />
  <input type="hidden" name="connect_id" value="<?php print(htmlspecialchars($_REQUEST['id'], ENT_QUOTES)) ?>" />
</form>
</div>

<p>削除番号とパスワードを入力してください</p>
<form id="comedel" name="comedel" method="post" action="come_del.php">

  <dt>
    <label for="come_id">削除番号</label>
  </dt>
  <dd>
    <input name="come_id" type="text" id="come_id" value="" size="25" maxlength="25" />
  </dd>

  <dt>
    <label for="come_pass">パスワード</label>
  </dt>
  <dd>
    <input name="come_pass" type="text" id="come_pass" value="" size="25" maxlength="25" />
  </dd>

  <input type="submit" value="消す" />
<input type="hidden" name="connect_id" value="<?php print(htmlspecialchars($_REQUEST['id'], ENT_QUOTES)) ?>" />
</form>

<div id="foot">

</div>

</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$('input[name=come_nama]').bind('blur', function() {
	if ($(this).val() == '') {
		$(this).addClass('error');
		$(this).parent().append('<p class="error">※この項目は必ずご記入ください</p>');
	}
})
.bind('focus', function() {
	if ($(this).next() !== false) {
		$(this).removeClass('error');
		$(this).next().remove();
	}
});
</script>
</body>
</html>
