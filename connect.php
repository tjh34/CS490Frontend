<html>
<html>
	<head>
		<title>AKT Testing System Version 0.1</title>
	</head>
	<body>
		<h1>AKT Testing System Version 0.1</h1>
	<div class='loginform-in'>
	<h2>Log In</h2>
	<div class='err' id='add_err'></div>
	<fieldset>
		<form action='connect.php' method='post' name="a">
			<label for='name'>Username</label>
			<input type='text' size='30'  name='user' id='user' required/><br><br>
			<label for='name'>Password</label>
			<input type='password' size='30'  name='pass' id='pass'  required/><br><br>
			
			<input type='submit' id='login' name='login' value='Login' class='loginbutton'">
		</form>	
	</fieldset>
	</div>
	</body>
<?php
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	
	
	$fields = 'user='.$user.'&pass='.$pass;

	
	$crl = curl_init();
	curl_setopt($crl, CURLOPT_URL, "https://web.njit.edu/~kkc22/CS490proj/login.php");
	curl_setopt($crl, CURLOPT_POST, 1);
	curl_setopt($crl, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($crl, CURLOPT_FOLLOWLOCATION, 1);
	$c = curl_exec($crl);
	curl_close($crl); 
?>
</html>