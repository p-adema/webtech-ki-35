#!/bin/bash
rsync ./server/schema.sql -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/schema.sql
rsync ./server/users.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/users.sh
rsync ./server/client-sql.sh -az "$1"@webtech-ki35.webtech-uva.nl:/var/www/server/client-sql.sh
ssh "$1"@webtech-ki35.webtech-uva.nl bash /var/www/server/client-sql.sh
