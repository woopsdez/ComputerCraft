<?
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

// 使用しているカラーパレット1つずつ、一番近いCCの色を探す
$cl_table = array();
for ($i=0; $i < count($cl_total) ; $i++) {
  for ($j=0; $j < count($colors); $j++) {     
    // 近い色を探す
    $nearest_color = imagecolorclosest($im, $colors[$j]["r"], $colors[$j]["g"], $colors[$j]["b"]);
  }
}

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

$cc_indexArray = array();
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

    array_push($cc_indexArray, $cc_index);

    $output .= sprintf("%x", $cc_index);
  }
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