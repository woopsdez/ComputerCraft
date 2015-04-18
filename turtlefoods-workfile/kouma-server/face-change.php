<?php

function fileSave($username,$faceImageSrc){
  // 画像をサーバー上から取得して保存
  $options = array(
    "http" => array(
      "method" => "GET",
      "header" => "User-Agent: Minecraft",
    ),
  );
  $context = stream_context_create($options);
  $url = "http://minecraft-skin-viewer.com/face.php?u=".$username."&s=8";

  // ファイルを取得
  $file = file_get_contents($url, false, $context);

  // ファイルを保存
  file_put_contents($faceImageSrc,$file);
}

// CC端末から情報を受けとる
$username = $_GET['name'];
$faceImageSrc = $username.".png";

// CC表示用ファイルを初期化

// アクセス時点でファイルがすでにあるかどうかを判別
if (file_exists($faceImageSrc)) {  
  // ファイルがあったら
  // タイムスタンプを見る
  $ts = filemtime($faceImageSrc);
  if (is_null($ts)){ $ts = 0;}
  
  $limit = $ts + 120;
  if ($ts <= $limit) {
    // 一定時間すぎていなければ、
    // そのまま既存のファイルを返す
    $faceImageSrc;
  } else {
    // 過ぎていたら、ダウンロード
    fileSave($username,$faceImageSrc);
  }
} else {
  // ファイルがなかったら取得 
  fileSave($username,$faceImageSrc);
}

// ----------------------------------------- //
//     [[[[[色情報をテキストデータに変換]]]]]     //
// ----------------------------------------- //

// CCで使える色を配列に格納
$colors = array(
  ["r" => 255, "g" => 255, "b" => 255],
  ["r" => 242, "g" => 178, "b" => 58],
  ["r" => 229, "g" => 127, "b" => 216],
  ["r" => 153, "g" => 178, "b" => 242],
  ["r" => 217, "g" => 228, "b" => 101],
  ["r" => 81,  "g" => 214, "b" => 21],
  ["r" => 242, "g" => 178, "b" => 204],
  ["r" => 76,  "g" => 76,  "b" => 76],
  ["r" => 153, "g" => 153, "b" => 153],
  ["r" => 76,  "g" => 153, "b" => 178],
  ["r" => 178, "g" => 102, "b" => 229],
  ["r" => 37,  "g" => 49,  "b" => 146],
  ["r" => 127, "g" => 102, "b" => 76],
  ["r" => 87,  "g" => 166, "b" => 78],
  ["r" => 204, "g" => 76,  "b" => 76],
  ["r" => 25,  "g" => 25,  "b" => 25]
);

$color_name = array(
  "white",
  "orange",
  "magenta",
  "lightBlue",
  "yellow",
  "lime",
  "pink",
  "gray",
  "lightGray",
  "cyan",
  "purple",
  "blue",
  "brown",
  "green",
  "red",
  "black"
);

$previewUrl = "http://minecraft-skin-viewer.com/face.php?u=".$username."&s=400";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
    body{background: #000; color: #FFF;}
    .text-center{margin:0 auto; text-align: center;}
    .left{width: 20%; float: left;}
    .right{width: 75%; float:right;}
    .hoge{
      display: inline-block;
      width: 1px;
      height: 1px;
      overflow: hidden;
    }
    span{padding: 1px;}
  </style>
</head>
<body>

<h1 class="text-center">この画像の色を解析するよ！</h1>
<hr>

<div class="left">
  <img src="<?php echo $previewUrl ?>" >
  <br>
  <p>ゲーム内で使える色、一覧</p>
  <?php
    for ($i=0; $i < count($colors) ; $i++) {
      printf(
        '<span style="display:inline-block; margin: 3px; padding: 3px; background: rgb(%d,%d,%d); ">%d,%d,%d</span>',
        $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"],
        $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]
      );
    }
  ?>
</div>
<div class="right">
<?php
$output = "";
// ----------------------------------------- //
//           [[[[[近似色を取得]]]]]            //
// ----------------------------------------- //

// 画像サイズを取得
$sizeArray = getimagesize($username.".png");
$imageW = $sizeArray[0]; // 幅
$imageH = $sizeArray[1]; // 高さ

// 画像を読み込み
$im = imagecreatefrompng($username.".png");
// trueカラーからパレットカラーへ変更
imagetruecolortopalette($im, false, 16);
// 画像を保存
$result = imagepng($im, $username."-16.png");
// ここまでで利用していた画像を削除
imagedestroy($im);

// 新たに作成した画像を読み込み
$im = imagecreatefrompng($username."-16.png");
echo '<br><img src="'.$username.'-16.png">';

// 画像の色数を調べる
$cl_total = imagecolorstotal($im);

// 色数のリストを取得
$color_list = array();

echo '<div style="padding: 10px; background:#efefef; color: #000;">';
echo '<p>カラーパレット</p>';

for ($i=0; $i < $cl_total ; $i++) { 
  // パレットで使われている色を配列に格納する
  $color_list[] = imagecolorsforindex($im, $i);
  printf(
    '<span style="color:rgb(%d,%d,%d);">■</span>',
    $color_list[$i]["red"],
    $color_list[$i]["green"],
    $color_list[$i]["blue"]
  );
}
echo '</div>';

echo '<div class="left">';
echo '<p>CCの色と近い色を表示</p>';

// 使用しているカラーパレット1つずつ、一番近いCCの色を探す
for ($i=0; $i < count($cl_total) ; $i++) { 

  // 指定した色と、CCの16色とつきあわせる
  for ($i=0; $i < count($colors) ; $i++) {
    $nearest_color = imagecolorclosest($im, $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]);
    print_r($nearest_color);
    // 近い色を表示
    $to_rgb = imagecolorsforindex ($im, $nearest_color);
    printf(
      '<span style="color:rgb(%d,%d,%d);">■</span>',
      $to_rgb["red"],
      $to_rgb["green"],
      $to_rgb["blue"]
    );
    echo " > ";
  }

  // カラーパレットから1色とりだす
  // CC色でその色に一番近いのを探す
    // 比較をする
  // $hogehogeっていう変数に、取得した近い色を入れていく
  // で、毎回$hogehogeっていうのと、新しく取得した色、どっちが近いかを判定しないといけない
  
  $nR = $nearest_color["red"];
  $nG = $nearest_color["green"];
  $nB = $nearest_color["blue"];

  $cR = $colors[$i]["r"];
  $cG = $colors[$i]["g"];
  $cB = $colors[$i]["b"];

  $distance = ($cR-$nR)*($cR-$nR)+($cG-$nG)*($cG-$nG)+($cB-$nB)*($cB-$nB);

  $distanceDetect = 999999;
  if ($distanceDetect > $distance) {
    $distanceDetect = $distance;
    $targetIndex = $i;
  }
}

// CCの色に変更
imagecolorset($im, $cl_total[$targetIndex], $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]);
$to_rgb = imagecolorsforindex ($im, $nearest_color);
printf(
  '<span style="color:rgb(%d,%d,%d);">■</span>',
  $to_rgb["red"],
  $to_rgb["green"],
  $to_rgb["blue"]
);
print_r($color_name[$i]."<br>"); 

?>
</div>
<div class="left">
  <p>CC表示用</p>
<?php
// 色を取得
for ($y=0; $y < $imageH ; $y++) { 
  for ($x=0; $x < $imageW ; $x++) {
    // 画像の色を取得
    $png_color = imagecolorat($im, $x, $y);
    $to_rgb = imagecolorsforindex($im, $png_color);
    printf(
      '<span style="color:rgb(%d,%d,%d);">%d</span>',
      $to_rgb["red"],
      $to_rgb["green"],
      $to_rgb["blue"],
      sprintf("%x", $png_color)
    );
  }
  echo "<br>";
}
?>
</div>

<p>モニターで表示したときの見た目</p>
<?php
// 色を取得
for ($y=0; $y < $imageH ; $y++) { 
  for ($x=0; $x < $imageW ; $x++) {
    // 画像の色を取得
    $png_color = imagecolorat($im, $x, $y);
  
    $to_rgb = imagecolorsforindex($im, $png_color);
    printf(
      '<div style="display:inline-block;width:20px;background:rgb(%d,%d,%d);">&nbsp;</div>',
      $to_rgb["red"],
      $to_rgb["green"],
      $to_rgb["blue"],
      $png_color
    );
  }
  echo "<br>";
}

// 読み込んだ画像を削除
imagedestroy($im);
?>
  </div>
</body>
</html>