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
  public function get_total_sessions($user_id)
  {
      return $this->query_unique(" SELECT COUNT(*) AS total_sessions FROM sessions WHERE user_id = :user_id LIMIT 1", ['user_id' => $user_id]);
  }
  public function get_average_session($user_id)
  {
      return $this->query_unique("SELECT AVG(TIMESTAMPDIFF(MINUTE, start, end)) AS avg_session_duration FROM sessions WHERE user_id = :user_id", ['user_id' => $user_id]);
  }
  public function get_total_keyboard($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS total_keyboard_presses FROM keyboard WHERE session_id IN (SELECT id FROM sessions WHERE user_id = :user_id);", ['user_id' => $user_id]);
  }
  public function get_total_mouse($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS total_mouse_clicks FROM mouse WHERE session_id IN (SELECT id FROM sessions WHERE user_id = :user_id)", ['user_id' => $user_id]);
  }
  public function get_total_new_lines($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS number_of_new_lines FROM keyboard k INNER JOIN sessions s ON k.session_id = s.id WHERE k.pressed = 'Key.enter' AND s.user_id = :user_id", ['user_id' => $user_id]);
  }
  public function get_most_frequenet_button($user_id)
  {
      return $this->query_unique("SELECT pressed, COUNT(*) AS count FROM keyboard k INNER JOIN sessions s ON k.session_id = s.id WHERE s.user_id = :user_id AND pressed NOT REGEXP '^[0-9]+$' GROUP BY pressed ORDER BY count DESC LIMIT 1;", ['user_id' => $user_id]);
  }
}
?>