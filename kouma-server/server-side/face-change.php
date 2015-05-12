<?php

// CC端末から情報を受けとる
$username = $_GET['name'];

// ----------------------------------------- //
// 変数定義
// ----------------------------------------- //

// ファイル名として格納
$faceImageSrc = $username.".png";

// 取得元の画像を表示する用の変数に格納
$previewUrl = $username;

// キャッシュ時間の設定
$cacheTime = 120;

// CC出力文字列格納用変数
$output = "";

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
include "image-conversion.php";
include "template.php";
?>

