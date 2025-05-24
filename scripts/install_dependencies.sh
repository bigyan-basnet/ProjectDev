#!/bin/bash
yum update -y
amazon-linux-extras enable php8.1
yum clean metadata
yum install -y php php-mbstring php-xml php-common php-mysqlnd unzip

cd /var/www/html
if [ -f "composer.json" ]; then
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install
fi
