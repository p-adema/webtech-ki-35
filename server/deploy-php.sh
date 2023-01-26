#!/bin/bash
echo 'Step 1/3: Upload pages (not course videos)' &&
rsync --include='example*.mp4' --exclude='*.mp4' --exclude='.DS_Store' ./pages/ -raz --no-perms  -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/ &&
echo 'Step 2/3: Upload components' &&
rsync ./components/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/components/ &&
echo 'Step 3/3: Overwrite remote.htaccess' &&
rsync ./server/remote.htaccess -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/.htaccess &&
echo '          PHP deployment success!'
