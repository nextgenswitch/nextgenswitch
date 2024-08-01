
local json = require "lua/json"
local actionparser  = require "lua/actionparser"

dofile("lua/enums.lua")
--print("call create jsonstr " .. jsonstr)
local call = json.decode(jsonstr)
local err = actionparser.setActions(call)
--print("actionparser entries " .. err)
if(err == 0) then return end

if(call.to == nill or call.channel_id == nil) then  return end
--print("call to ", call.to)
call["actions"] = nil
call['script'] = HTTP_CALL_CREATE
--print("returning json " .. json.encode(call))
--table.removekey(jsondata,'actions')
return json.encode(call)





