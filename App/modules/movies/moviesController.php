<?php

namespace App\Modules\Movies;
use App\Modules\Movies\MoviesModel;
use App\Utils\Http;
use Exception;


class MoviesController {
      
 /**
  * Método para seleccionar todas las peliculas.
  * @return MoviesModel[] ($movieList)
  */
  public static function getAllMovies() {
    try {
      $auth = Http::authJWT();       
        
      if ($auth) { 
        $movieList = MoviesModel::findAll(); 
        return $movieList;
      }
    } catch (Exception $e) {
      echo 'Error en el controlador: getAllMovies()',  $e->getMessage(), "\n";
    }
  }

 /**
  * Método para seleccionar peliculas por categoria.
  * 
  * @param Array ($params) 
  * @return MoviesModel[] ($movieListByCategory)
 */
 public static function getMoviesByCategory(array $params) {
  
  try {

    $auth = Http::authJWT();       
    if ($auth) { 

        $wherecondition = array(" category" . " = " . $params['category']);
        $movieListByCategory = MoviesModel::findAll($wherecondition); 
        
        return $movieListByCategory; 

      }
   } catch (Exception $e) {
     echo 'Error en el controlador: getMoviesByCategory()',  $e->getMessage(), "\n";
   }
 }

/**
  * Método para seleccionar peliculas por like.
  * 
  * @param Array ($params) 
  * @return MoviesModel[] ($movieById)
 */
 public static function getFavouriteMovies(array $params) {
   
  try {
    $auth = Http::authJWT();       
    if ($auth) { 

      $wherecondition = array(" like_status" . " = " . $params['like']);
      $movieListByLike = MoviesModel::findAll($wherecondition); 
  
      return $movieListByLike; 
    }
  } catch (Exception $e) {
    echo 'Error en el controlador: getMoviesByCategory()',  $e->getMessage(), "\n";
  }
 }


 /**
  * Método para seleccionar peliculas por id.
  * 
  * @param Array ($params) 
  * @return MoviesModel[] ($movieListByLike)
  */
 public static function getMoviesByMovieId($param) {
  try {
    $auth = Http::authJWT();       
    
    if ($auth) { 
      if ($param['id']) {
        $wherecondition = array("id" . " = " . $param['id']);
        $movieById = MoviesModel::findOne($wherecondition); 
      
        return $movieById;
        }
      }
    } catch (Exception $e) {
      echo 'Error en el controlador: getMoviesByMovieId()',  $e->getMessage(), "\n";
    }
 }

  /**
   * Método para insertar una pelicula.
   * 
   * @param Array (params) 
   */
 public static function createMovie($params) {
  try {
    $auth = Http::authJWT();       
    if ($auth) { 

      $movieName = $params['movieName'];
      $releaseYear = $params['releaseYear'];
      $director = $params['director'];
      $coverImage = $params['coverImage'];
      $actors =  explode(",", $params['actors']);
      $trailer = $params['trailer'];
      $category = $params['category'];

      $MoviesModel =  new MoviesModel(0, $movieName, $releaseYear, $director, $coverImage, $actors, $trailer, $category);
      $MoviesModel->insert();
    }
  } catch (Exception $e) {
    echo 'Error en el controlador: createMovie() ',  $e->getMessage(), "\n";
  }
 }


  /**
   * Método para actualizar una pelicula.
   * 
   * @param Array (params) 
   */
 public static function updateMovie($params){
  try {
    
      $auth = Http::authJWT();       
      if ($auth) { 

        $id = $params['id'];
        $movieName = $params['movieName'];
        $releaseYear = (int)$params['releaseYear'];
        $director = (string)$params['director'];
        $coverImage = (string)$params['coverImage'];
        $actors =  explode(",", $params['actors']);
        $trailer = $params['trailer'];
        $category = $params['category'];
        $MoviesModel =  new MoviesModel(0, $movieName, $releaseYear, $director, $coverImage, $actors, $trailer, $category);
      echo $id;
        $wherecondition = array(" id" . " = " . $id);
        $MoviesModel->update($wherecondition);
      }
    } catch (Exception $e) {
      echo 'Error en el controlador: updateMovie() ',  $e->getMessage(), "\n";
    }
 }
 
  /**
   * Método para borrar una pelicula.
   * 
   * @param Array (params) 
   */
 public static function deleteMovie($params) {
  try {
      $auth = Http::authJWT();       
      if ($auth) { 
        $id = $params['id'];
        $wherecondition = array(" id" . " = " . $id);
        MoviesModel::delete($wherecondition);
      }
    } catch (Exception $e) {
    echo 'Error en el controlador: deleteMovie() ',  $e->getMessage(), "\n";
      }
    }
  }
?>