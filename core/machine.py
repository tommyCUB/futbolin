# -*- coding: utf-8 -*-

import math
from coreMaster import *
from comandos import *


class executor:
    def __init__(self):
        self.ordenes = tuple()
        self.tabla_sym= dict()
        self.funciones= dict()
        self.errores = tuple()
        self.err=0
        self.ast= None
        self.ss= None
        self.forces=list()

    def getErrores(self):
        return self.errores

    def execute(self,ast,sj,forces):
            self.ast= ast
            self.ss=sj
            self.tabla_sym["ballx"]=int(sj.getBola().x)
            self.tabla_sym["bally"]=int(sj.getBola().y)
            self.tabla_sym["mineCloserBall"]=self.ss.getMasCercanoDeBola()
            self.forces=forces
            if self.ast:
                for inst in self.ast:
                    self.procesarTupla(inst)

    def getTablaSym(self):
        return self.tabla_sym

    def getOrdenes(self):
        return self.ordenes
    def es_flotante(self, variable):
	try:
	    float(variable)
	    return True
	except:
	    return False
    def es_entero(self, variable):
	try:
	    int(variable)
	    return True
	except:
	    return False
    def procesarTupla(self,t,err=0):
        if not t:
            return None

        if self.es_entero(t) or self.es_flotante(t):
            return t

        tipo= t[0]

        if tipo == "DECLFUNC":
            return self.procesar_declfunc(t)
        if tipo== 'IF':
            return self.procesar_if(t)
        if tipo == 'IFELSE':
            return self.procesar_ifelse(t)
        if tipo == 'FOR':
            return self.procesar_for(t)
        if tipo == 'FOREACH':
            return self.procesar_foreach(t)

        if tipo == 'WHILE':
            return self.procesar_while(t)
        if tipo == 'DOWHILE':
            return self.procesar_dowhile(t)
        if tipo == 'BINOP':
            return  self.procesar_binop(t)
        if tipo == 'ASSIGN':
            return self.procesar_assign(t)
        if tipo == 'RETURNED':
            return self.procesar_return(t)
        if tipo == 'PLUSPLUS':
            return self.procesar_plusplus(t)
        if tipo == 'MINUSMINUS':
            return self.procesar_minusminus(t)
        if tipo == 'FUNCCALL':
            return self.procesar_funccall(t)
        if tipo == 'INT':
            return self.procesar_int(t)
        if tipo == 'FLOAT':
            return self.procesar_float(t)
        if tipo == 'COMP':
            return self.procesar_comp(t)
        if tipo == 'ORDEN':
            return self.procesar_orden(t)
        if tipo=='ID':
            return self.procesar_id(t)
        if tipo=='MINUS':
            return self.procesar_minus(t)
        if tipo=='POW':
            return self.procesar_pow(t)
        if tipo=='ARRAY':
            return self.procesar_array(t)
        if tipo=='BOLEAN':
            if t[1] == "FALSE":
                return False
            return True
        if tipo=='BREAK':
            return "break"
        if self.err==1:
            self.errores+=("Error Line ( "+str(t[2])+" ) : unknow function.",)
        return None

    def procesar_minus(self,t):
        valor= self.procesarTupla(t[1])
        return valor*-1
    def procesar_array(self,t):
        idt= t[1]
        exp= t[2]

        if idt in self.tabla_sym:
            exp= self.procesarTupla(exp)
            if type(exp)==type(1):
                arr= self.tabla_sym[idt]

                return arr[exp]

    def procesar_id(self,t):
        name= t[1]
        if name in self.tabla_sym:
            return self.tabla_sym[name]
        self.errores+=("Error Line ( "+str(t[2])+" ) : ( "+name+" ) used before asigned..",)
        return 1

    def procesar_comp(self,t):
        signo=t[2]
        valor1= self.procesarTupla(t[1])
        valor2 = self.procesarTupla(t[3])


        if not self.es_entero(valor1) or not self.es_entero(valor2):
            self.errores+=("Error Line ( "+str(t[4])+" ) : Incomparable values...",)
            return False
        if signo== '==':
            return (valor1 == valor2)
        if signo == '!=':
            return (valor1 != valor2)
        if signo == '<=':
            return (valor1 <= valor2)
        if signo == '>=':
            return (valor1 >= valor2)
        if signo == '<':
            return (valor1 < valor2)
        if signo == '>':
            return (valor1 > valor2)
        if signo == '&&':
            return (valor1 and valor2)
        if signo == '||':
            return (valor1 or valor2)
        if signo == '@':
            if valor1== True or valor1 == False:
                return valor1
            else:
                return False
        return False


    def procesar_orden(self,t):
        orden= t[1]
        jug= int(self.procesarTupla(t[2][0]))-1

        if type(jug)!=type(1) or int(jug)<0 or int(jug)>10:
            self.errores+=("Error Line ( "+str(t[3])+ " ) : Number of player must be integer between 1 and 11 ..",)
            jug= random.randrange(11)

        if orden =='pass' or orden=='PASS':
            jug2= self.procesarTupla(t[2][1])

            if type(jug2)!=type(1) or int(jug2)<1 or int(jug2)>11:
                self.errores+=("Error Line ( "+str(t[3])+ " ) : Number of player must be integer between 1 and 11 ..",)
                jug2= random.randrange(11)

            self.ordenes+= (PASS(jug,jug2),)
            return None
        if orden =='shoot' or orden=='SHOOT':
            desv= int(self.procesarTupla(t[2][1]))
            if type(desv)!=type(1) or desv<0 or desv>15:
                self.errores+=("Error Line ( "+str(t[3])+ " ) : Deviation must be integer between 0 and 15 ..",)
                desv= random.randrange(15)
            self.ordenes+=(SHOOT(jug,desv),)
            return None

        x= int(self.procesarTupla(t[2][1]))
        y=int(self.procesarTupla(t[2][2]))

        if type(x)!=type(1)or x<-240 or x>240:
            self.errores+=("Error Line ( "+str(t[3])+ " ) : Coord X must be integer between -240 and 240 ..",)
            x= random.randrange(480) - 240

        if type(y)!=type(1) or y<-180 or y>180:
            self.errores+=("Error Line ( "+str(t[3])+ " ) : Coord Y must be integer between 0 and 240 ..",)
            y= random.randrange(360) - 180

        if orden =='hit' or orden=='HIT':
            self.ordenes+= (HIT(jug,x,y),)
            return 1

        if orden =='goto' or orden=='GOTO':
            self.ordenes+= (GOTO(jug,x,y),)
            return 1

    def procesar_int(self,t):
        return int(t[1])

    def procesar_float(self,t):
        return float(t[1])

    def procesar_declfunc(self,t):
        self.funciones[t[1]]= t
        return None

    def procesar_if(self,t):
        if self.procesarTupla(t[1]):
            for inst in t[2]:
                self.procesarTupla(inst)

        return None

    def procesar_ifelse(self,t):
        if self.procesarTupla(t[1]):
            for inst in t[2]:
                self.procesarTupla(inst)
            return None
        for inst in t[3]:
                self.procesarTupla(inst)
        return None

    def procesar_for(self,t):
        self.procesarTupla(t[1])
        while self.procesarTupla(t[2]):
            for inst in t[4]:
                valor=self.procesarTupla(inst)
                if valor=='break':

                    break
            self.procesarTupla(t[3])

    def procesar_foreach(self,t):
        arr= self.tabla_sym[t[1]]

        if isinstance(arr, list):
            for i in arr:
                self.procesar_assign(("ASSIGN",t[2],"ASSIGN",i,t[4]))
                for inst in t[3]:
                    valor= self.procesarTupla(inst)
                    if valor=="break":

                        break

        return None

    def procesar_while(self,t):
        if self.procesarTupla(t[1]):
            for inst in t[2]:
                valor= self.procesarTupla(inst)
                if valor=='break':
                    print "anja"
                    break

        return None

    def procesar_dowhile(self,t):
        while True:
            for inst in t[1]:
                valor=self.procesarTupla(inst)
                if valor=="break":
                    print "anja"
                    break
            if self.procesarTupla(t[2]):
                break
        return None


    def procesar_binop(self,t):
        valor1= self.procesarTupla(t[1])
        valor2= self.procesarTupla(t[3])
        op= t[2]
        if op == '+':
            try:
                return valor1 + valor2
            except:
                self.errores+=("Error Line ( "+str(t[4])+"): Cant complete "+op+" between types "+str(type(valor1))+" and "+str(type(valor2))+" ..",)
                return valor1
        if op =='-':
            try:
                return valor1 - valor2
            except:
                self.errores+=("Error Line ( "+str(t[4])+"): Cant complete "+op+" between types "+str(type(valor1))+" and "+str(type(valor2))+" ..",)
                return valor1
        if op == '*':
            try:
                return valor1 * valor2
            except:
                self.errores+=("Error Line ( "+str(t[4])+"): Cant complete "+op+" between types "+str(type(valor1))+" and "+str(type(valor2))+" ..",)
                return valor1
        if op == '/':
            if valor2==0:
                self.errores+=("Error Line ( "+str(t[4])+"): Division by zero...",)
                return 0
            try:
                return valor1 / valor2
            except:
                self.errores+=("Error Line ( "+str(t[4])+"): Cant complete "+op+" between types "+str(type(valor1))+" and "+str(type(valor2))+" ..",)
                return valor1

        if op == '%':
            if valor2==0:
                self.errores+=("Error Line ( "+str(t[4])+"): Division by zero...",)
                return 0
            try:
                return valor1 % valor2
            except:
                self.errores+=("Error  Line ( "+str(t[4])+"): Cant complete "+op+" between types "+str(type(valor1))+" and "+str(type(valor2))+" ..",)
                return valor1

        return None

    def procesar_assign(self,t):
        recep = t[1]
        pos=0
        if recep[0]=='ID':
            valor= self.procesarTupla(t[3])
            self.tabla_sym[recep[1]]= valor
            return None
        if recep[0] == 'ARRAY':
            pos= self.procesarTupla(recep[2])
            lista=list()
            if recep[1] in self.tabla_sym:
                lista= self.tabla_sym[recep[1]]
            else:
                self.tabla_sym[recep[1]]=list()
            if pos >= len(lista):
                for i in range(len(lista),pos+1):
                    lista.append(None)
            lista[pos]= self.procesarTupla(t[3])
            self.tabla_sym[recep[1]]= lista
            return None

    def procesar_return(self,t):
        if t[1] == 'SEMICOLON':
            return None
        return self.procesarTupla(t[1])
    def procesar_plusplus(self,t):
        valor= self.procesarTupla(t[1])
        try:
            v= valor+1
            recep=t[1][1]

            self.tabla_sym[recep]= v
        except:
            self.errores+=("Error Line ( "+str(t[2])+" cant complete op ++ over "+str(valor)+".)",)
            return valor
    def getDistancia(self, uno,dos,tres,cuatro):
        return math.sqrt((cuatro- dos)*(cuatro - dos) + (tres - uno)*(tres - uno))
    def procesar_minusminus(self,t):
        valor= self.procesarTupla(t[1])
        try:
            return valor-1
        except:
            self.errores+=("Error Line ( "+str(t[2])+" ) cant complete op ++ over "+str(valor)+".)",)
            return valor

    def procesar_funccall(self,t):
        nom= t[1]
        if nom in self.funciones:
            funcion = self.funciones[nom]
            ids= funcion[2]
            valores= t[2]
            if len(ids) != len(valores):
                self.errores+=("Error Line ("+str(t[3])+" ): "+ nom+ " takes "+len(ids)+" arguments "+", "+len(valores)+" given.",)
                c=len(ids)-len(valores)
                if c>0:
                    for i in range(c):
                        ids.append(1)
                if c<0:
                    for i in range(c*-1):
                        valores.pop()

            for i in range(0,len(ids)):
                v = None
                if valores[i]:
                    v= self.procesarTupla(valores[i])
                n= ids[i][1]
                self.tabla_sym[n]=v
            retorno=None
            for inst in funcion[3]:
                retorno= self.procesarTupla(inst)
                if retorno != None:
                    return retorno
            return None

        if  nom == 'SIN':
            valor= self.procesarTupla(t[2][0])
            return math.sin(valor)
        if  nom == 'COS':
            valor= self.procesarTupla(t[2][0])
            return math.cos(valor)
        if  nom == 'TAN':
            valor= self.procesarTupla(t[2][0])
            return math.tan(valor)
        if nom == 'miney' or nom == 'MINEY':
            jug= self.procesarTupla(t[2][0])-1
            return self.ss.getMisJugadores()[jug].y

        if nom == 'minex' or nom == 'MINEX':
            jug= self.procesarTupla(t[2][0])-1
            return self.ss.getMisJugadores()[jug].x

        if nom == 'hisx' or nom == 'HISX':
            jug= self.procesarTupla(t[2][0])-1
            return self.ss.getContrario()[jug].x
        if nom == 'hisy' or nom == 'HISY':
            jug= self.procesarTupla(t[2][0])-1
            return self.ss.getContrario()[jug].y
        if nom == 'mine' or nom == 'MINE':
            jug= self.procesarTupla(t[2][0])-1
            tipo=None
            if len(t[2])>1:
                try:
                    tipo= t[2][1][1]
                except:
                    pass
                if tipo== 'x':
                    return self.ss.getMisJugadores()[jug].x
                if tipo== 'y':
                    return self.ss.getMisJugadores()[jug].y
            return self.ss.getMisJugadores()[jug]
        if nom == 'yours' or nom == 'YOURS':
            jug= self.procesarTupla(t[2][0])-1
            tipo='x'
            if t[2]:
                try:
                    tipo= t[2][1][1]
                except:
                    pass
            if tipo== 'x':
                return self.ss.getMisJugadores()[jug].getX()
            if tipo=='y':
                return self.ss.getMisJugadores()[jug].getY()

            return self.ss.getMisJugadores()[jug]
        if nom =='ball' or nom=='BALL':
            tipo='x'
            if t[2]:
                tipo= t[2][0][1]
            if tipo=='x':
                return self.ss.getBola().x
            if tipo=='y':
                return self.ss.getBola().y
            return bola

        if nom=='time':
            return self.ss.getTiempo()
        if nom=='myGoals':
            return self.ss.getMarcador()[0]
        if nom=='hisGoals':
            return self.ss.getMarcador()[1]

# revizar y añadir funciones de apoyo
        if nom == 'amCloserToBall':
            return self.ss.estoyMasCercaDeBola()

        if nom == 'distance':
            xo= self.procesarTupla(t[2][0])
            yo= self.procesarTupla(t[2][1])
            xf= self.procesarTupla(t[2][2])
            yf= self.procesarTupla(t[2][3])
            return self.getDistancia(xo,yo,xf,yf)

        if nom=='yourCloserMine':
            jug= random.randrange(11)
            try:
                jug = self.procesarTupla(t[2][0])-1
            except:
                jug= random.ranndrange(11)
            return self.ss.getContrarioMasCerca(jug)
        if nom=='myNext':
            jug= random.randrange(11)
            try:
                jug = self.procesarTupla(t[2][0])-1
            except:
                pass
            return self.ss.getSiguienteJugador(jug)

        if nom== 'canHitBall':
            jug= self.procesarTupla(t[2][0])-1
            ballx=self.tabla_sym["ballx"]
            bally= self.tabla_sym["bally"]
            x=self.ss.getMisJugadores()[jug].x
            y=self.ss.getMisJugadores()[jug].y
            dist = self.getDistancia(x,y,ballx,bally)

            if dist<15:
                return True
            return False

        if nom=='myCloserToPoint':
            x= random.randrange(480) -240
            y= random.randrange(360) -180
            try:
                x= self.procesarTupla(t[2][0])
            except:
                x= random.randrange(480) -240
            try:
                y = self.procesarTupla(t[2][1])
            except:
                y= random.randrange(360) -180

            return self.ss.getMiJugadorMasCerca(x,y)


        if nom=='hisCloserToPoint':
            x= random.randrange(480) -240
            y= random.randrange(360) -180

            try:
                x= self.procesarTupla(t[2][0])
            except:
                x= random.randrange(480) -240
            try:
                y = self.procesarTupla(t[2][1])
            except:
                y= random.randrange(360) -180

            return self.ss.getContrarioMasCercaPunto(x,y)

        if nom=='push':
            nom= t[2][0][1]
            valor= self.procesarTupla(t[2][1])
            if not nom in self.tabla_sym:
                self.tabla_sym[nom]=list()
            self.tabla_sym[nom].append(valor)
            return None
        if nom=='pop':
            nom= t[2][0][1]
            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no es un arreglo.",)
                return None
            pos= self.tabla_sym[nom].__len__()-1
            try:
                pos=self.procesarTupla(t[2][1])
            except:
                pass
            if not isinstance(pos, int) or pos>=self.tabla_sym[nom].__len__():
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(pos)+" ) debe ser un numero menor de "+str(self.tabla_sym[nom].__len__())+".",)
                return None
            valor = self.tabla_sym[nom].pop(pos)

            return valor
        if nom=='shift':
            nom= t[2][0][1]
            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no es un arreglo.",)
                return None
            valor = self.tabla_sym[nom].pop(0)
            return valor
        if nom=='unshift':
            nom= t[2][0][1]
            valor= self.procesarTupla(t[2][1])

            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ):( "+str(nom)+" ) no es un arreglo.",)
                return None
            self.tabla_sym[nom].insert(0,valor)

            return None
        if nom=='indexOf':
            nom= t[2][0][1]
            valor= self.procesarTupla(t[2][1])

            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ):( "+str(nom)+" ) no es un arreglo.",)
                return None
            pos=0

            for i in self.tabla_sym[nom]:
                if i==valor:
                    return pos
                pos+=1

            return -1

        if nom=='erase':
            nom= t[2][0][1]
            pos= self.procesarTupla(t[2][1])

            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no es un arreglo.",)
                return None
            if not isinstance(pos, int) or pos>=self.tabla_sym[nom].__len__():
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(pos)+" ) debe ser un numero menor de "+str(self.tabla_sym[nom].__len__())+".",)
                return None

            self.tabla_sym[nom].pop(pos)
            return None

        if nom=='slice':
            nom= t[2][0][1]
            start= self.procesarTupla(t[2][1])
            end=self.procesarTupla(t[2][2])
            paso=1

            try:
                b= t[2][3]
                paso= self.procesarTupla(t[2][3])
            except:
                pass

            if not nom in self.tabla_sym:
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no existe.",)
                return None
            if not isinstance(self.tabla_sym[nom], list):
                self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) no es un arreglo.",)
                return None

            if not isinstance(start, int) or not isinstance(end, int):
                self.errores+=("Error Line ( "+str(t[3])+" ): ( start: "+str(start)+" y  end: "+str(end)+" ) deben ser numeros enteros.",)
                return None

            a= slice(start,end,paso)
            return self.tabla_sym[nom][a]

        if nom=='initialX':
            if t[2]:
                jug= self.procesarTupla(t[2][0])
                if not isinstance(jug, int) or jug<0 or jug>11:
                    self.errores+=("Error Line ( "+str(t[3])+" ): "+str(jug)+" debe ser un entero entre 1 y 11.",)
                    return None
                return self.ss.getXInicial(jug-1)
                
            self.errores+=("Error Linea ( "+str(t[3])+" ): ( "+ nom +" ) esperaba un argumento entero entre 1 y 11.","es")
            self.errores+=("Error Line ( "+str(t[3])+" ): ( "+ nom +" ) expected an integer argument between 1 and 11.","en")
            
            return None
        if nom=='initialY':
            if t[2]:
                jug= self.procesarTupla(t[2][0])
                if not isinstance(jug, int) or jug<0 or jug>11:
                    self.errores+=("Error Line ( "+str(t[3])+" ): "+str(jug)+" debe ser un entero entre 1 y 11.",)
                    return None
    
                return self.ss.getYInicial(jug-1)
            self.errores+=("Error Linea ( "+str(t[3])+" ): ( "+ nom +" ) esperaba un argumento entero entre 1 y 11.","es")
            self.errores+=("Error Line ( "+str(t[3])+" ): ( "+ nom +" ) expected an integer argument between 1 and 11.","en")
            
            return None
        if nom=='force':
            if t[2]:
                jug= self.procesarTupla(t[2][0])
                if not isinstance(jug, int) or jug<0 or jug>11:
                    self.errores+=("Error Line ( "+str(t[3])+" ): "+str(jug)+" debe ser un entero entre 1 y 11.",)
                    return None
                return self.forces[jug-1]
                
            self.errores+=("Error Linea ( "+str(t[3])+" ): ( "+ nom +" ) esperaba un argumento entero entre 1 y 11.","es")
            self.errores+=("Error Line ( "+str(t[3])+" ): ( "+ nom +" ) expected an integer argument between 1 and 11.","en")
            
            return None
        if nom== 'len':
            var=()
            if t[2]:
                if t[2].__len__()>0:
                    var= self.procesar_id(t[2][0])
                    if isinstance(var, list):
                        return var.__len__()
                    return 1
            self.errores+=("Error Linea ( "+str(t[3])+" ): ( "+str(nom)+" ) espera 1 argumento.","es") 
            self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) wait 1 argument.","en") 
            return 0
        if nom=='rand':
            minimo=0
            maximo=100
            
            if t[2]:
                if t[2].__len__()>0:
                    maximo= self.procesarTupla(t[2][0])
                if t[2].__len__()>1:
                    minimo= self.procesarTupla(t[2][0])
                    maximo= self.procesarTupla(t[2][1])
                import random
                return random.randint(minimo,maximo)
            return 0
        self.errores+=("Error Linea ( "+str(t[3])+" ): ( "+str(nom)+" ) no es una funcion.","es")
        self.errores+=("Error Line ( "+str(t[3])+" ): ( "+str(nom)+" ) it isnt a function.","en")
        return None
            
