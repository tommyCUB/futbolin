# -*- coding: utf-8 -*-

import MySQLdb as mysql

class conexion:

    def __init__(self):
        self.host="162.241.72.152"
        self.user="futbol_rooter"
        self.passwd="PlanetaEcil**2020"
        self.db="futbol_futbolin"
        self.port=3306

    def run_query(self,query):
        conn= mysql.connect(host=self.host,user=self.user,passwd=self.passwd,port=self.port,db=self.db)
        cursor = conn.cursor()
        cursor.execute(query)

        if query.upper().startswith('SELECT'):
            data = cursor.fetchall()
        else:
            conn.commit()
            data = None

        cursor.close()
        conn.close()

        return data