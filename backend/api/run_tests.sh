#!/bin/bash

db='expo_sat'
user='root'
pass='mysql'

echo "Dropping database"
/Applications/AMPPS/apps/mysql/bin/mysql --user="$user" --password="$pass" --execute="DROP DATABASE IF EXISTS $db;"
echo "Creating database"
/Applications/AMPPS/apps/mysql/bin/mysql --user="$user" --password="$pass" --execute="CREATE DATABASE $db;"
/Applications/AMPPS/apps/mysql/bin/mysql --user="$user" --password="$pass" $db --execute="SOURCE ../../expo_sat_h24.sql;"

./vendor/bin/phpunit ./tests/