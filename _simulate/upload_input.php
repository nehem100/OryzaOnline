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

$iyear = "IYEAR = ".$_POST['year']."\n";
$sttime = "STTIME = ".$_POST['day']."\n";
$estab = "ESTAB ='".$_POST['estab']."' \n";
$emd = "EMD = ".$_POST['day']."\n";
$emyr = "EMYR = ".$_POST['year']." \n";
$sbdur = "SBDUR = ".$_POST['sbdur']."\n";


$reading = fopen('$path/short_term.exp', 'r');
$writing = fopen('$path/short_term.tmp', 'w');

$replaced = false;

while (!feof($reading)) {
  $line = fgets($reading);
  if (stristr($line,'IYEAR')) {
	$line = $iyear;
	$replaced = true;
  }
  else if (stristr($line,'STTIME')) {
	$line = $sttime;
	$replaced = true;
  }
  else if (stristr($line,'ESTAB =')) {
	$line = $estab;
	$replaced = true;
  }
  else if (stristr($line,'EMD')) {
	$line = $emd;
	$replaced = true;
  }
  else if (stristr($line,'EMYR')) {
	$line = $emyr;
	$replaced = true;
  }
  else if (stristr($line,'SBDUR')) {
	$line = $sbdur;
	$replaced = true;
  }
  fputs($writing, $line);
}

fclose($reading); fclose($writing);
// might as well not overwrite the file if we didn't replace anything
if ($replaced) 
{
  rename('$path/short_term.tmp', '$path/short_term.exp');
} else {
  unlink('$path/short_term.tmp');
}

?>
