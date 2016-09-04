#!/usr/bin/env bash

all_version=($(ls ./deploy/$1 -t))
last_version=${all_version[0]}

currentDir=`pwd`
last_version_path=${currentDir}/deploy/$1/${last_version}

rm -rf ./deploy/$1/${last_version}/$2
ln -s $3 ${last_version_path}/$2