#!/usr/bin/env python3

import sys
import mysql.connector as mysqldb

DB_USERNAME = "root"
DB_PASSWORD = ""
DB_SERVERNAME = "localhost"
DB_NAME = 'learning'

def get_new_kdb_connection():
	return mysqldb.connect(
		username = DB_USERNAME,
		password = DB_PASSWORD,
		host = DB_SERVERNAME,
		database = DB_NAME
	)

db_conn = get_new_kdb_connection()
db_conn.autocommit = True

db_cursor = db_conn.cursor(dictionary=True, buffered=True)

REQUEST_TABLES = ['students']

FETCH_QUERY = f"SELECT * FROM {REQUEST_TABLES[0]}"

db_cursor.execute(FETCH_QUERY)
results = db_cursor.fetchall()

for row in results:
	sname = row['sname']
	semail = row['semail']

	print(sname, semail)