
# Php-subreg

![Php-subreg Logo](php-subreg-logo.png?raw=true "Project Logo")

CZ: PHP Knihovna pro snadnou práci s API [Subreg.cz](https://subreg.cz/manual/)

[![View php-subreg on GitHub](https://img.shields.io/github/stars/Spoje-NET/php-subreg?color=232323&label=php-subreg&logo=github&labelColor=232323)](https://github.com/Spoje-NET/php-subreg)
[![Author Spoje-NET](https://img.shields.io/badge/Spoje-NET-b820f9?labelColor=b820f9&logo=githubsponsors&logoColor=fff)](https://github.com/Spoje-NET) ![Written in PHP](https://img.shields.io/static/v1?label=&message=PHP&color=777BB4&logo=php&logoColor=FFFFFF)

[![Latest Version](https://img.shields.io/github/release/Spoje-NET/php-subreg.svg?style=flat-square)](https://github.com/Spoje-NET/php-subreg/releases)
[![Software License](https://img.shields.io/badge/license-GNU-brightgreen.svg?style=flat-square)](https://github.com/Spoje-NET/php-subreg/blob/master/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/spoje.net/php-subreg.svg?style=flat-square)](https://packagist.org/packages/spoje.net/php-subreg)
[![Latest stable](https://img.shields.io/packagist/v/spoje.net/php-subreg.svg?style=flat-square)](https://packagist.org/packages/spoje.net/php-subreg)

# Poděkování

Vznik této knihovny by nebyl možný bez laskavé podpory společnosti [Spoje.Net](http://www.spoje.net),
která hradila vývoj řešení pro navýšení kreditu registrace domén služby. :+1:

![Spoje.Net](https://github.com/Spoje-NET/php-subreg/raw/master/spoje-net_logo.gif "Spoje.Net")

U společnosti Spoje.Net, je možné si objednat komerční podporu pro integraci
knihovny do vašich projektů.

## Instalace

```bash
    composer require spojenet/subreg
```

## Konfigurace


Konfigurace se provádí nastavením následujících konstant:

```env
EASE_LOGGER=syslog|console
SUBREG_LOCATION=https://soap.subreg.cz/cmd.php
SUBREG_URI=https://soap.subreg.cz/soap
SUBREG_LOGIN=spojenetapi#spoje.net
SUBREG_PASSWORD=KfbBPb?Uk6Q@%uca

```

nebo je možné přihlašovací údaje zadávat při vytváření instance třídy.

```php
    $sr = new \Subreg\Client([
        "location": "https://ote-soap.subreg.cz/cmd.php",
        "uri": "https://ote-soap.subreg.cz/soap",
        "login": "php-subreg",
        "password": "661a2725fb"
            ]);
```

Tento způsob nastavení má vyšší prioritu než výše uvedené definovaní konstant.

## Jak to celé funguje ?


Ústřední komponentou celé knihovny je Třída Client, která je schopna pomocí
PHP rozšíření SoapClient komunikovat se soap.subreg.cz.

<http://demoreg.net/en/settings/settings>

