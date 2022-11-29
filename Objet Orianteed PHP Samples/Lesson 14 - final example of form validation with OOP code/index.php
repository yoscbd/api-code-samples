<?php 

  require('user_validator.php'); // Call our validation class

  $errors = []; // inisiate an erros array

  if(isset($_POST['submit'])){ // make sure this will run only if user submitted the form
    // validate entries
    $validation = new UserValidator($_POST); // create a new instance of our validation via our validation class,
											// this wiil make our constractor funtion to run and set our class static fields ($fields)
	
    $errors = $validation->validateForm(); // push any error from our validation calls code to the errors array by
										   // calling the "validateForm" funciton that inisiate the validaiton proccess

    // if errors is empty --> save data to db
  }

?>

<html lang="en">
<head>
  <title>PHP OOP</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  
  <div class="new-user">
    <h2>Create a new user</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!--"$_SERVER['PHP_SELF']" allow us to submit the form to this current page-->

      <label>username: </label>
	  
	  
      <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username']) ?? '' ?>"> <!-- if the form was submited with errors load the value entered by the user when he posted the form so he can see it and fix it-->
      <div class="error">
        <?php echo $errors['username'] ?? '' ?> <!-- if there is an error with user name echo it here-->
      </div>
      <label>email: </label>
      <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email']) ?? '' ?>"> <!-- if the form was submited with errors load the value entered by the user when he posted the form so he can see it and fix it-->
      <div class="error">
        <?php echo $errors['email'] ?? '' ?> <!-- if there is an error with email echo it here-->
      </div>
      <input type="submit" value="submit" name="submit" >

    </form>
  </div>

</body>
</html>