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
sys.setdefaultencoding( "utf8" )

pagenumber = 0
nextpage = ''
url_cmp = ''
re_data = re.compile(r'<h3><a href="([\s\S]*?)".*?>([\s\S]*?)</a>([\s\S]*?)</p>',re.S)

def search(table_task, table_type, table_keyword, engines = '搜搜',page = 10):
	print engines
	set_params(table_keyword,engines)
	do_search(table_task, table_type, table_keyword,engines,page)

def set_params(table_keyword,host):
	global url_cmp,nextpage,pagenumber,re_data
	pagenumber = 1
	nextpage = '&pg='
	keyword_rel = table_keyword.replace(' ','%20')
	url_cmp = 'http://www.soso.com/q?w=' + keyword_rel + nextpage
	re_data = re.compile(r'<h3><a href="([\s\S]*?)".*?>([\s\S]*?)</a>([\s\S]*?)</p>',re.S)

def do_search(table_task, table_type, table_keyword,host,page):
	table_time = time.strftime('%Y%m%d')
	pagenumber = 0
	#cur_timerecord.execute("insert into `timerecord`(`keyword`,`time`,`comefrom`,`type`,`task`) values('%s','%s','%s','%s','%s')"%(str(table_keyword), str(table_time), str(host), str(table_type), str(table_task)))
	table_number = 0
	while True:
		url = url_cmp + str(pagenumber)
		print url
		
		data = get_page_data(url)

		
		data = data.decode('gbk','ignore').encode('utf8')
		

		mine_data = re_data.findall(data)
		print len(mine_data)
		if len(mine_data)==0:
			break


		while (mine_data):
			head_url = mine_data[0][0]
			table_number += 1;
			title = mine_data[0][1]
			title = re.sub(r'<[\s\S]*?>','',title)
			
			extra_data = mine_data[0][2]
			extra_data = re.sub('<style>[\s\S]*?</style>','',extra_data)
			extra_data = re.sub('<script>[\s\S]*?</script>','',extra_data)
			extra_data = re.sub('<[\s\S]*?>','',extra_data)
				
			#print mine_data[0][0],'\n',title,'\n',mine_data[0][2],'\n\n'
			#cur_webpage.execute('insert into `webpage`(`title`,`url`,`keyword`,`time`,`comefrom`,`number`,`type`,`task`,`flag_whitelist`) values("%s","%s",\'%s\',"%s","%s",%d,"%s","%s",%d)'%(str(title), str(mine_data[0][0]), str(table_keyword), str(table_time), str(host), table_number, str(table_type), str(table_task),0 ))
			print mine_data[0][0].decode('utf8')
			print 'insert into `webpage`(`title`,`url`,`keyword`,`time`,`comefrom`,`number`,`type`,`task`,`flag_whitelist`) values("%s","%s",\'%s\',"%s","%s",%d,"%s","%s",%d)'%(str(title), str(mine_data[0][0]), str(table_keyword), str(table_time), str(host), table_number, str(table_type), str(table_task),0 )
			#print mine_data[0][0],'\n',title,'\n\n\n'
			del mine_data[0]
		
		if (pagenumber >=page) :
			return
		pagenumber = pagenumber + 1

		
def get_page_data(page):
	page_cmp = urllib2.Request(page)
	get_page = urllib2.urlopen(page_cmp)
	data     = get_page.read()
	get_page.close()
	return data



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
  search('体育新闻', '篮球', '体育用品-在线销售卓足球,篮球,排球,网球,羽毛球,乒乓球,游泳-亚马逊','搜搜',1)
  conn.commit()
  cur_webpage.close()
  cur_keyword.close()
  cur_timerecord.close()
  conn.close()




