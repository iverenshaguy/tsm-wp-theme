#!/bin/bash

# Script to update wp-config.php with correct table prefix
# Usage: ./scripts/fix-wp-config.sh

CONTAINER_NAME="tsm-theme-wordpress"
WP_CONFIG_PATH="/var/www/html/wp-config.php"

echo "Checking wp-config.php..."
echo ""

# Check if wp-config.php exists
if ! docker exec "$CONTAINER_NAME" test -f "$WP_CONFIG_PATH"; then
    echo "✗ wp-config.php not found!"
    echo "WordPress may not be installed yet."
    exit 1
fi

# Check current table prefix - handle various formats
TABLE_PREFIX_LINE=$(docker exec "$CONTAINER_NAME" grep -E "^\$table_prefix" "$WP_CONFIG_PATH" 2>/dev/null || echo "")

if [ -z "$TABLE_PREFIX_LINE" ]; then
    echo "⚠ No \$table_prefix line found in wp-config.php"
    echo "This might be a fresh WordPress installation."
    echo ""
    echo "Checking if we need to add the line..."
    # Check if file exists and has content
    if docker exec "$CONTAINER_NAME" test -f "$WP_CONFIG_PATH"; then
        echo "Adding table prefix line to wp-config.php..."
        # Find a good place to insert (after database settings, before authentication keys)
        docker exec "$CONTAINER_NAME" sed -i "/DB_COLLATE/a\\\$table_prefix = 'terrysha_';" "$WP_CONFIG_PATH" 2>/dev/null || \
        docker exec "$CONTAINER_NAME" sh -c "echo \"\$table_prefix = 'terrysha_';\" >> $WP_CONFIG_PATH"
    fi
else
    echo "Found table prefix line: $TABLE_PREFIX_LINE"
    CURRENT_PREFIX=$(echo "$TABLE_PREFIX_LINE" | sed -E "s/.*= ['\"](.*)['\"].*/\1/" | tr -d "';\" ")
    echo "Current table prefix: '$CURRENT_PREFIX'"
    echo ""
    
    if [ "$CURRENT_PREFIX" = "terrysha_" ]; then
        echo "✓ Table prefix is already correct!"
        exit 0
    fi
    
    echo "Updating table prefix to 'terrysha_'..."
    echo ""
    
    # Create a backup
    docker exec "$CONTAINER_NAME" cp "$WP_CONFIG_PATH" "${WP_CONFIG_PATH}.backup"
    
    # Update the table prefix - handle various formats
    # Try different patterns to match the line
    docker exec "$CONTAINER_NAME" sed -i "s/\$table_prefix\s*=\s*['\"][^'\"]*['\"];/\$table_prefix = 'terrysha_';/" "$WP_CONFIG_PATH" 2>/dev/null || \
    docker exec "$CONTAINER_NAME" sed -i "s/\$table_prefix\s*=\s*[\"'][^\"']*[\"'];/\$table_prefix = 'terrysha_';/" "$WP_CONFIG_PATH" 2>/dev/null || \
    docker exec "$CONTAINER_NAME" sed -i "s/\$table_prefix.*/\$table_prefix = 'terrysha_';/" "$WP_CONFIG_PATH"
fi

# Verify the change
NEW_PREFIX_LINE=$(docker exec "$CONTAINER_NAME" grep -E "^\$table_prefix" "$WP_CONFIG_PATH" 2>/dev/null || echo "")
NEW_PREFIX=$(echo "$NEW_PREFIX_LINE" | sed -E "s/.*= ['\"](.*)['\"].*/\1/" | tr -d "';\" " 2>/dev/null || echo "")

if [ "$NEW_PREFIX" = "terrysha_" ]; then
    echo "✓ Table prefix updated successfully!"
    echo "New line: $NEW_PREFIX_LINE"
    echo ""
    echo "WordPress should now be able to see your imported data."
    echo "You may need to refresh your browser or restart the WordPress container:"
    echo "  docker-compose restart wordpress"
else
    echo "✗ Failed to update table prefix!"
    echo "Current line: $NEW_PREFIX_LINE"
    echo ""
    echo "Trying alternative method..."
    
    # Alternative: Use a more aggressive replacement
    docker exec "$CONTAINER_NAME" sh -c "sed -i \"s/.*table_prefix.*/\$table_prefix = 'terrysha_';/\" $WP_CONFIG_PATH"
    
    # Check again
    FINAL_CHECK=$(docker exec "$CONTAINER_NAME" grep -E "^\$table_prefix" "$WP_CONFIG_PATH" 2>/dev/null | grep "terrysha_")
    
    if [ -n "$FINAL_CHECK" ]; then
        echo "✓ Successfully updated using alternative method!"
        echo "New line: $FINAL_CHECK"
    else
        echo "✗ Still failed. Please update manually:"
        echo ""
        echo "1. Copy wp-config.php:"
        echo "   docker cp tsm-theme-wordpress:/var/www/html/wp-config.php ./wp-config.php"
        echo ""
        echo "2. Edit locally and change/add:"
        echo "   \$table_prefix = 'terrysha_';"
        echo ""
        echo "3. Copy back:"
        echo "   docker cp ./wp-config.php tsm-theme-wordpress:/var/www/html/wp-config.php"
        echo ""
        if docker exec "$CONTAINER_NAME" test -f "${WP_CONFIG_PATH}.backup"; then
            echo "Restoring backup..."
            docker exec "$CONTAINER_NAME" mv "${WP_CONFIG_PATH}.backup" "$WP_CONFIG_PATH"
        fi
        exit 1
    fi
fi
