#!/usr/bin/env bash

echo "Moving module to a subfolder..."
mkdir module
mv * module/
result=$?
if [ "$result" = 0 ]; then
    echo 'copied'
fi
echo 'copied'