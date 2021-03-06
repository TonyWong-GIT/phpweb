<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html lang="zh-CN">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title>與情</title>
<link rel="stylesheet" type="text/css" href="css/easyui.css" />
<link rel="stylesheet" type="text/css" href="css/page.css" />
<link rel="stylesheet" type="text/css" href="css/icon.css" />

<script type="text/javascript"  language="JavaScript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript"  language="JavaScript" src="js/jquery.easyui.min.js"></script>
<script type="text/javascript"  language="JavaScript" src="js/page.js"></script>
<script type="text/javascript"  language="JavaScript" src="js/win.js"></script>
<!--<script type="text/javascript"  language="JavaScript" src="js/pagination.js"></script>-->
<script type="text/javascript">
function add(){
  task = document.getElementById('task-info').value;
  type = document.getElementById('type-info').value;
  keyword = document.getElementById('keyword-info').value;
  if(confirm("任务名: "+task+"\n子任务名: "+type+"\n关键字: "+keyword+"\n你确信要加入？")){
  	window.location.href="keyword_add.php?task="+task+"&type="+type+"&keyword="+keyword;
  }
};
function del(){
  task = document.getElementById('task-info').value;
  type = document.getElementById('type-info').value;
  keyword = document.getElementById('keyword-info').value;
  if(confirm("任务名: "+task+"\n子任务名: "+type+"\n关键字: "+keyword+"\n你确信要删除？")){
  	window.location.href="keyword_del.php?task="+task+"&type="+type+"&keyword="+keyword+"";
  }
};
function update(){
  task = document.getElementById('task-info').value;
  type = document.getElementById('type-info').value;
  keyword = document.getElementById('keyword-info').value;
  if(confirm("任务名: "+task+"\n子任务名: "+type+"\n关键字: "+keyword+"\n你确信要修改？")){
  	window.location.href="keyword_add.php?task="+task+"&type="+type+"&keyword="+keyword+"";
  }
};
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<div class="setup-window">

	<div class="setup-window-task">
	<div class="setup-window-task-head">任务</div>
	<div class="setup-window-task-content">
<?php
	echo '<ul  class="easyui-tree" data-options="animate:true,dnd:true">';
	echo '<li><span>任务列表</span><ul>';
 
	$con = mysql_connect("localhost","root","1234");
	  if (!$con)
	  {
	  	die('Could not connect: ' . mysql_error());
	  }

	  mysql_select_db("inet", $con);
	  mysql_query("set names utf8");     //php解决中文显示??????
	
	$sql_keyword_task = 'select distinct `task` from `keyword` where 1';
	$res_keyword_task = mysql_query($sql_keyword_task);
	
	while($row = mysql_fetch_array($res_keyword_task)){
		echo $row['task'];
		echo '<li data-options="state:\'closed\'">';
		echo '<span><a href="#" onclick="document.getElementById(\'keyword-info\').value=\'\';
						 document.getElementById(\'type-info\').value=\'\';
						 document.getElementById(\'task-info\').value=\''.$row['task'].'\'">'.$row['task'].'</a></span>';		
		//echo '<span><a href="#" onclick=\'change-task("'.$row['task'].'")\'>'.$row['task'].'</a></span>';
		echo '<ul>';
		$sql_keyword_type = 'select distinct `type` from `keyword` where `task`=\''.$row['task'].'\'';
		
		$res_keyword_type = mysql_query($sql_keyword_type);
		while($row1 = mysql_fetch_array($res_keyword_type)){
			$keyword_string = '';
			$sql_keyword_keyword = 'select distinct `keyword` from `keyword` where `task`=\''.$row['task'].'\'and `type`=\''.$row1['type'].'\'';		
			//echo '<li>select distinct `keyword` from `keyword` where `task`=\''.$row['task'].'\'and `type`=\''.$row1['type'].'\'</li>';
			$res_keyword_keyword = mysql_query($sql_keyword_keyword);
			while($row2 = mysql_fetch_array($res_keyword_keyword)){
				$keyword_string = $row2['keyword'];
			}
			//echo $keyword_string;//null
			echo '<li><span><a href="#" onclick="document.getElementById(\'keyword-info\').value=\''.$keyword_string.'\';
				document.getElementById(\'type-info\').value=\''.$row1['type'].'\';
				document.getElementById(\'task-info\').value=\''.$row['task'].'\'">'.$row1['type'].'</a></span></li>';
		}
		echo '</ul>';
		echo '</li>';
		
	}
	echo '</ul></li></ul>';

?>
	</div>
	</div>

		<div  class="setup-window-keyword">
			<div class="setup-window-keyword-head">关键字</div>
			<div class="setup-window-keyword-content">
			<div class="setup-window-keyword-content-keyword">

				<p>任务:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="task_input" id="task-info" type="text" class="setup-task-input-text"></p>	
				
				<p>子任务:&nbsp;<input name="type_input" id="type-info" type="text" class="setup-type-input-text" ></p>
				
				<p>关键字:&nbsp;<textarea name="keyword_input" id="keyword-info" class="setup-keyword-input-text"></textarea></p>	
		
				<!--- 此处显示关键字列表class为keyword_info -->
			</div>

			<div class="setup-window-keyword-content-setup">
				<p><a href="#" class="easyui-linkbutton" onclick="add()">添加</a></p>
				<p><a href="#" class="easyui-linkbutton" onclick="del()">删除</a></p>
				<!--<p><a href="#" class="easyui-linkbutton" onclick="update()">修改</a></p>-->
			</div>
			</div>
		</div>



	<div class="setup-window-info">
		<div class="setup-window-info-head">任务概要</div>
		<div class="setup-window-info-content">
		<p>天珣--VPN信息询查系统:

		天珣--VPN信息询查系统具有对互联网全访位舆情分析能力，可以全面满足公司企业政府机关的各方需要。 哈工大（威海）网络技术研究所（Institute of Network Technology，INET），简称网络所，创建于2010年，是在我国著名领域专家的指导与帮助下，以及学校的大力支持下成立的。 研究所现有在校全职教师6人，其中硕士生指导教师2人，在校学生40余人。研究所主要研究方向包括计算机网络信息对抗，网络与信息安全，物联网安全技术，虚拟化与可信计算，网络媒体计算等，研究基础雄厚，成果卓越。近年来，该研究所共承担国家计划项目2项、国家自然科学基金1项、省科技攻关项目1项，省自然科学基金项目1项，横向课题10余项。在国内外重要期刊发表文章40余篇。业已形成一支目标明确，技术基础过硬，能够攻坚的青年科研队伍。
		</p>
		<!--  此处显示系统盘配置的相关信息 -->
		</div>
	</div>

	</div>
</body>
</html>
