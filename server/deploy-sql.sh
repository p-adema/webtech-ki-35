#!/bin/bash
echo 'Step 1/4: Upload files' &&
rsync ./server/schema.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/schema.sql &&
rsync ./server/users.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/users.sh &&
rsync ./server/functions.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/functions.sql &&
rsync ./server/initial_data.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/initial_data.sql &&
rsync ./server/scraped/videos.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/videos.sql &&
rsync ./server/scraped/users.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/commenters.sql &&
rsync ./server/scraped/comments.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/comments.sql &&
echo 'Step 2/4: Handover to client script' &&
rsync ./server/client-sql.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/client-sql.sh &&
ssh "$1"@webtech-ki35.webtech-uva.nl bash /var/www/server/client-sql.sh
