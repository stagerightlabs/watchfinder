#!/bin/bash

# Build and tag the PHP 8.4 image
docker build . -t stagerightlabs/watchfinder:latest

if [ $? -eq 0 ]
then
    echo "The watchfinder image has been built and tagged."
fi
