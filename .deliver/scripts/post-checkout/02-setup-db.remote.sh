
TARGET_DB=$(cat "${DELIVERY_PATH}/.target-db")
if [ -z "$TARGET_DB" ]; then
  echo "Target DB not found. Bug"
  exit 1
fi
TARGET_ENV=$(cat "${DELIVERY_PATH}/.target-env")
if [ -z "$TARGET_ENV" ]; then
  echo "Target ENV not found. Bug"
  exit 1
fi

DB_FOUND=$(mysql -u root -BN -e "SHOW DATABASES LIKE '${TARGET_DB}'")
if [ -z "$DB_FOUND" ]; then
  echo "Database does not exist. Creating it..."
  mysql -u root -e "CREATE DATABASE ${TARGET_DB} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci"
else
  echo "Database found."
fi


NB_TABLES=$(mysql -u root -BN -e "SELECT COUNT(DISTINCT table_name) FROM information_schema.columns WHERE table_schema = '${TARGET_DB}'")

if [[ $NB_TABLES -lt 1 ]]; then
  echo "Empty database. Seeding it..."
  cat $DELIVERY_PATH/db/seeds.sql.gz | gunzip | mysql -u root ${TARGET_DB}
else
  echo "Database not empty."
fi

exit 0
 