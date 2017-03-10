
<?php ?>
<!DOCTYPE html>
<!--
Program: Buhrmann Farms Pasture Rental
Programmer: John Buhrmann
Date: 10 March 2017
-->
<html>
    <head>
        
        <meta charset='utf-8'>

	<title>Buhrmann Farms Pasture Rental</title>

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
    
    <body id="wrapper">
        <header>
            <img src="../View/assets/BF_grazingPasture-H3.jpg" alt="map of GrowRight Farms">
        </header>
        <h1>Buhrmann Farms Pasture Rental</h1>
        <h2><?php echo $alias; ?></h2>
        <p>The pastures above may be rented for the 2017 grazing season. The two pastures MUST
        be rented as a package. the cumulative area to be rented is 42.5 acres. The bid process is per acre per month multiplied by seven months.  </p>
        <div>
            <form action="../controller/index.php" method="post">
                <div>
                    <span><label>Enter Bid:</label>
                    <input type="text" name="bid" value="">
                    <input type="submit"  value="Submit">
                    <input type="hidden" name="action" value="submit_bid"></span>
                </div> 
                
            </form>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
