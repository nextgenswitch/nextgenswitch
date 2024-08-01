local actionparser = {}

local redisclient  = require "lua/redisclient"
local json = require "lua/json"
local wget = require 'lua/wget'


function process_play(f)
    local file = f.file 
    if(file == nil) then f.file = nil return f end  
    --print(" local file ?")
    --print(f.localfile)
    if(f.localfile == nil or f.localfile == false) then       
        file = wget.get(file)
    end
    f.file =  wget.convert(file)  
    return f
end    



  

function actionparser.setActions(jsondata)
    if(jsondata.call_id == nil or jsondata.actions == nil ) then return 0 end 
    redisclient.del("actions:" .. jsondata["call_id"])
    local actions = actionparser.parse(jsondata["actions"])
    local i = 0
    for k,v in pairs(actions) do
        redisclient.rpush("actions:" .. jsondata["call_id"] ,json.encode(v))
        i = i+1
    end    
    redisclient.expire("actions:" .. jsondata["call_id"] , 3600)
    return i
end    

function actionparser.parse(actions)
    local actiondata = {}
    for k,v in pairs(actions) do
        
        if(v.verb == "play") then 
            v = process_play(v)
            if(v.file ~= nil) then table.insert(actiondata,v) end

        elseif(v.verb == "gather" or v.verb == "dial") then
            if(v.actions ~= nil) then
                for kp,vp in pairs(v["actions"]) do                
                    vp = process_play(vp)
                    --print("processing gather/dial play " .. file)               
                    if(vp.file ~= nil) then v.actions[kp] = vp else v.actions[kp] = nil end
                       
                end
            end          
            table.insert(actiondata,v)
        else
            table.insert(actiondata,v)
        end
    end    
    return actiondata
end 

return actionparser  