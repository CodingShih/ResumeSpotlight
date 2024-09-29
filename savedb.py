# -*- coding: utf-8 -*-
"""
Created on Sun Oct 30 20:56:33 2022

@author: user
"""
import os
import pymysql
def connect(sql):
    db=pymysql.connect(host="localhost",user="root",password="",db="aispotlight",charset='utf8',cursorclass=pymysql.cursors.DictCursor)
    cursor=db.cursor()
    try:
        cursor.execute(sql)
        db.commit()
        print(sql)
    except:
        db.rollback()
        print("cannot reach db")
        
def save(path):
   
    allresult=os.listdir(path)
    print("save db cso---------------")
    
    for result in allresult:
        savesql="""UPDATE cso_classifier SET """
        i=1
        with open(path+"/"+result,"r")as f:
            content=f.read()
            cut=content.split("\n")
            for c in cut:
                if i>20:break
                if c !="":
                    sp=c.split(":")
                    #print(sp[0]+" and "+sp[1])
                    savesql+="""word_"""+str(i)+"""='"""+sp[0]+"""',weight_"""+str(i)+"""='"""+sp[1]+"""',"""
                    i=i+1
            result=result.replace("result_","").replace(".txt","")
            savesql=savesql[:-1]
            savesql+=""" WHERE account='"""+result+"""';"""
            connect(savesql)
            #print(savesql)
    