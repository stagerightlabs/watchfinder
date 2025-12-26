FROM php:8.4-alpine

LABEL authors="Ryan Durham <ryan@stagerightlabs.com>"

WORKDIR /var/www/html

# Install the watcher script and make it executable
COPY watch.php /usr/local/bin/watch.php
RUN chmod +x /usr/local/bin/watch.php

# Set the entrypoint to the watcher script
ENTRYPOINT ["/usr/local/bin/watch.php"]
