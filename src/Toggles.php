<?php

namespace Osinka\Toggles;

class Toggles {
  const TOGGLES_COOKIE = "toggles";

  public static $toggles = array();

  function __construct(array $toggleDef) {
    $inCookie = null;
    if (array_key_exists(self::TOGGLES_COOKIE, $_COOKIE)) {
      $inCookie = explode('|', $_COOKIE[self::TOGGLES_COOKIE]);
    }


    foreach ($toggleDef as $id => $def) {
      if ($def === FALSE) {
      } elseif (isset($inCookie)) {
        if (in_array($id, $inCookie)) {
          self::$toggles[$id] = TRUE;
        }
      } elseif ($def === TRUE) {
        self::$toggles[$id] = TRUE;
      } elseif (is_array($def)) {
        foreach ($def as $strategy) {
          $result = $strategy->enabled($id);
          if ($result === TRUE) {
            self::$toggles[$id] = TRUE;
          }
          if (!is_null($result)) {
            break;
          }
        }
      }
    }

    array_unique(self::$toggles);

    if (!isset($inCookie)) {
      setrawcookie(self::TOGGLES_COOKIE, implode('|', self::current()));
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