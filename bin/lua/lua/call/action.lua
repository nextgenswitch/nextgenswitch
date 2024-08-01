local json = require "lua/json"
local redisclient  = require "lua/redisclient"
local actionparser  = require "lua/actionparser"
local capture  = require "lua/capture" 
dofile("lua/enums.lua")
dofile("lua/config.lua")


--print("json string is " .. jsonstr)
local jsondata = json.decode(jsonstr)

if(jsondata.call_id == nil) then return end


local action_id = 0
--print("getting action data")
--local actiondata = redisclient.lpop("actions:" .. jsondata["call_id"])
--print("actiondata for " .. "actions:" .. jsondata.call_id )

::ACTION::
local action = {}
local curactiondata = redisclient.get("action:" .. jsondata.call_id)
local actionexist = false
local curaction = {}
if(curactiondata ~= nil) then   
    curaction =  json.decode(curactiondata)
    actionexist = true
    --print("curaction is not nil" .. curactiondata)
    if(curaction.actions ~= nil) then
        action = table.remove(curaction.actions,1)
        if(action == nil) then return end  
        redisclient.set("action:" .. jsondata.call_id,json.encode(curaction))
    else
        return
    end
else
    --print("curaction is  nil")

    local actiondata = redisclient.lpop("actions:" .. jsondata["call_id"])
    --print("actiondata for " .. "actions:" .. jsondata.call_id ,actiondata)
    if(actiondata ~= nil) then
        action = json.decode(actiondata)
    --else
    --    return    
    end            
end 
--if(action.verb ~= nil) then print("action verb is " .. action.verb,actionexist) end
if(action.verb == "play") then
    action_id = ACTION_PLAY
elseif(action.verb == "bridge") then
        --redisclient.set("action:" .. jsondata.call_id,json.encode(action))
        action_id = ACTION_BRIDGE
elseif(action.verb == "record") then
        redisclient.set("action:" .. jsondata.call_id,json.encode(action))
        action_id = ACTION_RECORD        
elseif(action.verb == "dial" and actionexist == false) then
        redisclient.set("action:" .. jsondata.call_id,json.encode(action))
        action_id = ACTION_DIAL                
elseif(action.verb == "gather" and actionexist == false) then
    redisclient.set("action:" .. jsondata.call_id,json.encode(action))
    action["play"] = nil
    action_id = ACTION_GATHER
elseif(action.verb == "redirect") then
    if(action_id == ACTION_REDIRECT) then
        --print("nesting action will not execute")
        action_id = ACTION_HANGUP  
        goto OUT
    end    
    action_id = ACTION_REDIRECT
    local data = {}
    data.url = action.url
    data.call_id = jsondata.call_id
    data.method = action.method
    data.body = {}
    --data.body.call_id = jsondata.call_id
    print("sending for url request" .. json.encode(data))
    --local err,resp = curl_post(SERVER_API_URL .. "/call/url_request",json.encode(data))
    local err,resp = capture.agi( "/call/url_request",json.encode(data))
    if(err == 0) then 
        --print("transfer response is " .. resp)
        local call = json.decode(resp)    
        if(actionexist == false) then 
            actionparser.setActions(call) 
        else
            curaction.actions = actionparser.parse(call.actions)
            redisclient.set("action:" .. jsondata.call_id,json.encode(curaction))
        end    
        --print("redirect completed , getting next action")
        goto ACTION
    else
        --print("error in redirect , trying next action")
        goto ACTION
    end

elseif(action.verb == "hangup") then
    action_id = ACTION_HANGUP  
elseif(action.verb == "sms") then
    local data = action
    data.call_id = jsondata.call_id
    --data.to = 
    --local err,resp = curl_post(SERVER_API_URL .. "/sms",json.encode(data))
    local err,resp = capture.agi(  "/sms",json.encode(data))
    goto ACTION  
elseif(action.verb == "leave") then
    print("got leave tag")
    --redisclient.del("action:" .. jsondata.call_id)
    --goto ACTION
    action_id = ACTION_LEAVE     
elseif(action.verb ~= nil) then
    print("not supported action " .. action.verb)
else
    action_id = ACTION_HANGUP     
end    
::OUT::
action['action_id'] = action_id 
--print("sending " .. action_id .. " " .. json.encode(action))
return json.encode(action);    
