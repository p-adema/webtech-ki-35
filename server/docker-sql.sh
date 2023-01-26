#!/bin/bash
IFS=' ' read -ra container_data <<< "$(docker ps | grep 'web-tech-sql')"
sql="${container_data[0]}"
echo 'Step 1/4: Transfer files' &&
docker cp schema.sql "$sql":/var/www/server/schema.sql &&
docker cp users.sh "$sql":/var/www/server/users.sh &&
docker cp triggers.sql "$sql":/var/www/server/triggers.sql &&
docker cp initial_data.sql "$sql":/var/www/server/initial_data.sql &&
docker cp scraped.sql "$sql":/var/www/server/scraped.sql &&
echo 'Step 2/4: Handover to client script' &&
docker cp client-sql.sh "$sql":/var/www/server/client-sql.sh &&
docker exec -it "$sql" bash /var/www/server/client-sql.sh
