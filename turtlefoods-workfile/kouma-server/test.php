<?php
// 画像を作成し、パレット画像に変換します
$im = imagecreatefrompng('migy.png');
imagetruecolortopalette($im, false, 255);

// 探したい色 (RGB)
$colors = array(
    array(254, 145, 154),
    array(153, 145, 188),
    array(153, 90, 145),
    array(255, 137, 92)
);

// それぞれを検索し、パレット内でもっとも近い色を見つけます
// 検索番号、検索した RGB、そして見つかった RGB を返します
foreach($colors as $id => $rgb)
{
    $result = imagecolorclosest($im, $rgb[0], $rgb[1], $rgb[2]);
    $result = imagecolorsforindex($im, $result);
    $result = "({$result['red']}, {$result['green']}, {$result['blue']})";

    echo "#$id: Search ($rgb[0], $rgb[1], $rgb[2]); Closest match: $result.\n";
}

imagedestroy($im);
?>