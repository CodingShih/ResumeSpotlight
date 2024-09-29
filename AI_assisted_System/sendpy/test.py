# -*- coding: utf-8 -*-
"""
Spyder Editor

This is a temporary script file.
"""
import os
import sys
import time
t=time.localtime(time.time())
clock=time.asctime(t)
message_=sys.argv
f = open('mess.txt',mode='w+',encoding='utf-8-sig')

f.write(clock+"\n")
for m in message_:
    if(m!="test.py"):
        f.write(m+" ")
f.close()
print(clock)
os._exit(0)