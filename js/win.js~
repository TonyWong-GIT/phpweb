$(function(){
			$('#dd').dialog({
				toolbar:[{
					text:'Add',
					iconCls:'icon-add',
					handler:function(){
						alert('add')
					}
				},'-',{
					text:'Save',
					iconCls:'icon-save',
					handler:function(){
						alert('save')
					}
				}],
				buttons:[{
					text:'Ok',
					iconCls:'icon-ok',
					handler:function(){
						alert('ok');
					}
				},{
					text:'Cancel',
					handler:function(){
						$('#dd').dialog('close');
					}
				}]
			});
		});
		function open1(){
			$('#dd').dialog('open');
		}
		function close1(){
			$('#dd').dialog('close');
		}
/*
function test(){
			$('#test').window('open');
		}

function open_url(){
			$('#open_url').window('open',{
				width: 600,
				height: 300
			});
		}
