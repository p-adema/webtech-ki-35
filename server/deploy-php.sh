#!/bin/bash
rsync ./pages/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/
rsync ./components/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/components/
rsync ./server/php.ini -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/php.ini
ssh "$1"@webtech-ki35.webtech-uva.nl sudo mv /var/www/server/php.ini /etc/php/8.1/cli/conf.d/40-webtech-custom.ini
