local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local capture  = require "lua/capture" 

local jsondata = json.decode( jsonstr )

local block = redisclient.get("ipblock:" .. jsondata.srcip)
if(block  ~= nil) then 
--    print("ip already blocked " .. jsondata.srcip) 
    return {} 
end

local err,resp = capture.agi("/is_ip_blocked",jsonstr)
--local count = redisclient.customfunc("evalsha 4d727fee5ff98a818f4626a07e283d8de04b0af0 0 ",'reglog:' .. jsondata.srcip .. ':*')
--print("block ip here ",count)
if(err ~=  0) then return end
--print(resp)
local data = json.decode( resp)

if(data.blocked == true) then    
    redisclient.set("ipblock:" .. jsondata.srcip,1)
    redisclient.expire("ipblock:" .. jsondata.srcip,data.expire)
    file = io.open('logs/temp_block.csv', "a")
    file:write(jsondata.srcip, "\n")
    file:close()
end

