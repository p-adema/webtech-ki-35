#!/bin/bash
echo 'Step 1/2: Clear remote videos' &&
ssh "$1"@webtech-ki35.webtech-uva.nl rm -rf /var/www/html/resources/* &&
echo 'Step 2/2: Upload all resources (about 4m per course)' &&
rsync ./pages/resources/ --exclude='*.DS_Store' -raz --no-perms -O "$1"@webtech-ki35.webtech-uva.nl:/var/www/html/resources &&
echo '          Resource deployment success!'
