#!/bin/bash

DIR=$(pwd)

echo "* * * * * cd $DIR && php artisan schedule:run >> /dev/null 2>&1" >> crontab

crontab ./crontab
