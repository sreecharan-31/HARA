import pymysql
connection = pymysql.connect(host="localhost", user="root", passwd="", database="hospital")
cursor = connection.cursor()
insert1 = "INSERT INTO patient VALUES(4,'subhajit2',20,79,'male','guwahati',84863547,'deekshu',2);"
cursor.execute(insert1)
connection.commit()
connection.close()

