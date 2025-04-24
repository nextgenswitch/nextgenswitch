local json = require "lua/json"
local redisclient  = require "lua/redisclient"
urlcode = require 'lua/urlcode'
--local jsondata = json.decode(jsonstr)
--redisclient.set("testk","testval")
print("redis get " .. redisclient.get("testk"))
--querystr = "call_id=2dsfdsf";

err,head = curl_get_head("http://dns6.infosoftbd.com/codecanyon.zip")
print("file head err " .. err .. " " .. head)
print(querystr)
local Args = {}
urlcode.parseQuery(querystr, Args)
print(Args.call_id)
local call = {}
call["status"] = 2
call["error_code"] = 400
call["call_status"] = "not found"
call["duration"] = 0
local call_data 
if(Args.call_id) then
    call_data = redisclient.get("cdr:" .. Args.call_id)
    --print(Args.call_id)
    --call_data = redis_get(1,"cdr:" .. Args.call_id)
    print(call_data)
    if(call_data ~= nil) then
        call_json = json.decode(call_data)
        call["status"] = 0
        if(call_json.call_status == "completed") then
            call["status"] = 1
        elseif(call_json.call_status == "failed") then 
            call["status"] = 2  
        end

        if(call_json.duration and call_json.duration > 0) then
            call["duration"] = call_json.duration 
        end

       
        if(call_json.error_code) then  
            call["error_code"] = call_json.error_code
        end
        
        if(call_json.call_status) then  
            call["call_status"] = call_json.call_status
        end
        
                

    end    

end  

   

return json.encode(call)



