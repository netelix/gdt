
TARGET_DB=$(cat "${DELIVERY_PATH}/.target-db")
mysqldump --add-locks --add-drop-table --extended-insert --lock-tables --ignore-table="{TARGET_DB}".apache_logs -u root "${TARGET_DB}" > "${REMOTE_PATH}/deliver-backup.sql"
