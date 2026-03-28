# -*- coding: utf-8 -*-

import time

class update:
    def __init__(self):
        a=0
        self.lugares=('1er','2do','3er','4to','5to','6to','7mo','8vo','9no','10mo','11mo','12mo','13mo','14mo','15mo','16mo','17mo','18mo','19mo','20mo','21ro','22do','23ero','24to','25to','26to','27mo','28vo','29no','30mo','31ero','32do')


    def actualizarElos(self,id1,id2,winner, cable,k):
        query="select elo from team where idTeam='"+str(id1)+"' limit 1"
        team1= cable.run_query(query)
        query="select elo from team where idTeam='"+str(id2)+"' limit 1"
        team2= cable.run_query(query)

        e1 = 1/(1+ pow(10,(team2[0][0]-team1[0][0])/400))
        e2 = 1/(1+ pow(10,(team1[0][0]-team2[0][0])/400))
        s1=1
        s2=0
        if winner==id2 :
            s1=0
            s2=1

        ne1= float(team1[0][0]) + k*(s1 - e1)
        ne2= float(team2[0][0]) + k*(s2 - e2)

        query= "update team set elo='"+str(ne1)+"' where idTeam='"+str(id1)+"' limit 1"
        cable.run_query(query)

        query= "update team set elo='"+str(ne2)+"' where idTeam='"+str(id2)+"' limit 1"
        cable.run_query(query)

    def actualizarChallenge(self,idUser,idTeam1, idTeam2,gol1,gol2,deliga,cable):
        query="select idUser,cantAmistosos,cantdeliga,premios,checkit,cantCheck from challdone where idUser='"+str(idUser)+"' limit 1"

        datos= cable.run_query(query)
        check=list()
        if datos[0][4]!= "":
            check=datos[0][4].split(";")

        cantAmistosos=datos[0][1]
        cantCheck=datos[0][5]
        cantdeLiga=datos[0][2]
        premio=datos[0][3]
        cdn=""
        if deliga==False:
            cdn+="CH005;"
            cantAmistosos+=1
            if gol1-gol2==2:
                cdn+="CH016;"
            if gol1-gol2==3:
                cdn+="CH018;"
            if gol1-gol2==5:
                cdn+="CH020;"
            if gol1==gol2:
                cdn+="CH022;"
            if cantAmistosos==5:
                cdn+="CH024;"
            if cantAmistosos==10:
                cdn+="CH025;"
            if cantAmistosos==15:
                cdn+="CH026;"
            if cantAmistosos==20:
                cdn+="CH027;"
            if gol2==0:
                cdn+="CH035;"
            if self.rankingTeam(idTeam2,cable)<=20:
                cdn+="CH037;"
            if self.rankingTeam(idTeam2,cable)<=10:
                cdn+="CH039;"
            if self.rankingTeam(idTeam2,cable)<=5:
                cdn+="CH041;"
            if self.rankingTeam(idTeam2,cable)==1:
                cdn+="CH043;"
            if cantAmistosos==50:
                cdn+="CH065;"
            if cantAmistosos==100:
                cdn+="CH067;"
        if deliga==True:
            cdn+="CH006;"
            cantdeLiga+=1
            if gol1-gol2==2:
                cdn+="CH017;"
            if gol1-gol2==3:
                cdn+="CH019;"
            if gol1-gol2==5:
                cdn+="CH021;"
            if gol1==gol2:
                cdn+="CH023;"
            if cantdeLiga==5:
                cdn+="CH028;"
            if cantdeLiga==10:
                cdn+="CH029;"
            if cantdeLiga==15:
                cdn+="CH030;"
            if cantdeLiga==20:
                cdn+="CH031;"
            if gol2==0:
                cdn+="CH036;"
            if self.rankingTeam(idTeam2,cable)<=20:
                cdn+="CH038;"
            if self.rankingTeam(idTeam2,cable)<=10:
                cdn+="CH040;"
            if self.rankingTeam(idTeam2,cable)<=5:
                cdn+="CH042;"
            if self.rankingTeam(idTeam2,cable)==1:
                cdn+="CH044;"
            if cantdeLiga==50:
                cdn+="CH068;"
            if cantdeLiga==100:
                cdn+="CH069;"

        if self.rankingTeam(idTeam1,cable)<=20:
            cdn+="CH070;"
        if self.rankingTeam(idTeam1,cable)<=10:
            cdn+="CH071;"
        if self.rankingTeam(idTeam1,cable)==1:
            cdn+="CH072;"



        cdn=cdn.split(";")

        for i in cdn:
            if i!="":
                if not i in check:
                    check.append(i)
                    cantCheck+=1
                    query= "select premio,asunto from challenge where codigo='"+str(i)+"' limit 1"

                    datos= cable.run_query(query)
                    premio+=datos[0][0]
                    self.creditar(datos[0][0],idUser,"FELICIDADES!!!. Has cumplido un reto, ( "+str(datos[0][1])+" )",cable)

                    if cantCheck==15 and not "CH073" in cdn:
                        cdn.append("CH073")
                    if cantCheck==25 and not "CH074" in cdn:
                        cdn.append("CH074")
                    if cantCheck==50 and not "CH075" in cdn:
                        cdn.append("CH075")
                    if cantCheck==70 and not "CH076" in cdn:
                        cdn.append("CH076")

        cdn= ""
        for i in check:
            if i!="":
                cdn+=i+";"

        query= "update challdone set cantAmistosos='"+str(cantAmistosos)+"',cantdeLiga='"+str(cantdeLiga)+"',premios='"+str(premio)+"',checkit='"+cdn+"', cantCheck='"+str(cantCheck)+"' where idUser='"+str(idUser)+"' limit 1"
        cable.run_query(query)

    def actualizarChallengeFinalLiga(self,idUser,tipo,cantTeams,pos,idLiga,cable):
        query="select idUser,premios,checkit,cantCheck,cantLiga,cantLigaTCT,cantLigaGrupo from challdone where idUser='"+str(idUser)+"' limit 1"
        datos= cable.run_query(query)
        cantLiga=datos[0][4]
        cantLigaTCT=datos[0][5]
        cantLigaGrupo=datos[0][6]
        check= datos[0][2].split(";")
        cantCheck=datos[0][3]
        premio=datos[0][1]
        cdn=""
        query="select idOwner from liga where idLiga='"+str(idLiga)+"' limit 1"
        dat= cable.run_query(query)

        if pos>=3:
            if tipo==1:
                cdn+="CH014;"
            if tipo==2:
                cdn+="CH015;"
            if idUser==dat[0][0]:
                cdn+="CH034;"


        if pos==1:
            if idUser==dat[0][0]:
                cdn+="CH033;"
            if tipo==1:
                cantLigaGrupo+=1
                if cantTeams==8:
                    cdn+="CH008;"
                if cantTeams==16:
                    cdn+="CH009;"
                if cantTeams==32:
                    cdn+="CH010;"
            if tipo==2:
                cantLigaTCT+=1
                if cantTeams==4:
                    cdn+="CH011;"
                if cantTeams==8:
                    cdn+="CH012;"
                if cantTeams==10:
                    cdn+="CH013;"
            if cantLigaGrupo==5:
                cdn+="CH056;"
            if cantLigaTCT==5:
                cdn+="CH057;"
            if cantLigaGrupo==10:
                cdn+="CH058;"
            if cantLigaTCT==10:
                cdn+="CH059;"
            if cantLigaGrupo==15:
                cdn+="CH045;"
            if cantLigaTCT==15:
                cdn+="CH047;"
            if cantLigaGrupo==20:
                cdn+="CH046;"
            if cantLigaTCT==20:
                cdn+="CH048;"

        cantLiga=cantLigaTCT+cantLigaGrupo

        if cantLiga==50:
            cdn+="CH049;"
        if cantLiga==100:
            cdn+="CH050;"

        cdn=cdn.split(";")

        for i in cdn:
            if i!="":
                if not i in check:
                    check.append(i)
                    cantCheck+=1
                    query= "select premio,asunto from challenge where codigo='"+str(i)+"' limit 1"
                    datos= cable.run_query(query)
                    premio+=datos[0][0]
                    self.creditar(datos[0][0],idUser,"FELICIDADES!!!. Has cumplido un reto, ("+str(datos[0][1])+")",cable)
                    if cantCheck==15 and not "CH073" in cdn:
                        cdn.append("CH073")
                    if cantCheck==25 and not "CH074" in cdn:
                        cdn.append("CH074")
                    if cantCheck==50 and not "CH075" in cdn:
                        cdn.append("CH075")
                    if cantCheck==70 and not "CH076" in cdn:
                        cdn.append("CH076")
        cdn=""
        for i in check:
            if i!="":
                cdn+=i+";"

        query= "update challdone set cantLiga='"+str(cantLiga)+"',cantLigaTCT='"+str(cantLigaTCT)+"',premios='"+str(premio)+"',checkit='"+cdn+"', cantCheck='"+str(cantCheck)+"' where idUser='"+str(idUser)+"' limit 1"
        cable.run_query(query)


    def creditar(self,valor,idUser,asunto,cable):
        query="select coins from gente where idUser='"+str(idUser)+"' limit 1"
        pers= cable.run_query(query)

        query= "update gente set coins='"+str(pers[0][0]+valor)+"' where idUser='"+str(idUser)+"' limit 1"
        cable.run_query(query)

        self.notificar(idUser,"+ "+str(valor)+"coins. "+asunto,5,cable)

        query= "insert into voucher (idUser,debito,credito, fecha, asunto) values('"+str(idUser)+"','0','"+str(valor)+"','"+str(time.time())+"','"+str(asunto)+"')"
        cable.run_query(query)

        query="select premios,checkit,cantCheck from challdone where idUser='"+str(idUser)+"' limit 1"
        datos= cable.run_query(query)

        cdn=""
        if datos[0][0]>=1000 and datos[0][0]<3000:
            cdn+="CH060"
        if datos[0][0]>=3000 and datos[0][0]<5000:
            cdn+="CH061"
        if datos[0][0]>=5000 and datos[0][0]<15000:
            cdn+="CH062"
        if datos[0][0]>=15000 and datos[0][0]<50000:
            cdn+="CH063"
        if datos[0][0]>=50000:
            cdn+="CH064"

        check= datos[0][1].split(";")

        if not cdn in check:
            if cdn!="":
                query= "select premio,asunto from challenge where codigo='"+str(cdn)+"' limit 1"
                datos2= cable.run_query(query)

                query="update challdone set checkit='"+str(datos[0][1])+cdn+";"+"', cantCheck='"+str(datos[0][2]+1)+"' where idUser='"+str(idUser)+"' limit 1"
                cable.run_query(query)

                self.creditar(datos2[0][0],idUser,"FELICIDADES!!!. Ha cumplido un reto, ("+str(datos2[0][1])+")",cable)
                msg="+ "+str(valor)+" coins: "+str(asunto)
                self.notificar(idUser,msg,4,cable)

    def notificar(self,idUser,msg,tipo,cable):
        query= "insert into news (idUser,msg,fecha,tipo) values('"+str(idUser)+"','"+msg+"','"+str(time.time())+"','"+str(tipo)+"')"
        cable.run_query(query)
    def actualizarTeam(self,idTeam,gol1,gol2,tiempo,winner,distie,cable):
        query="select idTeam,golFavor,golContra,segundo,win,lost,tie from team where idTeam='"+str(idTeam)+"' limit 1"
        team= cable.run_query(query)

        win=team[0][4]
        lost=team[0][5]
        if winner== idTeam:
            win+=1
        else:
            lost+=1
        golFavor= int(team[0][1])+gol1
        golContra= int(team[0][2])+gol2
        seg= int(team[0][3])+tiempo
        tie= int(team[0][6])+distie
        query="update team set golFavor='"+str(golFavor)+"', golContra='"+str(golContra)+"', segundo='"+str(seg)+"',win='"+str(win)+"', lost='"+str(lost)+"', tie='"+str(tie)+"' where idTeam='"+str(idTeam)+"' limit 1"

        cable.run_query(query)

    def getTeam(self,idTeam,cable):
        query="select nombre,iduser from team where idTeam='"+str(idTeam)+"' limit 1"
        datos=cable.run_query(query)
        return datos[0]
    def getLiga(self,idLiga,cable):
        query="select nombre from liga where idLiga='"+str(idLiga)+"' limit 1"
        datos=cable.run_query(query)
        return datos[0]
    def checkEtapa(self,etapa,estan,ligaGrupo):
        ret =""
        if etapa !="":
            etapa= ligaGrupo[0][3].split(';')
            tup=[]
            for team in etapa:
                if team!='':
                    cdn= team.split(',')
                    if not cdn[0] in estan:
                        tup.append([cdn[0],cdn[2],cdn[3],cdn[4]*(-1)])
                        estan.append(cdn[0])
                    if not cdn[1] in estan:
                        tup.append([cdn[1],cdn[2],cdn[3],cdn[4]])
                        estan.append(cdn[1])

            for e in range(tup.__len__()):
                for i in range(tup.__len__()-1):
                    cambiar=False
                    if tup[i][1]<tup[i+1][1]:
                        cambiar=True
                    if tup[i][1]==tup[i+1][1] and tup[i][2]>tup[i+1][2]:
                        cambiar=True
                    if tup[i][1]==tup[i+1][1] and tup[i][2]==tup[i+1][2] and tup[i][3]<tup[i+1][3]:
                        cambiar=True
                    if cambiar == True:
                        temp= tup[i]
                        tup[i]= tup[i+1]
                        tup[i+1]=temp
            for i in tup:
                ret+=str(i[0])+','
        return [ret,estan]

    def checkGrupos(self,grupos,estan,tablaPos):
        grupos= grupos.split(':')
        ret=""
        tup=[]
        for gr in grupos:
            if gr!="":
                teams= gr.split(";")
                for t in teams:
                    if t!="":
                        valores= t.split(',')
                        if not valores[0] in estan:
                            tup.append([valores[0],valores[1],valores[2],valores[3],valores[4],valores[5]])

        for i in range(tup.__len__()):
            for e in range(tup.__len__()-1):
                cambiar=False
                win1=int(tup[e][1])
                win2=int(tup[e+1][1])
                lost1=int(tup[e][2])
                lost2=int(tup[e+1][2])
                golf1=int(tup[e][3])
                golf2=int(tup[e+1][3])
                golc1=int(tup[e][4])
                golc2=int(tup[e+1][4])
                distie1=int(tup[e][5])
                distie2=int(tup[e+1][5])

                if win1<win2:
                    cambiar=True
                if win1== win2 and (golf1-golc1)<(golf2-golc2):
                    cambiar=True
                if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1<golf2:
                    cambiar=True
                if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1==golf2 and distie1<distie2:
                    cambiar=True
                if cambiar == True:
                    temp= tup[e]
                    tup[e]= tup[e+1]
                    tup[e+1]=temp
        for i in tup:
            ret+=str(i[0])+","
            estan.append(i[0])
        return [ret,estan]

    def actualizarLiga(self,idLiga,id1,id2,gol1,gol2,distie,winner,tiempo,idGame,cable):
        query="select idLiga, tipo, teams,cantTeams, nombre, prize from liga where idLiga='"+str(idLiga)+"' limit 1"
        liga= cable.run_query(query)
        query="select count(idPlan) from plan where idLiga='"+str(idLiga)+"' limit 1"
        cantPlan= cable.run_query(query)
        cantPlan= cantPlan[0][0]
        ordenFinal=""

        ##### LIGA TCT
        if liga[0][1]==2:
            query= "select tablaPos from ligatct where idLiga='"+str(idLiga)+"' limit 1"
            ligatct= cable.run_query(query)
            tabla= ligatct[0][0]
            tabla= tabla.split(';')
            tablaPos=[]
            for i in tabla:
                if i!='':
                    valores=[]
                    valores= i.split(',')
                    if '' in valores:
                        valores.remove('')
                    if int(valores[0])== int(id1):

                        if winner==id1:
                            valores[1]=int(valores[1])+1
                        else:
                            valores[2]=int(valores[2])+1
                        valores[3]=int(valores[3])+gol1
                        valores[4]= int(valores[4])+gol2
                        valores[5]=int(valores[5])+int(distie)
                    if int(valores[0]) == int(id2):

                        if int(winner)==int(id2):
                            valores[1]=int(valores[1])+1
                        else:
                            valores[2]=int(valores[2])+1
                        valores[3]=int(valores[3])+gol2
                        valores[4]= int(valores[4])+gol1
                        valores[5]=int(valores[5])+int(distie)*(-1)
                    tablaPos.append(valores)

            for i in range(tablaPos.__len__()):
                for e in range(tablaPos.__len__()-1):
                    cambiar=False
                    win1=int(tablaPos[e][1])
                    win2=int(tablaPos[e+1][1])
                    lost1=int(tablaPos[e][2])
                    lost2=int(tablaPos[e+1][1])
                    golf1=int(tablaPos[e][3])
                    golf2=int(tablaPos[e+1][3])
                    golc1=int(tablaPos[e][4])
                    golc2=int(tablaPos[e+1][4])
                    distie1=int(tablaPos[e][5])
                    distie2=int(tablaPos[e+1][5])

                    if win1<win2:
                        cambiar=True
                    if win1== win2 and (golf1-golc1)<(golf2-golc2):
                        cambiar=True
                    if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1<golf2:
                        cambiar=True
                    if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1==golf2 and distie1<distie2:
                        cambiar=True

                    if cambiar == True:
                        temp= tablaPos[e+1]
                        tablaPos[e+1]=tablaPos[e]
                        tablaPos[e]=temp

            cdn=""
            for i in range(tablaPos.__len__()):
                d=""
                for e in range(tablaPos[i].__len__()):
                    d+=str(tablaPos[i][e])
                    if e!= tablaPos[i].__len__()-1:
                         d+=','
                cdn+= d+";"
                ordenFinal+=tablaPos[i][0]+','

            query="update ligatct set tablaPos='"+str(cdn)+"' where idLiga='"+str(idLiga)+"' limit 1"
            cable.run_query(query)

        #### LIGA GRUPOS
        if liga[0][1]==1:
            query="select grupos,octavos,cuartos, semi, final, etapa from ligagroup where idLiga='"+str(idLiga)+"' limit 1"
            ligaGrupo= cable.run_query(query)
            etapa= ligaGrupo[0][5]

            if etapa==1:
                grupos= ligaGrupo[0][0]
                grupos= grupos.split(":")
                gr=0
                grupoAct=[]
                for g in grupos:
                    if g!="":
                        teams= g.split(";")
                        grupoAct.append([])
                        for valores in teams:
                            if valores != '':
                                numeros= valores.split(',')
                                if int(numeros[0])==id1:
                                    if winner==id1:
                                       numeros[1]=int(numeros[1])+1
                                    else:
                                        numeros[2]=int(numeros[2])+1
                                    numeros[3]=int(numeros[3])+gol1
                                    numeros[4]=int(numeros[4])+gol2
                                    numeros[5]=int(numeros[5])+int(distie)*-1

                                if int(numeros[0])==id2:
                                    if winner==id2:
                                       numeros[1]=int(numeros[1])+1
                                    else:
                                        numeros[2]=int(numeros[2])+1
                                    numeros[3]=int(numeros[3])+gol2
                                    numeros[4]=int(numeros[4])+gol1
                                    numeros[5]=int(numeros[5])+int(distie)

                                grupoAct[gr].append(numeros)
                        gr+=1

                for m in range(grupoAct.__len__()):
                    for i in range(grupoAct[m].__len__()):
                        for e in range(grupoAct[m].__len__()-1):
                            cambiar=False
                            win1=int(grupoAct[m][e][1])
                            win2=int(grupoAct[m][e+1][1])
                            lost1=int(grupoAct[m][e][2])
                            lost2=int(grupoAct[m][e+1][1])
                            golf1=int(grupoAct[m][e][3])
                            golf2=int(grupoAct[m][e+1][3])
                            golc1=int(grupoAct[m][e][4])
                            golc2=int(grupoAct[m][e+1][4])
                            distie1=int(grupoAct[m][e][5])
                            distie2=int(grupoAct[m][e+1][5])

                            if win1<win2:
                                cambiar=True
                            if win1== win2 and (golf1-golc1)<(golf2-golc2):
                                cambiar=True
                            if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1<golf2:
                                cambiar=True
                            if win1== win2 and (golf1-golc1)==(golf2-golc2) and golf1==golf2 and distie1<distie2:
                                cambiar=True

                            if cambiar==True:
                                temp= grupoAct[m][e]
                                grupoAct[m][e] = grupoAct[m][e+1]
                                grupoAct[m][e+1]= temp
                cdn=""
                for gr in grupoAct:
                    for team in gr:
                        for i in range(team.__len__()):
                            cdn+=str(team[i])
                            if i != team.__len__()-1:
                                cdn+=','
                        if teams!="":
                            cdn+=";"
                    if gr!="" and gr!=[]:
                        cdn+=":"

                query= "update ligagroup set grupos='"+cdn+"' where idLiga='"+str(idLiga)+"' limit 1"
                cable.run_query(query)
            if etapa>1:
                actual=""
                si=""
                if etapa==2:
                    actual=ligaGrupo[0][1]
                    si="octavos"
                if etapa==3:
                    actual=ligaGrupo[0][2]
                    si="cuartos"
                if etapa==4:
                    actual=ligaGrupo[0][3]
                    si="semi"
                if etapa==5:
                    actual=ligaGrupo[0][4]
                    si="final"
                actual= actual.split(';')
                cdn=""

                for game in actual:
                    if game!="":
                        valores= game.split(',')
                        if int(valores[0])== id1 and int(valores[1])==id2:
                            valores[2]=gol1
                            valores[3]=gol2
                            valores[4]=int(distie)
                            valores[5]=winner
                            if valores.__len__()<7:
                                valores.append(str(idGame))
                            else:
                                valores[6]=str(idGame)
                        if int(valores[1])== id1 and int(valores[0])==id2:
                            valores[2]=gol2
                            valores[3]=gol1
                            valores[4]=int(distie*-1)
                            valores[5]=winner
                            if valores.__len__()<7:
                                valores.append(str(idGame))
                            else:
                                valores[6]=str(idGame)
                        for i in valores:
                            if valores!='':
                                cdn=cdn + str(i)+","
                        cdn=cdn+";"
                query= "update ligagroup set "+si+"='"+cdn+"' where idLiga='"+str(idLiga)+"' limit 1"
                cable.run_query(query)

        ####### SE EJECUTARON TODOS LOS PARTIDOS PLANEADOS
        if cantPlan==0:
            if liga[0][1]==2:
                query="update liga set state='2', ordenFinal='"+ordenFinal+"', fechaDone='"+str(time.time())+"' where idLiga='"+str(idLiga)+"' limit 1"
                cable.run_query(query)

                ordenFinal= ordenFinal.split(',')
                pos=1
                for i in ordenFinal:
                    query="select idTeam, team.iduser, gente.coins, team.nombre from team inner join gente where gente.idUser=team.iduser and team.idTeam='"+str(i)+"' limit 1"
                    team = cable.run_query(query)
                    msg=str(self.lugares[pos-1])+" lugar, #"+str(team[0][0])+"#, en liga *"+str(liga[0][0])+"*."
                    self.notificar(team[0][1],msg,6,cable)
                    pos+=1
                    premio = liga[0][5]
                    actual=(premio*0.25)/(liga[0][3]-3)
                    if pos==1:
                        actual= premio*0.45
                    if pos==2:
                        actual= premio*0.20
                    if actual==3:
                        actual = premio*0.1
                    if actual>0:
                        self.creditar(actual,team[0][1],msg,cable)

            if int(liga[0][1])==1:
                query="select grupos,octavos,cuartos, semi, final, etapa from ligagroup where idLiga='"+str(idLiga)+"' limit 1"
                ligaGrupo= cable.run_query(query)

                if int(ligaGrupo[0][5])==1:
                    grupos= ligaGrupo[0][0].split(":")
                    ganadores=[]

                    for gr in grupos:
                        if gr!="":
                            teams= gr.split(';')
                            ganadores.append(teams[0].split(',')[0])
                            ganadores.append(teams[1].split(',')[0])

                    consultas=[]
                    cont=0
                    sig=""
                    etapa=ligaGrupo[0][5]
                    cantPlayer= liga[0][3]

                    if cantPlayer==8:
                        if etapa==4:
                            etapa=5
                        if etapa==1:
                            etapa=4
                    if cantPlayer==16:
                        if etapa==1:
                            etapa=3
                        else:
                            etapa+=1
                    if cantPlayer==32:
                        etapa+=1

                    for i in range(ganadores.__len__()/2):
                        id1= ganadores[i]
                        id2= ganadores[ganadores.__len__()-i-1]
                        sig+=str(id1)+","+str(id2)+",0,0,0,0;"
                        query="insert into plan (idLiga,idTeam1,idTeam2,desicion,fecha,etapa) values('"+str(idLiga)+"','"+str(id1)+"','"+str(id2)+"','1','"+str(time.time())+"','"+str(etapa)+"')"
                        consultas.append(query)


                    if ganadores.__len__()==4:
                        etapa= 4
                        sig= "semi='"+sig+"'"
                    if ganadores.__len__()==8:
                        etapa =3
                        sig= "cuartos='"+sig+"'"
                    if ganadores.__len__()==16:
                        etapa =2
                        sig= "octavos='"+sig+"'"

                    query="update ligagroup set etapa='"+str(etapa)+"', "+sig+" where idLiga='"+str(idLiga)+"' limit 1"
                    cable.run_query(query)

                    for i in consultas:
                        cable.run_query(i)

                if int(ligaGrupo[0][5])>1:
                    query="select grupos,octavos,cuartos, semi, final, etapa from ligagroup where idLiga='"+str(idLiga)+"' limit 1"
                    ligaGrupo= cable.run_query(query)
                    actual=""
                    etapa=ligaGrupo[0][5]
                    if int(ligaGrupo[0][5])==2:
                        actual = ligaGrupo[0][1]
                    if int(ligaGrupo[0][5])==3:
                        actual = ligaGrupo[0][2]
                    if int(ligaGrupo[0][5])==4:
                        actual = ligaGrupo[0][3]
                    if int(ligaGrupo[0][5])==5:
                        actual = ligaGrupo[0][4]

                    actual=actual.split(';')

                    ganadores=[]
                    for game in actual:
                        if game!='':
                            g= game.split(',')
                            ganadores.append(g[5])
                    sig=""
                    consultas=[]
                    cantPlayer= liga[0][3]

                    if cantPlayer==8:
                        if etapa==4:
                            etapa=5
                        if etapa==1:
                            etapa=4
                    if cantPlayer==16:
                        if etapa==1:
                            etapa=3
                        else:
                            etapa+=1
                    if cantPlayer==32:
                        etapa+=1

                    for i in range(ganadores.__len__()/2):
                        id1= ganadores[i*2]
                        id2= ganadores[i*2+1]
                        sig=sig + str(id1)+","+str(id2)+",0,0,0,0;"
                        query="insert into plan (idLiga,idTeam1,idTeam2,desicion,fecha,etapa) values('"+str(idLiga)+"','"+str(id1)+"','"+str(id2)+"','1','"+str(time.time())+"','"+str(etapa)+"')"

                        consultas.append(query)
                    if sig!="":
                        if ganadores.__len__()==4:
                            etapa= 4
                            sig= ",semi='"+sig+"'"
                        if ganadores.__len__()==8:
                            etapa =3
                            sig= ",cuartos='"+sig+"'"
                        if ganadores.__len__()==16:
                            etapa =2
                            sig= ",octavos='"+sig+"'"
                        if ganadores.__len__()==2:
                            etapa =5
                            sig= ",final='"+sig+"'"

                    query="update ligagroup set etapa='"+str(etapa)+"' "+sig+" where idLiga='"+str(idLiga)+"' limit 1"

                    cable.run_query(query)

                    for i in consultas:
                        cable.run_query(i)

                    if ganadores.__len__()==1:
                        tablaPos=str(ganadores[0])+","
                        estan=[]
                        estan.append(ganadores[0])
                        final= ligaGrupo[0][4].split(';')
                        final= final[0].split(',')
                        if final[5]==final[0]:
                            tablaPos+=str(final[1])+","
                            estan.append(final[1])
                        else:
                            tablaPos+=str(final[0])+","
                            estan.append(final[1])

                        semi= ligaGrupo[0][3]
                        res=self.checkEtapa(semi,estan,ligaGrupo)
                        tablaPos+=res[0]
                        estan= res[1]
                        cuartos= ligaGrupo[0][2]
                        res=self.checkEtapa(cuartos,estan,ligaGrupo)
                        tablaPos+=res[0]
                        estan= res[1]
                        octavos= ligaGrupo[0][1]
                        res=self.checkEtapa(octavos,estan,ligaGrupo)
                        tablaPos+=res[0]
                        estan= res[1]

                        grupos= ligaGrupo[0][0]
                        res=self.checkGrupos(grupos,estan,tablaPos)
                        tablaPos+=res[0]

                        query="update liga set state=2, ordenFinal='"+tablaPos+"', fechaDone='"+str(time.time())+"' where idLiga='"+str(idLiga)+"' limit 1"
                        cable.run_query(query)

                        tablaPos=tablaPos.split(',')

                        for i in range(0,tablaPos.__len__()):
                            if tablaPos[i] != '':
                                msg=str(self.lugares[i])+" lugar, #"+str(tablaPos[i])+"#, en liga *"+str(idLiga)+"*."
                                self.notificar(self.getUserFromTeam(tablaPos[i],cable),msg,6,cable)

                        self.actualizarChallengeFinalLiga(self.getUserFromTeam(tablaPos[0],cable),liga[0][1],liga[0][3],1,liga[0][0],cable)
                        self.actualizarChallengeFinalLiga(self.getUserFromTeam(tablaPos[1],cable),liga[0][1],liga[0][3],2,liga[0][0],cable)
                        self.actualizarChallengeFinalLiga(self.getUserFromTeam(tablaPos[2],cable),liga[0][1],liga[0][3],3,liga[0][0],cable)



    def getUserFromTeam(self,idTeam,cable):
        query="select idUser from team where idTeam='"+str(idTeam)+"' limit 1"
        dat = cable.run_query(query)

        return dat[0][0]

    def rankingTeam(self,idTeam,cable):
        query= "select elo from team where idTeam='"+str(idTeam)+"' limit 1"
        team = cable.run_query(query)
        elo= team[0][0]
        query= "select count(idTeam) as cant from team where elo>'"+str(elo)+"' and idTeam!='"+str(idTeam)+"'";
        result= cable.run_query(query)
        cantMasElo= result[0][0]
        query="select idTeam,win,lost,golFavor,golContra,elo from team where elo='"+str(elo)+"' and idTeam!='"+str(idTeam)+"'";
        result= cable.run_query(query)
        mismoElo= self.OrdenarElo(result)
        cont=0
        for team in mismoElo:
            if team[0]==idTeam:
                cont+=1
                break
            else:
                cont+=1
        return cont + cantMasElo + 1






    def OrdenarElo(self,retorno):
        cambiar=False
        cant= retorno.__len__()
        for i in range(0,cant):
            cambiar=False
            for e in range(0,cant-1):
                if retorno[e][5]<retorno[e+1][5]:
                    cambiar=True
                else:
                    if retorno[e][5]==retorno[e+1][5] and retorno[e][1]< retorno[e+1][1]:
                        cambiar=True
                    else:
                        if retorno[e][5]==retorno[e+1][5] and retorno[e][1]== retorno[e+1][1] and retorno[e][3]<retorno[e+1][3]:
                            cambiar=True
                        else:
                            if retorno[e][5]==retorno[e+1][5] and retorno[e][1]== retorno[e+1][1] and retorno[e][3]== retorno[e+1][3] and retorno[e][2] > retorno[e+1][2]:
                                cambiar=True
                            else:
                                if retorno[e][5]==retorno[e+1][5] and retorno[e][1]== retorno[e+1][1] and retorno[e][3]== retorno[e+1][3] and retorno[e][2] == retorno[e+1][2] and retorno[e][4] > retorno[e+1][4]:
                                    cambiar=True
                if cambiar==True:
                    temp= retorno[e]
                    retorno[e]=retorno[e+1]
                    retorno[e+1]=temp  	
	return retorno;
		