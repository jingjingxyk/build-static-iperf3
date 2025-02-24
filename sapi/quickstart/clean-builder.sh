#!/usr/bin/env bash

__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)
__PROJECT__=$(
  cd ${__DIR__}/../../
  pwd
)
cd ${__PROJECT__}

export PATH="${__PROJECT__}/bin/runtime:$PATH"
shopt -s expand_aliases
alias php="php -d curl.cainfo=${__PROJECT__}/bin/runtime/cacert.pem -d openssl.cafile=${__PROJECT__}/bin/runtime/cacert.pem"

EXT_NAME=''
FORECE_CLEAN_ACTION=0
while [ $# -gt 0 ]; do
  case "$1" in
  --ext-name)
    EXT_NAME="$2"
    ;;
  --force)
    FORECE_CLEAN_ACTION=1
    ;;
  --*)
    echo "Illegal option $1"
    ;;
  esac
  shift $(($# > 0 ? 1 : 0))
done

if [ ! -z "${EXT_NAME}" ]; then
  php prepare.php --without-docker --skip-download=1 +openssh --show-ext-deps=${EXT_NAME} --with-clean-deps-file=${FORECE_CLEAN_ACTION}
else
  echo '请传入参数 --ext-name=ext_name --force 1 '
fi
