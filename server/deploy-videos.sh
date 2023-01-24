#!/bin/bash
echo 'Step 1/6: Clear remote videos' &&
ssh "$1"@webtech-ki35.webtech-uva.nl rm -f /var/www/html/videos/*.mp4 &&
echo 'Step 2/6: Upload all videos (about 4m per course)' &&
rsync ./pages/videos -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/ &&
echo 'Step 3/6: Upload course sql files' &&
rsync -m --include='*/' --include='*.sql' --exclude='*' ./scraping/courses/ -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/ &&
echo 'Step 4/6: Handover to client script' &&
rsync ./server/client-videos.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/client-videos.sh &&
ssh "$1"@webtech-ki35.webtech-uva.nl bash /var/www/server/client-videos.sh
