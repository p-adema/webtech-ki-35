#!/bin/bash
rsync ./pages/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/
rsync ./components/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/components/
rsync ./server/remote.htaccess -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/.htaccess
