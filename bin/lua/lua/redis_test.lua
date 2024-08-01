local redisclient  = require "lua/redisclient"

redisclient.set("testk","testv")
print(redisclient.get("testk"))