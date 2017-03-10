<!DOCTYPE html>
<!--
Program: Buhrmann Farms Pasture Rental
Programmer: John Buhrmann
Date: 10 March 2017
-->
<html>
    <head>
        <meta charset='utf-8'>

	<title>Buhrmann Farms Register Page</title>

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
        <img src="../View/assets/GR_pasture.jpg" alt="map of GrowRight Farms" width="650" height="350">
    </header>
    
    <body>
        <?php if (!empty($error_message)) { ?>
    		<p class="error"><?php echo htmlspecialchars($error_message); ?></p>
		<?php } ?>
        
        <form action="../controller/index.php" method="post" >

		    <div id="data">
			<label>First Name:</label>
			<input type="text" name="first_name" 
			       value="<?php echo htmlspecialchars($firstName); ?>"> <br>
			
			<label>Last Name:</label>
			<input type="text" name="last_name" 
			       value="<?php echo htmlspecialchars($lastName); ?>"> <br>
			
			<label>Alias:</label>
			<input type="text" name="alias" 
			       value="<?php echo htmlspecialchars($alias); ?>"> <br>
			
			<label>Password:</label>
			<input type="text" name="password" 
			       value="<?php echo htmlspecialchars($password); ?>"> <br>
			
			<label>Email:</label>
			<input type="text" name="email" 
			       value="<?php echo htmlspecialchars($email); ?>"> <br>
                        
		    </div>

		    <div id="buttons">
			<br>
			<input type="submit" class="btnCustom" value="Register"><br>
                        <input type="hidden" name="action" value="try_register">
		    </div>
		</form>
    </body>
</html>
