#!/bin/bash

# Build, tag and then push the watchfinder image
./build.sh
if [ $? -ne 0 ]
then
    exit $?
fi

docker push stagerightlabs/watchfinder:latest
if [ $? -eq 0 ]
then
    echo "The newly built watchfinder image has been pushed to docker hub."
fi
