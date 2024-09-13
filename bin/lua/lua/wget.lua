local wget = {}

local json = require "lua/json"
local md5 = require 'lua/md5'


local cache_tm = 3600 -- 1 hour 
local download_path = "media/"
  
--c:perfom{writefunction = assert(io.open("README.txt", "w+b"))}

  function GetFileName(url)
    return url:match("^.+/(.+)$")
  end
  
  function GetFileExtension(url)
    return url:match("^.+(%..+)$")
  end

  function file_exists(name)
    local f=io.open(name,"r")
    if f~=nil then io.close(f) return true else return false end
  end

  function file_length(name)
    local f=io.open(name,"r")
    local len
    if f~=nil then len = f:seek("end") io.close(f)  return len end
  end



  function GetRealPath(url)
    return url
   --[[  local handle = io.popen("realpath "  .. url)
    local result = handle:read("*a")
    handle:close()
    return result ]]
  end  

  
 
  function wget.get(url)
    local err,head = curl_get_head(url)
    local headers = {}
    if(err == 0) then
      headers = json.decode(head)
    else 
      return false  
    end
    --print("audio file url " .. url .. " head " .. head .. " content type " .. headers["Content-Type"])   
    if(GetFileExtension(url) ~= '.mp3' or headers["Content-Type"]  ~=  'audio/mpeg') then return false end
    filepath = download_path  .. md5.sumhexa(url)
    filename = filepath   .. GetFileExtension(url)
    if(file_exists(filename) and file_length(filename) == headers["Content-Type"]) then 
    --print(filename .. " already exist")
    local f = io.popen("stat -c %Y " .. filename)
    local last_modified = f:read()
    if((os.time() - last_modified) < cache_tm) then
    --print(os.date("%c", last_modified))
    return filename
    end 
    
    os.remove(filename)
    os.remove(filepath  .. ".alaw")
    os.remove(filepath  .. ".ulaw")
    end
  
  --print(GetFileExtension(url))
    err = curl_download_file(url,filename)
    if(err == 0) then
      return filename
    end  
    return nil
  end

 function wget.convert(file)
   local localfile = download_path .. GetFileName(file)
   --print("localfile " .. localfile)
   if(file_exists(localfile) == false or file_length(file) ~= file_length(localfile)) then os.execute("cp "  .. file .. " " .. localfile)  end
   file = localfile
   --local converted = filename .. ".alaw"
   local ext =  GetFileExtension(file)
   local filename = file:match("(.+)%..+$")
  
   if(file_exists(filename .. ".alaw") and file_exists(filename .. ".ulaw")) then return filename end
   local exec 
   if(ext == ".mp3") then
    exec = "lame --decode " .. file .. " " .. filename .. ".wav"  
    --print(exec)
   os.execute(exec) 
   end

   exec = "sox -V " .. filename .. ".wav"  ..  " -r 8000 -c 1 -t ul  " .. filename .. ".ulaw"
   os.execute(exec)

   exec = "sox -V " .. filename .. ".wav"  ..  " -r 8000 -c 1 -t al  " .. filename .. ".alaw"
   os.execute(exec)
   
   return GetRealPath(filename)
 end 

  return wget  
--return '{"status":"ok"}'