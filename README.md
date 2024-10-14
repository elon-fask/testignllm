# CranePay and CraneAdmin web app

## Dependencies
- PHP 5.4+
- Apache 2.2+
- PHP Modules: php5-gd php5-intl php5-curl
- MySQL 14+
- [Composer](https://getcomposer.org/download/)
- [Composer Asset Plugin](https://github.com/francoispluchino/composer-asset-plugin/blob/master/Resources/doc/index.md)
- install pdftk

## Installation
1. Clone the [cso-ccs.git](https://git.tabletbasedtesting.com/summary/cso-ccs.git) repo.
2. Copy one of the sample db.env.php files to db.php. Update the DB configuration in the file.
3. Copy the params.php.sample.php file to params.php, update as necessary.
    -   Update the necessary authorize.net information for both CCS and ACS
    -   Update the correct base url for both CCS and ACS sites
4. Run composer install
5. Run ./yii migrate up
6. Host the ./web/ directory in Apache
