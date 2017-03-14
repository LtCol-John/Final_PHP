<?php 

/* 
 * Program: Buhrmann Farms Pasture Rental
 * Programmer: John Buhrmann
 * Date: 10 March 2017
 */

?>

<?php 
$dsn = 'mysql:host=localhost;dbname=pasturedb';
    $username = 'root';
    $dbpassword = '';
   
    
    

    try {
        $db= new PDO($dsn, $username, $dbpassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
    }
    
	//get all rows of user table
    function get_bidders()
    {
        global $db;
 
      $queryUsers = 'SELECT * FROM bidders';
      $statement = $db->prepare($queryUsers);
      $statement->execute();
      $bidders =  $statement->fetchAll();
 
        
        return $bidders;
    }
    
    function get_bidders_info($alias)
    {
        global $db;
 
      $queryUsers = 'SELECT * FROM bidders
                     WHERE alias = :aliasPlaceholder';
      $statement = $db->prepare($queryUsers);
      $statement->bindValue(':aliasPlaceholder', $alias);
      try{
        $success = $statement->execute();
      }catch(PDOException $e) {
        $error_message = $e->getMessage();
	include('database_error.php');
	exit();
      }
      $profile =  $statement->fetchAll();

        return $profile;
    }
    
	//get specified alias from user table, returns false if fails
    function get_alias($alias)
    {
        global $db;
         $query = 'SELECT alias from bidders WHERE alias=:aliasPlaceholder';

        //prepare the query, bind the values, then you execute
            $statement = $db->prepare($query);
            $statement->bindValue(':aliasPlaceholder', $alias);

            //the execute method returns a boolean TRUE on success or FALSE on failure.
            try{
                $success = $statement->execute();
            }catch(PDOException $e) {
		$error_message = $e->getMessage();
		include('database_error.php');
		exit();
            }
			
            //after the statement is executed you can then fetch the results
            $results = $statement->fetchAll();

            if($success)
            {
                return $results;
            }
            else
            {
                return $success;
            }
    }
    
    function login_check($alias,$password)
    {
        global $db;
         $query = 'SELECT alias, password from bidders WHERE alias=:aliasPlaceholder';

        //prepare the query, bind the values, then you execute
            $statement = $db->prepare($query);
            $statement->bindValue(':aliasPlaceholder', $alias);

            //the execute method returns a boolean TRUE on success or FALSE on failure.
            try{
                $success = $statement->execute();
            }catch(PDOException $e) {
		$error_message = $e->getMessage();
		include('database_error.php');
		exit();
            }
			
            //after the statement is executed you can then fetch the results
            $results = $statement->fetchAll();
            //print_r($results);
            if($success)
            {
                if(empty($results)){
                    return false;
                }else{
                    return password_verify($password,$results[0]['password']);
                }
            }
            else
            {
                return $success;
            }
    }
    
	//insert new record to user table
    function insert_bidder($firstName, $lastName, $alias, $password, $email)
    {

        global $db;
        $query = 'INSERT INTO bidders
                   (firstName, lastName, alias, password, email)
                   VALUES
                   (:firstNamePlaceholder, :lastNamePlaceholder, :aliasPlaceholder,:passwordPlaceholder, :emailPlaceholder)';

        $statement = $db->prepare($query);
        $statement->bindValue(':firstNamePlaceholder', $firstName);
        $statement->bindValue(':lastNamePlaceholder', $lastName);
        $statement->bindValue(':aliasPlaceholder', $alias);
        $statement->bindValue(':passwordPlaceholder', $password);
        $statement->bindValue(':emailPlaceholder', $email);
        
        
        try{
        $statement->execute();
        }catch(PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
        }
       
        $statement->closeCursor();
        
    }
    
    function insert_bid($alias, $bid, $bidDate)
    {
        global $db;
        $query = 'INSERT INTO bids
                   (alias, bid, bidDate)
                   VALUES
                   (:aliasPlaceholder, :bidPlaceholder, :bidDatePlaceholder)';

        $statement = $db->prepare($query);
        $statement->bindValue(':aliasPlaceholder', $alias);
        $statement->bindValue(':bidPlaceholder', $bid);
        $statement->bindValue(':bidDatePlaceholder', $bidDate);
        
        try{
        $statement->execute();
        }catch(PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
        }
       
        $statement->closeCursor();
        
    }
    
    function retrieve_hBid()
    {
        $alias = " ";
        $bid = 0;
        $bid_Date = date('Y-m-d H:i');
        
        global $db;
        
        $query = 'select alias, bid, bidDate 
                  from bids
                  order by bid desc
                  limit 1';
        $statement = $db->prepare($query);
        $statement->bindValue(':aliasPlaceholder', $alias);
        $statement->bindValue(':bidPlaceholder', $bid);
        $statement->bindValue(':DatePlaceholder', $bid_Date);
        try{
            $results = $statement->execute();
            }catch(PDOException $e) {
            $error_message = $e->getMessage();
            include('database_error.php');
            exit();
                                    }
        $results = $statement->fetchall();
        
        $statement->closeCursor();
        
        return $results;
    }
    
    function time_diff($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);    

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);    

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    return ($r1-$r2);

}


?>