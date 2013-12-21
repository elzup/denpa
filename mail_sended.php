<?php

$mailadr="elzzup.htam@i.softbank.jp";
$username="elzzup";
$reg_key = sha1(uniqid(rand(),1));

$to = $mailadr;
$subject = 'e-mail confirm';
$message = "http://www.example.com/path/to/confirm.php?username=$username&reg;_key=$reg_key%22";
$headers = 'From: webmaster@example.com';

mail($to, $subject, $message, $headers);

echo $mailadr."宛に確認メールを送信しました。";

?>