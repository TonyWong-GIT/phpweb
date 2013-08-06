#!/usr/bin/env python
# -*- coding: utf-8 -*-
import MySQLdb as mdb
import sys
from datetime import *

reload(sys)
sys.setdefaultencoding( "utf8" )

#print sys.getdefaultencoding()

conn=mdb.connect(host='127.0.0.1',user='root',passwd='1234',db='inet',port=3306,use_unicode=True,charset="utf8")

source_list = ('谷歌','百度','搜搜')

cur_del_special = conn.cursor()
cur_del_special.execute('DELETE FROM `specialurl` WHERE 1')

cur_select_metaetc = conn.cursor()
cur_select_metaetc.execute('select * from `metaetc` where 1')
row_select_metaetc = cur_select_metaetc.fetchall()
day_select_metaetc = row_select_metaetc[0][0]

today = date.today()
oneday = timedelta(days=1)
sixthday = (today-oneday*(day_select_metaetc-1)).strftime('%Y%m%d')
startday = (today-oneday*day_select_metaetc).strftime('%Y%m%d')
today = today.strftime('%Y%m%d')
 
cur_select_timerecord_type = conn.cursor()
cur_select_timerecord_keyword = conn.cursor()
cur_select_timerecord_count = conn.cursor()
cur_select_webpage_url = conn.cursor()
cur_select_webpage_count = conn.cursor()
cur_select_webpage_all = conn.cursor()
cur_insert_special_all = conn.cursor()

cur_select_timerecord_task = conn.cursor()
cur_select_timerecord_task.execute('select distinct `task` from `timerecord` where `time` >= "%s" and `time` <= "%s"'%(startday,today))
#print 'select distinct `task` from `timerecord` where `time` >= "%s" and `time` <= "%s"'%(startday,today)
rows_select_timerecord_task = cur_select_timerecord_task.fetchall()

for row_select_timerecord_task in rows_select_timerecord_task:
    cur_select_timerecord_type.execute('select distinct `type` from `timerecord` where `task`="%s" and `time` >= "%s" and `time` <= "%s"'%(row_select_timerecord_task[0],startday,today))
#    print 'select distinct `type` from `timerecord` where `task`="%s" and `time` >= "%s" and `time` <= "%s"'%(row_select_timerecord_task[0],startday,today)
    rows_select_timerecord_type = cur_select_timerecord_type.fetchall()

    for row_select_timerecord_type in rows_select_timerecord_type:
        cur_select_timerecord_keyword.execute('select distinct `keyword` from `timerecord` where `task`="%s" and `type`="%s" and `time` >= "%s" and `time` <= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0],startday,today))
#        print 'select distinct `keyword` from `timerecord` where `task`="%s" and `type`="%s" and `time` >= "%s" and `time` <= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0],startday,today)
        rows_select_timerecord_keyword = cur_select_timerecord_keyword.fetchall()

        for row_select_timerecord_keyword in rows_select_timerecord_keyword:
            for source in source_list:
                cur_select_timerecord_count.execute('select count(*) from `timerecord` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0],row_select_timerecord_keyword[0],source,startday,today))
#                print 'select count(*) from `timerecord` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0],row_select_timerecord_keyword[0],source,startday,today)
                rows_select_timerecord_count = cur_select_timerecord_count.fetchall()

                download_number = rows_select_timerecord_count[0][0]
                cur_select_webpage_url.execute('select distinct `url` from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0],row_select_timerecord_keyword[0],source,startday,today))
#                print 'select distinct `url` from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `time` >= "%s" and `time`<= "%s" and `flag_whitelist` != 1'%(row_select_timerecord_task[0],row_select_timerecord_type[0],row_select_timerecord_keyword[0],source,startday,today)
                rows_select_webpage_url = cur_select_webpage_url.fetchall()

                for row_select_webpage_url in rows_select_webpage_url:
                    cur_select_webpage_count.execute('select count(*) from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `url`="%s" and `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0], row_select_timerecord_keyword[0],source,row_select_webpage_url[0],startday,today))
#                    print 'select count(*) from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `url`="%s" and `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0], row_select_timerecord_keyword[0],source,row_select_webpage_url[0],startday,today)
                    rows_select_webpage_count = cur_select_webpage_count.fetchall()
                    url_count = rows_select_webpage_count[0][0]
                    if download_number != url_count:
                        cur_select_webpage_all.execute('select * from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `url`="%s" and `time` >= "%s" and `time`<= "%s" '%(row_select_timerecord_task[0],row_select_timerecord_type[0], row_select_timerecord_keyword[0],source,row_select_webpage_url[0],startday,today))
#                        print 'select * from `webpage` where `task`="%s" and `type`="%s" and `keyword`="%s" and `comefrom`="%s" and `url`="%s" and `time` >= "%s" and `time`<= "%s"'%(row_select_timerecord_task[0],row_select_timerecord_type[0], row_select_timerecord_keyword[0],source,row_select_webpage_url[0],startday,today)
                        rows_select_webpage_all = cur_select_webpage_all.fetchall()
		        #此处需注意：若同一关键字查询时，同一url出现了两次，则只统计第一次出现的number
                        cur_insert_special_all.execute('insert into `specialurl`(`title`,`url`,`keyword`,`time`,`comefrom`,`number`,`type`,`task`,`flag_whitelist`) values("%s","%s","%s","%s","%s",%d,"%s","%s",%d)'%(rows_select_webpage_all[0][0],rows_select_webpage_all[0][1],rows_select_webpage_all[0][2], rows_select_webpage_all[0][3],rows_select_webpage_all[0][4],rows_select_webpage_all[0][5],rows_select_webpage_all[0][6],rows_select_webpage_all[0][7],rows_select_webpage_all[0][8]))
#                        print 'insert into `specialurl`(`title`,`url`,`keyword`,`time`,`comefrom`,`number`,`type`,`task`) values("%s","%s","%s","%s","%s",%d,"%s","%s")'%(rows_select_webpage_all[0][0],rows_select_webpage_all[0][1],rows_select_webpage_all[0][2], rows_select_webpage_all[0][3],rows_select_webpage_all[0][4],rows_select_webpage_all[0][5],rows_select_webpage_all[0][6],rows_select_webpage_all[0][7])

conn.commit()
cur_select_timerecord_type.close()
cur_select_timerecord_keyword.close()
cur_select_timerecord_count.close()
cur_select_webpage_url.close()
cur_select_metaetc.close()
cur_select_webpage_count.close()
cur_select_webpage_all.close()
cur_insert_special_all.close()
cur_del_special.close()
cur_select_timerecord_task.close()
conn.close()











