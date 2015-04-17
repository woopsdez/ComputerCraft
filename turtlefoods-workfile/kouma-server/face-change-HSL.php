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
/*unlink($username.".txt");*/

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
//              [[[[[画像処理]]]]]            //
// ----------------------------------------- //

// 画像を拡大
$cmd_scale = sprintf("/usr/bin/convert -scale 150%% %s %sx2.png", $faceImageSrc,$username);

// 比率を変更
$cmd_geometry = sprintf("/usr/bin/convert -geometry 12x8! %sx2.png %s-convert.png", $username,$username);

exec($cmd_scale); //コマンド実行
exec($cmd_geometry); //コマンド実行

// ----------------------------------------- //
//     [[[[[色情報をテキストデータに変換]]]]]     //
// ----------------------------------------- //

function RGB_TO_HSV ($R, $G, $B)  // RGB Values:Number 0-255
{                                 // HSV Results:Number 0-1
   $HSL = array();

   $var_R = ($R / 255);
   $var_G = ($G / 255);
   $var_B = ($B / 255);

   $var_Min = min($var_R, $var_G, $var_B);
   $var_Max = max($var_R, $var_G, $var_B);
   $del_Max = $var_Max - $var_Min;

   $V = $var_Max;

   if ($del_Max == 0)
   {
      $H = 0;
      $S = 0;
   }
   else
   {
      $S = $del_Max / $var_Max;

      $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
      $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
      $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

      if      ($var_R == $var_Max) $H = $del_B - $del_G;
      else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
      else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

      if ($H<0) $H++;
      if ($H>1) $H--;
   }

   $HSL['H'] = $H;
   $HSL['S'] = $S;
   $HSL['V'] = $V;

   return $HSL;
}

// 画像サイズを取得
$sizeArray = getimagesize($username."-convert.png");
$imageW = $sizeArray[0]; // 幅
$imageH = $sizeArray[1]; // 高さ

// 画像を読み込み
$im = imagecreatefrompng($username."-convert.png");

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
   span{
     padding: 1px;
   }
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
      // HSLに変換
      $plt_HSL = RGB_TO_HSV($colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]);
      printf('<span style="display:inline-block; margin: 3px; padding: 3px; background: rgba(%d,%d,%d,1); ">%.2f,%.2f,%.2f,</span>',
              $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"],
              $plt_HSL["H"], $plt_HSL["S"], $plt_HSL["V"]
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

// 色を取得
for ($y=0; $y < $imageH ; $y++) { 
  for ($x=0; $x < $imageW ; $x++) { 
    $color_index = imagecolorat($im, $x, $y);
    $color_trans = imagecolorsforindex($im, $color_index);
    $web_HSL = RGB_TO_HSV($color_trans["red"], $color_trans["green"], $color_trans["blue"]);
    
    // 最小値の比較用変数
    $distanceDetect = 999999999;
    $targetIndex = 0;

    $threshold = 0.1;
    // CCで使える色に近い色の距離を取得
    for ($i=0; $i < count($colors); $i++) { 
      $cc_HSL = RGB_TO_HSV($colors[$i]["r"],$colors[$i]["g"],$colors[$i]["b"]);

      if ($web_HSL["V"] <= $threshold || (1.0 - $threshold) <= $web_HSL["V"]) {
        if ((1.0 - $threshold) <= $web_HSL["V"]) {              
          $distance = ($cc_HSL["S"] - $web_HSL["S"]) * ($cc_HSL["S"] - $web_HSL["S"]) +
                      ($cc_HSL["H"] - $web_HSL["H"]) * ($cc_HSL["H"] - $web_HSL["H"]);
        } else if ($web_HSL["S"] <= $threshold) {
          $distance = ($cc_HSL["S"] - $web_HSL["S"]) * ($cc_HSL["S"] - $web_HSL["S"]) +
                      ($cc_HSL["V"] - $web_HSL["V"]) * ($cc_HSL["V"] - $web_HSL["V"]);
        } else {
          $distance = ($cc_HSL["V"] - $web_HSL["V"]) * ($cc_HSL["V"] - $web_HSL["V"]);
        }
      } else {
        if ($cc_HSL["S"] <= 0.01) {
          $distance = 999999999;
        } else {
          // 色相環の距離計算
          $hue = abs($cc_HSL["H"] - $web_HSL["H"]);
          if (0.5 < $hue) { $hue = 1 - $hue; } 
          $distance = ($hue * $hue);// +
                      //0.1 * ($cc_HSL["S"] - $web_HSL["S"]) * ($cc_HSL["S"] - $web_HSL["S"]);// +                    
                      //($cc_HSL["V"] - $web_HSL["V"]) * ($cc_HSL["V"] - $web_HSL["V"]);          
        }
      }

      // 16色の中で、一番近い距離を取得
      if ($distanceDetect > $distance) {
        $distanceDetect = $distance;
        $targetIndex = $i;
      }
    }

    // 確認用表示
    printf(
      '<span style="display:inline-block;width:1.5%%; background: rgba(%d,%d,%d,1); "> %x </span>',
      $colors[$targetIndex]["r"],
      $colors[$targetIndex]["g"],
      $colors[$targetIndex]["b"],
      $targetIndex
      // $color_trans["red"],
      // $color_trans["green"],
      // $color_trans["blue"]
    );

    // CC用に16進数に変換
    $text = sprintf("%x", $targetIndex);

    // ファイル書き込み
    $output .= $text;
  }
  $output .= "\n";

  echo "<br>";
}

  file_put_contents($username.".txt", $output);
  unlink($username."x2.png");
  unlink($username."-convert.png");
?>
</div>
</body>
</html>