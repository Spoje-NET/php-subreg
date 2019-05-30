all: build install

fresh:
	git pull
	composer install
build:
	echo build


clean:
	rm -rf debian/php-subreg
	rm -rf debian/subreg .phpunit.result.cache debian/subreg.debhelper.log
	rm -rf debian/subreg-doc
	rm -rf debian/*.log
	rm -rf debian/*.substvars
	rm -rf docs/*
	rm -f  debianTest/composer.lock
	rm -rf vendor/* composer.lock

apigen:
	VERSION=`cat debian/composer.json | grep version | awk -F'"' '{print $4}'`; \
	apigen generate --source src --destination docs --title "subreg ${VERSION}" --charset UTF-8 --access-levels public --access-levels protected --php --tree

test: pretest phpunit

pretest:
	composer --ansi --no-interaction update

phpunit: pretest
	vendor/bin/phpunit --bootstrap tests/bootstrap.php

deb:
	dpkg-buildpackage -A -us -uc

rpm:
	rpmdev-bumpspec --comment="Build" --userstring="Vítězslav Dvořák <info@vitexsoftware.cz>" subreg.spec
	rpmbuild -ba subreg.spec 

verup:
	git commit debian/composer.json debian/version debian/revision  -m "`cat debian/version`-`cat debian/revision`"
	git push origin master

dimage:
	docker build -t vitexsoftware/subreg .

.PHONY : install
	
