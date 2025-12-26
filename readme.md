# Watch Finder

A docker container for monitoring PHP file changes an automatically running Laravel Wayfinder.

## Usage

Add this container as a separate service in your docker `compose.yaml` file.

```yaml
wayfinder:
    build:
        context: stagerightlabs/watchfinder
    environment:
        - WATCH_CMD=php artisan wayfinder:generate --with-form
    volumes:
        - ./:/var/www/html:cached
```

The `WATCH_CMD` variable is the artisan command you want to run when file changes are detected.

## Notes
