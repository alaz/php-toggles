<?php

namespace Osinka\Toggles;

abstract class UserStrategies {
  abstract function user_id();

  public function anonymousOn() {
    return new AnonymousOn($this);
  }

  public function anonymousOff() {
    return new AnonymousOff($this);
  }

  public function whitelist(array $whitelist) {
    return new Whitelist($this, $whitelist);
  }

  public function blacklist(array $blacklist) {
    return new Blacklist($this, $blacklist);
  }

  public function gradual($percentage) {
    return new Gradual($this, $percentage);
  }
}

class AnonymousOn extends Strategy {
  private $parent;

  function __construct(UserStrategies $parent) {
    $this->parent = $parent;
  }

  function enabled($toggle) {
    return is_null($this->parent->user_id()) ? true : null;
  }
}

class AnonymousOff extends Strategy {
  private $parent;

  function __construct(UserStrategies $parent) {
    $this->parent = $parent;
  }

  function enabled($toggle) {
    return is_null($this->parent->user_id()) ? false : null;
  }
}

class Whitelist extends Strategy {
  private $parent;
  private $whitelist;

  function __construct(UserStrategies $parent, array $whitelist) {
    $this->parent = $parent;
    $this->whitelist = $whitelist;
  }

  function enabled($toggle) {
    return in_array($this->parent->user_id(), $this->whitelist) ? true : null;
  }
}

class Blacklist extends Strategy {
  private $parent;
  private $blacklist;

  function __construct(UserStrategies $parent, array $blacklist) {
    $this->parent = $parent;
    $this->blacklist = $blacklist;
  }

  function enabled($toggle) {
    return in_array($this->parent->user_id(), $this->blacklist) ? false : null;
  }
}

class Gradual extends Strategy {
  private $parent;
  private $percentage;

  function __construct(UserStrategies $parent, $percentage) {
    $this->parent = $parent;
    $this->percentage = $percentage;
  }

  function enabled($toggle) {
    $s = "$toggle:".$this->parent->user_id();
    $i = ord(md5($s, true)[0]) & 0xFF;
    return $i % 100 < $this->percentage ? true : null;
  }
}

?>