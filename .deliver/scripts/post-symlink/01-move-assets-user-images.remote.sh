PREVIOUS_DIR="$REMOTE_PATH/delivered/previous"  
ASSETS_DIR="public/upload/images"
if [ ! "$(ls -A "${PREVIOUS_DIR}/${ASSETS_DIR}")" ]; then
  echo "Previous does not exist. We don't handle assets."
  exit 0
else
  echo "Previous found. Handling of assets..."

  rm -rf "${DELIVERY_PATH}/${ASSETS_DIR}"
  mv "${PREVIOUS_DIR}/${ASSETS_DIR}" "${DELIVERY_PATH}/${ASSETS_DIR}"
fi