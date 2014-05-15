print "let's go!"

r = 0
e = 0

h = fs.open("/log", "a") -- ログファイルに出力する
h.write("---- start ---- \n")

function erorrLog(...)
	if not r then
		h.write(e.."\n")
		h.flush()
	end
end

while true do
	h.write("start fishing\n")
	h.flush()
	turtle.attack()
	h.write("please wait\n")
	h.flush()
	sleep(10)
	h.write("end fishing\n")
	h.flush()

	r,e = turtle.dig()
	if r then -- もし魚がゲットできたら
		h.write("get fish\n")
		h.flush()
	else
		erorrLog()
	end
end