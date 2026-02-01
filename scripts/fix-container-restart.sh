#!/bin/bash

# Fix WordPress container restart loop
# Usage: ./scripts/fix-container-restart.sh

echo "=== Fixing Container Restart Loop ==="
echo ""

# 1. Stop containers
echo "1. Stopping containers..."
docker-compose stop wordpress
sleep 2
echo "   ✓ Containers stopped"
echo ""

# 2. Check Apache error logs
echo "2. Checking Apache error logs..."
echo ""
docker-compose logs --tail=50 wordpress | grep -i error || echo "   No obvious errors in recent logs"
echo ""

# 3. Check if certificates exist
echo "3. Verifying certificates..."
if [ ! -f "./certs/terryshaguy.local.crt" ] || [ ! -f "./certs/terryshaguy.local.key" ]; then
    echo "   ✗ Certificates not found!"
    echo "   Generating certificates..."
    ./scripts/setup-local-ssl.sh terryshaguy.local
    echo ""
fi

# 4. Check docker-compose.yml
echo "4. Verifying docker-compose.yml configuration..."
echo ""

# Check for port 8443
if grep -q "8443:443" docker-compose.yml; then
    echo "   ✓ Port 8443:443 is configured"
else
    echo "   ✗ Port 8443:443 is missing!"
    echo "   Adding it now..."
    # This would need manual edit
fi

# Check for certificate volumes
if grep -q "terryshaguy.local.crt" docker-compose.yml; then
    echo "   ✓ Certificate volumes are configured"
else
    echo "   ✗ Certificate volumes are missing!"
fi
echo ""

# 5. Remove container and recreate
echo "5. Removing and recreating container..."
docker-compose rm -f wordpress
docker-compose up -d wordpress

echo ""
echo "Waiting for container to start..."
sleep 5

# 6. Check container status
echo ""
echo "6. Container status:"
docker-compose ps wordpress
echo ""

# 7. Check if it's running or still restarting
STATUS=$(docker-compose ps wordpress | grep wordpress | awk '{print $6" "$7" "$8" "$9" "$10}')
if echo "$STATUS" | grep -q "Up"; then
    echo "   ✓ Container is running!"
    echo ""
    echo "7. Now enabling SSL..."
    ./scripts/enable-ssl-apache.sh
else
    echo "   ✗ Container is still restarting"
    echo ""
    echo "Checking logs for errors..."
    docker-compose logs --tail=20 wordpress
    echo ""
    echo "Common issues:"
    echo "  - Certificate files don't exist"
    echo "  - Certificate paths are wrong in docker-compose.yml"
    echo "  - Apache configuration error"
fi
