#!/bin/bash

# Find WordPress media uploads location
# Usage: ./scripts/find-uploads.sh

echo "=== WordPress Media Uploads Location ==="
echo ""

# 1. Check inside container
echo "1. Inside Docker container:"
echo "   Path: /var/www/html/wp-content/uploads/"
echo ""

# Check if directory exists
if docker exec tsm-theme-wordpress test -d /var/www/html/wp-content/uploads 2>/dev/null; then
    echo "   ✓ Uploads directory exists"
    
    # Count files
    FILE_COUNT=$(docker exec tsm-theme-wordpress find /var/www/html/wp-content/uploads -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "   Files found: $FILE_COUNT"
    
    # Show directory structure
    echo ""
    echo "   Directory structure:"
    docker exec tsm-theme-wordpress ls -la /var/www/html/wp-content/uploads/ 2>/dev/null | head -10
else
    echo "   ✗ Uploads directory doesn't exist yet"
fi
echo ""

# 2. Check Docker volume location
echo "2. Docker volume location (on your Mac):"
echo ""
VOLUME_PATH=$(docker volume inspect tsm-theme_wordpress_data --format '{{ .Mountpoint }}' 2>/dev/null)

if [ -n "$VOLUME_PATH" ]; then
    echo "   Volume path: $VOLUME_PATH"
    echo "   Full uploads path: $VOLUME_PATH/wp-content/uploads/"
    echo ""
    echo "   To access files:"
    echo "   cd $VOLUME_PATH/wp-content/uploads/"
    echo ""
    
    if [ -d "$VOLUME_PATH/wp-content/uploads" ]; then
        echo "   ✓ Directory exists on host"
        FILE_COUNT=$(find "$VOLUME_PATH/wp-content/uploads" -type f 2>/dev/null | wc -l | tr -d ' ')
        echo "   Files: $FILE_COUNT"
    else
        echo "   ⊘ Directory doesn't exist yet (will be created on first upload)"
    fi
else
    echo "   ⚠ Could not find volume path"
    echo "   Volume name: tsm-theme_wordpress_data"
fi
echo ""

# 3. WordPress uploads URL
echo "3. WordPress uploads URL:"
echo ""
SITE_URL=$(docker exec tsm-theme-db mysql -uwordpress -pwordpress wordpress -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'siteurl';" 2>/dev/null)
if [ -n "$SITE_URL" ]; then
    echo "   Site URL: $SITE_URL"
    echo "   Uploads URL: $SITE_URL/wp-content/uploads/"
else
    echo "   Could not determine site URL"
fi
echo ""

# 4. Show how to copy files
echo "4. How to copy files to/from container:"
echo ""
echo "   Copy file TO container:"
echo "   docker cp /path/to/file.jpg tsm-theme-wordpress:/var/www/html/wp-content/uploads/2026/01/"
echo ""
echo "   Copy file FROM container:"
echo "   docker cp tsm-theme-wordpress:/var/www/html/wp-content/uploads/2026/01/file.jpg ./"
echo ""
echo "   Copy entire uploads directory:"
echo "   docker cp tsm-theme-wordpress:/var/www/html/wp-content/uploads ./uploads-backup"
echo ""
