local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local data = json.decode(jsonstr)
redisclient.del("worker:" .. data.name);
--print("worker destroyed", data.name)