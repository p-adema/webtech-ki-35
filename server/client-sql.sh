#!/bin/bash
# This is a deployment script for the server. Do not run locally
echo 'Step 3/4: Generate database reset script'
cd /var/www/server || exit 1
{
  echo '\W; DROP SCHEMA IF EXISTS db;';
  cat schema.sql;
  bash users.sh;
  cat triggers.sql;
  cat initial_data.sql;
  cat videos.sql;
  cat commenters.sql;
  cat comments.sql;
} > reset.sql
echo 'Step 4/4: Drop tables & reset database'
mysql < reset.sql &&
rm schema.sql users.sh triggers.sql initial_data.sql videos.sql commenters.sql comments.sql reset.sql client-sql.sh &&
echo '          SQL deployment success!' &&
echo '          (Remember to log out of any previous sessions)'
