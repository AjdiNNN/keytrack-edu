<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/SessionDao.class.php';

class SessionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new SessionDao());
    }

    public function get_session($id)
    {
        return $this->dao->get_session($id);
    }

    public function get_unique_session($id)
    {
        return $this->dao->get_unique_session($id);
    }

    public function get_longest_session($id)
    {
        return $this->dao->get_longest_session($id);
    }

    public function get_early_start_session($id)
    {
        return $this->dao->get_early_start_session($id);
    }

    public function get_latest_end_session($id)
    {
        return $this->dao->get_latest_end_session($id);
    }

    public function get_most_active_day($user_id)
    {
        return $this->dao->get_most_active_day($user_id);
    }

    public function get_average_session($user_id)
    {
        return $this->dao->get_average_session($user_id);
    }

    public function get_total_sessions($user_id)
    {
        return $this->dao->get_total_sessions($user_id);
    }
    public function get_session_owner($id)
    {
        return $this->dao->get_session_owner($id);
    }
    public function get_sessions($id)
    {
        return $this->dao->get_sessions($id);
    }
}
?>