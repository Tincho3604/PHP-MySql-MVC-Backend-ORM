<?php

namespace App\Utils;

class FilesModule {
 /**
  *  Metodo utilizado para crear los loogers.
  * @param String { $log } correspondiente al log.
  */
  public static function Logger($log) {
    
  $path = __DIR__.'/../logs/log.txt';
  
  $file = false;
  if ( !file_exists($path) ) {
    $file = fopen( $path, "w") or die("Error al intentar crear el log.txt");
  } else {
    $file =  fopen( $path, "a") or die("Error al intentar editar el log.txt");
  }

    $time = date('m/d/y h:iA', time());
    $content = file_get_contents($path);
    $content .= "$time:"."\t".$log."\n"; 

   file_put_contents($path, $content);

   fclose ($file);
  }
}
?>
