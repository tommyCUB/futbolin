# -*- coding: utf-8 -*-
import math
width= 480
heigth= 360

class General :
    def __init__(self):
        self.PI= 3.14159265
    def getAngulo(self,x0,y0,xD,yD):
        ang=0
        dy=float(yD)-float(y0)
        dx=float(xD)-float(x0)
        if dx>0:
            ang=math.atan(dy/dx)
        else:
            if dx<0:
                ang=math.atan(dy/dx)+self.PI
            else:
                if y0<yD:
                    ang=self.PI/2
                else:
                    ang=-self.PI/2
        ang=self.corregirAngulo(ang)
        return ang
    def getDistancia(self, x1,y1,x2,y2):
       temp=0.00000
       temp = math.sqrt(pow(x1-x2,2)+pow(y1-y2,2))
       return temp
    def corregirAngulo(self,ang):
       while ang<0:
           ang+=self.PI*2
       while ang>=self.PI*2:
           ang-=self.PI*2
       return ang
    def control(self,numero,mini,maxi):
       if numero<mini:
           return mini
       if numero>maxi:
           return maxi
       return numero
    def DeComasPaArreglo(self,cadena,caracter,entero=False):
        retorno = list()
        temp=""
        for i in range(cadena.__len__()):
            if cadena[i]==caracter:
                if entero==True:
                    temp= int(temp)
                retorno.append(temp)
                temp=""
            else:
                temp+=str(cadena[i])
                temp =str(temp)
        if entero==True:
             temp= int(temp)
        retorno.append(temp)
        return retorno
    def ConvertirXACoordenaEstandar(self,xo):
        return width/2+ int(xo)
    def ConvertirYACoordenaEstandar(self,yo):
        return heigth/2+ int(yo)
class Comando :
    def __init__(self):
        self.equipo
        self.jugador
        self.general= General()
    def setEquipo(self,nEquipo):
        self.equipo=nEquipo
    def getEquipo(self):
        return self.equipo
    def getJugador(self):
        return self.jugador
    def setJugador(self,nJugador):
        self.jugador=nJugador
    def tipo(self):
        pass
class GOTO(Comando):
    def __init__(self,j,nX,nY):
        self.x=nX
        self.y=nY
        self.general= General()
        self.setJugador(self.general.control(j,0,10))
    def getCadena(self):
        return "GOTO("+str(self.jugador)+","+str(self.general.ConvertirXACoordenaEstandar(self.x))+","+str(self.general.ConvertirYACoordenaEstandar(self.y))+")"
class HIT(Comando):
    def __init__(self, j,xn,yn):
        self.x=xn
        self.y=yn
        self.general= General()
        self.setJugador(self.general.control(j,0,10))
    def getX(self):
        return self.x
    def getY(self):
        return self.y
    def getCadena(self):
        return "HIT("+str(self.jugador)+","+str(self.general.ConvertirXACoordenaEstandar(self.x))+","+str(self.general.ConvertirYACoordenaEstandar(self.y))+")"
class PASS(Comando):
    def __init__(self,jugador1,jugador2):
        self.general= General()
        self.jugadorDestino= self.general.control(jugador2,0,10)
        self.setJugador(self.general.control(jugador1,0,10))
    def getCadena(self):
        return "PASS("+str(self.jugador)+","+str(self.jugadorDestino)+")"
class SHOOT (Comando):
     def __init__(self,jugador, aDesvio):
        self.general= General()
        self.setJugador(self.general.control(jugador,0,10))
        self.desvio=aDesvio
     def getCadena(self):
        return "SHOOT("+str(self.jugador)+","+str(self.desvio)+")"
