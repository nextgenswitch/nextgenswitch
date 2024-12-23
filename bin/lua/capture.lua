local capture = {}
dofile("lua/config.lua")
  
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
    if(USE_AGI) then
        err,resp =  curl_post("http://" .. FASTAGI_HOST  .. path,json)    
    else
        err,resp = curl_post(SERVER_API_URL  .. path,json,API_USERNAME,API_PASSOWRD)
    end
    return err,resp
  end  

  return capture