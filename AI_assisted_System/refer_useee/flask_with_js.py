# -*- coding: utf-8 -*-
"""
Created on Fri Jan 21 10:57:00 2022

@author: user
"""


from cso_classifier import CSOClassifier as cc
cc.setup()

from cso_classifier import CSOClassifier as CSOO

CSOO.test_classifier_single_paper() # to test it with one paper
CSOO.test_classifier_batch_mode() # to test it with multiple papers



from flask import Flask, render_template, request, jsonify

#創建Flask物件connect并初始化
connect = Flask(__name__)
@connect.route("/localhost/AI_assisted_System/index.html",methods=["GET", "POST"])

#@connect.route("/")
def root():
    return render_template("/localhost/AI_assisted_System/index.html",name="zxy",age=21)

def submit():
    #由于POST、GET獲取資料的方式不同，需要使用if陳述句進行判斷
    if request.method == "POST":
        name = request.form.get("name")
        age = request.form.get("age")
    if request.method == "GET":
        name = request.args.get("name")
        age = request.args.get("age")
    #如果獲取的資料為空
    if len(name) == 0 or len(age) ==0:
        return {'message':"error!"}
    else:
        return {'message':"success!",'name':name,'age':age}

connect.run(port=8080)

#import sys
#sys模块提供了一系列有关Python运行环境的变量和函数。
#print(sys.version)



#pip install mysql-connector

import mysql.connector

#connect=mysql.connector.connect(host='localhost',
#                                database='ai_assisted',
#                                user='root',
#                                password='nuuadmin')
connect = mysql.connector.connect(user='root', password='nuuadmin',
                              host='localhost',
                              database='ai_assisted',
                              auth_plugin='mysql_native_password')
    



def InsertIntoDatabase(infoList):
    #資料庫連線
    connect=mysql.connector.connect(host='localhost',
                                database='ai_assisted',
                                user='root',
                                password='nuuadmin')
    
    
    
    cursorOBJ=connect.cursor(buffered=True)
    query='''select * from vocabulary_table'''
    cursorOBJ.execute(query)
    #回傳表單筆數
    num=cursorOBJ.rowcount
    if num > 0 :
        infoList[0]=num+1
    #將圖檔轉成sql能夠儲存的檔案格式
    with open(infoList[4],'rb') as file:
        blobData=file.read()
        infoList.append(blobData)
    #將資料填入表單
    query='''INSERT INTO vocabulary_table (NO, time, location, ADDRESS, filePath, category, photo) VALUES (%s, %s, %s, %s, %s, %s, %s)'''
    cursorOBJ.execute(query,infoList)
    connect.commit()
    connect.close()
    return





