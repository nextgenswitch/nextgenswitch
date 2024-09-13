local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local capture  = require "lua/capture" 


local data = json.decode(jsonstr)
jsonstr = redisclient.get("worker:" .. data.name,jsonstr);
redisclient.expire("worker:" .. data.name,3600);

--print("worker running " .. jsonstr)
local worker = json.decode(jsonstr)

local err,resp = capture.agi("/worker/run",jsonstr)
       

if(err ~=  0) then return end
--print("worker response",err,resp)
return resp  

