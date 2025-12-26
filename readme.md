# Watch Finder

A docker container for monitoring PHP file changes an automatically running Laravel Wayfinder.

Using this container service will allow you to take advantage of the benefits of [Wayfinder](https://github.com/laravel/wayfinder) without needing to have node installed in your PHP container.

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

With this service in place in your local docker environment you will no longer need to use the Vite Wayfinder plugin; you can remove it completely from your vite config file.
