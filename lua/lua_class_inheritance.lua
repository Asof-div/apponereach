Object = {}
function Object:new (type,id,name)
    o = {}
    self.type = type or "none"
    self.id = id or 0
    self.name = name or "noname"
    setmetatable(o, self)
    self.__index = self
    return o
end

function Object:place(x,y)
    self.x = x
    self.y = y
end

-- Player class
Player = Object:new()

function Player:new(id,name)
    o = Object:new("player",id,name)
    o.inventory = {}
    o.collisions = {}
    return o
end

function Player:move(x,y)
    return print("moved to " ..x.." x " .. y)
end

local player = Player:new(1, "plyr1")
player:move(2,2)



---------------

---------

Object = {}

function Object:__tostring()
   if rawget(self, "type") then  -- only classes have field "type"
      return "Class: "..tostring(self.type)
   else                          -- instances of classes do not have field "type"
      return 
         "Type: "..tostring(self.type)..", id: "..tostring(self.id)
         ..", name: "..tostring(self.name)
   end
end

function Object:newChildClass(type)  -- constructor of subclass
   self.__index = self
   return
      setmetatable({
         type = type or "none",
         parentClass = self,
         __tostring = self.__tostring
      }, self)
end

function Object:new(id, name)        -- constructor of instance
   self.__index = self
   return
      setmetatable({
         id = id or 0,
         name = name or "noname"
      }, self)
end

function Object:place(x,y)
   self.x = x
   self.y = y
end

------
Player = Object:newChildClass("player")

function Player:new(id,name)
  local o = Player.parentClass.new(self, id, name)  -- call inherited constructor
  o.inventory = {}
  o.collisions = {}
  return o
end

function Player:move(x, y)
   self:place(x, y)
   print("moved to (" ..self.x..", " .. self.y..")")
end

local player = Player:new(1, "plyr1")
print(player)     -->  Type: player, id: 1, name: plyr1
player:move(2,2)  -->  moved to (2, 2)