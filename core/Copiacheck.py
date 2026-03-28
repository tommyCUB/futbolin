# -*- coding: utf-8 -*-
import sys
import miparser
from conexion import *
import os

ids=263

cable= conexion()
query="select code from chec where idChec='"+str(ids)+"' limit 1"
datos= cable.run_query(query)


place=os.path.abspath("../chec/"+str(ids)+"chec.txt")
print place

ttt= file("../chec/"+str(ids)+"chec.txt","r")
code= ttt.read()
ttt.close()


if code:
    ast = miparser.parse(code,ids)
    print ast
    errores=file(str(ids)+"(error).txt",'r')
    err = tuple(errores.readlines())
    errores.close()

    import os
    #os.remove(str(ids)+"(error).txt")

    cadena=""
    cant= err.__len__()
    if cant>0:
        cant-=1
    for i in err:
        cadena+=str(i)+"\n"

    query="update chec set err='"+cadena+"',cant="+str(cant)+",done=1 where idChec='"+str(ids)+"' limit 1"
    print query
    cable.run_query(query)
    errores.close()

    query="delete from chec where iduser='"+str(ids)+"' limit 1"
    cable.run_query(query)
    #os.remove("../chec/"+str(ids)+"chec.txt")
