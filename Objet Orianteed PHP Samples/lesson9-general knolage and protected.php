<?php 
// this is a forck of:
//https://github.com/iamshaunjp/object-oriented-php/blob/lesson-9/index.php
  class User {
/**
* define class variables, 
* public : variable will be avaiable outside the class
* private: variable will be aviable inside class only!
* protected: variable will be aviable inside class only but also in childrenn classes that inharet the parent class
*/


    public $username;
    protected $email;
    public $role = 'member';


/**
* Our main constractor function, it will be automaticly run firstm this is our point of entery 
* 
*/
    public function __construct($username, $email){
      //$this->username = 'ken';
      $this->username = $username; // $this reffers to the current instance of the class.
      $this->email = $email;
    }

    public function addFriend(){
      //return "added a new friend";
      return "$this->username just added a new friend";
    }

    public function message(){
      return "$this->email sent a new message";
    }

    // **Getters function: allow us to pull data from the class, if we have a praivate variable in the class the
	// only way to get it outside the class will be to use this getter function that will return its value
    public function getEmail(){
      return $this->email;
    }

    // **Setters: allow us to chnage variables data
    public function setEmail($username){
      if(strpos($username, '@') > -1){
        $this->email = $username;
      };
    }

  }



/**
* This is an example to a child class: "AdminUser" inharit all functions and variables from the parent "user" class:
*/
  class AdminUser extends User {

    public $level; // a new variable in out "AdminUser" class
    public $role = 'admin'; // run over the "role" variable from the parent "user" class


// here we are using a new constractor that will allow us to add the "level" variable as well
    public function __construct($username, $email, $level){
      parent::__construct($username, $email); // this allow us to inharit the parent constractor funtion
      $this->level = $level; // this add the new "role" variable to our constractor
    }

    public function message(){
      return "admin $this->email sent a new message";
    }

  }

/**
* Creating new object from our class:
*/

// using the parent "user" constractor:
  $userOne = new User('mario', 'mario@thenetninja.co.uk'); 
  $userTwo = new User('luigi', 'luigi@thenetninja.co.uk');
  
// using the child "AdminUser" constractor:  
  $userThree = new AdminUser('yoshi', 'yoshi@thenetninja.co.uk', 5);

  echo $userOne->message() . '<br>';
  echo $userThree->message() . '<br>'; 

  //echo $userOne->email . '<br>';

?>

<html lang="en">
<head>
  <title>PHP OOP</title>
</head>
<body>
  
</body>
</html>