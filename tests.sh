#!/bin/bash

# Install PHPUnit
#curl -L https://phar.phpunit.de/phpunit.phar -o phpunit.phar
#chmod +x phpunit.phar
#mv phpunit.phar /usr/local/bin/phpunit

# Git (+Github)

# git tag -a v0.1.0 -m 'first version'
# git push origin v0.1.0
# git tag -l


phpunit \
  --verbose \
  --bootstrap ./vendor/autoload.php \
  --colors=auto \
  tests/

## EOF ##