#!/bin/bash
# This is a deployment script for the remote server. Do not run locally
cd /var/www/server || exit 1
echo '\W; DROP SCHEMA IF EXISTS db;' > reset.sql
cat schema.sql >> reset.sql
bash users.sh >> reset.sql
mysql < reset.sql &&
rm users.sh schema.sql reset.sql client-sql.sh
