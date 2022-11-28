<?php
   
namespace App\Router;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Http;
use App\Modules\Movies\MoviesController;
use App\Modules\Users\UsersController;
use Exception;

   class Router {
      
    private const GET_METHOD = 'GET';
    private const POST_METHOD = 'POST';
    private const PUT_METHOD = 'PUT';
    private const DELETE_METHOD = 'DELETE';
    private static array $ROUTER_LIST;

    public function __construct() {
      
      Router::$ROUTER_LIST[Router::GET_METHOD] = array(
          
        // Movies GET routes
        "/getAllMovies" => function(){
          return MoviesController::getAllMovies();
        },
        "/getMoviesByCategory" => function($params){
          return MoviesController::getMoviesByCategory($params);
        },
        "/getMoviesByMovieId" => function($params){
          return MoviesController::getMoviesByMovieId($params);
        },
        "/getFavouriteMovies" => function($params){
          return MoviesController::getFavouriteMovies($params);
        },
        // Users GET routes
        "/getAllUsers" => function(){
          return UsersController::getAllUsers();
        },
        "/getUserById" => function($params){
          return UsersController::getUserById($params);
        },
      ); 
   
      Router::$ROUTER_LIST[Router::POST_METHOD] = array(
          // Movies POST routes
          "/createMovie" => function($params){
            return MoviesController::createMovie($params);
          },
          // Movies DELETE routes
          "/deleteMovie" => function($params){
            return MoviesController::deleteMovie($params);
          },
          // User POST routes
          "/signUp" => function($params){
            return UsersController::signUp($params);
          },

          "/login" => function($params){
            return UsersController::login($params);
          }

      ); 

      Router::$ROUTER_LIST[Router::PUT_METHOD] = array(
           // Movies PUT routes
           "/updateMovie" => function($params){
            return MoviesController::updateMovie($params);
           },
           // User PUT routes
           "/updateUser" => function($params){
            return UsersController::updateUser($params);
           }
      );
      
      Router::$ROUTER_LIST[Router::DELETE_METHOD] = array(
            // Movies DELETE routes
            "/deleteMovie" => function($params){
              return MoviesController::deleteMovie($params);
            },
            // User DELETE routes
            "/deleteUser" => function($params){
              return UsersController::deleteUser($params);
      }
    );
  }

 /**
  * Método para manejar el ruteo de las APIS.
  * @return Function (sqlQueryResult)
  */
  public function router(){
  
  try {

      $method = $_SERVER['REQUEST_METHOD'];
      $endpoint = $_SERVER['PATH_INFO'];
  
      // Valido que el metodo HTTP sea el correcto.
    // if (!array_key_exists($method, Router::$ROUTER_LIST)) return Http::methodNotFound();
  
     // Valido que el endopint se encuentre en el listado de endopints.
     if (!array_key_exists($endpoint, Router::$ROUTER_LIST[$method])) return Http::urlNotFound();
  
     $callbackController = Router::$ROUTER_LIST[$method][$endpoint];

      if ($_SERVER['REQUEST_METHOD'] !== Router::GET_METHOD) {
  
        $requestBody = file_get_contents('php://input');
        $jsonContent = json_decode($requestBody, true);

        return $callbackController($jsonContent);
      }

    return $callbackController($_GET);

    } catch (Exception $e) {
      echo 'Error en el router: ',  $e->getMessage(), "\n";
    }
  }
}
 
?>