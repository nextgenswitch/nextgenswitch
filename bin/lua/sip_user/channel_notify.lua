local capture  = require "lua/capture" 
local err,resp = capture.agi("/sip_user/channel_notify",jsonstr  )
--print("POST err" .. err .. "resp" .. resp)
if(err ~=  0) then return end
return