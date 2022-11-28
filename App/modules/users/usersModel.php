<?php

namespace App\Modules\Users;
use App\Config\Database;


require_once __DIR__.'/../../utils/constants.php';
require_once 'userTypes.php';


class UsersModel {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    static string $tableName = "users";
    public array $userColumnsValues;
    
    public function __construct($id, $userName, $email, $password, $role) {
      $this->id = $id;		
      $this->username = $userName;
      $this->email = $email;
      $this->password = $password;
      $this->role = $role;
    }

  /**
   * Método para seleccionar los datos de multiples usuarios.
   * 
   * @param Array (whereConditions, columnsSelected) 
   * @return Integer (sqlQueryResult)
   */
   public static function findAll($whereConditions = array(), $columnsSelected = array(), $conditionType = '') {
      $DB_Connection = new Database();
      $Connection= $DB_Connection->connect();

      $selectedColumns = count($columnsSelected) > 0 ? printSqlValues($columnsSelected)  :  " * "; 
      $sqlQuery = " SELECT ". $selectedColumns . " FROM " . Self::$tableName; 
   
        
    if(count($whereConditions) > 0) { 
      	$sqlQuery .= ' WHERE '; 
        $sqlQuery .=  join($conditionType === '' ? " AND " : ' ' . $conditionType . ' ',  $whereConditions);
    }


    $result = mysqli_query($Connection, $sqlQuery);
   
    $listResult = mysqli_fetch_all ($result , MYSQLI_ASSOC);

     mysqli_free_result($result);
     mysqli_close($Connection);
 
     $listUsers = array();
     
     foreach ( $listResult as $value) {
   
    if (count($columnsSelected) === 0 ) {

      [
        'id'=> $id,
        'username' => $username, 
        'email' => $email, 
        'password' => $password, 
        'user_type' => $role] =  $value;

    } else {
      $id = array_key_exists('id', $value) ? $value['id'] : 0;
      $username = array_key_exists('username', $value) ? $value['username']  : '';
      $email = array_key_exists('email', $value) ? $value['email']  : '';
      $password = array_key_exists('password', $value) ? $value['password']  : '';
      $role = array_key_exists('user_type', $value) ? $value['user_type']  : '';
    }

       array_push($listUsers, new UsersModel($id, $username, $email, $password, $role));
    }      
      return $listUsers; 
     
  }

  /**
   * Método para seleccionar los datos de un usuario.
   * 
   * @param Array (whereConditions) 
   * @return Integer (sqlQueryResult)
   */
  public static function findOne($whereConditions = array(),  $selectedColumns= array(), $conditionType = '') {
    $listUsers = UsersModel::findAll($whereConditions,  $selectedColumns, $conditionType);
    


    return $listUsers ? $listUsers[0] : array();
  }

   /**
    * Método para insertar los datos de un usuario.
    * @return Integer (sqlQueryResult)
    */
    public function insert(): int {

      $this->userColumnsValues = array(
        "username" => strQuote($this->username),
        "email" => strQuote($this->email),
        "password" => strQuote($this->password),
        "user_type" => strQuote($this->role)
      );

      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();

      $sqlQuery = " INSERT INTO " . Self::$tableName;
      $sqlQuery .= " ( ". printSqlValues(array_keys($this->userColumnsValues)) . " ) ";
      $sqlQuery .= " VALUES ( " . printSqlValues($this->userColumnsValues) . " ) ";
      
      $sqlQueryResult = mysqli_query($Connection, $sqlQuery);
      
      mysqli_close($Connection);
     return $sqlQueryResult;
  }
    

   /**
    * Método para actualizar los datos de un usuario.
    * 
    * @param Array (whereConditions) 
    * @return Integer (sqlQueryResult)
    */

    public function update($whereConditions): int {

      $this->userColumnsValues = array(
        "username" => strQuote($this->username),
        "email" => strQuote($this->email),
        "user_type" => strQuote($this->role)
      );


      if ($this->password !== '') $this->userColumnsValues['password'] = strQuote($this->password);

      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();
      $sqlQuery = " UPDATE " . Self::$tableName . " SET ";

      $sqlQuery .= printSqlValues($this->userColumnsValues, true);
    
      if(count($whereConditions) > 0) { 
        $sqlQuery .= ' WHERE '; 
        $sqlQuery .=  join(" AND ", $whereConditions);
      }
  
     $sqlQueryResult = mysqli_query($Connection, $sqlQuery);

      mysqli_close($Connection);
            
    return  $sqlQueryResult;

  }
    
   /**
    * Método para borrar un usuario.
    * 
    * @param Array (whereConditions) 
    * @return Integer (sqlQueryResult)
    */

    public static function delete($whereConditions = array()) : int {
      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();
      $sqlQuery = " DELETE FROM " . Self::$tableName;
      
      if(count($whereConditions) > 0) { 
        $sqlQuery .= ' WHERE '; 
        $sqlQuery .=  join(" AND ", $whereConditions);
      }


      $sqlQueryResult = mysqli_query($Connection, $sqlQuery);
     
      mysqli_close($Connection);
            
    return $sqlQueryResult;

    }
  }
?>