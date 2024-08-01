local logger = {}
local logging  = require"lua.logging"
require"lua.logging.file"

--[[ local appender = function(self, level, message)
    print(level, message)
    return true
  end ]]
  
--local logger = Logging.new(appender)

logger = logging.file {
    filename = "logs/lua_log_%s.log",
    datePattern = "%Y-%m-%d",
} 

return logger;