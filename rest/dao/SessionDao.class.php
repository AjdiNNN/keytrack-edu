<?php
require_once __DIR__.'/BaseDao.class.php';

class SessionDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("sessions");
  }
  public function get_session($id)
  {
      return $this->query("SELECT * FROM sessions WHERE user_id = :id;", ['id' => $id]);
  }
  public function get_unique_session($id)
  {
      return $this->query_unique("SELECT s.id AS session_id, COUNT(DISTINCT k.pressed) AS distinct_keys_pressed FROM sessions s INNER JOIN keyboard k ON k.session_id = s.id WHERE s.user_id = :id GROUP BY s.id ORDER BY distinct_keys_pressed DESC LIMIT 1;", ['id' => $id]);
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
  public function get_most_active_day($user_id)
  {
      return $this->query("SELECT DAYNAME(start) AS active_day, COUNT(*) AS activity_count FROM sessions WHERE user_id = :user_id GROUP BY active_day ORDER BY activity_count DESC LIMIT 1", ['user_id' => $user_id]);
  }
  public function get_average_session($user_id)
  {
      return $this->query_unique("SELECT AVG(TIMESTAMPDIFF(MINUTE, start, end)) AS avg_session_duration FROM sessions WHERE user_id = :user_id", ['user_id' => $user_id]);
  }
  public function get_total_sessions($user_id)
  {
      return $this->query_unique(" SELECT COUNT(*) AS total_sessions FROM sessions WHERE user_id = :user_id LIMIT 1", ['user_id' => $user_id]);
  }
  public function get_session_owner($id)
  {
      return $this->query_unique("SELECT user_id FROM sessions WHERE id = :id", ['id' => $id]);
  }
  public function get_sessions($id)
  {
      return $this->query("SELECT * FROM sessions WHERE user_id = :id;", ['id' => $id]);
  }
}
?>