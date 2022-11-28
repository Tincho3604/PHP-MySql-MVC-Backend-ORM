<?php

 /**
  * Funcion que agrega comillasa los strings para las consultas sql.
  * @return String ($field)
  */
  function strQuote($field){
    return gettype($field) === 'string' ? "'".str_replace("'", "'", $field)."'" : $field;
  }

 /**
  * Funcion que imprime valores y columnas para crear consultas sql.
  * @return String ($field)
  */
  function printSqlValues($arrayValues, $printColumnValue = false) {
    $sqlQuery = '';
    $i = 0;

    foreach($arrayValues as $key => $columnName) {
        $sqlQuery .=  $printColumnValue ? $key ." = ". $columnName : $columnName;
        $sqlQuery .=  $i === count($arrayValues) - 1 ? " " : ", ";
        $i++;
    }

    return  $sqlQuery;
  }

 /**
  * Funcion recorre y retorna los valores de string de un array.
  * @return String ($field)
  */
  function printArrayStringValues($arrayValues) {
    $sqlQuery = '';

    foreach($arrayValues as $values) {
        $sqlQuery .=  $values . " ,";
    }

    return  $sqlQuery;
  }

 /**
  * Funcion que transforma un array en un string.
  * @return String ($field)
  */
  function convertArrayToString($string) {
      return strQuote(implode(",", $string));
  }

  
 /**
  * Funcion que transforma un string en un array.
  * @return String ($field)
  */
  function convertStringToArray($array) {
    return explode(",", $array);
}
?>