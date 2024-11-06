local json = require "lua/json"
local capture  = require "lua/capture" 
local jsondata = json.decode( jsonstr )

local err,resp = capture.agi("/sip_user/sms",jsonstr)

--print(jsonstr)
--print(resp)
--local decode = json.decode( jsonstr )
return resp
