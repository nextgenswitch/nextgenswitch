local json = require "lua/json"
local redisclient  = require "lua/redisclient"
dofile("lua/enums.lua")

local worker = json.decode(jsonstr)

redisclient.set("worker:" .. worker.name,jsonstr);
redisclient.expire("worker:" .. worker.name,3600);

worker['script'] = HTTP_WORKER_CREATE

return json.encode(worker)