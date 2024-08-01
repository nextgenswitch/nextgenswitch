local capture  = require "lua/capture" 
local err,resp = capture.agi("/config","{}")
if(err ~= 0) then print("config failed loading.",err,SERVER_API_URL .. "/config")
else
    return resp
end        
