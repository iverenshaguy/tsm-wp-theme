#!/bin/bash

# Enable SSL in Apache WordPress container
# Usage: ./scripts/enable-ssl-apache.sh

echo "=== Enabling SSL in Apache ==="
echo ""

# Check if SSL config exists
if [ ! -f "./certs/ssl.conf" ]; then
    echo "✗ SSL config not found!"
    echo "Run: ./scripts/setup-local-ssl.sh terryshaguy.local"
    exit 1
fi

# Copy SSL config
echo "1. Copying SSL configuration..."
docker cp ./certs/ssl.conf tsm-theme-wordpress:/etc/apache2/sites-available/ssl.conf 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✓ SSL config copied"
else
    echo "   ✗ Failed to copy SSL config"
    echo "   Make sure container is running: docker-compose up -d"
    exit 1
fi
echo ""

# Enable SSL module
echo "2. Enabling SSL module..."
docker exec tsm-theme-wordpress a2enmod ssl 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✓ SSL module enabled"
else
    echo "   ✗ Failed to enable SSL module"
fi
echo ""

# Enable SSL site
echo "3. Enabling SSL site..."
docker exec tsm-theme-wordpress a2ensite ssl 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✓ SSL site enabled"
else
    echo "   ✗ Failed to enable SSL site"
fi
echo ""

# Test Apache configuration
echo "4. Testing Apache configuration..."
CONFIG_TEST=$(docker exec tsm-theme-wordpress apache2ctl configtest 2>&1)

if echo "$CONFIG_TEST" | grep -q "Syntax OK"; then
    echo "   ✓ Apache configuration is valid"
else
    echo "   ✗ Apache configuration has errors:"
    echo "$CONFIG_TEST"
fi
echo ""

# Restart Apache
echo "5. Restarting Apache..."
docker exec tsm-theme-wordpress apache2ctl graceful 2>/dev/null || docker exec tsm-theme-wordpress service apache2 reload 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✓ Apache restarted"
else
    echo "   ⚠ Could not restart Apache gracefully"
    echo "   Restarting container instead..."
    docker-compose restart wordpress
fi
echo ""

echo "=== SSL Setup Complete ==="
echo ""
echo "Test your site:"
echo "  https://terryshaguy.local:8443"
echo ""
echo "If you still get connection refused:"
echo "1. Make sure docker-compose.yml has certificate volumes uncommented"
echo "2. Restart containers: docker-compose restart"
echo "3. Check: ./scripts/troubleshoot-ssl.sh"
