<?php
require_once __DIR__.'/BaseDao.class.php';

class MouseDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("mouse");
  }
  public function get_mouse_from_session($id)
  {
      return $this->query("SELECT CONCAT(x,',', y) AS position, pressedAt FROM mouse WHERE session_id = :id", ['id' => $id]);
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
  public function get_total_mouse($user_id)
  {
      return $this->query_unique("SELECT COUNT(*) AS total_mouse_clicks FROM mouse WHERE session_id IN (SELECT id FROM sessions WHERE user_id = :user_id)", ['user_id' => $user_id]);
  }
}

?>