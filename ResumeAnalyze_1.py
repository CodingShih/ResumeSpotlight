import rdflib
import googletrans
from cso_classifier import CSOClassifier
import pandas as pd
from ckip_transformers.nlp import CkipWordSegmenter

class Calculator:
    def __init__(self):
        self.graph = rdflib.Graph()
        self.graph.parse("CSO.txt",format='xml')
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
        csvf=pd.read_csv('all_word.csv',sep='|')
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

#____________________ example
# abc=Calculator()
# ret="""各行各業都需要資訊管理,資訊力量之大讓我震驚
# 我發現資訊管理的確是無所不在,各個產業皆需要資訊,如:金融業必須倚靠資訊才能夠快速的
# 掌握世界金融資訊:因為金融業,若沒有好的資訊系統,如何協助顧客存資金?我在學校內工讀
# 時,也明確知道學校必須倚靠資訊才能夠正確的整合學生資料,因為學校若無良好的資訊系統,上萬
# 名學生資料該如何統整且容易尋找?甚至在經者做決策時,也必須靠資訊的智慧決策判斷。
# 我上網找資料時,這些資料更告訴我,在企業中,以利用資料探勘的方式來分析資訊,如:客
# 戶管理系統,提供給客戶更準確的服務利用企業資源規劃系統,以替企業帶來流程上的改進。瞭
# 解愈多,就愈讓我震驚資訊力量之大。也更加深我想報考資訊管理科系的原因 ㅇ个
# 市面開始通行的比特幣、支付寶更是如此,運用網際網路的資源與方便性,推廣其貨幣,而當令
# 流行地區非常之多,讓我為之驚嘆。希望能進入資訊管理學系,學習應該具備的知識"""
# abc.execute(ret)
#____________________ others 
#def search_uriref(target):
#  link=[]
#  query_a = """
#
# SELECT DISTINCT *
#  WHERE {
#   """
#  query_b=""" ns0:superTopicOf ?object.
#    OPTIONAL{?object ns0:preferentialEquivalent ?b.}
#  }"""
#  qres = g.query(query_a+target+query_b)
#  for row in qres:
#    if row.b==None:
#      link.append(row.object)
#    else:
#      if row.object==row.b: 
#        link.append(row.object)
#  return link
#all=[]
#n=rdflib.URIRef('https://cso.kmi.open.ac.uk/topics/computer_science')
#all.append(n)
#for i in all:
#  x=i.n3()
#  res=search_uriref(x)
#  for j in res:
#    if j in all:
#      continue
#    else:
#      all.append(j)
#print(all.__len__())