
TARGET_DB=$(cat "${DELIVERY_PATH}/.target-db")
mysqldump --add-locks --add-drop-table --extended-insert --lock-tables -u root "${TARGET_DB}" > "${REMOTE_PATH}/deliver-backup.sql"