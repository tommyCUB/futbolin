# -*- coding: utf-8 -*-

import sys
import miparser
from conexion import *
ids=sys.argv[1]
cable= conexion()
query="select code,idTeam,iduser from chec where idChec='"+str(ids)+"' limit 1"
datos= cable.run_query(query)
ttt= file("/home/futbol/public_html/chec/"+str(ids)+"chec.txt","r")
code= ttt.read()
ttt.close()
query="delete from chec where iduser='"+str(datos[0][2])+"' and idTeam='"+str(datos[0][1])+"' and done='1'"
cable.run_query(query)
if code:
    ast = miparser.parse(code,ids)
    errores=file(str(ids)+"(error).txt",'r')
    err = tuple(errores.readlines())
    errores.close()
    import os
    os.remove(str(ids)+"(error).txt")

    cadena=""
    cant= err.__len__()
    if cant>0:
        cant-=1
    for i in err:
        cadena+=str(i)+"\n"

    query="update chec set err='"+cadena+"',cant="+str(cant)+",done=1 where idChec='"+str(ids)+"' limit 1"
    cable.run_query(query)
    errores.close()

    query="delete from chec where iduser='"+str(ids)+"' limit 1"
    cable.run_query(query)
    os.remove("/home/futbol/public_html/chec/"+str(ids)+"chec.txt")
