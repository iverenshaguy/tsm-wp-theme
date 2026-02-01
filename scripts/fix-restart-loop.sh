#!/bin/bash

# Fix WordPress container restart loop caused by SSL setup
# Usage: ./scripts/fix-restart-loop.sh

echo "=== Fixing Container Restart Loop ==="
echo ""

# 1. Check if certificates exist
echo "1. Checking certificates..."
if [ ! -f "./certs/terryshaguy.local.crt" ] || [ ! -f "./certs/terryshaguy.local.key" ]; then
    echo "   ✗ Certificates not found!"
    echo "   Generating them now..."
    ./scripts/setup-local-ssl.sh terryshaguy.local
    if [ $? -ne 0 ]; then
        echo "   ✗ Failed to generate certificates"
        exit 1
    fi
else
    echo "   ✓ Certificates exist"
fi
echo ""

# 2. Temporarily remove certificate volumes to get container running
echo "2. Temporarily removing certificate volumes to fix restart loop..."
echo ""

# Create backup
cp docker-compose.yml docker-compose.yml.backup

# Comment out certificate volumes temporarily
sed -i.bak 's|- ./certs/terryshaguy.local.crt:/etc/ssl/certs/wordpress.crt|# - ./certs/terryshaguy.local.crt:/etc/ssl/certs/wordpress.crt|' docker-compose.yml
sed -i.bak 's|- ./certs/terryshaguy.local.key:/etc/ssl/private/wordpress.key|# - ./certs/terryshaguy.local.key:/etc/ssl/private/wordpress.key|' docker-compose.yml

echo "   ✓ Commented out certificate volumes"
echo ""

# 3. Recreate container without SSL volumes
echo "3. Recreating container without SSL volumes..."
docker-compose stop wordpress
docker-compose rm -f wordpress
docker-compose up -d wordpress

echo "   Waiting for container to start..."
sleep 5

# Check if it's running
STATUS=$(docker-compose ps wordpress | grep wordpress | awk '{print $6" "$7" "$8" "$9" "$10}')
if echo "$STATUS" | grep -q "Up"; then
    echo "   ✓ Container is now running!"
else
    echo "   ✗ Container still restarting"
    echo "   Checking logs..."
    docker-compose logs --tail=30 wordpress
    exit 1
fi
echo ""

# 4. Copy certificates manually into container
echo "4. Copying certificates into container..."
docker cp ./certs/terryshaguy.local.crt tsm-theme-wordpress:/etc/ssl/certs/wordpress.crt 2>/dev/null
docker cp ./certs/terryshaguy.local.key tsm-theme-wordpress:/etc/ssl/private/wordpress.key 2>/dev/null

# Create directory if it doesn't exist
docker exec tsm-theme-wordpress mkdir -p /etc/ssl/private 2>/dev/null
docker exec tsm-theme-wordpress chmod 600 /etc/ssl/private/wordpress.key 2>/dev/null

if docker exec tsm-theme-wordpress test -f /etc/ssl/certs/wordpress.crt 2>/dev/null; then
    echo "   ✓ Certificates copied"
else
    echo "   ✗ Failed to copy certificates"
fi
echo ""

# 5. Enable SSL in Apache
echo "5. Enabling SSL in Apache..."
docker cp ./certs/ssl.conf tsm-theme-wordpress:/etc/apache2/sites-available/ssl.conf 2>/dev/null
docker exec tsm-theme-wordpress a2enmod ssl 2>/dev/null
docker exec tsm-theme-wordpress a2ensite ssl 2>/dev/null

# Test Apache config
CONFIG_TEST=$(docker exec tsm-theme-wordpress apache2ctl configtest 2>&1)
if echo "$CONFIG_TEST" | grep -q "Syntax OK"; then
    echo "   ✓ Apache configuration is valid"
    docker exec tsm-theme-wordpress apache2ctl graceful 2>/dev/null
    echo "   ✓ Apache restarted with SSL"
else
    echo "   ✗ Apache configuration error:"
    echo "$CONFIG_TEST"
fi
echo ""

# 6. Now uncomment volumes for future restarts
echo "6. Re-enabling certificate volumes in docker-compose.yml..."
sed -i.bak 's|# - ./certs/terryshaguy.local.crt:/etc/ssl/certs/wordpress.crt|- ./certs/terryshaguy.local.crt:/etc/ssl/certs/wordpress.crt|' docker-compose.yml
sed -i.bak 's|# - ./certs/terryshaguy.local.key:/etc/ssl/private/wordpress.key|- ./certs/terryshaguy.local.key:/etc/ssl/private/wordpress.key|' docker-compose.yml

echo "   ✓ Certificate volumes re-enabled"
echo ""

echo "=== Fix Complete ==="
echo ""
echo "Your WordPress site should now be accessible at:"
echo "  HTTP:  http://localhost:8080"
echo "  HTTPS: https://terryshaguy.local:8443"
echo ""
echo "Test the connection:"
echo "  curl -k https://terryshaguy.local:8443"
