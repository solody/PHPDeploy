#!/usr/bin/env bash

all_version=($(ls ./deploy/$1 -t))

last_version=${all_version[0]}

cd ./deploy/$1/${last_version}

if [ ! -f "./composer.phar" ]; then
  echo "Running Global Composer"
  composer install -vvv 2>&1
else
  echo "Running Project Local Composer.phar"
  php composer.phar install -vvv 2>&1
fi
