local redis = {}



function redis.get(key)
   return redis_cmd_get("GET",key)
end    

function redis.set(key,val)
    return redis_cmd_set("SET",key,val)
end    

function redis.lpop(key)
    return redis_cmd_get("LPOP",key)
end    
 
function redis.lpush(key,val)
     return redis_cmd_set("LPUSH",key,val)
end 

function redis.rpop(key)
    return redis_cmd_get("RPOP",key)
end  

function redis.rpush(key,val)
    return redis_cmd_set("RPUSH",key,val)
end 

function redis.del(key)
    return redis_cmd_get("DEL",key)
end

function redis.expire(key,val)
    return redis_cmd_set("EXPIRE",key,tostring(val))
end

function redis.customfunc(key,val)
    return redis_cmd_get(key,val)
end



return redis
