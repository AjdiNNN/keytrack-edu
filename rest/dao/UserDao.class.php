<?php

require_once __DIR__.'/BaseDao.class.php';

class UserDao extends BaseDao
{
  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("users");
  }

  public function get_user_by_username($username){
    return $this->query_unique("SELECT * FROM users WHERE username = :username LIMIT 1", ['username' => $username]);
  }

  public function get_user_by_email($email){
    return $this->query_unique("SELECT * FROM users WHERE email = :email LIMIT 1", ['email' => $email]);
  }





}

?>