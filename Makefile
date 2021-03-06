build: vendor

vendor: composer.json
	composer install
	touch vendor
	
clean:
	rm -fr vendor

check: vendor
	./vendor/bin/phpcs --extensions=php --standard=PSR2 -s libs/
	./vendor/bin/phpmd libs/ text phpmd.xml

phpdoc: vendor
	rm -fr phpDocs/
	mkdir -p phpDocs/
	./vendor/bin/phpdoc -f snippets.php -d libs/ -t phpDocs/

test:
	./vendor/bin/phpunit -c ./