
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

Instalace
---------

    composer require spoje.net/subreg

Konfigurace
-----------

Konfigurace se provádí nastavením následujících konstant:

```php
/**
 * Write logs as:
 */
define('LOG_TYPE', 'syslog');

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

Jak to celé funguje ?
---------------------

Ústřední komponentou celé knihovny je Třída Client, která je schopna pomocí 
PHP rozšíření SoapClient komunikovat se soap.subreg.cz.

http://demoreg.net/en/settings/settings

Debian/Ubuntu
-------------

Pro Linux jsou k dispozici .deb balíčky. Prosím použijte repo:

```
sudo apt install lsb-release wget apt-transport-https bzip2

wget -qO- https://repo.vitexsoftware.com/keyring.gpg | sudo tee /etc/apt/trusted.gpg.d/vitexsoftware.gpg
echo "deb [signed-by=/etc/apt/trusted.gpg.d/vitexsoftware.gpg]  https://repo.vitexsoftware.com  $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo apt update
sudo apt install php-spojenet-subreg
```


V tomto případě je potřeba do souboru composer.json vaší aplikace přidat:

```json
    "require": {
        "php-subreg": "*",
        "ease-core": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "/usr/share/php/Subreg",
            "options": {
                "symlink": true
            }
        },
        {
            "type": "path",
            "url": "/usr/share/php/EaseCore",
            "options": {
                "symlink": true
            }
        }
    ]
```

Takže při instalaci závislostí bude vypadat nějak takto:

    Loading composer repositories with package information
    Installing dependencies from lock file
      - Installing ease-core (1.1.3.3)
        Symlinked from /usr/share/php/Ease

      - Installing php-subreg (0.2.1)
        Symlinked from /usr/share/php/Subreg

A aktualizaci bude možné dělat globálně pro celý systém prostřednictvím apt-get.

