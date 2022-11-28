<?php 
namespace App\Config;

/* Files*/
require_once 'config.php';

class Database {

	private $host;
	private $db;
	private $user;
	private $pass;
	public $conection;

	public function __construct() {		

		$this->host = constant('DB_HOST');
		$this->db = constant('DB');
		$this->user = constant('DB_USER');
		$this->pass = constant('DB_PASS');
	  }
	  
	  public function connect() {
		$conection = mysqli_connect($this->host, $this->user, $this->pass, $this->db) or die ("No se pudo establecer una conexión");

		return $conection;
	}
   }
?>

