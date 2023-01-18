#!/bin/bash
# This is a deployment script for the remote server. Do not run locally
cd /var/www/server || exit 1
echo '\W; DROP SCHEMA IF EXISTS db;' > reset.sql
{ cat schema.sql;
bash users.sh;
cat initial_data.sql; } >> reset.sql
mysql < reset.sql &&
rm users.sh schema.sql initial_data.sql reset.sql client-sql.sh
