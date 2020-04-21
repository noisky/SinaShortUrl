/**
 * Created by Noisky on 2017/12/31.
 * Revised by Noisky on 2020/04/21.
 */
function get() {
	var long = document.input.long.value;//获取长网址 
	if (!long.startsWith("http")) {
		alert("网址需以 http/https 开头！");
		return;
	}
	var long=long.replace(/\&/g,'%26');//对神奇的&进行转义
	var long=long.replace(/\#/g,'%23');//对神奇的#进行转义
	var apiUrl = "get.php";//填写api的地址
	var requestUrl = apiUrl + "?longUrl=" + encodeURIComponent(long); //生成请求地址
	var startClock;
	//建立ajax对象
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",requestUrl,true);//异步请求，GET
	xmlhttp.onreadystatechange = function() {
		// readyState == 4说明请求已完成
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200 || xmlhttp.status == 304) { 
		  // 从服务器获得数据 
			var fuckjs = new Function("return" + xmlhttp.responseText)();//将返回数据视作对象
			var fuckjson = fuckjs.url_short;//提取短网址
			//停止定时器
			clearInterval(startClock);
			//隐藏loading
			document.getElementById("loading").style.display = "none";
			//输出短网址
			document.getElementById("link").innerHTML="<p>短网址：</p><br><input type='text' class='kw' value='"+fuckjson+"'><br><br>";
		}
	  };
	xmlhttp.send();//发送GET请求
	//显示loading
	var loadInfo = document.getElementById("loading");
	loadInfo.style.display = "block";
	var startClock = setInterval(function clock() {
		if (loadInfo.innerText.length > 12) {
			loadInfo.innerText = "Loading";
		}
		var text = loadInfo.innerText;
		loadInfo.innerText = text + ".";
	},1000);
}; 
