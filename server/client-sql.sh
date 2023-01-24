#!/bin/bash
# This is a deployment script for the remote server. Do not run locally
echo 'Step 6/7: Generate database reset script'
cd /var/www/server || exit 1
{
  echo '\W; DROP SCHEMA IF EXISTS db;';
  cat schema.sql;
  bash users.sh;
  cat triggers.sql;
  cat initial_data.sql;
} > reset.sql
echo 'Step 7/7: Drop tables & reset database'
mysql < reset.sql &&
rm schema.sql users.sh initial_data.sql triggers.sql reset.sql client-sql.sh &&
echo '          SQL deployment success!'
