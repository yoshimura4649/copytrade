<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Environment

- Laravel 11.22.0
- PHP 8.3.9

## Local cmd

- composer install --dev
- composer run-script post-root-package-install
- composer run-script post-create-project-cmd

## Local queue (for import csv...)

- php artisan queue:listen

## Server Supervisord

- supervisorctl stop laravel-worker:\*
- supervisorctl start laravel-worker:\*
- supervisorctl restart laravel-worker:\*
