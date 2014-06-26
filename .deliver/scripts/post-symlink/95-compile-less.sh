TARGET_ENV=$(cat "${DELIVERY_PATH}/.target-env")
pushd "${DELIVERY_PATH}" 

CURRENT_DIR="$REMOTE_PATH/delivered/current"  

APPLICATION_ENV="${TARGET_ENV}" bin/script Lessc
RES=$?
popd

exit $RES



