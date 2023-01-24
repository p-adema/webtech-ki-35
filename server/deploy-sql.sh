#!/bin/bash
echo 'Step 1/7: Upload schema' &&
rsync ./server/schema.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/schema.sql &&
echo 'Step 2/7: Upload users' &&
rsync ./server/users.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/users.sh &&
echo 'Step 3/7: Upload initial_data' &&
rsync ./server/initial_data.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/initial_data.sql &&
echo 'Step 4/7: Upload triggers' &&
rsync ./server/triggers.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/triggers.sql &&
echo 'Step 5/7: Handover to client script' &&
rsync ./server/client-sql.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/client-sql.sh &&
ssh "$1"@webtech-ki35.webtech-uva.nl bash /var/www/server/client-sql.sh
