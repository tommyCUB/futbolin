# -*- coding: utf-8 -*-
# -*- coding: utf-8 -*-
from coreMaster import *
from machine import *
from conexion import *
from update import *
import sys
import miparser
import time
import os
import zipfile

update= update()

ids= sys.argv[1]
deb= 1
cable= conexion()

query= "select idTeam1,idTeam2 from friendly where idFriendly='"+str(ids)+"' limit 1"
friend= cable.run_query(query)
ids1= friend[0][0]
ids2=friend[0][1]

query= "select forces,initial,dirCode from tactic where idTeam='"+str(ids1)+"' limit 1"
stactic1= cable.run_query(query)
query= "select forces,initial,dirCode from tactic where idTeam='"+str(ids2)+"' limit 1"
stactic2= cable.run_query(query)

code1=""
code2=""

dir1= os.path.abspath("./codes/"+str(ids1)+".fer")
dir2= os.path.abspath("./codes/"+str(ids2)+".fer")

archivo= open(dir1, "r")
code1 = archivo.read()
archivo.close()

archivo2= file(dir2, "r")
code2 = archivo2.read()
archivo2.close()

tactica1= tactica(ids1,stactic1[0][1].split(','),stactic1[0][0].split(','),code1)
tactica2= tactica(ids2,stactic2[0][1].split(','),stactic2[0][0].split(','),code2)


t=0.5
place=os.path.abspath("friendly/")
cadena=str(ids1)+"vs"+str(ids2)+"-"+str(int(time.time()))
juego = Partido(tactica1,tactica2,float(t),place+"/"+cadena+".fer",1,11)
place2=""

deb= int(deb)
if deb==1:
    place2=os.path.abspath("debug/")+"/"+cadena+".fer.debug"

while juego.getTiempo()>0:
    juego.mover(int(deb),place2)



gol1= juego.getPuntos(0)
gol2= juego.getPuntos(1)
distie=juego.getPosicionDesempate()
winner= ids1
loser= ids2
golW=gol1
golL=gol2


tarjeta= file("friendly/"+cadena+".txt","w")
tarjeta.write(str(ids1)+","+str(ids2)+","+str(gol1)+","+str(gol2)+","+str(distie)+","+str(ids)+","+str(time.time())+"\n")
tarjeta.write("id1,id2,gol1,gol2,distie,idGame,tiempo")
tarjeta.close()
tarjeta="friendly/"+cadena+".txt"

rar=os.path.abspath("friendly/"+cadena+".zip")
fer="friendly/"+cadena+".fer"
jungle_zip = zipfile.ZipFile(rar, 'w')
jungle_zip.write(fer, compress_type=zipfile.ZIP_DEFLATED)
jungle_zip.write(tarjeta, compress_type=zipfile.ZIP_DEFLATED)
jungle_zip.close()
os.remove(fer);
os.remove(tarjeta)


rar=os.path.abspath("debug/"+cadena+".zip")
fer="debug/"+cadena+".fer.debug"
jungle_zip = zipfile.ZipFile(rar, 'w')
jungle_zip.write(fer, compress_type=zipfile.ZIP_DEFLATED)
jungle_zip.close()
os.remove(fer)


if juego.getGanador()==1:
    winner=ids2
    loser= ids1
    golW=gol2
    golL=gol1

query = "update friendly set executed='"+str(int(time.time()))+"', dirGame='"+str(cadena)+".zip', gol1='"+str(gol1)+"', gol2='"+str(gol2)+"', distie='"+str(distie)+"', winner='"+winner+"', done=1 where idFriendly='"+str(ids)+"' limit 1"
cable.run_query(query)

update.actualizarElos(ids1,ids2,winner,cable,8)
msgw="Su equipo #"+str(winner)+"# , ganó un partido amistoso "+str(golW)+"-"+str(golL)+" contra #"+str(loser)+"#. Ver partido %"+str(ids)+",f%"
update.notificar(update.getUserFromTeam(winner,cable),msgw,2,cable)
msgl="Su equipo #"+str(loser)+"# , perdió un partido amistoso "+str(golL)+"-"+str(golW)+" contra #"+str(winner)+"#. Ver partido %"+str(ids)+",f%"
update.notificar(update.getUserFromTeam(loser,cable),msgl,3,cable)

update.creditar(7,update.getUserFromTeam(winner,cable),msgw,cable)
update.actualizarChallenge(update.getUserFromTeam(winner,cable),winner,loser,golW,golL,False,cable)



