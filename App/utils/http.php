<?php
   namespace App\Utils;
   use \Firebase\JWT\JWT;
   use Firebase\JWT\Key;
   require_once __DIR__.'../../Config/config.php';

   class Http {

   /**
    * Método que devuelve una respuesta HTTP 404
    * @return Http => "Method Not Allowed"
    */
     public static function urlNotFound ($message = '¡La URL solicitada no existe!') {
        http_response_code(404);
        echo json_encode([
            'status' => 404,
            'message' => $message,
        ]);
    }  
  
   /**
    * Método que devuelve una respuesta HTTP 405
    * @return Http => "Not Found"
    */
     public static function methodNotFound ($message = '¡El metodo HTTP no es correcto!') {
        http_response_code(405);
        echo json_encode([
          'status' => 405,
          'message' => $message,
        ]);
    }
  
   /**
    * Método que devuelve una respuesta HTTP 200
    * @return Http => "OK"
    */
    public static function ok ($message = '¡Success!') {
        http_response_code(200);
        echo json_encode([
            'status' => 200,
            'message' => $message,
          ]);
    }

   /**
    * Método que devuelve una respuesta HTTP 500
    * @return Http => "Internal Server Error"
    */
    public static function internalServerError ($message = '¡Internal Server Error!') {
        http_response_code(500);
        echo json_encode([
            'status' => 500,
            'message' => $message,
        ]);
      }

   /**
    * Método que devuelve una respuesta HTTP 400
    * @return Http => "Invalid Params"
    */
    public static function invalidParams ($message = '¡Parametros invalidos!') {
        http_response_code(400);
        echo json_encode([
            'status' => 400,
            'message' => $message,
        ]);
      }

      
   /**
    * Método que devuelve una respuesta HTTP 401
    * @return Http => "Unauthorized"
    */
    public static function unauthorized ($message = '¡No esta autorizado!') {
        http_response_code(401);
        echo json_encode([
            'status' => 401,
            'message' => $message,
        ]);
      }

         /**
    * Método que devuelve una respuesta HTTP 403
    * @return Http => "Forbidden"
    */
    public static function forbidden ($message = '¡El recurso ya existe!') {
        http_response_code(403);
        echo json_encode([
            'status' => 403,
            'message' => $message,
        ]);
      }



     /**
      * Método que auntentica el token.
      * @return Http => "Http::ok || Http::unauthorized"
      */
      public static function authJWT(): bool 
      {
        $headers = getallheaders();

           if (array_key_exists('Authorization', $headers)) {

               $jwt = $headers['Authorization'];
               $secret_key = constant("SECRET_KEY");
               JWT::decode($jwt, new Key($secret_key, 'HS256'));

               return true;
       
        } else {

            Http::unauthorized(); 
            return false;
        }
      }


     /**
      * Método que genera token de sesión.
      * @return Http => "Http::ok"
      */
      public static function generateToken($user): string {
        $payload = [
            'iss' => "localhost",
            'aud' => 'localhost',
            'exp' => time() + 60 * 60 * 24 * 60,
            'data' => [
                'id' => $user['id'],
                'name' => $user['username'],
                'email' => $user['email'],
            ],
        ];

        $secret_key = constant("SECRET_KEY");
        $jwt = JWT::encode($payload, $secret_key, 'HS256');
       
        return $jwt;
      }
   }    
?>