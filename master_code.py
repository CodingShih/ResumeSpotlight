# -*- coding: utf-8 -*-
"""
Created on Mon Sep 26 15:07:19 2022

@author: Meow
"""

import pymysql
import numpy
import json
import jieba
import pdfplumber
import os
import sys
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
#import pdf
#import ResumeAnalyze_1 as ra
#import test
#import savedb 

MessageFromFront=sys.argv

def gettfidf(texts):    
    vectorizer=TfidfVectorizer(smooth_idf=True)
    tfidf=vectorizer.fit_transform(texts)
    result = pd.DataFrame(tfidf.toarray(), columns=vectorizer.get_feature_names())
    return result

def connect (order,name):
    try:
        db=pymysql.connect(host='localhost',user='root',password='',db=name,charset='utf8',cursorclass=pymysql.cursors.DictCursor ) 
        cursor = db.cursor()
        cursor.execute(order)#存入JSON資訊
        x=cursor.fetchall()
        db.commit()
        return x
    except:
        db.rollback()  

User_Account=MessageFromFront[1]
sql="""INSERT INTO average_compare (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")
#sql="""INSERT INTO chosen_group (account) VALUES ('"""+User_Account+"""',"""+'資訊學群'+"""',"""+'管理學群'+""");"""
#connect(sql,"aispotlight")
sql="""INSERT INTO cso_classifier (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")
sql="""INSERT INTO ratio (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")
sql="""INSERT INTO spe_count (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")
sql="""INSERT INTO tfidf (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")
sql="""INSERT INTO vocabulary (account) VALUES ('"""+User_Account+"""');"""
connect(sql,"aispotlight")


#getid="""SELECT member_id from student WHERE account="""+User_Account+""";"""
#User_id=connect(getid,"aispotlight")
     
"""-----------------------------------------------抓取75個領域名稱-----------------------------------------------------------------"""

TableeWord=[]#雙語資料庫所有Table名稱的list
sql="""SHOW TABLES FROM word;"""#MYSQL語法
All_Word=connect(sql,"")
for AW in All_Word:
    TableeWord.append(str(AW).replace("{'Tables_in_word': '"," ").replace("'}"," "))#字串處理
#print(TableeWord)

"""-----------------------------------------------抓取75個領域內的內容-------------------------------------------------------------"""

category=[]#領域的專業詞彙的list
allsub=[]#該詞彙隸屬領域的list
catecount=dict()#計算各領域提及次數
for TA in TableeWord:
    sql="""SELECT 中文名稱 FROM """+ TA +""";"""
    ALL_Data=connect(sql,"word")
    #print(ALL_Data)
    for AD in ALL_Data:
        AD=str(AD).replace("{'中文名稱': '","").replace("'}","")
        category.append(AD)
        allsub.append(TA)
        catecount[TA]=0

"""-----------------------------------------------抓取PDF的內容------------------------------------------------------------------"""

text=""
#pdf轉文字
with pdfplumber.open("C:/xampp/htdocs/NewDatabase/UPLOAD_FILE/"+MessageFromFront[1]+".pdf") as Temp:
    for TE in Temp.pages:
        first_page = TE
        text+=first_page.extract_text()
#print(text)
print("PDF轉文字完成") 

"""-----------------------------------------------結巴斷詞------------------------------------------------------------------"""

remove=['，','。','、',' ','?']
jieba.load_userdict("D:/data.txt")#讀取詞彙表
#print('詞彙表讀取完成')
#print(type(text))
for r in text:
    if r in remove:
        text=text.replace(r,'')
#print(text)
words=" ".join(jieba.lcut(text,cut_all=False,HMM=True))
#print(words)
cut_word=words.split(" ")
#print(cut_word)
print("-------------------------------------------")
"""-----------------------------------------------比對專業詞彙------------------------------------------------------------------"""
category=numpy.array(category)
order="""UPDATE vocabulary SET voca_json= '[{"""

for j in cut_word: 
    results=numpy.where(category==j) #將比對中的目標放置results並進下一個迴圈
    for r in results[0]:
        subject=allsub[r]
        #print(j+" : "+subject)
        catecount[subject]=catecount[subject]+1#計算各領域命中次數
        order+='"'+j+'"'+":"+'"'+subject+'"'+","
order=order[:-1]
order=order+"""}]' WHERE account='"""+User_Account+"""';"""
#print(order)
connect(order,"aispotlight")

catecounts=sorted(catecount.items(),key = lambda kv:(kv[1], kv[0])) #將所有的命中次數排序
All_Catecount=dict()
SaveCount="""UPDATE spe_count SET"""
for c in catecounts:
    All_Catecount[c[0]]=c[1]#將命中次數等資料放至dictionary
for AC in All_Catecount.keys():
    SaveCount+=AC+"""='"""+str(All_Catecount[AC])+"""',"""#將dict累加update語法
SaveCount=SaveCount[:-1]#去除最後逗點
SaveCount=SaveCount+"""WHERE account='"""+User_Account+"""';"""

connect(SaveCount,"aispotlight")#執行sql語法
"""---------------------以下是平均值比較的程式----------------------------------------------------------------"""
noun1=("醫藥衛生學群","中醫")
noun2=("數理化學群","力學")
noun3=("生物資源學群","動物學")
noun4=("生命科學學群","動物學")
noun5=("數理化學群","化合物")
noun6=("數理化學群","化學")
noun7=("文史哲學群","圖書館學與資訊科學名詞")
noun8=("資訊學群","圖書館學與資訊科學名詞")
noun9=("地球環境學群","土壤學")#有做更動09/18
noun10=("工程學群","土木")
noun11=("地球環境學群","地理")
noun12=("地球環境學群","地科")
noun13=("地球環境學群","地質學")
noun14=("天文學群","天文")  #18學群沒有 dc上的 "學者" 也沒有
noun15=("醫藥衛生學群","實驗動物及比較醫學名詞") 
noun16=("生物資源學群","實驗動物及比較醫學名詞")
noun17=("建築設計學群","工業工程")
noun18=("工程學群","工業工程")
noun19=("管理學群","市場學")
noun20=("社會心理學群","心理學")
noun21=("教育學群","教育學")
noun22=("數理化學群","數學")
noun23=("數理化學群","數學名詞")
noun24=("大眾傳播學群","新聞傳播學名詞")
noun25=("財經學群","會計學")
noun26=("工程學群","材料學")
noun27=("生物資源學群","林學")
noun28=("地球環境學群","核能")
noun29=("工程學群","機械工程")
noun30=("地球環境學群","氣象學")
noun31=("工程學群","水利工程")
noun32=("地球環境學群","水利工程")
noun33=("法政學群","法律")
noun34=("生物資源學群","海事")
noun35=("管理學群","海事")
noun36=("生物資源學群","海洋地質")
noun37=("地球環境學群","海洋地質")
noun38=("生物資源學群","海洋科學")
noun39=("地球環境學群","海洋科學")
noun40=("數理化學群","測量學")
noun41=("數理化學群","物理")
noun42=("生物資源學群","獸醫")
noun43=("生命科學學群","獸醫")
noun44=("生命科學學群","生命科學")
noun45=("生物資源學群","生態學")
noun46=("生物資源學群","生物")
noun47=("生物資源學群","畜牧")
noun48=("醫藥衛生學群","病理學")
noun49=("數理化學群","發生學")
noun50=("工程學群","礦冶工程名詞")
noun51=("生物資源學群","礦物學名詞")
noun52=("社會心理學群","社會學")
noun53=("社會心理學群","社會福利")
noun54=("管理學群","管理學名詞")
noun55=("社會心理學群","精神病理學")
noun56=("醫藥衛生學群","精神病理學")
noun57=("工程學群","紡織學") #我認為與材料有關
noun58=("醫藥衛生學群","細菌免疫學")
noun59=("數理化學群","統計學名詞")
noun60=("財經學群","經濟學")
noun61=("生物資源學群","肥料學")
noun62=("數理化學群","肥料學")#有做新增09/18
noun63=("工程學群","自動化")
noun64=("遊憩運動學群","舞蹈名詞")
noun65=("藝術學群","舞蹈名詞")
noun66=("工程學群","航太")
noun67=("醫藥衛生學群","藥學名詞")
noun68=("管理學群","行政")
noun69=("藝術學群","視覺藝術名詞")
noun70=("生命科學學群","解剖學")
noun71=("醫藥衛生學群","解剖學")
noun72=("數理化學群","計量學")
noun73=("工程學群","設備學")
noun74=("建築設計學群","設計學")
noun75=("藝術學群","設計學")
noun76=("資訊學群","資訊")
noun77=("工程學群","資訊")
noun78=("資訊學群","資訊名詞")
noun79=("工程學群","資訊名詞")
noun80=("生物資源學群","農業推廣學")
noun81=("大眾傳播學群","農業推廣學")
noun82=("生物資源學群","農業機械名詞")
noun83=("工程學群","農業機械名詞")
noun84=("資訊學群","通訊工程")
noun85=("工程學群","通訊工程")
noun86=("工程學群","造船工程名詞")
noun87=("醫藥衛生學群","醫學")
noun88=("工程學群","鑄造學")
noun89=("工程學群","電力工程")
noun90=("資訊學群","電力工程")
noun91=("工程學群","電子工程")
noun92=("資訊學群","電子工程")
noun93=("藝術學群","音樂")
noun94=("生物資源學群","食品科技")
noun95=("生命科學學群","食品科技")
noun96=("生物資源學群","魚類")

#Total_noun是noun1-96的list
Total_Noun=[noun1,noun2,noun3,noun4,noun5,noun6,noun7,noun8,noun9,noun10,noun11,noun12,noun13,noun14,noun15,noun16,noun17,noun18,noun19,noun20,noun21,noun22,noun23,noun24,noun25,noun26,noun27,noun28,noun29,noun30,noun31,noun32,noun33,noun34,noun35,noun36,noun37,noun38,noun39,noun40,noun41,noun42,noun43,noun44,noun45,noun46,noun47,noun48,noun49,noun50,noun51,noun52,noun53,noun54,noun55,noun56,noun57,noun58,noun59,noun60,noun61,noun62,noun63,noun64,noun65,noun66,noun67,noun68,noun69,noun70,noun71,noun72,noun73,noun74,noun75,noun76,noun77,noun78,noun79,noun80,noun81,noun82,noun83,noun84,noun85,noun86,noun87,noun88,noun89,noun90,noun91,noun93,noun94,noun95,noun96]

sql="""SELECT account FROM student"""
All_Account=connect(sql,"aispotlight")
StdAcc=[]#所有學生的帳號
#print(type(All_Account[0]))

for a in All_Account:
    a_str=str(a)
    a_str=a_str.replace("}" ,"").replace("'" ,"").replace("{","")#字串處理
    a_str=a_str.replace(":", "")                                     #將所有學生帳號處理放置list
    a_str=a_str.split(" ")
    #print(a_str)
    StdAcc.append(a_str[1])

Tableword_dict={}#計算專業詞彙出現次數
Tableword_count_dict={}#計算專業詞彙出現在幾分備審當中
for T_word in TableeWord:
    Tableword_dict[T_word.strip()]=0#將KEY值設為雙語詞彙資料庫所有Table名稱，並將Value設為0
    Tableword_count_dict[T_word.strip()]=0#將KEY值設為雙語詞彙資料庫所有Table名稱，並將Value設為0

text="""SELECT COUNT(*) FROM student;"""#MYSQL語法
User_Amount=connect(text, "aispotlight")#呼叫資料庫連線函式
User_Amount_Str=str(User_Amount)#轉換型別STR
User_Amount_Process=User_Amount_Str.split(":")#用冒號切割字串
Data_ratio=User_Amount_Process[1].replace("}]']" ,"").replace("}]" ,"")#字串處理
Front_End_Group=[]#存放學生所選之學群
Field_Dic=dict()#存放學生領域命中次數
Number=0#就只是用來計數
#print(Tableword_dict)
#while Number<int(Data_ratio):
for sa in StdAcc:
    
    #接收前端的資料庫
    #text="""SELECT * FROM chosen_group WHERE account='"""+User_Account+"""';"""#MYSQL語法
    text="""SELECT * FROM chosen_group WHERE account='"""+sa+"""';"""#MYSQL語法
    Group_Data=connect(text, "aispotlight")#呼叫資料庫連線函式
    Group_Data_Str=str(Group_Data)#轉換型別STR
    Group_Data_Process=Group_Data_Str.split(",")#用冒號切割字串  
    print(Group_Data_Process)
    for g in Group_Data_Process:
        g=g.replace("'}]" ,"").replace("'" ,"").replace("[{","")#字串處理
        g=g.replace("':", "")
        g=g.split(":")
        Front_End_Group.append(g[1].strip())
        #print(g[1])
    Front_End_Group.pop(0)
    #print(Front_End_Group)
    text="""SELECT * FROM spe_count WHERE account='"""+sa+"""';"""#MYSQL語法
    Field_Count=connect(text, "aispotlight")#呼叫資料庫連線函式
    Field_str=str(Field_Count[0])
    Field_str=Field_str.split(",")
    for f in Field_str:
        f=f.replace("{'","").replace("'","").replace("}","")
        f=f.split(":")
        Field_Dic[f[0].strip()]=f[1]
    Field_Dic.pop("account")

    #將資料存入專業詞彙出現次數、專業詞彙出現在幾備審當中的Dict
    for Pro in Field_Dic:#調出各領域提及次數的Key值
        for X in Total_Noun:#調出雙語詞彙資料庫所有Table名稱
           #print(X[0]+" VS "+str(Front_End_Group))
            #print(X[0] in Front_End_Group)
            for f in Front_End_Group:
                #print(X[0]+"VS"+f)
                if X[0] == f:#篩選出使用者所選取的學群對應的雙語資料庫中的Table
                    #print(X[1]+":"+Pro)
                    if X[1] in Pro:#將有符合的領域找出
                        #print("second")
                        if Pro in str(Tableword_dict.keys()):
                           # print(Pro)
                            Tableword_dict[Pro]=int(Tableword_dict[Pro])+int(Field_Dic[Pro])#將每一份備審同樣領域的詞彙數量加總
                            Tableword_count_dict[Pro]=int(Tableword_count_dict[Pro])+1#計算有幾份備審提及該領域
    Number=Number+1#完成上述動作後則+1去分析下一份備審
#print(Tableword_dict)
#print(Tableword_count_dict)
Tableword_Calculation_Results={}
for H in Tableword_dict:
    if Tableword_dict[H]!=0:#排除掉0的領域
        Tableword_Calculation_Results[H]=0#將Key值設為雙語詞彙資料庫所有Table名稱，並將Value歸零
        Tableword_Calculation_Results[H]=Tableword_dict[H]/Tableword_count_dict[H]#計算平均值    
        
print("aaaaaaaaaaaaaaaaa")
#print(Tableword_Calculation_Results)
Get_Spe="""SELECT * FROM spe_count WHERE account='"""+User_Account+"""';""" #將該學生的所有次數取出，用於和平均計算之sql
Get_Spe_db=connect(Get_Spe,"aispotlight") #將該學生的所有次數取出，用於和平均計算
StdSpe=dict()#該學生之所有領域次數
Spe_list=str(Get_Spe_db).split(",")
for s in Spe_list:
    s=s.replace("[{","").replace("'","").replace("}]","")
    s=s.split(":")
    StdSpe[s[0].strip()]=s[1].strip() #將資料轉為字串並處理，切割最後放到StdSpe
for ss in StdSpe:
    ss=ss.replace("[{","").replace("'","").strip()   
StdSpe.pop("account")
#print(StdSpe)
average_compare=dict() #存放平均比對結果之dict
for QWE in Tableword_Calculation_Results:#領域詞彙出現平均值
    for EWQ in StdSpe:#最新一份備審的數據
        if QWE==EWQ:#公式:高的-低的/高的*100
            if int(Tableword_Calculation_Results[QWE]) > int(StdSpe[EWQ]):#低於平均值
                Lower=(((int(Tableword_Calculation_Results[QWE])-int(StdSpe[EWQ])))/int(Tableword_Calculation_Results[QWE]))*100
                IntLower=int(Lower)
                #print(QWE+"低於平均值:"+str(IntLower)+"%")
                average_compare[QWE]=IntLower*-1
               # sql_t="""UPDATE average_compare SET"""+QWE+"""="""+str(average_compare[QWE])+""" WHERE member_id="""+str(userid)
              #  connect(sql_t, "ai_assisted")
                #print(sql_t)
            else:#高於平均值
                Higher=((int(StdSpe[EWQ])-int(Tableword_Calculation_Results[QWE]))/int(StdSpe[EWQ]))*100
                IntHigher=int(Higher)
                #print(QWE+"高於平均值:"+str(IntHigher)+"%") 
                average_compare[QWE]=IntHigher
            

Save_Ave_sql="""UPDATE average_compare SET """#放置平均比對之sql語法
for acs in average_compare:
    Save_Ave_sql+=acs+"""="""+str(average_compare[acs])+""","""    #將比對結果放至sql語法中
Save_Ave_sql=Save_Ave_sql[:-1]#去除多餘逗號
Save_Ave_sql=Save_Ave_sql+""" WHERE account='"""+User_Account+"""';"""
#print(Save_Ave_sql)#存入資料庫
connect(Save_Ave_sql,"aispotlight")
"""---------------------以下是單一備審專業詞彙佔比的程式--------------------------------------------------------------------------------""" 
average=dict()
allsum=0
for fd in Field_Dic:
    for X in Total_Noun:#篩選出使用者所選取的學群對應的雙語資料庫中的Table
        for f in Front_End_Group:
            if X[0] == f.strip():
                if X[1] in fd:  
                    #Noun_Count=Noun_Count+int(dd[d])
                    #print(d+","+str(dd[d]))
                    average[fd]=Field_Dic[fd]
for a in average:
    allsum+=int(average[a])
#print(allsum)
for a in average:
    average[a]='{:.0%}'.format((int(average[a])/allsum))
    average[a]=str(average[a]).replace("%","")
#print(average)
SaveRatio="""UPDATE ratio SET """
for AV in average:
    SaveRatio+=AV+"""="""+str(average[AV])+""","""#將dict累加update語法
SaveRatio=SaveRatio[:-1]#去除最後逗點
SaveRatio=SaveRatio+""" WHERE account='"""+User_Account+"""';"""
#print(SaveRatio)
connect(SaveRatio,"aispotlight")

"""---------------------以下是TFIDF的程式---------------------"""
doc_all = [] #存放所有文本
filename=list()#存取檔名
accountlist=list()#存取帳號
tfidf=dict()#存放詞彙及權重
maxnum=10
remove=['，','。','、',' ','?']
path="C:/xampp/htdocs/NewDatabase/UPLOAD_FILE"
jieba.load_userdict("D:/data.txt")#讀取詞彙表
#print('詞彙表讀取完成')
allFileList = os.listdir(path)
for file in allFileList:
   with pdfplumber.open(os.path.join(path, file)) as temp:
        filetext=""
        for t in temp.pages:
            first_page=t
            filetext+=first_page.extract_text()
        for r in filetext:
                if r in remove:
                    filetext=filetext.replace(r,'')#讀取資料夾檔案並去除符號
        words=" ".join(jieba.lcut(filetext,cut_all=False,HMM=True))
        doc_all.append(words)
        print("cut:"+words)
        print("檔案:"+file+"讀取成功")
        filename.append(file)
ans=gettfidf(doc_all)#tfidf分析
for f in filename:
    accountlist.append(f.replace(".pdf",""))
ans=ans.T
alltext=len(doc_all)
print(alltext)
save="D:/wiki-data/analyzed"
memberid=0
wordcount=1
#accountid=0
for i in range(alltext):
    allw=list()
    print("第"+str(i+1)+"份文件:")
    ans=ans.sort_values([i],ascending=False)#根據不同col排序
    tdict=ans.iloc[0:maxnum]#取前10高的詞
    print(tdict[i])
    index=[]
    index=tdict[i].index
    indexnum=0
    for t in tdict[i]:
            wordcount=1
            print(index[indexnum]+":"+str(t))
            tfidf[index[indexnum]]=t
            indexnum+=1
    #print(tfidf.keys())
    text="""UPDATE tfidf SET """
    #tdidf=json.dumps(tfidf,ensure_ascii=False)
    #print(tdidf)
    for t in tfidf:
       text+="""word_"""+str(wordcount)+"""='"""+t+"""',"""+"""weight_"""+str(wordcount)+"""='"""+str(tfidf[t])+"""',"""
       wordcount+=1
    text=text[:-1]
    text=text+""" WHERE account='"""+accountlist[memberid]+"""';"""
    tfidf={}
    print(text)
    connect(text,"aispotlight")
    memberid+=1

#----------------------------------------------------------------------
# print("----------------do the cso-------------------------")
# test.GO_all(User_Account)
# savedb.save("D:/result")   
# print("over")

#-----------------------------------------------------------------------cso
#resumeAnalyze.py
import rdflib
import googletrans
from cso_classifier import CSOClassifier
import pandas as pd
from ckip_transformers.nlp import CkipWordSegmenter

class Calculator:
    def __init__(self):
        
        self.graph = rdflib.Graph()
        print("graph1")
        self.graph.parse("C:/Users/CSO.txt",format='xml')
        print("graph_fini")
        self.cso=CSOClassifier(modules = "both", enhancement = "first")
        self.cso_result={}
        self.translator=googletrans.Translator()
        self.article=""
        self.article_en=""
        self.topic={}
        self.topic_score={}
        self.topic_ratio={}
        self.di={}
        self.tList=[] #ckip form ...input of ws_driver is a List
        # load dictionary
        csvf=pd.read_csv('C:/Users/nuuuser/py/all_word.csv',sep='|')
        for i, j in zip(csvf['中'], csvf['英']):
            self.di[str(i)]=str(j)
        self.ws_driver = CkipWordSegmenter()
    def csoRun(self):
        #self.cso=CSOClassifier(modules = "both", enhancement = "first", explanation = True)
        self.cso_result=self.cso.run(self.article_en)
    def trans(self,text):
        self.article=text
        self.article_en=self.translator.translate(self.article).text
        #print(self.translator.dectect(self.article))
    def ckip(self,text):
        #zh -> cut -> dictionary ->en
        self.article=text
        self.tList[0]=text
        ws = self.ws_driver(self.tList)
        for sentence_ws in ws:
            for word_ws in sentence_ws:
                try:
                    self.article_en+=self.di[word_ws]
                except:
                    continue
    def reset(self):
        self.article_en=""
        self.topic_ratio={}
        self.topic={}
        self.topic_score={}
    def search(self,target):
        link=[]
        #n=rdflib.URIRef('https://cso.kmi.open.ac.uk/topics/computer_science')
        #link.append(n)
        query_a = """

        SELECT DISTINCT *
        WHERE {
        <https://cso.kmi.open.ac.uk/topics/"""
        query_b="""> ns0:superTopicOf ?object.
        OPTIONAL{?object ns0:preferentialEquivalent ?b.}
        }"""
        #what the OPTIONAL query does is search synonymous(equivalent)
        qres = self.graph.query(query_a+target+query_b)
        for row in qres:
            if row.b==None:       #if there isn't any equivalent, put the ?object in link[]. 
              link.append(row.object)
            else:
              if row.object==row.b: #if the ?object is equal to the equivalent, put the object in link[]. what it does is prevent two or more same ?object in link[]
                link.append(row.object)
        #print(link)
        return link
    def calculate(self):
        self.topic={}
        self.topic_score={}

        for i in self.cso_result['enhanced']:
          self.topic[i]=self.search(i.replace(" ","_"))  #search subTopic(?

        for enh_tpc in self.cso_result['enhanced']:
          self.topic_score[enh_tpc]=0

        #use cso syntacic or semantic result to calculate score in each enhanced result 
        for syn in self.cso_result['union']:
          for enh_tpc in self.cso_result['enhanced']:
            for tpc in self.topic[enh_tpc]:
              if "https://cso.kmi.open.ac.uk/topics/"+syn.replace(" ","_")==str(tpc):
                self.topic_score[enh_tpc]=self.topic_score[enh_tpc]+1
                print(syn+"  <belong to>  "+enh_tpc)
        #print(self.topic_score)
        for enh_tpc in self.cso_result['enhanced']:
          self.topic_ratio[enh_tpc]=str(self.topic_score[enh_tpc])+"/"+str(self.topic[enh_tpc].__len__())
        #print(self.topic_ratio)
    def execute(self,text):
        self.trans(text)
        self.csoRun()
        self.calculate()
    def execute_ckip(self, text):
        self.ckip(text) 
        self.csoRun()
        self.calculate()
        self.reset()
#---------------------------------------------------------------------
#pdf
import sys 
import importlib 
importlib.reload(sys) 
 

#from pdfminer.pdfparser import PDFParser,PDFDocument 
from pdfminer.pdfparser import PDFParser
from pdfminer.pdfdocument import PDFDocument
from pdfminer.pdfpage import PDFPage
from pdfminer.pdfinterp import PDFResourceManager, PDFPageInterpreter 
from pdfminer.converter import PDFPageAggregator 
from pdfminer.layout import * 
#from pdfminer.pdfinterp import PDFTextExtractionNotAllowed 
from pdfminer.pdfpage import PDFTextExtractionNotAllowed
 
''''' 
解析pdf檔案，獲取檔案中包含的各種物件 
''' 
 
# 解析pdf檔案函式 
def parse(pdf_dir, pdf_fn, txt_dir): 
  fp = open(pdf_dir+"/"+pdf_fn+".pdf", 'rb') # 以二進位制讀模式開啟 
  # 用檔案物件來建立一個pdf文件分析器 
  parser = PDFParser(fp) 
  # 建立一個PDF文件 
  #doc = PDFDocument()
  doc = PDFDocument(parser)
  # 連線分析器 與文件物件 
  parser.set_document(doc) 
  #doc.set_parser(parser) 
 
  # 提供初始化密碼 
  # 如果沒有密碼 就建立一個空的字串 
  #doc.initialize() 
 
  # 檢測文件是否提供txt轉換，不提供就忽略 
  if not doc.is_extractable: 
    raise PDFTextExtractionNotAllowed 
  else: 
    # 建立PDf 資源管理器 來管理共享資源 
    rsrcmgr = PDFResourceManager() 
    # 建立一個PDF裝置物件 
    laparams = LAParams() 
    device = PDFPageAggregator(rsrcmgr, laparams=laparams) 
    # 建立一個PDF直譯器物件 
    interpreter = PDFPageInterpreter(rsrcmgr, device) 
 
    # 用來計數頁面，圖片，曲線，figure，水平文字框等物件的數量 
    num_page, num_image, num_curve, num_figure, num_TextBoxHorizontal = 0, 0, 0, 0, 0 
    a=""
    # 迴圈遍歷列表，每次處理一個page的內容 
    #for page in doc.get_pages(): # doc.get_pages() 獲取page列表 
    for page in PDFPage.create_pages(doc):
      num_page += 1 # 頁面增一 
      interpreter.process_page(page) 
      # 接受該頁面的LTPage物件 
      layout = device.get_result() 
      #print(pdf_fn)
      txt_filename=txt_dir+"\\"+pdf_fn.replace('.pdf','.txt')
      #print(txt_filename)
      #txt_filename=txt_dir+"\\"+pdf_fn+".txt"
      
      
      
      for x in layout: 
        if isinstance(x,LTImage): # 圖片物件 
          num_image += 1 
        if isinstance(x,LTCurve): # 曲線物件 
          num_curve += 1 
        if isinstance(x,LTFigure): # figure物件 
          num_figure += 1 
        if isinstance(x, LTTextBoxHorizontal): # 獲取文字內容 
          num_TextBoxHorizontal += 1 # 水平文字框物件增一 
          # 儲存文字內容 
          results = x.get_text() 
          a+=results
          
      
    f=open(txt_filename, 'w',encoding='utf-8')
    f.writelines(a) 
    f.close()
    # print(a)
    # print('物件數量：\n','頁面數：%s\n'%num_page,'圖片數：%s\n'%num_image,'曲線數：%s\n'%num_curve,'水平文字框：%s\n' 
       # %num_TextBoxHorizontal) 
    print("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
    return a
#--------------------------------------------------------------------
#test.py
import os

# from cso_classifier import CSOClassifier as cc
# cc.setup()
def pdf_txt(fn):
    #讀取檔案
    txt_dir="./pdf_txt"
    if not os.path.isdir(txt_dir):
        os.mkdir(txt_dir)
    pdf_file_path="C:/xampp/htdocs/NewDatabase/UPLOAD_FILE"
    pdf_filenames=os.listdir(pdf_file_path)
    #for fn in pdf_filenames:
    parse(pdf_file_path,fn,txt_dir)
    
def GO_all(file_name):
    pdf_txt(file_name)#讀取檔案
    txt_dir="./pdf_txt"
    txt_filenames=os.listdir(txt_dir)
    outf_dir="./result"
    if not os.path.isdir(outf_dir):
        os.mkdir(outf_dir)
    for txt_fn in txt_filenames:
        text=""
        txtf=open(txt_dir+"/"+txt_fn,'r',encoding='utf-8')
        text=txtf.read()
        c=Calculator()
        c.execute(text)
        outf=open(outf_dir+"/result_"+txt_fn,"w")
        for i in c.topic_ratio:
            outf.write(i+":")
            outf.write(c.topic_ratio[i])
            outf.write("\n")
        outf.close()
#GO_all()

def do_one(file_name):
#只分析一份
    #pdf文字檔目錄
    txt_dir="./pdf_txt"
    if not os.path.isdir(txt_dir):
        os.mkdir(txt_dir)
    #pdf檔目錄
    pdf_file_path="./pdf"
    #pdf轉文字
    parse(pdf_file_path,file_name,txt_dir)
    #分析結果文字檔目錄
    outf_dir="./result"
    if not os.path.isdir(outf_dir):
        os.mkdir(outf_dir)
    text=""
    txtf=open(txt_dir+"/"+file_name,'r',encoding='utf-8')
    text=txtf.read()
    c=Calculator()
    c.execute(text)
    outf=open(outf_dir+"/result_"+file_name+".txt","w")
    for i in c.topic_ratio:
        outf.write(i+":")
        outf.write(c.topic_ratio[i])
        outf.write("\n")
    outf.close()
def do_one_nofile(file_name):
#只分析一份
    #pdf文字檔目錄
    txt_dir="C:/Users/nuuuser/py/pdf_txt"
    if not os.path.isdir(txt_dir):
        os.mkdir(txt_dir)
    #pdf檔目錄
    pdf_file_path="C:/xampp/htdocs/NewDatabase/UPLOAD_FILE"
    #pdf轉文字
    text=parse(pdf_file_path,file_name,txt_dir)
    #分析結果文字檔目錄
    """
    outf_dir="./result"
    if not os.path.isdir(outf_dir):
        os.mkdir(outf_dir)
        """
    text=""
    txtf=open(txt_dir+"/"+file_name,'r',encoding='utf-8')
    text=txtf.read()
    print("1calculator")
    c=Calculator()
    print("cal finished")
    c.execute(text)
    output_text_nof=""
    for i in c.topic_ratio:
        x_112=i+":"+c.topic_ratio[i]+"\n"
        print(x_112)
        output_text_nof=output_text_nof+x_112
    return output_text_nof


#-----------------------------------------------------------------
#savedb.py
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
            print(savesql)
def save_one(user_account,content):
    cut=content.split("\n")
    savesql="""UPDATE cso_classifier SET """
    i=1
    for c in cut:
        if i>20:break
        if c !="":
            sp=c.split(":")
            #print(sp[0]+" and "+sp[1])
            savesql+="""word_"""+str(i)+"""='"""+sp[0]+"""',weight_"""+str(i)+"""='"""+sp[1]+"""',"""
            i=i+1

    savesql=savesql[:-1]
    savesql+=""" WHERE account='"""+user_account+"""';"""
    connect(savesql)
#------------------------------------------------------------
print("----------------do the cso-------------------------")
out_112=do_one_nofile(User_Account)
#print("cso result   "+out_112)
save_one(User_Account,out_112)   
print("over")