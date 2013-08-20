#!/usr/bin/python
# -*- coding: utf-8 -*-
#multiple.py

import re
import sys
import time
import chardet
import urllib2,httplib
import MySQLdb as mdb

reload(sys)
sys.setdefaultencoding( "utf-8" )



url_cmp = 'http://www.google.com.hk/search?q='
nextpage = '&start='
pagenumber = 0
re_data = re.compile(r'<h3 class="r"><a href="[\s\S]*?" target="_blank">([\s\S]*?)<cite.*?>([\s\S]*?)</cite>.*?<span.*?>([\s\S]*?)</li>',re.S)



def search(table_task, table_type, table_keyword, engines = '谷歌',page = 10):
	print engines
	set_params(table_keyword,engines)
	do_search(table_task, table_type, table_keyword,engines,page)

def set_params(table_keyword,host):
	global url_cmp,nextpage,pagenumber,re_data
	pagenumber = 0
	nextpage = '&start='
	keyword_rel = table_keyword.replace(' ','+')
	url_cmp = 'http://www.google.com.hk/search?q='+ keyword_rel + nextpage 
	re_data = re.compile(r'<h3 class="r"><a href="[\s\S]*?" target="_blank">([\s\S]*?)<cite.*?>([\s\S]*?)</cite>.*?<span.*?>([\s\S]*?)</li>',re.S)
	

def do_search(table_task, table_type, table_keyword,host,page):
	table_time = time.strftime('%Y%m%d')
	pagenumber = 0
	cur_timerecord.execute("insert into `timerecord`(`keyword`,`time`,`comefrom`,`type`,`task`) values('%s','%s','%s','%s','%s')"%(str(table_keyword), str(table_time), str(host), str(table_type), str(table_task)))
	table_number = 0
	while True:
		url = url_cmp + str(pagenumber)
		print url

		data = google_data(pagenumber,table_keyword)
		

		data = data.decode('big5','ignore').encode('utf-8')

		mine_data = re_data.findall(data)
		print len(mine_data)
		if len(mine_data)==0:
			break


		while(mine_data):
			title_words = re.sub('<[\s\S]*?>','',mine_data[0][0])
			cite_words  = re.sub('<[\s\S]*?>','',mine_data[0][1])
			text_words  = re.sub('<[\s\S]*?>','',mine_data[0][2])
			table_number += 1
			cur_webpage.execute('insert into `webpage`(`title`,`url`,`keyword`,`time`,`comefrom`,`number`,`type`,`task`,`flag_whitelist`) values("%s","%s",\'%s\',"%s","%s",%d,"%s","%s",%d)'%(str(title_words), str(cite_words), str(table_keyword), str(table_time), str(host), table_number, str(table_type), str(table_task), 0 ))
			print mine_data[0][0],'\n',title_words,'\n\n\n'
			del mine_data[0]
		if pagenumber >= page*10:
			return
		pagenumber = pagenumber + 10
		time.sleep(5)
		continue

		
		
def get_page_data(page):
	page_cmp = urllib2.Request(page)
	get_page = urllib2.urlopen(page_cmp)
	data     = get_page.read()
	get_page.close()
	return data

def google_data(pagenumber,table_keyword):
	table_keyword = table_keyword.replace(' ','+')
	link = '/search?&q=' + table_keyword + '&start=' + str(pagenumber)
	conn = httplib.HTTPConnection('www.google.com.hk')
	conn.request('GET',link)
	page = conn.getresponse()
	return page.read()


def url_change(host,link):
	conn = httplib.HTTPConnection(host)
	conn.request('GET',link)
	url_change_page = conn.getresponse()
	url_change_data = url_change_page.read()
	conn.close()
	url_change_re = re.compile(r'<a\shref="([\s\S]*?)">',re.S)
	url_change_result = url_change_re.search(url_change_data)
	return url_change_result.group(1)
	
if __name__ == '__main__':
  conn=mdb.connect(host='127.0.0.1',user='root',passwd='1234',db='inet',port=3306,use_unicode=True,charset="utf8")
  
  cur_webpage = conn.cursor()
  cur_timerecord = conn.cursor()
  cur_keyword = conn.cursor()
  cur_keyword.execute('select * from `keyword` where 1')
  rows_keyword = cur_keyword.fetchall()
  print "开始检索。。。\n" 
  #for row_keyword in rows_keyword:
  search('虚拟专网', '虚拟专网软件', '虚拟专用网  软件下载 美国','谷歌',10)
  conn.commit()
  cur_webpage.close()
  cur_keyword.close()
  cur_timerecord.close()
  conn.close()





