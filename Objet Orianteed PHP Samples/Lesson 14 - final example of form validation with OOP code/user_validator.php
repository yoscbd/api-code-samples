<?php 

class UserValidator {

  private $data;
  private $errors = [];
  private static $fields = ['username', 'email'];

  public function __construct($post_data){
    $this->data = $post_data; // asign the post request data to our data array.
  }

  public function validateForm(){ // this is our main function that initiate the validation process, we call it on our index.php line 12

    foreach(self::$fields as $field){ // *note the use of "self::$fields" as the fields array is static and we use it without creating an instance
//1. first calidation: make sure every filed exist in the POST request:	
      if(!array_key_exists($field, $this->data)){ // if the field (username or emaill) dosnt exist in the post request trow an error:
        trigger_error("'$field' is not present in the data");
        return;
      }
    }

    $this->validateUsername(); //2. second validation: validate the username
    $this->validateEmail(); //3. third validation: validate the email
    return $this->errors; //4. return the erros array that will be populated if there are any validation errors

  }

  private function validateUsername(){

    $val = trim($this->data['username']); // remove any whith space

    if(empty($val)){
      $this->addError('username', 'username cannot be empty'); // call the adderror funtion to add an error to our erros array
    } else {
      if(!preg_match('/^[a-zA-Z0-9]{6,12}$/', $val)){
        $this->addError('username','username must be 6-12 chars & alphanumeric');
      }
    }

  }

  private function validateEmail(){

    $val = trim($this->data['email']);

    if(empty($val)){
      $this->addError('email', 'email cannot be empty');
    } else {
      if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
        $this->addError('email', 'email must be a valid email address');
      }
    }

  }

  private function addError($key, $val){ // add an error to the errors array, expection to get the field name($key) and the error message($value)
    $this->errors[$key] = $val; // add the error to the erros array
  }

}

?>