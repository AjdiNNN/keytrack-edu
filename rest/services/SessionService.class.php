
<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/SessionDao.class.php';

class SessionService extends BaseService{

  public function __construct(){
    parent::__construct(new SessionDao());
  }
}
?>