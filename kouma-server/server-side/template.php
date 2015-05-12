<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>showplayerface:preview：工魔サーバー</title>

  <!-- Bootstrap CSS -->
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .arrow{font-size: 8em; display: block; width: 100%; text-align: center; margin-top: 100px;}
  .color-block{display:inline; font-size:8px; padding: 4px 6px;}
  .pallete-block{display:inline-block; width: 30px; height: 30px; margin: 0 1px; border: 1px solid;}
  #cc-monitor{width:288px;}
  .debug{display: none;}
  .on{display: inline;}
  .cc-color0{background-color: rgb(255,255,255);}
  .cc-color1{background-color: rgb(242,178,58);}
  .cc-color2{background-color: rgb(229,127,216);}
  .cc-color3{background-color: rgb(153,178,242);}
  .cc-color4{background-color: rgb(217,228,101);}
  .cc-color5{background-color: rgb( 81,214, 21);}
  .cc-color6{background-color: rgb(242,178,204);}
  .cc-color7{background-color: rgb( 76, 76, 76);}
  .cc-color8{background-color: rgb(153,153,153);}
  .cc-color9{background-color: rgb( 76,153,178);}
  .cc-color10{background-color: rgb(178,102,229);}
  .cc-color11{background-color: rgb( 37, 49,146);}
  .cc-color12{background-color: rgb(127,102, 76);}
  .cc-color13{background-color: rgb( 87,166, 78);}
  .cc-color14{background-color: rgb(204, 76, 76);}
  .cc-color15{background-color: rgb( 25, 25, 25);}
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>

      <header>
        <nav class="navbar navbar-inverse" role="navigation">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">工魔サーバー 顔表示プログラム ビューワー</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://www.nicovideo.jp/mylist/49460779">参加者動画</a></li>
              <li><a href="http://www.nicovideo.jp/user/361422">作った人</a></li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </nav>
      </header>

      <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
        <section class="preview">
          <div class="jumbotron">
            <div class="container">
              <h1>スキンプレビュー</h1>
              <p>あなたのスキンはこんな感じで表示されます。</p>
              <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                  <h2>スキン</h2>
                  <img class="img-responsive" src="http://minecraft-skin-viewer.com/face.php?u=<?php echo $previewUrl; ?>&s=288">
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                  <i class="glyphicon glyphicon glyphicon-chevron-right arrow"></i>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                  <h2>モニタ表示</h2>
                  <div id="cc-monitor"></div>
                  <!-- <p><a id="debug" class="btn btn-primary" href='#'>デバック用表示</a></p> -->
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="faq">
          <h1>よくある質問</h1>
          <p>趣味で作ったものなのでお問い合わせいただいても対応はできかねます。ご了承くださいなー。</p>

          <h2>1. なんで色が変わっちゃうの？</h2>
          <p>ComputerCraftで追加されるモニターは下記の16色しか表示されないのです。</p>
          <p>ゲーム内で使える色、一覧</p>
          <div id="cc_tables"></div>

          <h2>2. 顔がスティーブになっちゃう</h2>
          <p>Minecraftスキンサーバー(<a href="http://minecraft-skin-viewer.com/">http://minecraft-skin-viewer.com/</a>)にてユーザー名で検索して顔の画像を取得しています。
            このサイトで表示されていない場合はスティーブになってしまうので、表示させたい場合は上記サイトに登録してみてください。
          </p>

          <h2>3. 顔が真っ黒になっちゃうんだけど…</h2>
          <p>スキンに透過処理が行われている場合、Minecraftスキンサーバー(<a href="http://minecraft-skin-viewer.com/">http://minecraft-skin-viewer.com/</a>)の仕様上、その部分は黒く表示されてしまいます。ペインターなどで開いて、改めて塗り直してスキンに適用してみてください。</p>

          <h2>4. 色がぜんぜんあってないんだけど…</h2>
          <p>制作者のスキル不足です。あきらめてください。</p>

<!--           <h2>5. プログラム欲しいです！</h2>
          <p>どうぞどうぞ、こちらにあります。</p> -->
        </section>

        <footer>
          <hr>
          <div class="copyright text-center">
            <ul class="list-unstyled list-inline">
              <li><a href="http://www.nicovideo.jp/user/361422">ミギー</a></li>
              <li>スペシャルサンクス：<a href="http://minecraft-skin-viewer.com/">Minecraft Skin Server</a></li>
            </ul>
          </div>
        </footer>

      </div>

      <!-- jQuery -->
      <script src="//code.jquery.com/jquery.js"></script>
      <!-- Bootstrap JavaScript -->
      <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
      <script type="text/javascript">
      // ccで使える色 
      var CCcolors = <?php echo json_encode($colors); ?>;
      $.each(CCcolors, function(i, val) {
        var ccColorName = "cc-color" + i;
        $("#cc_tables").append('<span class="' + ccColorName + ' pallete-block"> </span>');
      });

      // モニタ用表示部分
      var cc_indexArray = <?php echo json_encode($cc_indexArray) ?>;
      $.each(cc_indexArray, function(i, val) {
        $('#cc-monitor').append(
          '<div class="color-block cc-color'+ cc_indexArray[i] +'"><span class="debug">'+ cc_indexArray[i] +'</span></div>'
          );
      });

      // ボタンクリック
      $('#debug').click(function(event) {
        event.preventDefault();
        $('.debug').toggleClass('on');
      });


      </script>
    </body>
    </html>