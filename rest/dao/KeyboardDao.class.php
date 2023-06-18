<?php
require_once __DIR__.'/BaseDao.class.php';

class KeyboardDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("keyboard");
  }
  public function get_keyboard_from_session($id)
  {
      return $this->query("SELECT pressed, pressedAt FROM keyboard WHERE session_id = :id AND pressed NOT REGEXP '^[0-9]+$'", ['id' => $id]);
  }

  public function get_most_frequenet_button($user_id)
  {
      return $this->query("SELECT pressed, COUNT(*) AS count FROM keyboard k INNER JOIN sessions s ON k.session_id = s.id WHERE s.user_id = :user_id AND pressed NOT REGEXP '^[0-9]+$' GROUP BY pressed ORDER BY count DESC LIMIT 4", ['user_id' => $user_id]);
  }

  public function get_total_new_lines($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS number_of_new_lines FROM keyboard k INNER JOIN sessions s ON k.session_id = s.id WHERE k.pressed = 'Key.enter' AND s.user_id = :user_id", ['user_id' => $user_id]);
  }
  public function get_total_keyboard($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS total_keyboard_presses FROM keyboard WHERE session_id IN (SELECT id FROM sessions WHERE user_id = :user_id);", ['user_id' => $user_id]);
  }
  public function get_average_time_between_press($user_id)
  {
      return $this->query_unique("SELECT AVG(time_diff) AS avg_time_between_presses FROM ( SELECT TIMESTAMPDIFF(SECOND, prev_press_time, pressedAt) AS time_diff FROM ( SELECT pressedAt, LAG(pressedAt) OVER (ORDER BY pressedAt) AS prev_press_time, session_id FROM keyboard ) AS keyboard_with_prev_press WHERE session_id IN ( SELECT id FROM sessions WHERE user_id = :user_id ) ) AS time_diffs", ['user_id' => $user_id]);
  }

}

?>