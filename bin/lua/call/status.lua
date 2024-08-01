local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local capture  = require "lua/capture" 
dofile("lua/config.lua")
local jsondata = json.decode(jsonstr)


redisclient.set("cdr:" .. jsondata["call_id"],jsonstr)
redisclient.expire("cdr:" .. jsondata["call_id"],3600)
--print("sending json for call update " .. jsonstr)
--local err,resp = curl_post(SERVER_API_URL .. "/call/update",jsonstr)
local err,resp = capture.agi( "/call/update",jsonstr)
--print("call update err " .. err,resp)
if(jsondata.disconnect_time > 0) then
    --print("deleting redis data for call")
    redisclient.del("actions:" .. jsondata["call_id"])
    redisclient.del("action:" .. jsondata["call_id"])
end    
