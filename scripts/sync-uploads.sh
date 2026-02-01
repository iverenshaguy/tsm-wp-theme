#!/bin/bash

# Sync local uploads folder with WordPress container
# Usage: ./scripts/sync-uploads.sh [local-uploads-path]
# Example: ./scripts/sync-uploads.sh ./uploads

LOCAL_PATH="${1:-./uploads}"
CONTAINER_NAME="tsm-theme-wordpress"
CONTAINER_PATH="/var/www/html/wp-content/uploads"

echo "=== Syncing Uploads ==="
echo ""
echo "Local: $LOCAL_PATH"
echo "Container: $CONTAINER_PATH"
echo ""

if [ ! -d "$LOCAL_PATH" ]; then
    echo "✗ Local directory does not exist: $LOCAL_PATH"
    echo "Creating it..."
    mkdir -p "$LOCAL_PATH"
fi

# Check if container is running
if ! docker ps | grep -q "$CONTAINER_NAME"; then
    echo "✗ Container is not running"
    exit 1
fi

echo "1. Copying files to container (skipping existing)..."
echo ""

# Use temp file to track counts (while loop runs in subshell)
TEMP_COUNTS=$(mktemp)
echo "0 0" > "$TEMP_COUNTS"

# Process each file individually to skip existing ones
find "$LOCAL_PATH" -type f 2>/dev/null | while read -r file; do
    # Get relative path from source
    REL_PATH="${file#$LOCAL_PATH/}"
    DEST_FILE="$CONTAINER_PATH/$REL_PATH"
    
    # Check if file exists in container
    if docker exec "$CONTAINER_NAME" test -f "$DEST_FILE" 2>/dev/null; then
        read COPIED SKIPPED < "$TEMP_COUNTS"
        echo "$COPIED $((SKIPPED + 1))" > "$TEMP_COUNTS"
        echo "   ⊘ Skipping (exists): $REL_PATH"
    else
        # Create directory if needed
        DEST_DIR=$(dirname "$DEST_FILE")
        docker exec "$CONTAINER_NAME" mkdir -p "$DEST_DIR" 2>/dev/null
        
        # Copy file
        docker cp "$file" "$CONTAINER_NAME:$DEST_FILE" 2>/dev/null
        if [ $? -eq 0 ]; then
            read COPIED SKIPPED < "$TEMP_COUNTS"
            echo "$((COPIED + 1)) $SKIPPED" > "$TEMP_COUNTS"
            echo "   ✓ Copied: $REL_PATH"
        fi
    fi
done

# Read final counts
read COPIED SKIPPED < "$TEMP_COUNTS"
rm "$TEMP_COUNTS"

echo ""
echo "   Summary: $COPIED copied, $SKIPPED skipped"

echo ""
echo "2. Fixing permissions..."
docker exec "$CONTAINER_NAME" chown -R www-data:www-data "$CONTAINER_PATH" 2>/dev/null
docker exec "$CONTAINER_NAME" find "$CONTAINER_PATH" -type d -exec chmod 755 {} \; 2>/dev/null
docker exec "$CONTAINER_NAME" find "$CONTAINER_PATH" -type f -exec chmod 644 {} \; 2>/dev/null

echo "   ✓ Permissions fixed"
echo ""
echo "=== Sync Complete ==="
