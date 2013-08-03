$(function(){
			$('#datagrid').datagrid({
				iconCls:'icon-save',
				width:1086,
				height:523,
				nowrap: true,
				autoRowHeight: false,
				striped: true,
				collapsible:true,
				url:'json/datagrid_data.json',
				sortName: 'code',
				sortOrder: 'desc',
				remoteSort: false,
				idField:'code',
				
				frozenColumns:[[
	                {title:'URL',field:'url',width:300,align:'center',}
				]],
				columns:[[
					{field:'title',title:'标题',width:300,align:'center', rowspan:2,	
					}
				],[
					{field:'time',title:'时间',width:190,align:'center',sortable:true,
						sorter:function(a,b){
							return (a>b?1:-1);
						}},
					{field:'source',title:'来源',width:140,rowspan:2,align:'center',
						
					},
					{field:'quanz',title:'权重',width:130,rowspan:2,align:'center',},
				]],
				pagination:true,
				rownumbers:true,
				
			});
			var p = $('#datagrid').datagrid('getPager');
			$(p).pagination({
				onBeforeRefresh:function(){
					alert('before refresh');
				}
			});
		});
		function resize(){
			$('#datagrid').datagrid('resize', {
				width:700,
				height:400
			});
		}
		function getSelected(){
			var selected = $('#datagrid').datagrid('getSelected');
			if (selected){
				alert(selected.code+":"+selected.name+":"+selected.addr+":"+selected.col4);
			}
		}
		function getSelections(){
			var ids = [];
			var rows = $('#datagrid').datagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].code);
			}
			alert(ids.join(':'));
		}
		function clearSelections(){
			$('#datagrid').datagrid('clearSelections');
		}
		function selectRow(){
			$('#datagrid').datagrid('selectRow',2);
		}
		function selectRecord(){
			$('#datagrid').datagrid('selectRecord','002');
		}
		function unselectRow(){
			$('#datagrid').datagrid('unselectRow',2);
		}
		function mergeCells(){
			$('#datagrid').datagrid('mergeCells',{
				index:2,
				field:'addr',
				rowspan:2,
				colspan:2
			});
		}
