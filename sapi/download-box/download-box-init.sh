#!/bin/bash

set -exu
__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)

__PROJECT__=$(
  cd ${__DIR__}/../../
  pwd
)

cd ${__PROJECT__}

test -d ${__PROJECT__}/var || mkdir -p ${__PROJECT__}/var


export COMPOSER_ALLOW_SUPERUSER=1
composer update --no-dev --optimize-autoloader

php prepare.php --with-build-type=release +ds +inotify +apcu +protobuf +pgsql +pdo_pgsql --with-swoole-pgsql=1 --without-docker=1 --with-skip-download=1

cd ${__PROJECT__}
bash sapi/download-box/download-dependencies-use-aria2.sh
cd ${__PROJECT__}
bash sapi/download-box/download-dependencies-use-git.sh
cd ${__PROJECT__}

# 生成扩展依赖图
sh sapi/scripts/generate-dependency-graph.sh
