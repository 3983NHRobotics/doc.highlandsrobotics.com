var xmlHttp=createXmlHttpRequestObject();function createXmlHttpRequestObject()
{var xmlHttp;if(window.ActiveXObject)
{try
{xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e)
{xmlHttp=false;}}
else
{try
{xmlHttp=new XMLHttpRequest();}
catch(e)
{xmlHttp=false;}}
if(!xmlHttp)
alert("Error creating the XMLHttpRequest object.");else
return xmlHttp;}
function process()
{if(xmlHttp.readyState==4||xmlHttp.readyState==0)
{var url="changeme.php";var params="var1=val1&var2=val2";xmlHttp.open("POST",url,true);xmlHttp.onreadystatechange=handleServerResponse;xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");xmlHttp.send(params);}
else
setTimeout('process()',1000);}
function handleServerResponse()
{if(xmlHttp.readyState==4)
{if(xmlHttp.status==200)
{txtResponse=xmlHttp.responseText;}
else
{alert("There was a problem accessing the server: "+xmlHttp.statusText);}}}