# WARP.md - Working AI Reference for php-spojenet-subreg

## Project Overview
**Type**: PHP library + Debian package (`php-spojenet-subreg`)
**Purpose**: SOAP client for https://soap.subreg.cz (Gransy/Subreg domain registrar)
**Repository**: git@github.com:Spoje-NET/php-subreg.git
**Package**: `spojenet/subreg` on Packagist / `php-spojenet-subreg` .deb

## Key Technologies
- PHP >= 8.1, ext-soap
- vitexsoftware/ease-core
- Debian packaging via phpab + pkg-php-tools

## Architecture
```
src/Subreg/
  Client.php       # main API client + high-level helpers
  ApiException.php # status=error → exception with major/minor
  Commands.php     # 1:1 wrappers for Subreg commands
  Orders.php       # Make_Order type helpers
Examples/          # CLI samples (incl. DomainHealth.php)
tests/             # offline helpers + live integration tests
```

## Gotchas learned in production (2026-10)
- Autorenew needs `credit.amount >= credit.threshold` (not just enough for one renew).
- Expired .com domains get NS switched to `*.parktons.com` → parking A records (`46.8.8.x`); zone data in Get_DNS_Zone can still look correct while public DNS is wrong.
- `Info_Order` parameter is `order`, **not** `orderid`.
- `Make_Order` payload must be `['order' => ['domain'=>…,'type'=>…,'params'=>…]]`.
- Prefer omitting `curExpDate` on Renew_Domain, or fetch exDate immediately before the call.

## Development
```bash
composer install
./vendor/bin/phpunit                          # helpers + live (needs config.json)
./vendor/bin/phpunit tests/src/Subreg/ClientHelpersTest.php
php Examples/DomainHealth.php example.com
dpkg-buildpackage -b -uc
```
