import pymysql
from PIL import Image
import base64
import io
import PIL.Image

def write_file(data, filename):
    with open(filename, 'a') as f:
        f.write(data)
        f.close()
connection = pymysql.connect(host="localhost",user="root",passwd="",database="hospital" )
cursor = connection.cursor()
num=input()
retrive = "Select image from image where name like \'"+num+"\';"
cursor.execute(retrive)
rows = cursor.fetchall()
file_like=io.BytesIO(rows[0][0])
img=PIL.Image.open(file_like)
img.show()
connection.commit()
connection.close()
