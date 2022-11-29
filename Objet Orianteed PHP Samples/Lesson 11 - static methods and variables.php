<?php 



/**
* This is an example of using "static" for methods and variables, 
* If we dont want to assing the value of anything to an instance like so: 
* $weatherInstance = new Weather();
* echo $weatherInstance -> tempConditions;
* we can add "static" to our method or variable and call it without assining it to an instance (instance = temp vcariable pointing to the current value)
* see: https://www.youtube.com/watch?v=5zw1VjQIbNQ&list=PL4cUxeGkcC9hNpT-yVAYxNWOmxjxL51Hy&index=11

* note that static  keyword is also used to declare variables in a function which keep their value after the function has ended, see this example :
*https://www.w3schools.com/php/phptryit.asp?filename=tryphp_keyword_static2
*/


  class Weather {

    public static $tempConditions = ['cold', 'mild', 'warm'];

    public static function celsiusToFarenheit($c){
      return $c * 9 / 5 + 32;
    }

    public static function determineTempCondition($f){
      if($f < 40){
        return self::$tempConditions[0]; // calling the $tempConditions array first item from our class with no instance by using "self::"
      } elseif($f < 70){
        return self::$tempConditions[1];
      } else {
        return self::$tempConditions[2];
      }
    }

  }

// **Calling our statice "tempConditions" array outside the class:
  //print_r(Weather::$tempConditions);
  //echo Weather::celsiusToFarenheit(20);
  
//** Calling our  "determineTempCondition" method outside the class while it is using the "tempConditions" array
//   without assigning it to an isntance by using "self::" that point to our class instead of "$this->"
  echo Weather::determineTempCondition(80);

?>

<html lang="en">
<head>
  <title>PHP OOP</title>
</head>
<body>
  
</body>
</html>