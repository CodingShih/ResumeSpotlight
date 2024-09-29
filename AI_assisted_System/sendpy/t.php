<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>hello</title>
</head>
<body>
<?php
$mess=$_POST["message_"];

/*
function getpyfile()
{
	$json=exec("python test.py");
	return $json;
}
getpyfile();
echo $json."<br/><br/>";
*/


echo exec("WhoamI");
echo exec("ls -l test.py") . "<br>";





$BB=escapeshellcmd("python test.py".$mess);


$LI=exec("test.py");



$LETSGO=shell_exec($BB);
if($LI != "")
{	
	echo $LI;
}
else
{
	echo "Get nothing!";
}

//echo "<br/><br/>".$mess;
?>
</body>
</html>