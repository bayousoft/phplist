#!/bin/sh

for i in `find . -type f -name "*.php"`; do
  php -l $i
done
for i in `find . -type f -name "*.inc"`; do
  php -l $i
done
