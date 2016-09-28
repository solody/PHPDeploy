#!/usr/bin/env bash

all_version=($(ls ./deploy/$1 -t))

last_version=${all_version[0]}

cd ./deploy/$1/${last_version}
composer install -vvv 2>&1