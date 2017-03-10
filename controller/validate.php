<?php

   function validate_reg_update($firstName,$lastName,$alias,$password,$email, $update){
       
        $options = ['cost' => 12,];   //options for hashing password  
        $hash = password_hash($password, PASSWORD_DEFAULT,$options); //hash password
        $testalias = "";
    
        if(get_alias($alias) != false){
            $testalias = get_alias($alias)[0][0];
        }

        
        if ($firstName === '' ) { //validate first name, is present
            $error_message = 'First name is required'; 
        } else if ( $lastName === '' ) { //validate last name, is present
            $error_message = 'Last name is required'; 
        } else if ( $alias === '' )  { //validate alias
            $error_message = 'Alias is required';
        } else if ( !preg_match('/^[a-z][a-z0-9]{3,19}$/',$alias) )  { //validate alias
            $error_message = 'Alias must start with a letter, be between 4 and 19 in length and only contain numbers and letters';
        } else if ( $password == '' )  { //validate alias
            $error_message = 'Password is required';
        } else if ( !preg_match('/^\S*(?=\S{10,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/',$password) )  { //validate alias
            $error_message = 'password must be at least 10 chars, have a upper case, lower case and special character';
        } else if ( $email === FALSE ) { //Validate email
            $error_message = 'Please enter a valid Email address'; 
        // set error message to empty string if no invalid entries
        } else {
            $error_message = ''; 
        }
        
        if($update === false){
            if ( $testalias == $alias )  { //validate alias
                $error_message = 'Alias must be unique';
            }
        }
        
        return $error_message;
   }
?>

