<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/KeyboardDao.class.php';

class KeyboardService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new KeyboardDao());
    }

    public function get_keyboard_from_session($id)
    {
        return $this->dao->get_keyboard_from_session($id);
    }

    public function get_most_frequent_button($user_id)
    {
        return $this->dao->get_most_frequent_button($user_id);
    }

    public function get_total_new_lines($user_id)
    {
        return $this->dao->get_total_new_lines($user_id);
    }

    public function get_total_keyboard($user_id)
    {
        return $this->dao->get_total_keyboard($user_id);
    }

    public function get_average_time_between_press($user_id)
    {
        return $this->dao->get_average_time_between_press($user_id);
    }
}
?>