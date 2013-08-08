#!/usr/bin/python
# -*- coding: utf-8 -*-
#multiple.py

import re
import sys
import time
import chardet
import urllib2,httplib


url_cmp = 'http://www.google.com.hk/search?q='
nextpage = '&start='
pagenumber = 0
re_data = re.compile(r'<h3 class="r"><a href="[\s\S]*?" target="_blank">([\s\S]*?)<cite.*?>([\s\S]*?)</cite>.*?<span.*?>([\s\S]*?)</li>',re.S)



def search(keyword,fw,engines = ['google','biying','baidu','soso'],page = 10):
	print engines
	while len(engines)!=0:
		set_params(keyword,engines[0])
		do_search(keyword,engines[0],fw,page)
		print engines[0]
		del engines[0]

def set_params(keyword,host):
	global url_cmp,nextpage,pagenumber,re_data
	if host == 'google':
		pagenumber = 0
		nextpage = '&start='
		url_cmp = 'http://www.google.com.hk/search?q='+ keyword + nextpage 
		re_data = re.compile(r'<h3 class="r"><a href="[\s\S]*?" target="_blank">([\s\S]*?)<cite.*?>([\s\S]*?)</cite>.*?<span.*?>([\s\S]*?)</li>',re.S)
	if host == 'baidu':
		pagenumber = 0
		nextpage = '&pn='
		url_cmp = 'http://www.baidu.com/s?wd='+keyword+nextpage
		re_data = re.compile(r'<table.*?id="\d*?".*?<a.*?href="([\s\S]*?)".*?>([\s\S]*?)</a>.*?</h3>([\s\S]*?)</td>',re.S)
		#re_data = re.compile(r'<h3 class="t"><a href="([\s\S]*?)".*?>([\s\S]*?)</a>.*?</h3>([\s\S]*?)</table>',re.S)
	if host == 'biying':
		pagenumber = 1
		nextpage = '&first='
		url_cmp = 'http://cn.bing.com/search?q=' + keyword + nextpage
		re_data = re.compile(r'<h3><a href="([\s\S]*?)".*?>([\s\S]*?)</a>.*?<p>([\s\S]*?)</p>',re.S)
	if host == 'soso':
		pagenumber = 1
		nextpage = '&pg='
		url_cmp = 'http://www.soso.com/q?w=' + keyword + nextpage
		re_data = re.compile(r'<h3><a href="([\s\S]*?)".*?>([\s\S]*?)</a>([\s\S]*?)</p>',re.S)

def do_search(keyword,host,fw,page):
	pagenumber = 0
	while True:
		url = url_cmp + str(pagenumber)
		print url
		if host == 'google':
			data = google_data(pagenumber,keyword)
		else:
			data = get_page_data(url)

		if host == 'soso':
			data = data.decode('gbk','ignore').encode('utf-8')
		if host == 'google':
			data = data.decode('big5','ignore').encode('utf-8')

		mine_data = re_data.findall(data)
		print len(mine_data)
		if len(mine_data)==0:
			break

		if host == 'google':
			while(mine_data):
				title_words = re.sub('<[\s\S]*?>','',mine_data[0][0])
				cite_words  = re.sub('<[\s\S]*?>','',mine_data[0][1])
				text_words  = re.sub('<[\s\S]*?>','',mine_data[0][2])
				#print   title_words,'\n',cite_words,'\n',text_words,'\n\n\n'
				fw.write(title_words+'\n'+cite_words+'\n'+text_words+'\n\n\n')
				del mine_data[0]
			if pagenumber >= 100:
				return
			pagenumber = pagenumber + 10
			continue

		while (mine_data):
			head_url = mine_data[0][0]
			if host == 'google':
				head_url = re.sub('<.*?>','',mine_data[0][0])

			if host == 'baidu':
				head_url_cmp = mine_data[0][0]
				head_url_cmp = head_url_cmp[7:]
				num          = head_url_cmp.find('/')
				host_cmp     = head_url_cmp[0:num]
				link         = head_url_cmp[num:]
				if host_cmp == 'www.baidu.com':
					head_url     = url_change(host_cmp,link)

			title = mine_data[0][1]
			title = re.sub(r'<[\s\S]*?>','',title)
			
			extra_data = mine_data[0][2]
			extra_data = re.sub('<style>[\s\S]*?</style>','',extra_data)
			extra_data = re.sub('<script>[\s\S]*?</script>','',extra_data)
			extra_data = re.sub('<[\s\S]*?>','',extra_data)
				
			#print mine_data[0][0],'\n',title,'\n',mine_data[0][2],'\n\n'
			fw.write(mine_data[0][0]+ '\n'+head_url+'\n'+title+'\n'+extra_data+'\n\n\n')
			del mine_data[0]
		
		if pagenumber >= 10* page:
		 	return
		if (host == 'soso') & (pagenumber >=page) :
			return
		if host == 'soso':
			pagenumber = pagenumber + 1
		else:
			pagenumber = pagenumber + 10
		
def get_page_data(page):
	page_cmp = urllib2.Request(page)
	get_page = urllib2.urlopen(page_cmp)
	data     = get_page.read()
	get_page.close()
	return data

def google_data(pagenumber,keyword):
	link = '/search?&q=' + keyword + '&start=' + str(pagenumber)
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
  fw = file('mul','w')
  search('你好',fw,['google','biying','baidu','soso'],10)
  fw.close()
