<?php
namespace App\Utils;

class ClassValidator {
   
   /**
    * Método que valida email.
    * @return String (True|False)
    */  
    public static function email(string $email): bool {

        return filter_var($email, FILTER_VALIDATE_EMAIL); 
  
      }


    /**
    * Método que valida entero.
    * @return String (True|False)
    */  
    public static function integer(int $integer): bool {
        if (!filter_var($integer, FILTER_VALIDATE_INT)) {
          return false;
        }
        return true;
       }

   /**
    * Método que valida un flotante.
    * @return String (True|False)
    */  
    public static function float(int $integer): string {
        if (!filter_var($integer, FILTER_VALIDATE_FLOAT)) {
          return "false";
        }
        return "true";
       }
    

   /**
    * Método que valida un string.
    * @return String (True|False) "/^[a-zA-z\s]+$/"
    */  
    public static function isString(string $stringValue) {
       return is_string($stringValue);
       }
    }
?>

//