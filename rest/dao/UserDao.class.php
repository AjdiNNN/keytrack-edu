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
      return $this->query("SELECT pressed, COUNT(*) AS count FROM keyboard k INNER JOIN sessions s ON k.session_id = s.id WHERE s.user_id = :user_id AND pressed NOT REGEXP '^[0-9]+$' GROUP BY pressed ORDER BY count DESC LIMIT 4", ['user_id' => $user_id]);
  }
  public function get_most_active_day($user_id)
  {
      return $this->query("SELECT DAYNAME(start) AS active_day, COUNT(*) AS activity_count FROM sessions WHERE user_id = :user_id GROUP BY active_day ORDER BY activity_count DESC LIMIT 1", ['user_id' => $user_id]);
  }
  public function get_total_mouse_movement_distance($user_id)
  {
      return $this->query_unique("
          SELECT SUM(
              SQRT(POW(m2.x - m1.x, 2) + POW(m2.y - m1.y, 2))
          ) AS total_distance
          FROM mouse m1
          JOIN mouse m2 ON m2.id = (
              SELECT MIN(id)
              FROM mouse
              WHERE session_id = m1.session_id
                AND id > m1.id
          )
          WHERE m1.session_id IN (
              SELECT id
              FROM sessions
              WHERE user_id = :user_id
          )
      ", ['user_id' => $user_id]);
  }
  public function get_average_time_between_press($user_id)
  {
      return $this->query_unique("SELECT AVG(time_diff) AS avg_time_between_presses FROM ( SELECT TIMESTAMPDIFF(SECOND, prev_press_time, pressedAt) AS time_diff FROM ( SELECT pressedAt, LAG(pressedAt) OVER (ORDER BY pressedAt) AS prev_press_time, session_id FROM keyboard ) AS keyboard_with_prev_press WHERE session_id IN ( SELECT id FROM sessions WHERE user_id = :user_id ) ) AS time_diffs", ['user_id' => $user_id]);
  }
  public function get_mouse_from_session($id)
  {
      return $this->query("SELECT CONCAT(x,',', y) AS position, pressedAt FROM mouse WHERE session_id = :id", ['id' => $id]);
  }
  public function get_keyboard_from_session($id)
  {
      return $this->query("SELECT pressed, pressedAt FROM keyboard WHERE session_id = :id AND pressed NOT REGEXP '^[0-9]+$'", ['id' => $id]);
  }
  public function get_longest_session($id)
  {
      return $this->query_unique("SELECT s.id, SEC_TO_TIME(MAX(TIMESTAMPDIFF(SECOND, s.start, s.end))) AS session_duration FROM sessions s WHERE s.user_id = :id GROUP BY s.user_id", ['id' => $id]);
  }
  public function get_early_start_session($id)
  {
      return $this->query_unique("SELECT id, MIN(TIME_FORMAT(start, '%H:%i')) AS earliest_start_time FROM sessions WHERE user_id = :id;", ['id' => $id]);
  }
  public function get_latest_end_session($id)
  {
      return $this->query_unique("SELECT id,MAX(TIME_FORMAT(end, '%H:%i')) AS latest_end_time FROM sessions WHERE user_id = :id;", ['id' => $id]);
  }
  public function get_unique_session($id)
  {
      return $this->query_unique("SELECT s.id AS session_id, COUNT(DISTINCT k.pressed) AS distinct_keys_pressed FROM sessions s INNER JOIN keyboard k ON k.session_id = s.id WHERE s.user_id = :id GROUP BY s.id ORDER BY distinct_keys_pressed DESC LIMIT 1;", ['id' => $id]);
  }

  
}

?>