#!/bin/sh +xe

# this script updates

secret=$(head -c 64 /dev/urandom  | base64 -w0 | tr '/' '\\' -d)
sed -i "s/^APP_SECRET='.*'$/APP_SECRET='$secret'/g" .env
