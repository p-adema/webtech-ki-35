#!/bin/bash
echo 'Step 1/4: Upload pages (not course videos)' &&
rsync --include='example*.mp4' --include='homepage*.mp4' --exclude='*.mp4' --exclude='.DS_Store' ./pages/ -raz --no-perms  -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/ &&
echo 'Step 2/4: Upload components' &&
rsync ./components/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/components/ &&
echo 'Step 3/4: Overwrite remote.htaccess' &&
rsync ./server/remote.htaccess -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/.htaccess &&
echo 'Step 4/4: Grant www-data permissions on files' &&
ssh "$1"@webtech-ki35.webtech-uva.nl sudo chown -R www-data /var/www/html &&
echo '          PHP deployment success!'
