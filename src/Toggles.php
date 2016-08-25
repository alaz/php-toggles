<?php

namespace Osinka\Toggles;

class Toggles {
  const TOGGLES_COOKIE = "toggles";

  public static $toggles = array();

  function __construct(array $toggleDef, $cookieTTL = 0) {
    $inCookie = null;
    if (array_key_exists(self::TOGGLES_COOKIE, $_COOKIE)) {
      $inCookie = explode('|', $_COOKIE[self::TOGGLES_COOKIE]);
    }

    $forCookie = array();
    foreach ($toggleDef as $id => $def) {
      if ($def === FALSE) {
      } elseif ($def === TRUE) {
        self::$toggles[$id] = TRUE;
      } elseif (isset($inCookie)) {
        if (in_array($id, $inCookie)) {
          $forCookie[] = $id;
          self::$toggles[$id] = TRUE;
        }
      } elseif (is_array($def)) {
        foreach ($def as $strategy) {
          $result = $strategy->enabled($id);
          if ($result === TRUE) {
            $forCookie[] = $id;
            self::$toggles[$id] = TRUE;
          }
          if (!is_null($result)) {
            break;
          }
        }
      }
    }

    array_unique($forCookie);
    sort($forCookie);
    $newCookie = implode('|', $forCookie);

    if ($newCookie != $_COOKIE[self::TOGGLES_COOKIE]) {
      $expire = $cookieTTL == 0 ? 0 : time() + $cookieTTL;
      setrawcookie(self::TOGGLES_COOKIE, $newCookie, $expire);
    }
  }

  public static function active($id) {
    return array_key_exists($id, self::$toggles);
  }

  public static function current() {
    return array_keys(self::$toggles);
  }
}

?>