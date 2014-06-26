
run_remote <<EOF
  echo ${TARGET_DB} > ${DELIVERY_PATH}/.target-db 
  echo ${TARGET_ENV} > ${DELIVERY_PATH}/.target-env

  APPLICATION_ENV=${TARGET_ENV} ${DELIVERY_PATH}/vendor/uop/bin/install
EOF

if [[ $? -gt 0 ]]; then
  echo "Setup failed on remote"
  exit 1
fi
echo "Setup success !"
exit 0