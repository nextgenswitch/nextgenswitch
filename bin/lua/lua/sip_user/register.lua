local json = require "lua/json"
dofile("lua/enums.lua")
local register = json.decode(jsonstr)
register.script = HTTP_CHANNEL_REGISTER

return json.encode(register)
