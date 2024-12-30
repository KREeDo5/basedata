#!/bin/bash

# Перейти в директорию проекта
cd /var/www/basedata/forum

# Обновить проект из Git
git checkout backend
git pull origin backend

# Убедиться, что все зависимости установлены
composer install

# Перезапустить сервер Laravel
pkill -f "php artisan serve"
nohup php artisan serve --host=0.0.0.0 --port=8001 > /dev/null 2>&1 &