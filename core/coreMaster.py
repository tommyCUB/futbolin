# -*- coding: utf-8 -*-
import math, random
import miparser
from machine import *
import os
num_players=11
width= 480
heigth= 360


class Point:
    def __init__(self,ax=0,ay=0):
        self.x=ax
        self.y=ay

    def getPuntoStr(self):
        return str(str(self.x)+','+str(self.y))

class Tiempo:
    def __init__(self):
        self.min=0
        self.seg=0
class Marcador:
    def __init__(self):
        self.GolesMios=0
        self.GolesContrarios=0
class Jugador :
    def __init__(self,nNumero,nFuerza):
        self.y=0
        self.x=0
        self.xDes=0
        self.yDes=0
        self.general= General()
        self.fuerza=self.general.control(int(nFuerza),4,7)
        self.velocidad=11-int(self.fuerza)
        self.numero=nNumero
    def getX(self):
        return self.x
    def getY(self):
        return self.y
    def getFuerza(self):
        return self.fuerza
    def setXY(self, nx,  ny):
        self.x=int(nx)
        self.y=int(ny)
        self.xDes=self.x
        self.yDes=self.y
    def irA(self,nX,nY):
        self.xDes=int(nX)
        self.yDes=int(nY)
    def getNumero(self):
        return self.numero
    def mover(self):
        if self.general.getDistancia(self.x,self.y,self.xDes,self.yDes)>self.velocidad+4:
            angulo=self.general.getAngulo(self.x,self.y,self.xDes,self.yDes)
            self.x+= math.cos(angulo)*self.velocidad
            self.y+= math.sin(angulo)*self.velocidad
        else:
            self.x=self.xDes
            self.y=self.yDes
    def getPoint(self, f):
        p=  Point()
        p.x = self.x*f
        p.y = self.y*f
        return p
class screenShot:
    def __init__(self, aJugadores,aContrario, bol,t,m,fact,tactica):
        self.misJugadores= list()
        self.contrario=list()
        self.bola= bol
        self.tiempo= t
        self.marcador=m
        self.general=  General()
        self.factor= fact
        self.num_players=11
        self.tactica=tactica

        for i in range(self.num_players):
             self.misJugadores.append(aJugadores[i])
             self.contrario.append(aContrario[i])

    def getXInicial(self,jug):
        return int(self.tactica.getXInicial(jug))
    def getYInicial(self,jug):
        return int(self.tactica.getYInicial(jug))
    def getTiempo(self):
        return self.tiempo
    def getMiFactor(self):
        return self.factor
    def getFactorContrario(self):
        return self.factor * -1
    def getMarcador(self):
        return self.marcador
    def getMisJugadores(self):
        return self.misJugadores
    def getContrario(self):
        return self.contrario
    def getBola(self):
        return self.bola
    def estaEnVector(self, vector, num):
        if vector==[]:
            return False
        for n in range(vector.__len__()):
            if vector[n]==num:
                return True
        return False
    def getInfoMasCercano(self,lista, punto, excluir, soloDelante):
        dev=[0,0]
        dev[0]=-1
        dev[1]=999
        nDis=0
        for n in range(lista.__len__()):
            if self.estaEnVector(excluir, n)==False:
                if soloDelante==False or lista[n].x>punto.x:
                    nDis=self.general.getDistancia(lista[n].x, lista[n].y, punto.x, punto.y)
                    if nDis<dev[1]:
                        dev[0]=n
                        dev[1]=nDis
        return dev
    def estoyMasCercaDeBola(self):
        dev1=self.getInfoMasCercano(self.misJugadores, self.bola, [], False)
        dev2=self.getInfoMasCercano(self.contrario, self.bola, [], False)
        return dev1[1]<dev2[1]
    def getMasCercanoDeBola(self):
        dev1=self.getInfoMasCercano(self.misJugadores, self.bola, [], False)
        return dev1[0]
    def getSiguienteJugador(self,nJugador):
        nExc=[nJugador]
        dev1= self.getInfoMasCercano(self.misJugadores,self.misJugadores[nJugador],nExc, True)
        return dev1[0]
    def getContrarioMasCerca(self, nJugador):
        dev1=self.getInfoMasCercano(self.contrario,self.misJugadores[nJugador],[],False)
        return dev1[0]
    def getContrarioMasCercaPunto(self, x, y):
        p=Point()
        p.x=x
        p.y=y
        dev1=self.getInfoMasCercano(self.contrario,p,[],False)
        return dev1[0]
    def getMiJugadorMasCerca(self,x,y):
        p= Point()
        p.x=x
        p.y=y
        dev1=self.getInfoMasCercano(self.misJugadores,p,[],False)
        return dev1[0]
class Bola:
    def __init__(self):
        self.x=0
        self.y=0
        self.velocidad=0
        self.antiBloqueo=[9,9,9,9,9]
        self.reserSistemaAntibloqueo()
        self.general= General()
        self.angulo=0.00000
    def getX(self):
        return self.x
    def getY(self):
        return self.y
    def getVelocidad(self):
        return self.velocidad
    def setXY(self, nx,ny):
        self.x=nx
        self.y=ny
        self.velocidad=0
    def getPoint(self, f):
        p = Point()
        p.x = self.x*f
        p.y = self.y*f
        return p
    def esGol(self):
        if self.x>=width*160/320  and self.y>-20 and self.y<20:
            return 0
        if self.x<=-width*160/320 and self.y>-20 and self.y<20:
            return 1
        return -1
    def golpearBola(self, equipo, jugador, xDes, yDes, nFuerza):
        if self.velocidad<16:
            var=random.randint(0,100)
            ramd = float(float(var)/100)
            self.angulo=self.general.getAngulo(self.x,self.y,xDes,yDes) + ramd * self.general.PI/5 - self.general.PI/10
            self.velocidad=int(nFuerza)*3
            for n in range(4):
                self.antiBloqueo[n]=self.antiBloqueo[n+1]
            self.antiBloqueo[4]=jugador*10+equipo
            bBloqueo=False
            if self.antiBloqueo[0]==self.antiBloqueo[2] and self.antiBloqueo[0]==self.antiBloqueo[4] and self.antiBloqueo[1]==self.antiBloqueo[3]:
                bBloqueo=True
            if self.antiBloqueo[0]==self.antiBloqueo[1] and self.antiBloqueo[0]==self.antiBloqueo[4] and self.antiBloqueo[2]==self.antiBloqueo[3]:
                bBloqueo=True
            if self.antiBloqueo[0]==self.antiBloqueo[3] and self.antiBloqueo[1]==self.antiBloqueo[4]:
                bBloqueo=True
            nContarTipoEquipo=0
            for n in range(5):
                nContarTipoEquipo += self.antiBloqueo[n]%10
            if nContarTipoEquipo==0 or nContarTipoEquipo==5:
                bBloqueo=False
            if bBloqueo==True:
                self.velocidad=17
                self.angulo=float(random.randint(0,100))/100*self.general.PI*2
    def mover(self):
        for n in range(int(self.velocidad)):
            if self.esGol()!=-1:
                break
            self.x+=math.cos(self.angulo)
            self.y+=math.sin(self.angulo)
            if self.esGol()==-1:
                if self.x>width*160/320:
                    self.x=width-self.x
                    self.angulo=self.general.corregirAngulo(self.general.PI-self.angulo)
                if self.x<-width*160/320:
                    self.x=-width-self.x
                    self.angulo=self.general.corregirAngulo(self.general.PI-self.angulo)
                if self.y>heigth*120/240:
                    self.y=heigth-self.y
                    self.angulo=self.general.corregirAngulo(-self.angulo)
                if self.y<-heigth*120/240:
                    self.y=-heigth-self.y
                    self.angulo=self.general.corregirAngulo(-self.angulo)
        if self.velocidad>0:
             self.velocidad-=1

    def reserSistemaAntibloqueo(self):
        for n in range(5):
            self.antiBloqueo[n]=9

class Equipo:
    def __init__(self, t):
        self.jugadores=list()
        for n in range(num_players):
            j= Jugador(n+1, t.getFuerza(n)+3)
            self.jugadores.append(j)
    def getJugador(self, n):
        return self.jugadores[n]
    def mover(self):
        for n in range(num_players):
            self.jugadores[n].mover()
    def getPoints(self,f):
        p= list()
        for n in range(num_players):
            p.append(self.jugadores[n].getPoint(f))
        return p
    def setPosJug(self,jug,x,y):
        self.jugadores[jug].setXY(x,y)

from comandos import *

class tactica:
    def __init__(self,ci,init,f,codes):
        self.inicial=init
        self.force= f
        self.ci= ci
        self.code= codes
        self.ast= miparser.parse(self.code,self.ci)
        os.remove(str(ci)+"(error).txt")
        self.executer=executor()
        self.memory=list([0,0,0,0,0])

    def getXInicial(self,jug):
        return int(self.inicial[jug*2])-240
    def getYInicial(self,jug):
        return int(self.inicial[jug*2+1])-180
    def getFuerza(self,jug):
        return int(self.force[jug])
    def getCode(self):
        return self.code
    def getCI(self):
        return self.ci
    def getComandos(self,sj):
        del self.executer
        self.executer= executor()
        self.executer.execute(self.ast,sj,self.force)
        return self.executer.getOrdenes()
    def getExecuter(self):
        return self.executer
    def getAST(self):
        return self.ast

class Partido:
    def __init__(self,t1, t2,  t, salva, adesicion,cantJ):   # desicion: si es 1 permite empate si es 0 se alarga el tiempo de 20 segundos el primero que marca gana
        self.tiempo=int(t*60000)
        self.equipos=list()
        self.tacticas=list()
        self.general= General()
        self.tacticas.append(t1)
        self.tacticas.append(t2)
        self.equipos.append(Equipo(t1))
        self.equipos.append(Equipo(t2))
        self.bola= Bola()
        self.nGanador=-1
        self.posicionBolaDesempate=0
        self.nUltimaJugadaHuboGol=-1

        self.nPuntos=list()
        self.nPuntos.append(0)
        self.nPuntos.append(0)
        self.posicionarJugadoresSaqueCentro()
        self.cont=0
        self.tenerBalon =Point(-1,-1)
        self.cantPlayers= cantJ
        self.pathSalva= salva
        self.desicion=adesicion
        self.centro= Point(0,0)
        self.tic=0
        self.ultimaLinea=list()
        self.comandos0=list()
        self.comandos1=list()
        self.totalTics= self.tiempo/100
        self.ticActual= 0
        self.lastTouch=[-1,-1]

        for i in range(46):
            self.ultimaLinea.append(0)
    def getTiempo(self):
        return self.tiempo
    def getPosicionDesempate(self):
        return self.posicionBolaDesempate
    def getTiempoRestante(self):
        r= Tiempo()
        r.min = self.tiempo / 60000
        r.seg = (self.tiempo % 60000)/1000
        return r
    def getGanador(self):
        if self.getPuntos(0)>self.getPuntos(1):
            return 0
        if self.getPuntos(0)<self.getPuntos(1):
            return 1
        if self.getPuntos(0)==self.getPuntos(1):
            if self.posicionBolaDesempate<0:
                return 1
            return 0
        return -1
    def DisminuirTiempo(self,c):
        self.tiempo-=c
        return self.tiempo > 0
    def getQuienTieneBalon(self):
        return self.tenerBalon
    def getEquipo(self,n):
        return self.equipos[n]
    def getTactica(self, n):
        return self.tacticas[n]
    def getBola(self):
        return self.bola
    def getFactor(self,nEquipo):
        if nEquipo==0:
             return 1
        else:
            return -1
    def ConvertirXACoordenaEstandar(self,xo):
        return width/2+ int(xo)
    def ConvertirYACoordenaEstandar(self,yo):
        return heigth/2+ int(yo)
    def SalvarMomento2(self, hayGol,deb,place):
        f= file(self.pathSalva,'a+')
        linea=""
        bx= self.ConvertirXACoordenaEstandar(self.bola.getX())
        by= self.ConvertirYACoordenaEstandar(self.bola.getY())
        linea= str(bx)+","+str(by)
        for i in range(0,self.cantPlayers):
            px=self.ConvertirXACoordenaEstandar( self.equipos[0].getJugador(i).getX())
            py= self.ConvertirYACoordenaEstandar(self.equipos[0].getJugador(i).getY())
            linea=linea + ","+str(px)+","+str(py)
        for i in range(0,self.cantPlayers):
            px=self.ConvertirXACoordenaEstandar( self.equipos[1].getJugador(i).getX())
            py= self.ConvertirYACoordenaEstandar(self.equipos[1].getJugador(i).getY())
            linea=linea + ","+str(px)+","+str(py)
        f.write(linea+";"+'\n')
        if hayGol!=-1:
            f.write("GOL ("+str(hayGol)+","+str(self.nPuntos[0])+","+str(self.nPuntos[1])+","+str(self.lastTouch[hayGol])+");"+'\n')
        f.close()

        if deb == 1:
            d= file(place,'a+')
            if hayGol!=-1:
                d.write("GOL ("+str(self.nPuntos[0])+","+str(self.nPuntos[1])+","+str(self.lastTouch[hayGol])+");"+'\n')
            else:
                comandos=""
                for m in self.comandos0:
                    comandos+=m.getCadena()+":"
                d.write(comandos+';'+"\n")
                tabla=""
                tt= list(self.tacticas[0].getExecuter().getTablaSym())
                for t in tt:
                    v= self.tacticas[0].getExecuter().getTablaSym()[t]
                    tabla+=" "+str(t)+":"+str(v)+"#"
                d.write(tabla+';'+'\n')
                err= str(self.tacticas[0].getExecuter().getErrores())
                if err=="":
                    err="e:"
                d.write(err+';'+'\n')
                status=str(self.ticActual)+","+str(self.totalTics)+","
                status+= str( self.ConvertirXACoordenaEstandar(self.getBola().x))+","+str(self.ConvertirYACoordenaEstandar(self.getBola().y))+","
                sj0=self.getSituacionJugadores(0)
                sj1=self.getSituacionJugadores(1)
                status+=str(sj0.estoyMasCercaDeBola())+","+str(sj0.getMasCercanoDeBola()+1)+","+str(sj1.getMasCercanoDeBola()+1)+","
                status+=str(int(self.posicionBolaDesempate))+";"
                d.write(status+'\n')
            d.write("%%"+'\n')
            d.close()

    def posicionarJugadoresSaqueCentro(self):
        for n in range(2):
            f=self.getFactor(n)
            for m in range(11):
                x=(self.general.control(self.tacticas[n].getXInicial(m),-int(width/2) ,int(width/2))-int(width/2))/2
                y=self.tacticas[n].getYInicial(m)
                if self.general.getDistancia(0,0,x,y)<int(heigth/2):
                    x-=10
                self.equipos[n].setPosJug(m,x*f,y*f)


    def mover(self, deb,place):
        self.nUltimaJugadaHuboGol=-1
        self.DisminuirTiempo(100)
        self.ejecutarTacticas()
        for n in range(2):
            self.equipos[n].mover()
        

        self.cont+=1
        self.nUltimaJugadaHuboGol=self.bola.esGol()
        if self.nUltimaJugadaHuboGol!=-1:
            self.nPuntos[self.nUltimaJugadaHuboGol]+=1
            self.posicionarJugadoresSaqueCentro()
            self.bola.setXY(0,0)
            self.bola.reserSistemaAntibloqueo()
            self.equipos[1-self.nUltimaJugadaHuboGol].getJugador(10).setXY(0,0)
        self.posicionBolaDesempate=self.posicionBolaDesempate+self.bola.getX()
        self.SalvarMomento2(self.nUltimaJugadaHuboGol,deb,place)
        self.bola.mover()
        self.ticActual+=1

    def getUltimaJugadaHuboGol(self):
        return self.nUltimaJugadaHuboGol
    def getSituacionJugadores(self, nEquipo):
        f=self.getFactor(nEquipo)
        misj = self.equipos[nEquipo].getPoints(f)
        contj = self.equipos[1-nEquipo].getPoints(f)
        m= Marcador()
        m.GolesMios = self.getPuntos(nEquipo)
        m.GolesContrarios = self.getPuntos(1-nEquipo)
        situacion = screenShot(misj,contj,self.bola.getPoint(f),self.tiempo/1000,m,f,self.tacticas[nEquipo])
        return situacion

    def ejecutarTacticas(self):
        self.comandos0=  self.tacticas[0].getComandos(self.getSituacionJugadores(0))
        self.comandos1=  self.tacticas[1].getComandos(self.getSituacionJugadores(1))

        alJugadores= list()
        for nEquipo in range(2):
            for nJugador in range(self.cantPlayers):
                alJugadores.append(nJugador*10 + nEquipo)
        nEquipo=0
        nJugador=0
        nValor=0
        nPosAleatorio=0
        #random.seed()
        while alJugadores.__len__()>0:
            nPosAleatorio= random.randint(0,alJugadores.__len__()-1)

            nValor= alJugadores[nPosAleatorio]
            alJugadores.remove(nValor)
            nJugador = int(nValor/10)
            nEquipo = int(nValor%10)
            toExecute=None

            if nEquipo==0:
                for o in self.comandos0:
                    if nJugador == o.getJugador():
                        toExecute=o
                        toExecute.setEquipo(nEquipo)
                        self.ejecutar(toExecute,nEquipo)
            else:
                if nEquipo==1:
                    for d in self.comandos1:
                        if nJugador==d.getJugador():
                            toExecute=d
                            toExecute.setEquipo(nEquipo)
                            self.ejecutar(toExecute,nEquipo)


            if toExecute == None:
                toExecute= GOTO(nJugador,self.tacticas[nEquipo].getXInicial(nJugador),self.tacticas[nEquipo].getYInicial(nJugador))
                self.ejecutar(toExecute,nEquipo)


    def getPuntos(self, n):
        return self.nPuntos[n]
    def ejecutar(self,c,nEquipo):
        self.cont+=1
        if isinstance(c,GOTO):
            f=self.getFactor(nEquipo)
            self.getEquipo(nEquipo).getJugador(c.getJugador()).irA(c.x*f,c.y*f)
        if isinstance(c,HIT):
            e=self.getEquipo(nEquipo)
            j1=e.getJugador(c.getJugador())
            x1=float(j1.getX())
            y1=float(j1.getY())
            f=self.getFactor(nEquipo)
            nGrado=100
            nDis=float(self.general.getDistancia(x1*f,y1*f,c.getX(),c.getY()))
            if nDis<width*50/320:
                 nGrado=int(nDis*2)
            self.golpearBola(c.getX()*f, c.getY()*f, nGrado,nEquipo,c)
        if  isinstance(c,PASS):
             e=self.getEquipo(nEquipo)
             j2=e.getJugador(c.jugadorDestino)
             f=self.getFactor(nEquipo)
             com=HIT(c.getJugador(), j2.getX()*f, j2.getY()*f)
             com.setEquipo(nEquipo)
             self.ejecutar(com,nEquipo)
        if isinstance(c,SHOOT):
            f=self.getFactor(nEquipo)
            com=HIT(c.getJugador(),width*190/320 , c.desvio)
            com.setEquipo(nEquipo)
            self.golpearBola(width*190/320*f, c.desvio*f, 100,nEquipo,com)
    def golpearBola(self, xAbs,  yAbs, nGrado, nEquipo, c):
        if self.puedeGolpearBola(nEquipo,c)==True:
            self.tenerBalon.x = nEquipo
            self.tenerBalon.y = c.getJugador()
            fuerza=self.getEquipo(nEquipo).getJugador(c.getJugador()).getFuerza()
            self.getBola().golpearBola(nEquipo, c.getJugador(), xAbs, yAbs, int((fuerza*nGrado)/100))
    def puedeGolpearBola(self,nEquipo, c):
        j=self.getEquipo(nEquipo).getJugador(c.getJugador())
        b=self.getBola()
        dist = self.general.getDistancia(j.getX(),j.getY(),b.getX(),b.getY())

        if dist<width*10/320:
            self.lastTouch[nEquipo]=c.getJugador()
            return True
        return False


















