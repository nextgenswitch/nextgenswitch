urlcode = require 'lua/urlcode'
local json = require "lua/json"
local md5 = require 'lua/md5'

function file_exists(name)
    local f=io.open(name,"r")
    if f~=nil then io.close(f) return true else return false end
end

local Args = {}
urlcode.parseQuery(querystr, Args)
if(Args.text == nil) then return end
--print(Args.text)
local filename   = "records/" ..  md5.sumhexa(querystr) .. ".mp3"

if(file_exists(filename) == false) then 

local cmd = 'gtts-cli "' .. string.gsub(Args.text, '"', '\\"') .. '"'
if(Args.lang ~= nil) then cmd = cmd .. ' --lang ' .. Args.lang end
cmd = cmd .. " --output " .. filename
print(cmd)
local handle = io.popen(cmd)
local result = handle:read("*a")
handle:close()
end

local resp = {}
resp.file  =  filename

return json.encode(resp)