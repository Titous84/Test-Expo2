$db = 'expo_sat'
$user = 'root'
$pass = 'root'

echo "Dropping database"
& mysql.exe --user="$user" --password="$pass" --execute="DROP DATABASE IF EXISTS $db;"
echo "Creating database"
& mysql.exe --user="$user" --password="$pass" --execute="CREATE DATABASE $db;"
& mysql.exe --user="$user" --password="$pass" $db --execute="SOURCE ../../expo_sat_h24.sql;"

& .\vendor\bin\phpunit .\tests\