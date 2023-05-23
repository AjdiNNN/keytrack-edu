
<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/MouseDao.class.php';

class MouseService extends BaseService{

  public function __construct(){
    parent::__construct(new MouseDao());
  }
}
?>
