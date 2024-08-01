local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local capture  = require "lua/capture" 


local jsondata = json.decode( jsonstr )

local block = redisclient.get("ipblock:" .. jsondata.srcip)

if(block  ~= nil) then 
--	print("ip is blocked "  .. jsondata.srcip) 
	return {} 
end


local file = io.open("blacklist.csv", "r");
--local blacklist = {}
if(file ~= nil) then
	for line in file:lines() do
		local ip = string.gsub(line, "%s+", "")
		--print("inserting",ip)
		if(ip == jsondata.srcip) then 
			file:close()
		 	return "{}"
		end
	    --table.insert (blacklist, line);
	end
	file:close()
end




local err,resp = capture.agi("/sip_user/validate",jsonstr)

--print(resp)
--local decode = json.decode( jsonstr )
return resp


