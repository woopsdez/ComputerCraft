-- [/////// 概    要 ///////] --

-- # おみくじプログラム

-- # マイクラ内で必要なもの
-- コンピューター or タートル
-- アドバンスドモニター ??個

-- # 動作順序
-- 1. プログラムが起動  /clear!
-- 2. プレイヤーの顔が表示される  /clear!
-- 3. レッドストーン入力 or マウスタッチ入力でおみくじが開始  /clear!
-- 4. プレイヤーの顔と吉と凶がぐるぐる表示される /clear!
-- 5. 吉か凶がランダムで表示される /clear!
-- 6. 吉と凶でブロックやアイテム、タートルが動作する
--    吉 > タートルがでてきてアイテムがぽこぽこと投げられる
--    凶 > drawbrigeで床が消えて下に落ちる、蜘蛛の巣&パンクステージ&ホッパーでアイテム回収、外のチェストに収納
-- ------------------------- --

-- 変数
local running = true
local _st = 0

-- ストップボタンをセンター表示
local x,y = term.getSize()
local stopStr = "          stop         "
local padding = "                       "
local stopStrlen = x + string.len(stopStr)
local stopBtnX = math.ceil(stopStrlen/5)
local stopBtnY = (y/2)+2
local stopBtnXEnd = stopBtnX + stopStrlen
local stopBtnYEnd = stopBtnY + 3

-- 定数imageから10進数に変更
function lookupColor(index)
  return bit.blshift(1, index) 
end

-- [/////// 表示画像の読み込み ///////] --
function showImage(imagename)
  term.clear()
  if fs.exists(imagename) then
    image = paintutils.loadImage(imagename)
    paintutils.drawImage(image, 1, 1)
  else
    print("no file")
  end
end

function showTitle()
  -- モニター側のスタート画面を表示
  term.setBackgroundColor(colors.yellow)
  showImage("os/omikuji-image/hyoushi")
  term.setBackgroundColor(colors.black)
  term.setTextColor(colors.white)
  write("[Strat => right-click]")
end

function startOmikuji()
  os.startTimer(0.2)
  while true do
    event = os.pullEvent()
    -- 0.2秒で画像表示をくりかえす
    if event == "timer" and _st == 0 then
      -- 表示する画像を配列に格納
      local fileList = fs.list("data/")
      i = math.random(table.maxn(fileList))
      showImage("data/"..fileList[i])
      os.startTimer(0.4)
    elseif event == "monitor_touch" and _st == 0 then
       -- [/////// 吉と凶の判定 ///////] --
      local kuji = math.random(100)
      if kuji >= 50 then
        showImage("os/omikuji-image/kichi")
      else
        showImage("os/omikuji-image/kyou")
      end
      term.setBackgroundColor(colors.black)
      write("[Restrat => right-click]")
      _st = 1
    elseif _st == 1 then
      return false
    end
  end
end

-- ストップボタンを表示
function drawStopButton(x,y)
  term.setCursorPos(8, stopBtnY-2)
  term.setTextColor(colors.black)
  term.setBackgroundColor(colors.yellow)
  term.write("can't stop if rotate image on monitor")
  -- 描写
  term.setBackgroundColor(colors.red)
  term.setTextColor(colors.white)
  term.setCursorPos(stopBtnX, stopBtnY)
  term.setBackgroundColor(colors.black)
  print(padding)
  term.setCursorPos(stopBtnX, stopBtnY+1)
  print(stopStr)
  term.setCursorPos(stopBtnX, stopBtnY+2)
  print(padding)
end

function reboot()
  term.setBackgroundColor(colors.gray)
  term.clear()
  term.setCursorPos(1, 1)
  term.setTextColor(colors.yellow)
  term.write("Restart OS")
  term.setTextColor(colors.orange)
  textutils.slowPrint(".....Loading")
  sleep(1)
  os.reboot()
end

function runTime()
  while running do
    event, button, x, y = os.pullEvent()
    if event == "mouse_click" then
      -- モニターをクリア
      monitor.clear()
      monitor.setBackgroundColor(colors.black)
      term.redirect(monitor.restoreTo)
      reboot()
    elseif event == "monitor_touch" then
      -- ぐるぐるまわる
      term.redirect(monitor)
      startOmikuji()
      event = os.pullEvent()
      if event == "monitor_touch" then
        term.redirect(monitor.restoreTo)
        shell.run("os/omikuji")
      end
    else
      shell.run("os/omikuji")
    end
  end
end

-- [/////// モニターへの接続、表示の切り替え ///////] --
monitor = peripheral.wrap("right")
monitor.clear()
monitor.setTextScale(2)
-- モニターから、元の画面に戻るための変数 monitor.restoreToを定義
if term.current then monitor.restoreTo = term.current() end

while true do
  -- モニターに切り替え
  term.redirect(monitor)
  showTitle()
  
  -- コンピューターに切り替え
  term.redirect(monitor.restoreTo)
  term.clear()
  term.setBackgroundColor(colors.yellow)
  showImage("os/omikuji-image/omikuji-computer")  

  -- ストップボタンの表示
  drawStopButton(stopBtnX,stopBtnY)
  runTime()
end