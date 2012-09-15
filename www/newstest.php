<!DOCTYPE html>
<html>
<body>
<h1>Getting server updates</h1>
<div id="result"></div>

<script>
if(typeof(EventSource)!=="undefined")
  {
  var source=new EventSource("newsfeed.php");
  source.onmessage=function(event)
    {
    document.getElementById("result").innerHTML+=event.data + "<br />";
    };
  }
else
  {
  document.getElementById("result").innerHTML="Sorry, your browser does not support server-sent events...";
  }
  
var txtScreenName, ele;
	
txtScreenName = "BlackBerryDev";					//use as a default
ele = document.getElementById("txtScreenName");
if (ele) {
	txtScreenName = (ele.value !== "") ? ele.value : "BlackBerryDev";
}

setContent("twitterFeed", "Loading Twitter feed for @" + txtScreenName + " ...");
req = new XMLHttpRequest();
req.open("GET", "http://http://andreterron.com/weachieve/login.php?json={%22userLogin%22:%22test%22,%22userPassword%22:%2287654321%22}", false);
req.onreadystatechange = handleResponse;
req.send(null);
</script>

</body>
</html>
