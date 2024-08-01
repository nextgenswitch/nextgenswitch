local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local actionparser  = require "lua/actionparser"
local capture  = require "lua/capture" 
dofile("lua/config.lua")
local jsondata = json.decode(jsonstr)
local actiondata = redisclient.get("action:" .. jsondata.call_id)
if(actiondata == nil) then  return end
local curaction = json.decode(actiondata)
   
for k,v in pairs(jsondata) do curaction[k] = v end

curaction.actions = nil
jsonstr = json.encode(curaction)
--print("dialing call with json " .. jsonstr)
local err,resp = capture.agi("/call/dial",jsonstr)
--local err,resp = curl_post(SERVER_API_URL .. "/call/dial",jsonstr)
--print("dialing error " .. err,resp)
if(err ~=  0) then return end

local data = json.decode(resp)
if(data.error ~= nil and data.error == 1) then return end

if(data.actions ~= nil) then
    curaction.actions = actionparser.parse(data.actions)
    redisclient.set("action:" .. jsondata.call_id,json.encode(curaction))
end    

return resp;