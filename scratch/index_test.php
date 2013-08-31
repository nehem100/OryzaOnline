
<?php
include ('connect.php');

$run = 0;
$engine_id = 0;

if (isset($_POST['simulate'])){

while($run==0){
	$result = mysql_query("SELECT min(id) FROM st_engines WHERE status = 0");
	while($row = mysql_fetch_array($result)){
			$engine_id = $row['min(id)'];
			}
	if ($engine_id != NULL){
		mysql_query("UPDATE st_engines SET status=1 WHERE id = $engine_id");
		$result = mysql_query("SELECT path FROM st_engines WHERE id = $engine_id");
		while($row = mysql_fetch_array($result)){
			$path = $row['path'];
			}
		echo "galing";
		break;   
		}
	else{
		echo "panget";
		sleep(2);
		}
	}
}

if ($run==0){
echo "OMG";
}
else{
echo "GRR";
}
?>

<html>
<head>
<title>Oryza2000 Online</title>
</head>
<body>
<h1>Oryza2000 Online </h1>

<p>
<?php
//$reading = file_get_contents('/engines/st/short_term_1/res.dat', true);
if (isset($path)){
$reading = file_get_contents($path.'res.dat',true);
$n = explode("\n", $reading);  
foreach($n as $line){  
    if(0 === strpos($line, " ")){
        echo $line; ?>
		<br>
		<?php
    }
}
}
else{
echo "bading";
}
?>
</p>

<form action="index.php" method="post">
<input type="submit" id="simulate" name="simulate" value="Simulate"/>
</form>

</body>

</html>
