#!/bin/bash 

nohup php artisan queue:work --daemon --sleep=4 --tries=4 > /var/www/html/apponereach/storage/logs/jobs.log 2>&1 & 

