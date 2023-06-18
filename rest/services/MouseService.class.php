<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/MouseDao.class.php';

class MouseService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new MouseDao());
    }

    public function get_mouse_from_session($id)
    {
        return $this->dao->get_mouse_from_session($id);
    }

    public function get_total_mouse_movement_distance($user_id)
    {
        return $this->dao->get_total_mouse_movement_distance($user_id);
    }

    public function get_total_mouse($user_id)
    {
        return $this->dao->get_total_mouse($user_id);
    }
}
?>
