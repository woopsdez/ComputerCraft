<?php

// CC端末から情報を受けとる
$username = $_GET['name'];

// ----------------------------------------- //
// 変数定義
// ----------------------------------------- //

// ファイル名として格納
$faceImageSrc = $username.".png";

// キャッシュ時間の設定
$cacheTime = 120;

// 工魔メンバー定義
$user_opt = array(
  "198215knock",
  "Aguillette",
  "awa112",
  "Ganeesha",
  "hori2015",
  "HYODO_P",
  "kuroshio9314",
  "lucinda_u",
  "master_cheap",
  "migy",
  "Potato0314",
  "Q61",
  "seven5588",
  "shigekix",
  "gengenetrix"
);

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

// CCの名前定義
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

// ----------------------------------------- //
// 関数定義
// ----------------------------------------- //

// 画像をサーバーから取得する
function fileSave($username,$faceImageSrc){
  // 画像をサーバー上から取得して保存
  $options = array(
    "http" => array(
      "method" => "GET",
      // ユーザーエージェントを変更しないとアクセスできないため
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

// キャッシュ
// アクセス時点でファイルがすでにあるかどうかを判別
function fileCache($filename,$cacheTime){
  // ファイルがあるかどうか
  if (file_exists($filename)) {
    // タイムスタンプを見る
    $timeStamp = filemtime($filename);
    // nullの場合は0を代入
    if (is_null($timeStamp)){$timeStamp = 0;}
    // キャッシュ保持期間の設定
    $limit = $timeStamp + $cacheTime;
    // キャッシュ保持期間内であるかどうか
    if ($timeStamp <= $limit) {
      return true;
    } else {
      return false;
    }
  } else {
    // falseを返す
    return false;
  }
}

// ----------------------------------------- //
// 実行部分
// ----------------------------------------- //

// 渡されたusernameが不適切なものではないかチェック
if(!preg_match("/^[a-zA-Z0-9_-]+$/", $username)){
  // 不適切なものの場合処理を終了
  print("It is inappropriate argument.");
  exit();
}

// ----------------------------------------- //
// 画像が期間内に生成されているものがあればそちらを使う
// ----------------------------------------- //

// スキンサーバーから取得してきた画像
if(fileCache($faceImageSrc,$cacheTime) === false){
  fileSave($username, $faceImageSrc);
}

// ----------------------------------------- //
// 最適化された画像があればそちらを優先する
// ----------------------------------------- //

// 上記ユーザーのリストがusernameに入っているかを確認
foreach ($user_opt as $key => $value) {
  if ($username === $value) {
    // もしメンバーだったら最適可画像を利用
    $username = $username."-opti";
  } else {
    // メンバー以外はCC用に最適化した画像を作成

    // 画像データをCCで正方形に見えるように調整
    $cmdPreview = sprintf("convert %s.png -sample 3600%% %s-view.png", $username,$username);
    $cmdUpscale = sprintf("convert %s.png -sample 300%%x200%% %s-convert.png", $username,$username);
    
    // ファイルキャッシュ確認
    $viewImage = $username."-view.png";
    $convertImage = $username."-convert.png";
    if(fileCache($viewImage,$cacheTime) === false){
      exec($cmdPreview);
    }
    if(fileCache($convertImage,$cacheTime) === false){
      exec($cmdUpscale);
    }
  }
}

// 取得元の画像を表示する用の変数に格納
$previewUrl = $username;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
    body{background: #000; color: #FFF;}
    .text-center{margin:0 auto; text-align: center;}
    .left{float: left;}
    .right{width: 75%; float:right;}
    .hoge{
      display: inline-block;
      width: 10px;
      height: 23px;
      margin: 0 auto;
    }
    span{padding: 1px;}
  </style>
</head>
<body>

<h1 class="text-center">この画像の色を解析するよ！</h1>
<hr>

<div class="left" style="width: 20%;">
  <img src="<?php echo $previewUrl ?>-view.png" >
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
$sizeArray = getimagesize($username."-convert.png");
$imageW = $sizeArray[0]; // 幅
$imageH = $sizeArray[1]; // 高さ

// 画像を読み込み
$im = imagecreatefrompng($username."-convert.png");
// trueカラーからパレットカラーへ変更
imagetruecolortopalette($im, false, 16);
// 画像を保存
$result = imagepng($im, $username."-16.png");
// ここまでで利用していた画像を削除
imagedestroy($im);

// 新たに作成した画像を読み込み
$im = imagecreatefrompng($username."-16.png");
// 画像の色数を調べる
$cl_total = imagecolorstotal($im);
echo '<div style="padding: 10px; background:#efefef; color: #000;">';
echo '<p>このアイコンで使わている色一覧</p>';

// 色数のリストを取得
$color_list = array();
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

// 使用しているカラーパレット1つずつ、一番近いCCの色を探す
$cl_table = array();
for ($i=0; $i < count($cl_total) ; $i++) {
  for ($j=0; $j < count($colors); $j++) { 
    
    // 近い色を探す
    $nearest_color = imagecolorclosest($im, $colors[$j]["r"], $colors[$j]["g"], $colors[$j]["b"]);
  }
}

?>

<!-- </div> -->
<div class="left" style="width: 30%;">
<p>CC表示用に変換</p>

<?php

// CCパレットをもった画像を作成
$cc = imagecreate(16, 16);
$x = 1;
$cc_pallet = array();
for ($i=0; $i < count($colors) ; $i++) { 
  // カラーパレットを設定
  imagecolorset($cc, $i, $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]);
  // 使用する色を設定
  $color = imagecolorallocate($cc, $colors[$i]["r"], $colors[$i]["g"], $colors[$i]["b"]);
  imagefill($cc, $i, 1, $color);  
}

// アイコン画像のサイズで二重ループをかける
for ($y=0; $y < $imageH ; $y++) { 
  for ($x=0; $x < $imageW ; $x++) { 
    // 横のときの処理
    // 左上から順番に色情報を取得する
    $icon_color = imagecolorat($im, $x, $y);
    $icon_rgb = imagecolorsforindex($im, $icon_color);

    // $ccのパレットと比較して一番近い色を取得
    $cc_index = imagecolorclosest($cc, $icon_rgb["red"], $icon_rgb["green"], $icon_rgb["blue"]);
    $cc_rgb = imagecolorsforindex($cc, $cc_index);

    // その色で背景を描写する
    printf('<span class="hoge" style="background-color:rgb(%d,%d,%d);">&nbsp;</span>',
      $cc_rgb["red"],
      $cc_rgb["green"],
      $cc_rgb["blue"],
      $cc_index
    );
  }
  // 縦のときの処理
  echo "<br>";
}
?>

</div>

<div>
  <p>CC表示用テキスト</p>
<?php
// アイコン画像のサイズで二重ループをかける
for ($y=0; $y < $imageH ; $y++) { 
  for ($x=0; $x < $imageW ; $x++) { 
    // 横のときの処理
    // 左上から順番に色情報を取得する
    $icon_color = imagecolorat($im, $x, $y);
    $icon_rgb = imagecolorsforindex($im, $icon_color);
    // $ccのパレットと比較して一番近い色を取得

    $cc_index = imagecolorclosest($cc, $icon_rgb["red"], $icon_rgb["green"], $icon_rgb["blue"]);
    $cc_rgb = imagecolorsforindex($cc, $cc_index);

    // その色で背景を描写する
    printf('<span class="hoge" style="background-color:rgb(%d,%d,%d);">%x</span>',
      $cc_rgb["red"],
      $cc_rgb["green"],
      $cc_rgb["blue"],
      $cc_index
    );

    $output .= sprintf("%x", $cc_index);
  }
  // 縦のときの処理
  echo "<br>";
  // CC上で1px足りないため、ここで継ぎ足し
  $output .= sprintf("%x", $cc_index)."\n";
}

// ユーザー名にoptが入ってるか確認
$opt = strstr($username, "-opt");
// その内容を削除
$final_username = str_replace($opt, "", $username);

file_put_contents($final_username.".txt", $output);

// 読み込んだ画像を削除
imagedestroy($im);
imagedestroy($cc);
unlink($username."-16.png");
unlink($username."-convert.png");
?>

</body>
</html>