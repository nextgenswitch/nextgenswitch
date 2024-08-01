local json = require "lua/json"
local redisclient  = require "lua/redisclient"
dofile("lua/enums.lua")

data = json.decode(jsonstr)

data['script'] = HTTP_SET_SETTINGS

return json.encode(data)