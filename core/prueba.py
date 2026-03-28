# -*- coding: utf-8 -*-

import sys

ids= sys.argv[1]


f= file("/home/futbol/public_html/core/prueba.txt",'a+')

f.write("asdsdfdsgsgsg"+str(ids))

f.close()
