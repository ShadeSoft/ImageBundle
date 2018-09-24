@echo off
"vendor/bin/phpunit" --bootstrap vendor/autoload.php tests
rm tests/img/test.jpg
