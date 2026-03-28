# -*- coding: utf-8 -*-
from coreMaster import *
from machine import *
from conexion import *
import sys
import miparser
import hashlib
import time

inicio=time.time()

idPlan=86
cable= conexion()

query= "select idTeam1,idTeam2,idLiga,desicion,fecha from plan where idPlan='"+str(idPlan)+"' limit 1"
plan= cable.run_query(query)

team1=plan[0][0]
team2=plan[0][1]
idLiga= plan[0][2]
desicion= plan[0][3]

cable= conexion()

print plan

query= "select forces,initial,dirCode from tactic where idTeam='"+str(team1)+"' limit 1"
stactic1= cable.run_query(query)
query= "select forces,initial,dirCode from tactic where idTeam='"+str(team2)+"' limit 1"
stactic2= cable.run_query(query)


dir1= os.path.abspath("../codes/"+str(stactic1[0][2]))
dir2= os.path.abspath("../codes/"+str(stactic2[0][2]))

archivo= open(dir1, "r")
code1 = archivo.read()
archivo.close()

archivo2= file(dir2, "r")
code2 = archivo2.read()
archivo2.close()

tactica1= tactica(team1,stactic1[0][1].split(','),stactic1[0][0].split(','),code1)
tactica2= tactica(team2,stactic2[0][1].split(','),stactic2[0][0].split(','),code2)
t=2

utf= time.time()
idGame= hashlib.md5(str(team1)+str(team2)+str(utf)).hexdigest()

place=os.path.abspath("../games/"+idGame+".fer")

print place

juego = Partido(tactica1,tactica2,float(t),place,1,11)

while juego.getTiempo()>0:
    juego.mover(0,"")

gol1= juego.getPuntos(0)
gol2= juego.getPuntos(1)
distie=juego.getPosicionDesempate()
winner= team1

if juego.getGanador()==1:
    winner=team2

utf= time.time()


query = "insert into game (idGame,idTeam1,idTeam2,gol1,gol2,distie,winner,done,fechaDone,dirGame, fechaInicio,idLiga) values('"+str(idGame)+"','"+str(team1)+"','"+str(team2)+"',"+str(gol1)+", "+str(gol2)+", "+str(distie)+", '"+str(winner)+"',1,"+str(utf)+",'"+str(idGame)+".fer','"+str(inicio)+"','"+str(idLiga)+"')"
cable.run_query(query)

query= "delete from plan where idPlan='"+str(idPlan)+"' limit 1"
cable.run_query(query)













