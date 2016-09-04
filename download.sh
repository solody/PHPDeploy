#!/usr/bin/env bash

if [ ! -d "./package" ]; then
  mkdir ./package
fi

if [ ! -d "./deploy" ]; then
  mkdir ./deploy
fi

if [ ! -d "./package/$1" ]; then
  mkdir ./package/$1
fi

if [ ! -d "./deploy/$1" ]; then
  mkdir ./deploy/$1
fi

wget --http-user=$4 --http-passwd=$5 -P ./package/$1 $2$3.tar.gz

tar zxvf ./package/$1/$3.tar.gz -C ./deploy/$1