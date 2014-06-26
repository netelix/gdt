# Temporary solution : TODO -> handle php user properly
pushd ${DELIVERY_PATH}

PREVIOUS_DIR="$REMOTE_PATH/delivered/previous"  
CRON_FILE="application/configs/cron"
if [ ! "$(ls -A "${PREVIOUS_DIR}/${CRON_FILE}")" ]; then
  echo "Previous cron does not exist... please create one on path ${DELIVERY_PATH}/${CRON_FILE}"
else
  echo "Previous cron found. copying..."
  cp "${PREVIOUS_DIR}/${CRON_FILE}" "${DELIVERY_PATH}/${CRON_FILE}"
fi

while [ 1 ]; do
  crontab -u $USER application/configs/cron

  if [ $? -eq 0 ]
  then
    break
  fi
  echo "Bad cron file, please correct manually..."
  sleep 2
done
popd