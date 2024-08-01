local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local actionparser  = require "lua/actionparser"
local logger  = require "lua/logger"
local capture  = require "lua/capture" 

dofile("lua/config.lua")

--logger:info("logging.file test")
--print("post json string",jsonstr)
local jsondata = json.decode(jsonstr)

if(jsondata.call_id == nil) then return end

local parseReturn = true
local curactiondata = redisclient.get("action:" .. jsondata.call_id)
--print("post data for current action " .. curactiondata)
err = redisclient.del("action:" .. jsondata.call_id)
local curaction = {}
if(curactiondata) then curaction = json.decode(curactiondata) end

--logger:info("json string is " .. curactiondata)


--print("json string is ",jsonstr,curactiondata)
if(curaction and curaction.verb == "dial" and jsondata.record_file ~= nil) then
    --local soxcmd = "sox -m " .. jsondata.record_file .. "_outbound.wav " .. jsondata.record_file  .. "_inbound.wav " .. jsondata.record_file .. ".wav"
    --print(soxcmd)
    --os.execute(soxcmd)
    
    local filename = jsondata.record_file:match("(.+)%..+$")
    soxcmd =  "sox " .. filename .. ".wav -n noiseprof " .. filename .. "_noise.prof"
    os.execute(soxcmd)

    soxcmd =  "sox " .. filename .. ".wav " ..  filename .. "_clean.wav noisered " ..   filename .. "_noise.prof 0.21"
    os.execute(soxcmd)

    
    
    if(curaction.trim ~= nil) then
    soxcmd =  "sox " .. filename .. "_clean.wav " ..  filename .. ".wav silence -l 1 0.3 1% -1 2.0 1%"
    os.execute(soxcmd)
    else
        os.execute("cp "  .. filename .. "_clean.wav " .. " " .. filename .. ".wav ")
    end    

    soxcmd =  "lame -V2 " .. filename .. ".wav " ..  filename .. ".mp3"
    os.execute(soxcmd)
    jsondata.record_file = filename .. ".mp3"

    if(curaction.recordingStatusCallback ~= nil) then
        jsondata.recordingStatusCallback = curaction.recordingStatusCallback
    end    
    os.execute(soxcmd)
    os.execute("rm -f " .. filename .. ".wav " .. filename .. "_clean.wav")
   
    --local err,resp = curl_post(SERVER_API_URL .. "/call/record_update",json.encode(jsondata))
    local err,resp = capture.agi( "/call/record_update",json.encode(jsondata))
    
end  

--[[ if(curaction and (curaction.enqueue ~= nil or curaction.queue ~= nil)) then
    local data = {}
    local post = jsondata
    post.name = curaction.to
    if(curaction.enqueue) then 
        data = curaction.enqueue         
    else 
        data = curaction.queue 
    end
    post.status = jsondata.dial_status
    
    
    data.call_id = jsondata.call_id
    data.body = post
    
    --local err,resp = curl_post(SERVER_API_URL .. "/call/url_request",json.encode(data))
    local err,resp = capture.agi("/call/url_request",json.encode(data))
  --  logger:info("sent enqueue/queue post req " .. err  .. json.encode(data))
    
end ]]

--print("del action response for " .. "action:" .. jsondata.call_id,err)
if(jsondata.error ~= nil and jsondata.error == true) then return 1 end



 

if(curaction and curaction.verb == "gather") then
    if(jsondata.rec_path ~= "") then
        local filename = jsondata.rec_path
        filename = filename:match("(.+)%..+$")
        
       --[[  local soxcmd =  "sox " .. filename .. ".wav -n noiseprof " .. filename .. "_noise.prof"
        os.execute(soxcmd)
        
        soxcmd =  "sox " .. filename .. ".wav " ..  filename .. "_clean.wav noisered " ..   filename .. "_noise.prof 0.21"
        os.execute(soxcmd)

        soxcmd =  "sox " .. filename .. "_clean.wav " ..  filename .. ".wav pad 1 1" 
        os.execute(soxcmd) ]]
 
        jsondata.voice = filename .. ".wav"
        
        if(curaction.transcript == true) then
            --local err,resp = curl_post(SERVER_API_URL .. "/speech_to_text",json.encode(jsondata))
            local err,resp = capture.agi( "/speech_to_text",json.encode(jsondata))
            --print("synthesize error " .. err)
            if(err ~=  0) then return end
           -- print("synthesize result ",err,resp)
            local data = json.decode(resp)
            if(data.error ~= nil and data.error == 1) then 
                logger:info("speech to text error " .. data.error)  
            else
                jsondata.speech_result = data.text
                if(data.confidence ~= nil) then jsondata.confidence = data.confidence end
            end
        end

        
    end

end

  

if(curaction and curaction.verb == "record") then 
    --parseReturn = false 
    if(jsondata.rec_path ~= "") then
        local filename = jsondata.rec_path
        filename = filename:match("(.+)%..+$")
        
        local soxcmd =  "sox " .. filename .. ".wav -n noiseprof " .. filename .. "_noise.prof"
        os.execute(soxcmd)
        
        soxcmd =  "sox " .. filename .. ".wav " ..  filename .. "_clean.wav noisered " ..   filename .. "_noise.prof 0.21"
        os.execute(soxcmd)
        jsondata.voice = filename .. "_clean.wav"
        if(curaction.transcribe == true) then
            --local err,resp = curl_post(SERVER_API_URL .. "/speech_to_text",json.encode(jsondata))
            local err,resp = capture.agi("/speech_to_text",json.encode(jsondata))
            --print("synthesize error " .. err)
            if(err ==  0) then 
                local data = json.decode(resp)
                if(data.error ~= nil and data.error == 1) then 
                    logger:info("speech to text error " .. data.error)  
                else
                    jsondata.speech_result = data.text
                    if(data.confidence ~= nil) then jsondata.confidence = data.confidence end
                end
            end
        end

        if(curaction.trim ~= nil) then
            soxcmd =  "sox " .. filename .. "_clean.wav " ..  filename .. ".wav silence -l 1 0.3 1% -1 2.0 1%"
            os.execute(soxcmd)
            else
                os.execute("cp "  .. filename .. "_clean.wav " .. " " .. filename .. ".wav ")
        end    
        
        soxcmd =  "lame -V2 " .. filename .. ".wav " ..  filename .. ".mp3"
        os.execute(soxcmd)
        jsondata.record_file = filename .. ".mp3"
        os.execute("rm -f " .. filename .. ".wav " .. filename .. "_clean.wav")
    
        
        
    end
end

if(jsondata.call_destroy == 1)  then 
    jsondata.call_destroy = nil 
    parseReturn = false 
end

if(curaction and curaction.action ~= nil) then
    --if(curaction.method ~= nil) then jsondata.method = curaction.method end

    local data = {}
    data.url = curaction.action
    data.call_id = jsondata.call_id
    data.method = curaction.method
    data.body = jsondata
    --jsondata.action = curaction
   
    --local err,resp = curl_post(SERVER_API_URL .. "/call/url_request",json.encode(data))
    local err,resp = capture.agi("/call/url_request",json.encode(data))
    --print("sent post req" .. json.encode(data),err)
    if(err ~= 0) then   return err end
    --print("got post resp" .. resp)
    local call = json.decode(resp)
    if(call.error ~= null and call.error == true) then logger:info("error on post request") return 1 end    
    if(parseReturn == true) then err = actionparser.setActions(call) end
    return err;
end
return 1