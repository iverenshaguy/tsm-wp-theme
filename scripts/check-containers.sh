#!/bin/bash

# Script to check Docker container status and troubleshoot issues
# Usage: ./scripts/check-containers.sh

echo "=== Docker Container Status ==="
echo ""

# Check if containers are running
echo "Checking container status..."
docker-compose ps

echo ""
echo "=== WordPress Container Logs (last 20 lines) ==="
echo ""

# Check WordPress logs for errors
docker-compose logs --tail=20 wordpress

echo ""
echo "=== Database Container Logs (last 10 lines) ==="
echo ""

# Check database logs
docker-compose logs --tail=10 db

echo ""
echo "=== Checking wp-config.php Syntax ==="
echo ""

# Check if wp-config.php exists and has correct syntax
if docker exec tsm-theme-wordpress test -f /var/www/html/wp-config.php 2>/dev/null; then
    echo "✓ wp-config.php exists"
    
    # Check for table_prefix
    TABLE_PREFIX=$(docker exec tsm-theme-wordpress grep -E "^\$table_prefix" /var/www/html/wp-config.php 2>/dev/null)
    if [ -n "$TABLE_PREFIX" ]; then
        echo "✓ Table prefix found: $TABLE_PREFIX"
    else
        echo "✗ Table prefix not found!"
    fi
    
    # Check for syntax errors
    PHP_CHECK=$(docker exec tsm-theme-wordpress php -l /var/www/html/wp-config.php 2>&1)
    if echo "$PHP_CHECK" | grep -q "No syntax errors"; then
        echo "✓ No PHP syntax errors"
    else
        echo "✗ PHP syntax errors found:"
        echo "$PHP_CHECK"
    fi
else
    echo "✗ wp-config.php not found!"
fi

echo ""
echo "=== Troubleshooting Steps ==="
echo ""
echo "If containers are not running, try:"
echo "  docker-compose up -d"
echo ""
echo "If WordPress container keeps restarting, check logs:"
echo "  docker-compose logs wordpress"
echo ""
echo "To restart containers:"
echo "  docker-compose restart"
echo ""
echo "To completely rebuild:"
echo "  docker-compose down"
echo "  docker-compose up -d"
