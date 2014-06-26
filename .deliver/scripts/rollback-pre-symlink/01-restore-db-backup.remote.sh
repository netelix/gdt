
TARGET_DB=$(cat "${DELIVERY_PATH}/.target-db")
DUMP_PATH="${REMOTE_PATH}/deliver-backup.sql"
if [ -f "${DUMP_PATH}" ]; then
  echo "Backup dump found. Restoring it..."
  #mysql -u root -p "${TARGET_DB}" < "${DUMP_PATH}"
else
  echo "Backup dump not found."
fi 
