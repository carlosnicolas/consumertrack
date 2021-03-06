# Consumer Track (logger exercise)

Simple exercise for web development. Making a logger using a file to grab all client's data records.

## Requirements
* PHP 7.4+
* NodeJS 14.19.0+
* Composer 2.3.7+


## Instalation
Follow the next steps to run this app.

- Clone the current repository
- Go to the local directory
- Install using `composer install`
- Duplicate the `.env.example` file and rename to `.env` only
- In the `.env` file, set the environment values to "GEO_CLIENT" and "GEO_KEY" using a valid GeoIP2 keys.
- Run server with the command `php -S localhost:8000 -t public`
- Access in the browser to the url `localhost:8000`or running the cli command `php public/index.php`
- The log file is in the `public/log.svg` directory
- The logger core code is in `app/Http/Controllers/LoggerController.php`


## Additional packages

* MobileDetect - [Website](http://mobiledetect.net/)
* GeoIP2 - [Website](https://dev.maxmind.com/geoip/geolocate-an-ip/web-services)
