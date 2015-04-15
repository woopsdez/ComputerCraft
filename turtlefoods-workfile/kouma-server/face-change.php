<? php

// CC端末から情報を受けとる
// $usename = htmlspecialchars($_GET['name'], ENT_QUOTES);
$usename = migy;

// 顔画像取得
$faceData = file_get_contents("http://minecraft-skin-viewer.com/face.php?u=migy&s=16");

/* 画像の読み込み */
$image = new Imagick($filename);
 
/* 画像を出力 */
header("Content-Type: image/jpeg");     //表示する画像ヘッダー
echo $image;
 
$image->clear();


?>