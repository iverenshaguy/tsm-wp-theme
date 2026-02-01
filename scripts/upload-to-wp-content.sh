#!/bin/bash

# Upload folders/files to WordPress wp-content directory
# Usage: ./scripts/upload-to-wp-content.sh <local-path> [destination]
# Example: ./scripts/upload-to-wp-content.sh ./uploads wp-content/uploads
# Example: ./scripts/upload-to-wp-content.sh ./plugins wp-content/plugins

if [ -z "$1" ]; then
    echo "Usage: $0 <local-path> [destination]"
    echo ""
    echo "Examples:"
    echo "  $0 ./uploads wp-content/uploads"
    echo "  $0 ./plugins wp-content/plugins"
    echo "  $0 ./themes wp-content/themes"
    echo "  $0 ./uploads/2026 wp-content/uploads/2026"
    echo ""
    echo "If destination is not specified, it will be inferred from the source path."
    exit 1
fi

LOCAL_PATH="$1"
DEST_PATH="${2:-wp-content/$(basename "$LOCAL_PATH")}"

CONTAINER_NAME="tsm-theme-wordpress"
CONTAINER_PATH="/var/www/html/$DEST_PATH"

echo "=== Uploading to WordPress wp-content ==="
echo ""
echo "Source: $LOCAL_PATH"
echo "Destination: $CONTAINER_PATH"
echo ""

# Check if source exists
if [ ! -e "$LOCAL_PATH" ]; then
    echo "✗ Source path does not exist: $LOCAL_PATH"
    exit 1
fi

# Check if container is running
if ! docker ps | grep -q "$CONTAINER_NAME"; then
    echo "✗ Container $CONTAINER_NAME is not running"
    echo "Start it with: docker-compose up -d"
    exit 1
fi

# Create destination directory if it doesn't exist
echo "1. Creating destination directory..."
docker exec "$CONTAINER_NAME" mkdir -p "$(dirname "$CONTAINER_PATH")" 2>/dev/null
echo "   ✓ Directory ready"
echo ""

# Copy files
echo "2. Copying files..."
echo ""

if [ -d "$LOCAL_PATH" ]; then
    # It's a directory - copy recursively, skipping existing files
    echo "   Copying directory (skipping existing files)..."
    
    # Count files to copy
    TOTAL_FILES=$(find "$LOCAL_PATH" -type f | wc -l | tr -d ' ')
    echo "   Found $TOTAL_FILES files to process"
    echo ""
    
    # Use a temp file to track counts (since while loop runs in subshell)
    TEMP_COUNTS=$(mktemp)
    echo "0 0" > "$TEMP_COUNTS"
    
    # Process each file individually
    find "$LOCAL_PATH" -type f | while read -r file; do
        # Get relative path from source
        REL_PATH="${file#$LOCAL_PATH/}"
        DEST_FILE="$CONTAINER_PATH/$REL_PATH"
        
        # Check if file exists in container
        if docker exec "$CONTAINER_NAME" test -f "$DEST_FILE" 2>/dev/null; then
            # Update skipped count
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
                # Update copied count
                read COPIED SKIPPED < "$TEMP_COUNTS"
                echo "$((COPIED + 1)) $SKIPPED" > "$TEMP_COUNTS"
                echo "   ✓ Copied: $REL_PATH"
            else
                echo "   ✗ Failed: $REL_PATH"
            fi
        fi
    done
    
    # Read final counts
    read COPIED SKIPPED < "$TEMP_COUNTS"
    rm "$TEMP_COUNTS"
    
    echo ""
    echo "   Summary: $COPIED copied, $SKIPPED skipped"
    
    # Fix permissions for all files
    echo ""
    echo "3. Fixing permissions..."
    docker exec "$CONTAINER_NAME" chown -R www-data:www-data "$CONTAINER_PATH" 2>/dev/null
    docker exec "$CONTAINER_NAME" find "$CONTAINER_PATH" -type d -exec chmod 755 {} \; 2>/dev/null
    docker exec "$CONTAINER_NAME" find "$CONTAINER_PATH" -type f -exec chmod 644 {} \; 2>/dev/null
    echo "   ✓ Permissions fixed"
else
    # It's a file
    # Check if file exists
    if docker exec "$CONTAINER_NAME" test -f "$CONTAINER_PATH" 2>/dev/null; then
        echo "   ⊘ File already exists, skipping"
    else
        echo "   Copying file..."
        docker cp "$LOCAL_PATH" "$CONTAINER_NAME:$CONTAINER_PATH"
        
        if [ $? -eq 0 ]; then
            echo "   ✓ File copied successfully"
            
            # Fix permissions
            echo ""
            echo "3. Fixing permissions..."
            docker exec "$CONTAINER_NAME" chown www-data:www-data "$CONTAINER_PATH" 2>/dev/null
            docker exec "$CONTAINER_NAME" chmod 644 "$CONTAINER_PATH" 2>/dev/null
            echo "   ✓ Permissions fixed"
        else
            echo "   ✗ Failed to copy file"
            exit 1
        fi
    fi
fi

echo ""
echo "=== Upload Complete ==="
echo ""
echo "Files are now available at:"
echo "  Container: $CONTAINER_PATH"
echo ""
echo "Verify:"
echo "  docker exec $CONTAINER_NAME ls -la $CONTAINER_PATH"
