local json = require "lua/json"
local actionparser  = require "lua/actionparser"
local redisclient  = require "lua/redisclient"
dofile("lua/enums.lua")

local call = json.decode(jsonstr)
--local err = actionparser.setActions(call)
--if(err == 0) then return end

redisclient.set("modify:" .. call.call_id,jsonstr)

call["actions"] = nil
call['script'] = HTTP_CALL_MODIFY
--print("returning json " .. json.encode(call))
--table.removekey(jsondata,'actions')
return json.encode(call)