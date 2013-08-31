<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Oryza2000 Online!</title>
<link rel="stylesheet" type="text/css" href="_design/style.css" media="screen" />
<script type="text/javascript" src="_js/jquery.min.js"></script>

<?php 
include ('_include/connect.php');

$_POST['engine_id'] = 0;

function select_engine(){
$run = 0;
$engine_id = 0;
$variety = " ";
if (isset($_POST['variety'])){
$variety = $_POST['variety'];
}
while($run == 0){
	$result = mysql_query("SELECT min(id) FROM $variety WHERE status = 0");
	while($row = mysql_fetch_array($result)){
			$engine_id = $row['min(id)'];
			}
	if ($engine_id != NULL){
		mysql_query("UPDATE $variety SET status=1 WHERE id = $engine_id");
		$result = mysql_query("SELECT path FROM $variety WHERE id = $engine_id");
		while($row = mysql_fetch_array($result)){
			$path = $row['path'];
			}
		$_POST['engine_id'] = $engine_id;
		process($path);
		//$out = shell_exec($path."shellscript.sh");
		//echo $out;	
		return $path;
		break;   
		}
	else{
		sleep(random(2));
		break;
		}
	}
}

function process($path){
	$iyear = "IYEAR= ".$_POST['year']."                ! start year of simulation (year)\r\n";
	$sttime = "STTIME =  ".$_POST['day'].".               ! start time (day numer)\r\n";
	if ($_POST['estab'] == "DIRECT-SEED"){
	$estab = "ESTAB='DIRECT-SEED'\r\n";
	}
	else if ($_POST['estab'] == "TRANSPLANT"){
	$estab = "ESTAB='TRANSPLANT'\r\n";
	}
	$emd = "EMD=   ".$_POST['day']."    ! Day of emergence (either direct, or in seed-bed)\r\n";
	$emyr = "EMYR= ".$_POST['year']."    ! Year of emergence (1996)\r\n";
	$sbdur = "SBDUR=   ".$_POST['sbdur']."    ! seed-bed duration (days)\r\n";

	$reading = fopen($path.'short_term.exp', 'r');
	$writing = fopen($path.'short_term.tmp', 'w');

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
	  else if (stristr($line,'ESTAB=')) {
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

	if ($replaced) 
	{
	  rename($path.'short_term.tmp', $path.'short_term.exp');
	} else {
	  unlink($path.'short_term.tmp');
	}
}

function graph($n_arr){
?>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            title: {
                text: 'Monthly Average Temperature',
                x: -20 //center
            },
            subtitle: {
                text: 'Source: WorldClimate.com',
                x: -20
            },
            xAxis: {
                categories: [ <?php 
					$count = count($n_arr);
					for ($ctr = 0; $ctr < $count; $ctr++)
					{ 
					print_r($n_arr[$ctr][1]);
					echo ","; 
					}
					?>
					0]
            },
            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '°C'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'WRR14',
                data: [ <?php 
					$count = count($n_arr);
					for ($ctr = 0; $ctr < $count; $ctr++)
					{ 
					print_r($n_arr[$ctr][23]);
					echo ", "; 
					}
					?> ]
            }]
        });
    });
	
</script><?php
}
?>

</head>

<body>
<div id="main_container">
	<div id="header">
    	<div id="logo"><a href="home.html"><img src="_design/images/logo.gif" alt="" title="" border="0" /></a></div>
        
        <div id="menu">
            <ul>                                        
                <li><a class="current" href="home.html" title="">Online Simulation</a></li>
                <li><a href="services.html" title="">About Oryza2000</a></li>
                <li><a href="#" title="">Download Software</a></li>
                <li><a href="#" title="">Training Program</a></li>
                <li><a href="contact.html" title="">FAQ</a></li>
		<li><a href="contact.html" title="">Contact Us</a></li>
            </ul>
        </div>
        
    </div>
    
    <div class="green_box">
    	<div class="clock">
        <img src="_design/images/graph-icon.png" alt="" title="" width="200" height="auto" />             
        </div>
        <div class="text_content">
        <h1>What is your biological clock?</h1>
		<p></p>
		<!-------FORM---------->
        <form id="ff" action="index.php" method="post">
		<table border="0">
		<tr>
			<td>Year:</td>
			<td><input type="text" name="year"><br> <!--iyear, emyr--></td>
		</tr>
		<tr>
			<td>Variety:</td>
			<td><input type="radio" name="variety" value="st_engines">Short Term <input type="radio" name="variety" value="mt_engines">Medium Term <input type="radio" name="variety" value="lt_engines">Long Term </td>
		</tr>
		<tr>
			<td>Day of Sowing:</td>
			<td><input type="text" name="day"> <!--sttime, emd--></td>
		</tr>
		<tr>
			<td>Seeding:</td>
			<td><input type="radio" name="estab" value="DIRECT-SEED">Direct <input type="radio" name="estab" value="TRANSPLANT">Transplant</td>
		</tr>
		<tr>
			<td>Duration:</td>
			<td><input type="text" name="sbdur"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="START" name="simulate"></td>
		</tr>
		</table> 
	</form>
		<!--END oF FORM------>
        </div>
		
        <div id="right_nav">
            <p>                                        
            "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodoo ua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br /><br /> 
			</p>
		</div> 
		
    </div><!--end of green box-->
    
    <div id="main_content">
    	<div id="wide_content">
        <h2>Simulation Results</h2>
		<p>
		
		<?php
		if (isset($_POST['simulate'])){
			$path = select_engine();
		}
		if (isset($path)){
		$reading = file_get_contents($path.'res.dat', true);

		$n = explode("\n", $reading); 
		$arr = $n;
		$i = -1 ;
		$count = 0;
		foreach($n as $line)
			{  
				if(0 === strpos($line, "   "))
				{
					if (strlen($line)>1){
					$i=$i+1;
					$arr[$i] = $line;
					$arr1 = explode("\t",$arr[$i]); 
					$j = 0;
					foreach($arr1 as $arr_line)
						{
						$j=$j + 1;
						$str = "$arr_line";
						if (preg_match('/E/', $str, $matches, PREG_OFFSET_CAPTURE, 1) == 1){
							$cut = explode("E", $str);
							$str = " ".pow($cut[0],$cut[1]);
							$n_arr[$i][$j] = $str;
						}
						else
						{
						$str = str_replace("-", '0', $arr_line);
						$n_arr[$i][$j] = $str;
						}
						print_r($n_arr[$i][$j]);
						}
					echo "<br>";
					}
				}
			}
		graph($n_arr);
		mysql_query("UPDATE ".$_POST['variety']." SET status=0 WHERE id = $_POST[engine_id]");
		?>
		<form>
		
		</form>
		<?php
		}
		?>
		
		</p>       
        
        <script src="_js/highcharts.js"></script>
		<script src="_js/modules/exporting.js"></script>

		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div><!--end of wide content-->

    </div><!--end of main content-->
 
	<div id="footer">
     	<div class="copyright">
		<a href="home.html"><img src="_design/images/footer_logo.gif" border="0" alt="" title="" /></a>
        </div>
    	<div class="footer_links"> 
        <a href="#">About us</a>
        <a href="privacy.html">Privacy policy</a> 
        <a href="contact.html">Contact us </a>
        </div>
    </div>  
 
</div> <!--end of main container-->
</body>
</html>
