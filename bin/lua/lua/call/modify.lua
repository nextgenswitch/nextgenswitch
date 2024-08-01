local json = require "lua/json"
local actionparser  = require "lua/actionparser"

dofile("lua/enums.lua")
--print("call modify jsonstr " .. jsonstr)
local call = json.decode(jsonstr)
local err = actionparser.setActions(call)
--print("actionparser entries " .. err)
if(err == 0) then return end

call["actions"] = nil
call['script'] = HTTP_CALL_MODIFY
--print("returning json " .. json.encode(call))
--table.removekey(jsondata,'actions')
return json.encode(call)