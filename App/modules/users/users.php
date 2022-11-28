<?php

namespace App\Modules\Users;
use App\Modules\Users\UsersModel;

  class User extends UsersModel {
    public function __construct($userName = '', $email = '', $password = '', $role = '') {		
      $this->username = $userName;
      $this->email = $email;
      $this->password = $password;
      $this->role = $role;
    }
  }

?>

