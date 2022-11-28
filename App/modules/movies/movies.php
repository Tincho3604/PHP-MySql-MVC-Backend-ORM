<?php

namespace App\Modules\Movies;
use App\Modules\Movies\MoviesModel;

class Movies extends MoviesModel {
    
    public function __construct($movieName, $releaseYear, $director, $coverImage, $actors, $trailer) {		
        $this->movieName = $movieName;
        $this->releaseYear = $releaseYear;
        $this->director = $director;
        $this->coverImage = $coverImage;
        $this->actors = $actors;
        $this->trailer = $trailer;
      }

} 

?>