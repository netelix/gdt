

if [ -z "${TARGET_DB}" ]; then
  echo "TARGET_DB variable not set"
  exit 1
fi


if [ -z "${TARGET_ENV}" ]; then
  echo "TARGET_ENV variable not set"
  exit 1
fi
