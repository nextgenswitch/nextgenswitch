--print("Sending req " .. jsonstr)
local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local actionparser  = require "lua/actionparser"
local capture  = require "lua/capture" 
dofile("lua/enums.lua")
dofile("lua/config.lua")

local err,resp = capture.agi("/call/incoming",jsonstr)
--print("got response " .. resp)
local call = json.decode(resp)
err = actionparser.setActions(call)
--print("action parser entries " .. err)
if(err == 0) then return end

call["actions"] = nil

--print("returning response " .. json.encode(call))

return json.encode(call)




