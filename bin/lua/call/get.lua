local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local urlcode  = require "lua/urlcode"

local Args = {}
urlcode.parseQuery(querystr, Args)

return redisclient.get("cdr:" .. Args.call_id)