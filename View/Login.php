<!DOCTYPE html>
<!--
Program: Buhrmann Farms Website 
    This website will login users who will then bid on pasture ground.
Programmer: John Buhrmann
-->
<html>
    <head>
        <meta charset='utf-8'>

	<title>Buhrmann Farms</title>

	<meta name="Description" content="Nebraskan family farm" />
	

	<link href="../View/GrowRight.css" rel="stylesheet" type="text/css" />

	<script 
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js">
	</script>

<link  href="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> 
<script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
    </head>
    <header>
    			
        <img src="../View/assets/tractorNancyAndThelma.jpg" alt="tractor Nancy and Thelma" data-caption="Nancy and Thelma on th D-17"  width="650" height="350">
	
    </header>
    <body>
        <form action='index.php' method='post'>                       
                    <label>Alias:</label>
                    <input type='text' name='alias'><br>
                    <label>Password:</label>
                    <input type='password' name='password'><br>

                    <label>&nbsp;</label>
                    <input type='submit'  name='login'  value='Login'>
                    <input type='hidden'  name='action' value='login'>
                </form>

                <form action='index.php'>
                    <input type='submit' value='Register now!' name='register'>
                    <input type='hidden'  name='action' value='register'>
                </form>
    </body>
</html>
