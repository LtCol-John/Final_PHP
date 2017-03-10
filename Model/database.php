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
    function insert_bidder($firstName, $lastName, $alias, $password, $email, $image)
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
    
    function update_user($firstName, $lastName, $alias, $password, $email)
    {

        global $db;
        $query = 'UPDATE users
                   SET firstName = :firstNamePlaceholder,
                       lastName = :lastNamePlaceholder,
                       password = :passwordPlaceholder,
                       email = :emailPlaceholder
                   WHERE alias = :aliasPlaceholder';

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
    
    function add_image($alias,$image){
        global $db;
        $query = 'UPDATE users
                   SET image = :imagePlaceholder
                   WHERE alias = :aliasPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':imagePlaceholder', $image);
        $statement->bindValue(':aliasPlaceholder', $alias);
        try{
            $statement->execute();
        }catch(PDOException $e) {
            $error_message = $e->getMessage();
            include('database_error.php');
            exit();
        }
       
        $statement->closeCursor();
    }

    
    function get_comments($alias)
    {
        global $db;
 
      $query = 'SELECT * FROM comments
                     WHERE alias = :aliasPlaceholder';
      $statement = $db->prepare($query);
      $statement->bindValue(':aliasPlaceholder', $alias);
      $statement->execute();
      $comments =  $statement->fetchAll();
 
        
        return $comments;
    }
    
    function insert_comment($alias,$comment,$commentdate,$commenter)
    {

        global $db;
        $query = 'INSERT INTO comments
                   (alias, comment, commentDate, commenter)
                   VALUES
                   (:aliasPlaceholder, :commentPlaceholder, :commentDatePlaceholder,:commenterPlaceholder)';

        $statement = $db->prepare($query);
        $statement->bindValue(':aliasPlaceholder', $alias);
        $statement->bindValue(':commentPlaceholder', $comment);
        $statement->bindValue(':commentDatePlaceholder', $commentdate);
        $statement->bindValue(':commenterPlaceholder', $commenter);

        
        try{
            $statement->execute();
        }catch(PDOException $e) {
            $error_message = $e->getMessage();
            include('database_error.php');
            exit();
        }
       
        $statement->closeCursor();
        
    }

?>