Koine Mvc
-----------------

Another very simple MVC framework.

Code information:

[![Build Status](https://travis-ci.org/koinephp/Mvc.png?branch=master)](https://travis-ci.org/koinephp/Mvc)
[![Coverage Status](https://coveralls.io/repos/koinephp/Mvc/badge.png?branch=master)](https://coveralls.io/r/koinephp/Mvc?branch=master)
[![Code Climate](https://codeclimate.com/github/koinephp/Mvc.png)](https://codeclimate.com/github/koinephp/Mvc)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koinephp/Mvc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/koinephp/Mvc/?branch=master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/koine/mvc/v/stable.svg)](https://packagist.org/packages/koine/mvc)
[![Total Downloads](https://poser.pugx.org/koine/mvc/downloads.svg)](https://packagist.org/packages/koine/mvc)
[![Latest Unstable Version](https://poser.pugx.org/koine/mvc/v/unstable.svg)](https://packagist.org/packages/koine/mvc)
[![License](https://poser.pugx.org/koine/mvc/license.svg)](https://packagist.org/packages/koine/mvc)

### Usage

```php
```

### Installing

#### Via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/mvc": "dev-master"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

### Issues/Features proposals

[Here](https://github.com/koinephp/mvc/issues) is the issue tracker.

### Contributing

Only TDD code will be accepted. Please follow the [PSR-2 code standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

```bash
phpunit --configuration tests/phpunit.xml
```

### To check the code standard run:

```bash
phpcs --standard=PSR2 lib
phpcs --standard=PSR2 tests
```

### Lincense
[MIT](MIT-LICENSE)

### Authors

- [Marcelo Jacobus](https://github.com/mjacobus)
