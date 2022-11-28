<?php

namespace App\Modules\Movies;
use App\Config\Database;
require_once __DIR__.'/../../utils/constants.php';


class MoviesModel {

    public int $id;
    public string $movieName;
    public int $releaseYear;
    public string $director;
    public string $coverImage;
    public array $actors;
    public string $trailer;
    public string $category;
    static string $tableName = "movies";


    public function __construct($id = 0, $movieName, $releaseYear, $director, $coverImage, $actors, $trailer, $category) {		
      $this->id = $id;
      $this->movieName = $movieName;
      $this->releaseYear = $releaseYear;
      $this->director = $director;
      $this->coverImage = $coverImage;
      $this->actors = $actors;
      $this->trailer = $trailer;
      $this->category = $category;
    }

  /**
   * Método para seleccionar los datos de multiples peliculas.
   * 
   * @param Array (whereConditions, columnsSelected) 
   * @return Integer (sqlQueryResult)
   */
  public static function findAll($whereConditions = array(), $columnsSelected = array(), $conditionType = '') {
    $DB_Connection = new Database();
    $Connection= $DB_Connection->connect();

    $selectedColumns = count($columnsSelected) > 0 ? printSqlValues($columnsSelected)  :  " * "; 
    $sqlQuery = " SELECT ". $selectedColumns. " FROM " . Self::$tableName; 
 
     
  if(count($whereConditions) > 0) { 
    $sqlQuery .= ' WHERE '; 
    $sqlQuery .=  join($conditionType === '' ? " AND " : ' ' . $conditionType . ' ', $whereConditions);
  }

  $result = mysqli_query($Connection, $sqlQuery);
  $listResult = mysqli_fetch_all ($result , MYSQLI_ASSOC);

   mysqli_free_result($result);
   mysqli_close($Connection);

   $listMovies = array();

  foreach ( $listResult as $value) {
 
  if (count($columnsSelected) === 0 ) {

    [
     'id'=> $id,
     'movie_name' => $movieName, 
     'release_year' => $releaseYear, 
     'director' => $director, 
     'cover_image' => $coverImage,
     'actors' => $actors, 
     'trailer' => $trailer,
     'category' => $category
     ] =  $value;

  } else {

    $id = array_key_exists('id', $value) ? $value['id'] : 0;
    $movieName = array_key_exists('movie_name', $value) ? $value['movie_name']  : '';
    $releaseYear = array_key_exists('release_year', $value) ? $value['release_year']  : 0;
    $director = array_key_exists('director', $value) ? $value['director']  : '';
    $coverImage = array_key_exists('cover_image', $value) ? $value['cover_image']  : '';
    $actors = array_key_exists('actors', $value) ? convertStringToArray($value['actors'])  : array();
    $trailer = array_key_exists('trailer', $value) ? $value['trailer']  : '';
    $category = array_key_exists('category', $value) ? $value['category']  : '';
  }
    $actors = convertStringToArray($actors);
    
    array_push($listMovies, new MoviesModel($id, $movieName, $releaseYear, $director, $coverImage, $actors, $trailer, $category));
  }      
   return $listMovies;
}

  /**
   * Método para seleccionar los datos de una pelicula.
   * 
   * @param Array (whereConditions) 
   * @return Integer (sqlQueryResult)
   */
    public static function findOne($whereConditions = array(),  $selectedColumns= array(), $conditionType = '') {
      $listMovies = MoviesModel::findAll($whereConditions,  $selectedColumns, $conditionType);
      return $listMovies ? $listMovies[0] : array();
    }


   /**
    * Método para insertar los datos de una pelicula.
    * @return Integer (sqlQueryResult)
    */
    public function insert(): int {

      $this->moviesColumnsValues = array(
        "movie_name" => strQuote($this->movieName),
        "release_year" => strQuote($this->releaseYear),
        "director" => strQuote($this->director),
        "cover_image" => strQuote($this->coverImage),
        "actors" => convertArrayToString(strQuote($this->actors)),
        "trailer" => strQuote($this->trailer), 
        "category" => strQuote($this->category),
      );


      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();

      $sqlQuery = " INSERT INTO " . Self::$tableName;
      $sqlQuery .= " ( ". printSqlValues(array_keys($this->moviesColumnsValues)) . " ) ";
      $sqlQuery .= " VALUES ( " . printSqlValues($this->moviesColumnsValues) . " ) ";

      echo $sqlQuery;
      $sqlQueryResult = mysqli_query($Connection, $sqlQuery);
      
   

     mysqli_close($Connection);
     return $sqlQueryResult;
  }

   /**
    * Método para actualizar los datos de una pelicula.
    * 
    * @param Array (whereConditions) 
    * @return Integer (sqlQueryResult)
    */
    public function update($whereConditions = array()): int {

      $this->moviesColumnsValues = array(
        "movie_name" => strQuote($this->movieName),
        "release_year" => strQuote($this->releaseYear),
        "director" => strQuote($this->director),
        "cover_image" => strQuote($this->coverImage),
        "actors" => convertArrayToString($this->actors),
        "trailer" => strQuote($this->trailer),
        "category" => strQuote($this->category),
      );

      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();
      $sqlQuery = " UPDATE " . Self::$tableName . " SET ";

      $sqlQuery .= printSqlValues($this->moviesColumnsValues, true);
    
      if(count($whereConditions) > 0) { 
        $sqlQuery .= ' WHERE '; 
        $sqlQuery .=  join(" AND ", $whereConditions);
      }
     print_r($sqlQuery);
     $sqlQueryResult = mysqli_query($Connection, $sqlQuery);
     
      mysqli_close($Connection);
            
    return  $sqlQueryResult;
  }
    

   /**
    * Método para borrar una pelicula.
    * 
    * @param Array (whereConditions) 
    * @return Integer (sqlQueryResult)
    */
    public static function delete($whereConditions = array()) {
      $DB_Connection = new Database();
      $Connection = $DB_Connection->connect();
      $sqlQuery = " DELETE FROM " . Self::$tableName;
      
      if(count($whereConditions) > 0) { 
        $sqlQuery .= ' WHERE '; 
        $sqlQuery .=  join(" AND ", $whereConditions);
      }
      print_r($sqlQuery);
      $sqlQueryResult = mysqli_query($Connection, $sqlQuery);
     
      mysqli_close($Connection);
            
    return $sqlQueryResult;
    }
  }
?>