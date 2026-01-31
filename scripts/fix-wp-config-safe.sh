#!/bin/bash

# Safe script to fix wp-config.php by copying from local file
# Usage: ./scripts/fix-wp-config-safe.sh

CONTAINER_NAME="tsm-theme-wordpress"
LOCAL_CONFIG="./wp-config.php"

if [ ! -f "$LOCAL_CONFIG" ]; then
    echo "✗ wp-config.php not found in current directory!"
    echo "Make sure you're in the project root and wp-config.php exists."
    exit 1
fi

echo "Checking local wp-config.php syntax..."
PHP_CHECK=$(php -l "$LOCAL_CONFIG" 2>&1)
if ! echo "$PHP_CHECK" | grep -q "No syntax errors"; then
    echo "✗ Local wp-config.php has syntax errors:"
    echo "$PHP_CHECK"
    echo ""
    echo "Please fix the syntax errors before copying to container."
    exit 1
fi

echo "✓ Local wp-config.php syntax is valid"
echo ""

# Check if table_prefix is correct
if grep -q "\$table_prefix = 'terrysha_';" "$LOCAL_CONFIG"; then
    echo "✓ Table prefix is set to 'terrysha_'"
else
    echo "⚠ Warning: Table prefix might not be set correctly"
    echo "Current line:"
    grep -E "^\$table_prefix" "$LOCAL_CONFIG" || echo "  (not found)"
fi

echo ""
echo "Copying wp-config.php to container..."
echo ""

# Copy the file
if docker cp "$LOCAL_CONFIG" "$CONTAINER_NAME:/var/www/html/wp-config.php" 2>&1; then
    echo "✓ File copied successfully"
    echo ""
    echo "Verifying in container..."
    
    # Verify it's there
    if docker exec "$CONTAINER_NAME" test -f /var/www/html/wp-config.php; then
        echo "✓ File exists in container"
        
        # Check syntax in container
        CONTAINER_CHECK=$(docker exec "$CONTAINER_NAME" php -l /var/www/html/wp-config.php 2>&1)
        if echo "$CONTAINER_CHECK" | grep -q "No syntax errors"; then
            echo "✓ No syntax errors in container"
            echo ""
            echo "✓ wp-config.php is ready!"
            echo ""
            echo "Restart WordPress container:"
            echo "  docker-compose restart wordpress"
        else
            echo "✗ Syntax errors in container:"
            echo "$CONTAINER_CHECK"
        fi
    else
        echo "✗ File not found in container after copy"
    fi
else
    echo "✗ Failed to copy file"
    echo ""
    echo "Try manually:"
    echo "  docker cp wp-config.php tsm-theme-wordpress:/var/www/html/wp-config.php"
    exit 1
fi
