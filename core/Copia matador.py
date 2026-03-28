# -*- coding: utf-8 -*-
from conexion import *
import time
import os
from coreMaster import *
from machine import *
from conexion import *
from update import *
import sys
import miparser
import hashlib
import zipfile

idLigaMaster= 61

hilos=2
cable= conexion()
update= update()
planes= []
query= "select idTeam1,idTeam2,idLiga,desicion,fecha,idPlan,etapa from plan  where idLiga='"+str(idLigaMaster)+"' order by fecha ASC limit "+str(hilos)
planes= cable.run_query(query)


t=1.5
while planes!=():
    juegos=[]
    pos=0
    cant= planes.__len__()
    ids=[]
    done=[]

    for i in range(cant):
        query= "select forces,initial,dirCode from tactic where idTeam='"+str(planes[i][0])+"' limit 1"
        stactic1= cable.run_query(query)
        query= "select forces,initial,dirCode from tactic where idTeam='"+str(planes[i][1])+"' limit 1"
        stactic2= cable.run_query(query)


        dir1= os.path.abspath("../codes/"+str(stactic1[0][2]))
        dir2= os.path.abspath("../codes/"+str(stactic2[0][2]))

        archivo= open(dir1, "r")
        code1 = archivo.read()
        archivo.close()
        try:
            archivo2= file(dir2, "r")
        except OSError as err:
            raise
        except ValueError:
            raise
        except:
            raise
        code2 = archivo2.read()
        archivo2.close()

        team1=planes[i][0]
        team2=planes[i][0]
        tactica1= tactica(team1,stactic1[0][1].split(','),stactic1[0][0].split(','),code1)
        tactica2= tactica(team2,stactic2[0][1].split(','),stactic2[0][0].split(','),code2)

        utf= time.time()
        idGame= hashlib.md5(str(team1)+str(team2)+str(utf)+str(planes[i][5])).hexdigest()
        ids.append(idGame)
        place=os.path.abspath("../games/"+idGame+".fer")
        juego = Partido(tactica1,tactica2,float(t),place,1,11)
        juegos.append(juego)
        done.append(False)

    inicio=time.time()
    finito=cant

    while finito>0:
        for i in range(cant):
            if juegos[i].getTiempo()>0:
                juegos[i].mover(0,"")
            else:
                if done[i]== False:
                    gol1= juegos[i].getPuntos(0)
                    gol2= juegos[i].getPuntos(1)

                    distie=juegos[i].getPosicionDesempate()
                    winner= planes[i][0]
                    loser= planes[i][1]
                    golW=gol1
                    golL=gol2

                    if juegos[i].getGanador()==1:
                        winner=planes[i][1]
                        loser= planes[i][0]
                        golW=gol2
                        golL=gol1

                    utf= time.time()
                    print planes[i]
                    print planes[i][6]
                    query = "insert into game (idGame,idTeam1,idTeam2,gol1,gol2,distie,winner,done,fechaDone,dirGame, fechaInicio,idLiga,etapa) values('"+str(ids[i])+"','"+str(planes[i][0])+"','"+str(planes[i][1])+"',"+str(gol1)+", "+str(gol2)+", "+str(distie)+", '"+str(winner)+"',1,"+str(utf)+",'"+str(ids[i])+".zip','"+str(inicio)+"','"+str(planes[i][2])+"','"+str(planes[i][6])+"')"

                    cable.run_query(query)

                    msg="Su equipo #"+str(winner)+"# ganó partido de la liga *"+str(idLigaMaster)+"* contra #"+str(loser)+"#. Ver partido %"+str(ids[i])+",l%"
                    update.notificar(update.getTeam(winner,cable)[1],msg,2,cable)
                    msg="Su equipo #"+str(loser)+"# perdió partido de la liga *"+str(idLigaMaster)+"* contra #"+str(winner)+"#. Ver partido %"+str(ids[i])+",l%"
                    update.notificar(update.getTeam(loser,cable)[1],msg,3,cable)


                    tarjeta= file("../games/"+ids[i]+".txt","w")
                    tarjeta.write(str(planes[i][0])+","+str(planes[i][1])+","+str(gol1)+","+str(gol2)+","+str(distie)+","+ids[i]+","+str(time.time())+"\n")
                    tarjeta.write("id1,id2,gol1,gol2,distie,idGame,tiempo")
                    tarjeta.close()
                    tarjeta="../games/"+ids[i]+".txt"

                    rar=os.path.abspath("../games/"+ids[i]+".zip")
                    fer="../games/"+ids[i]+".fer"
                    jungle_zip = zipfile.ZipFile(rar, 'w')
                    jungle_zip.write(fer, compress_type=zipfile.ZIP_DEFLATED)
                    jungle_zip.write(tarjeta, compress_type=zipfile.ZIP_DEFLATED)
                    jungle_zip.close()
                    os.remove(fer);
                    os.remove(tarjeta);

                    update.actualizarElos(planes[i][0],planes[i][1],winner,cable,32)
                    query= "delete from plan where idPlan='"+str(planes[i][5])+"' limit 1"
                    cable.run_query(query)
                    finito-=1
                    done[i]=True
                    update.actualizarTeam(planes[i][0],gol1,gol2,t*60,winner,distie,cable)
                    update.actualizarTeam(planes[i][1],gol2,gol1,t*60,winner,distie*-1,cable)

                    update.actualizarLiga(planes[i][2],planes[i][0],planes[i][1],gol1,gol2,distie,winner,t*60,ids[i], cable)

                    update.actualizarChallenge(update.getUserFromTeam(winner,cable),winner,loser,golW,golL,True,cable)

    query= "select idTeam1,idTeam2,idLiga,desicion,fecha,idPlan,etapa from plan where idLiga='"+str(idLigaMaster)+"' order by fecha ASC limit "+str(hilos)
    planes= cable.run_query(query)




