local json = require "lua/json"
dofile("lua/enums.lua")
--print("license call" .. jsonstr)
local err,resp = set_license(jsonstr)
local jsondata = json.decode(jsonstr)
--jsondata['script'] = HTTP_LICENSE_VERIFY
return json.encode(jsondata)