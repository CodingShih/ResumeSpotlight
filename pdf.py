# -*- coding: utf-8 -*-
"""
Created on Mon Mar 21 18:10:43 2022

@author: 010
"""

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

 
 
if __name__ == '__main__': 
  pdf_path = r'長者照護C803.pdf' #pdf檔案路徑及檔名 
  parse(pdf_path) 