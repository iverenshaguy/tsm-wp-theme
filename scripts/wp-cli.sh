#!/bin/bash

# WP-CLI wrapper script for Docker
# Usage: ./scripts/wp-cli.sh <wp-cli-command>
# Example: ./scripts/wp-cli.sh search-replace 'terryshaguy.org' 'localhost:8080' --allow-root

if [ -z "$1" ]; then
    echo "Usage: $0 <wp-cli-command>"
    echo "Example: $0 search-replace 'terryshaguy.org' 'localhost:8080' --allow-root"
    echo "Example: $0 option get siteurl"
    exit 1
fi

CONTAINER_NAME="tsm-theme-wp-cli"

# Pass all arguments to wp-cli container
docker exec "$CONTAINER_NAME" wp "$@"
