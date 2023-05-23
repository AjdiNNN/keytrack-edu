
<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/KeyboardDao.class.php';

class KeyboardService extends BaseService{

  public function __construct(){
    parent::__construct(new KeyboardDao());
  }
}
?>
