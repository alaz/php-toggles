## Feature Toggles, aka. Features, Toggles, Flags, etc.

* [Martin Fowler: Feature Toggles](http://martinfowler.com/articles/feature-toggles.html)
* [Martin Fowler: FeatureToggle](http://martinfowler.com/bliki/FeatureToggle.html)
* [PHP: Feature Flags, Toggles, Controls](http://featureflags.io/php-feature-flags/)

## Why another library?

* Very short & simple
* Compatible with its Play Framework counterpart: [play-toggles](https://github.com/osinka/play-toggles)

## Features

* Calculates strategies only once per browser session and then caches the result in a session cookie
* `Gradual` rollout strategy does not depend on user ID per se. Instead, it may be given any user-related textual ID.
* `Whitelist` and `Blacklist` depend on the same user ID.
* Provides `AnonymousOn` (enables a feature for anonymous users) and `AnonymousOff` (disables a feature for anonymous users)

## Use

Init:

```php
use Osinka\Toggles\Toggles;
use Osinka\Toggles\Strategies;

class UserStrategies extends Osinka\Toggles\UserStrategies {
  function user_id() {
    # return `null` when the current user is anonymous and
    # user ID if he/she is authenticated
  }
}

$UserStrategies = new UserStrategies();

$Toggles = new Toggles(
  array(
    'socialBlock' => array(
      Strategies::internalNet(),
      $UserStrategies->anonymousOff(),
      $UserStrategies->whitelist(array("012345", "98765")),
      $UserStrategies->blacklist(array("678")),
      $UserStrategies->gradual(10) # 10%
    ),
    'testedAndOn' => TRUE,
    'inDevelopment' => FALSE
  )
);
```

Check anywhere:

```php
if (Toggles::active('socialBlock')) {
 # display new social block
}
```
