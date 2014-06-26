PREVIOUS_DIR="$REMOTE_PATH/delivered/previous"  
ASSETS_DIR="public/upload/images"
if [ -d "$PREVIOUS_DIR" ] && [ "$(ls -A "${PREVIOUS_DIR}")" ]; then
  echo "Previous found. Restoring assets..."
 #mv -T "${DELIVERY_PATH}/${ASSETS_DIR}" "${PREVIOUS_DIR}/${ASSETS_DIR}" 
else
  echo "Previous does not exist. We don't handle assets."
fi


exit 0