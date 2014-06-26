
TARGET_ENV=$(cat "${DELIVERY_PATH}/.target-env")
pushd "${DELIVERY_PATH}" 

CURRENT_DIR="$REMOTE_PATH/delivered/current"  
ENV_RPATH="application/configs/environment.ini"
sleep 20
if [ -f "${CURRENT_DIR}/${ENV_RPATH}" ]; then
  echo "Current environment.ini found. Uses it..."
  cp "${CURRENT_DIR}/${ENV_RPATH}" "${DELIVERY_PATH}/${ENV_RPATH}"
fi

while [ ! -f application/configs/environment.ini ]; do
  echo "${DELIVERY_PATH}/${ENV_RPATH} is missing. Please add it manually..."
  sleep 5
done 

APPLICATION_ENV="${TARGET_ENV}" bin/script Migrator
RES=$?
popd

exit $RES
