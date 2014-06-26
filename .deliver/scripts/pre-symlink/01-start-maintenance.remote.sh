touch "${DELIVERY_PATH}/.maintenance"

CURRENT_PATH="${REMOTE_PATH}/delivered/current"
if [ -d "${CURRENT_PATH}" ]; then
  touch "${CURRENT_PATH}/.maintenance"
fi