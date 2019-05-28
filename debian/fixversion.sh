#!/bin/bash
VERSTR=`dpkg-parsechangelog --show-field Version`
COMPOSER_VERSTR=`echo ${VERSTR}|sed 's/-/./g'`
echo update debian/php-subreg/usr/share/php/Subreg/composer.json version to ${COMPOSER_VERSTR}
sed -i -e '/\"version\"/c\    \"version\": \"'${COMPOSER_VERSTR}'",' debian/php-subreg/usr/share/php/Ease/composer.json
echo Update debian/php-subreg/usr/share/php/Ease/Atom.php version to ${VERSTR}
sed -i -e "/static public \$libVersion/c\    static public \$frameworkVersion = '${VERSTR}';" debian/php-subreg/usr/share/php/Subreg/Client.php
echo Update src/Ease/Atom.php version to ${VERSTR}
sed -i -e "/static public \$libVersion/c\    static public \$frameworkVersion = '${VERSTR}';" src/Subreg/Client.php
