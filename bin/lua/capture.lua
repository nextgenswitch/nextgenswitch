local capture = {}
dofile("lua/config.lua")
function os.capture(cmd, raw)
    local f = assert(io.popen(cmd, 'r'))
    --local s = assert(f:read())
    --if(s == '') then return nil end
    local s = assert(f:read('*a'))
    f:close()
    if raw then return s end
    s = string.gsub(s, '^%s+', '')
    s = string.gsub(s, '%s+$', '')
    s = string.gsub(s, '[\n\r]+', ' ')
    return s
  end
  
  function capture.execute(path,json)
    local cmd = ("echo  '$foo' | php82 /var/www/html/laravel/easypbx/artisan app:switch-console  $path 2>/dev/null"):gsub('$foo', json):gsub('$path',path);
    --print(cmd)
    local ret = os.capture(cmd)
    --print("capture ret",ret)
    local err = 0
    if(ret == nil or ret == '') then err = 1 end
    --print(ret)
    return err,ret
  end  
  
  function capture.fastagi(path,json)
    local data = "Easypbx AGI 1.0\r\t" ..
    "Length: " ..  #json .. "\r\t" ..
    "Path: " .. path .. "\r\t\r\t" ..
    json .. "\n"
    local err,resp = fastagi_exec(FASTAGI_HOST,FASTAGI_PORT,data)
    --print("fastagi resp",resp,err)
    return err,resp
  end

  function capture.agi(path,json)
    local err,resp
    if(USE_CURL) then
      err,resp = curl_post(SERVER_API_URL  .. path,json,API_USERNAME,API_PASSOWRD)
    else
        err,resp =  capture.fastagi(path,json)
    end
    return err,resp
  end  

  return capture