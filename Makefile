default:
	echo "Please specify a task!" && exit

update:
	composer update -o

build-docs: ensure-dependencies
	./vendor/bin/phpdoc -c "./phpdoc.xml"

ensure-dependencies: composer.lock
	
composer.lock:
	composer install -o