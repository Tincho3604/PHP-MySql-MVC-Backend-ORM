<?php


namespace App\Modules\Users;
require_once __DIR__.'/../../utils/constants.php';
use App\Modules\Users\UsersModel;
use App\Utils\ClassValidator;
use App\Utils\Http;
use Exception;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');



  class UsersController {

   /**
    * Método para seleccionar todos los usuarios.
    * @return UsersModel[] 
    *  [
    *    {
    *      "username": "martin3604",
    *      "email": "117113",
    *      "password": "1234",
    *      "role": "user"
    *     } 
    *  ]
    */
    public static function getAllUsers(){
      try {
          
          $auth = Http::authJWT();       
          if ($auth) { 

            $userList = UsersModel::findAll();
            return $userList;

          }
        } catch (Exception $e) {
          echo 'Error en el controlador: getAllUsers()',  $e->getMessage(), "\n";
      }
    }

    /**
     * Método para seleccionar usuario por email.
     * @return UsersModel[]      
     *    {
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *    } 
     * 
     */
    public static function getOneUserByEmailUsername($alias = array(), $selectedColumns = array(), $valueConditionType = ''){
      try {


        $usernameCondition = !empty($alias['username']) && $alias['username'] !== '' ? ' username '. " = " . strQuote($alias['username']) : '';
        $emailCondition = !empty($alias['email']) && 
                          $alias['email'] !== '' &&
                          ClassValidator::email($alias['email'])
                          ? ' email '. " = " . strQuote($alias['email']) : '';

         $aliasConditions = array($usernameCondition, $emailCondition);
         $whereConditions = array();

         foreach ($aliasConditions as $value) { 
         
          if($value !== '') array_push($whereConditions, $value);

         }
         
        $userList = UsersModel::findOne($whereConditions, $selectedColumns, $valueConditionType); 
        
        return $userList;
        
        } catch (Exception $e) {
          echo 'Error en el controlador: getOneUserByEmailUsername()',  $e->getMessage(), "\n";
        }
    }

    /**
     * Método para seleccionar usuario por id.
     * @param  Integer ($id)
     * @return UsersModel[]     
     *    {
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *    } 
     */
    public static function getUserById($id){
      try {
      
        $auth = Http::authJWT();       
        if ($auth) { 

        if( !empty($id['id']) && ClassValidator::integer((int)$id['id'])) {

          $wherecondition = array("id" . " = " . $id['id']);
          $movieById = UsersModel::findOne($wherecondition); 
          
          return $movieById;

        } else {
          Http::invalidParams();
          }
        }
      } catch (Exception $e) {
        echo 'Error en el controlador: getUserById()',  $e->getMessage(), "\n";
      }
    }


    /**
     * Método para verificar si el usuario existe.
     * @param  string ($email)
     * @return boolean true|false
     */
    public static function verifyIfUserExist($email, $username) {

      $alias = array(
        "email" => $email,
        "username" => $username
      );

      $userExist = (array)UsersController::getOneUserByEmailUsername($alias, array(), 'OR'); 

      if (array_key_exists('email', $userExist) && $userExist['email'] == $email ||
          array_key_exists('username', $userExist) && $userExist['username'] == $username
      ) return true;

      else return false;
    }


    /**
     * Método para insertar usuarios.
     * @param  Array ($param)
     *    {
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *     } 
     */
    public static function signUp($params) {
    try {
      $email = $params['email'];  
      $username = $params['username'];
      
      if (UsersController::verifyIfUserExist($email, $username)) {

        Http::forbidden('¡El usuario ya existe!');
    
    } else {

      $username = $params['username'];
      $password = password_hash($params['password'], PASSWORD_BCRYPT, ["cost"=>10]);
      $role = $params['role'];
      $MoviesModel =  new UsersModel(0, $username, $email, $password, $role);
      $MoviesModel->insert();
    
      Http::ok('¡El usuario fue ingresado con exito!');
    
      }
    } catch (Exception $e) {
      echo 'Error en el controlador: signUp()',  $e->getMessage(), "\n";
    }
  }

    /**
     * Método para actualizar usuario.
     * @param  Array ($param)
     *    {
     *      "id": 1
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *     } 
     */
    public static function updateUser($param) {
      try {
        $auth = Http::authJWT();       
        if ($auth) { 
         $id = $param['id'];
         $userName = $param['username'];
         $email = $param['email'];
         $password = '';
        
         if($param['password'] !== '') {

          $password = password_hash($param['password'], PASSWORD_BCRYPT, ["cost"=>10]);
         }
         $role = $param['role'];

       if (UsersController::verifyIfUserExist($email, $userName)) {
  
        $MoviesModel =  new UsersModel($id, $userName, $email, $password, $role);
        $wherecondition = array(" id" . " = " . $id);
    
        $MoviesModel->update($wherecondition);
        
        Http::ok('¡El usuario fue actualizado con exito!');
      
        } 
      }
    } catch (Exception $e) {
        echo 'Error en el controlador: updateUser()',  $e->getMessage(), "\n";
    }
  }



    /**
     * Método para borrar usuario.
     * @param  Integer ($param)
     *    { 
     *      "id": 1
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *    } 
     */
    public static function deleteUser($param) {
      try {
        $auth = Http::authJWT();       
        
        if ($auth) { 
          $id = (int)$param['id'];
          $userName = $param['username'];
            $email = $param['email'];


        UsersModel::delete(array(" id" . " = " . $id, 
                            " username" . " = " . strQuote($userName),
                            " email" . " = " . strQuote($email),
                          ));
        }
      } catch (Exception $e) {
        echo 'Error en el controlador: deleteUser()',  $e->getMessage(), "\n";
      }
    }



    /**
     * Método para logear usuario.
     * @param  UserModel ($param)
     *    { 
     *      "id": 1
     *      "username": "martin3604",
     *      "email": "117113",
     *      "password": "1234",
     *      "role": "user"
     *    } 
     */
    public static function login($param) {
      try {
       
       $email = '';
       $username = '';
       $password = $param['password'];

       if (!empty($param['email'])) {

        $isEmail = filter_var($param['email'], FILTER_VALIDATE_EMAIL); 
          if ($isEmail) $email = $param['email'];
             else  $username = $param['email'];
       
      }
     
      $alias = array(
        "email" => $email,
        "username" => $username
      );

       if (!empty($password)) { 
       
        $user = (array)UsersController::getOneUserByEmailUsername($alias, array(), 'AND');

        if (count($user) > 0 && password_verify($password, $user['password']) ) {
          
           $token = Http::generateToken($user);

            return array(
              'jwt' => $token,
              'id' => $user['id'],
              'email' => $user['email'],
              'username' => $user['username'],
              'role' => $user['role'],
            );

          } else {

            Http::forbidden();
          }

        } else {

          Http::invalidParams();
        }
        
      } catch (Exception $e) {
        echo 'Error en el controlador: login()',  $e->getMessage(), "\n"; 
      }
    }
  }
?>