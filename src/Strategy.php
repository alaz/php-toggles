<?php

namespace Osinka\Toggles;

abstract class Strategy {
  abstract function enabled($toggle);
}

class InternalNet extends Strategy {
  function enabled($toggle) {
    global $_SERVER;

    $ip = $_SERVER['REMOTE_ADDR'];
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) ) {
      return true;
    }
    return null;
  }
}

?>