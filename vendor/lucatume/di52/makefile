php52 = /Applications/Mamp/bin/php/php5.2.17/bin/php
benchmarksFolder = /Users/Luca/Repos/php-dependency-injection-benchmarks

test:
	$(php52) vendor/bin/phpunit-php52 -v
	vendor/bin/phpunit-php52 -v

benchmark:
	cd $(benchmarksFolder); \
	php test1-5_runner.php; \
	ls -p -t ./results/test1-5_results-*.html | head -1 | xargs open;

benchmark6:
	cd $(benchmarksFolder); \
	php test6_runner.php; \
	ls -p -t ./results/test6_results-*.html | head -1 | xargs open;

cover:
	vendor/bin/phpunit-php52 --coverage-html ./tests/coverage tests
	open ./tests/coverage/index.html
