<html lang="en">
<head>
<meta charset="utf-8" />
<title>jQuery UI Datepicker - Default functionality</title>
<link rel="stylesheet" href="css/jquery-ui.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/style.css" />
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
</script>
</head>
<body>
<p>Date: <input type="text" id="datepicker" /></p>


<form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">

    <textarea rows="25" cols="40" name="content"><?php readfile($reading); ?></textarea>

    <input type="submit" value="Sauver"> 

</form>
</body>
</html>