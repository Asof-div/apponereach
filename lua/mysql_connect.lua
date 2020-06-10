#!/usr/local/bin/lua
luasql = require "luasql.postgres"
json = require "json"

env = assert (luasql.postgres())
con = assert (env:connect("onereach","postgres","postgres","127.0.0.1"))
-- cur,errorString = assert (con:execute([[select * from pilot_lines ]]))
-- print(cur,errorString)

-- local ColNames = cur:getcolnames()
-- local ColWidths = {}
-- for I, Name in ipairs(ColWidths) do
-- 	ColWidths[I] = string.len(Name)
-- end

-- -- Find iut which columns hold numbers:
-- local ColTypes = cur:getcoltypes()
-- local ColIsNums = {}
-- for I, Type in ipairs(ColTypes) do
-- 	if string.sub(Type, 1, 6) == "number" then
-- 		ColIsNums[I] = true
-- 	else
-- 		ColIsNums[I] = false
-- 	end
-- end

local function RowsIter()
	local Row = {}
	return cur:fetch(Row, 'a')
end


-- local Rows = {}
-- for Row in RowsIter do
-- 	table.insert(Rows, Row)
-- end

-- print(Rows)

cur,errorString = assert (con:execute( string.format([[select * from call_flows where direction = 'inbound' and dial_string = '%s' order by priority asc ]], arg[1]) ))
cur,errorString = assert (con:execute( string.format([[select * from call_flows where direction = 'intecom' and dial_string = '%s' order by priority asc ]], arg[1]) ))

res = assert (con:execute(string.format([[
    INSERT INTO cdrs (tenant_id, direction, start_timestamp)
    VALUES ('%d', '%s', '%s')]], 1 , 'outbound', string.format(os.date("%Y-%m-%d %X",os.time())))
  ))

local default = nill;
local autoattendant = nill;
-- print("error" ,errorString)

-- Rows = cur:fetch ({}, 'a')

-- print(Rows.condition)
-- print(Rows.number)
-- print(os.time())
for Row in RowsIter do
	-- for I , field in ipairs(Row) do
		print(" Dial String ".. Row.dial_string)
		print("Direction " .. Row.direction)
		print("Destination Type " .. Row.dest_type)
		if Row.priority == '0' then

			default = Row

		else 

			autoattendant = Row

		end
	-- end
end 

function showResult()
	
	print(default.dest_id ~= nil )
	min, sec = string.match(autoattendant.start_time, '^(%d%d)%:(%d%d)$') 
	wday = json.decode(autoattendant.wday)
	for  _,v in pairs(wday) do
		print(v)
	end

	print(wday[2] )

end

-- showResult()


-- temp =  os.date('*t')
-- print("wday " ..temp.wday)
-- print("mon " ..temp.month)
-- print("hour " ..temp.hour)
-- print("min " ..temp.min)
-- print("day " ..temp.day)
-- print("mon " ..temp.month)
-- print("mon " ..temp.month)

-- row = cur:fetch ({}, "a")

-- print( row)
-- print (cur:numrows());

-- print(#row)

-- while row do
   -- print(string.format("Id: %s, Number: %s", row.id, row.number))
   -- reusing the table of results
   -- row = cur:fetch (row, "a")

-- end








