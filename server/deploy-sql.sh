#!/bin/bash
echo 'Step 1/4: Upload files' &&
rsync ./server/schema.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/schema.sql &&
rsync ./server/users.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/users.sh &&
rsync ./server/triggers.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/triggers.sql &&
rsync ./server/initial_data.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/initial_data.sql &&
rsync ./server/scraped.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/scraped.sql &&
echo 'Step 2/4: Handover to client script' &&
rsync ./server/client-sql.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/client-sql.sh &&
ssh "$1"@webtech-ki35.webtech-uva.nl bash /var/www/server/client-sql.sh
