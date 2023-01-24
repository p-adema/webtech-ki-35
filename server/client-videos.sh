#!/bin/bash
# This is a deployment script for the remote server. Do not run locally
echo 'Step 5/6: Gather course sql files'
cd /var/www/server || exit 1
echo '\W;' > courses.out;
for course in /var/www/server/*.sql
do
  cat "$course" >> courses.out
done

echo 'Step 6/6: Insert gathered data' &&
mysql < courses.out &&
#rm ./*.sql courses.out client-videos.sh &&
echo '          Video deployment success!'
