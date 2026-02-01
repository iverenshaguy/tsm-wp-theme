#!/bin/bash

# Troubleshoot SSL connection issues
# Usage: ./scripts/troubleshoot-ssl.sh

echo "=== SSL Connection Troubleshooting ==="
echo ""

# 1. Check if containers are running
echo "1. Checking container status..."
docker-compose ps
echo ""

# 2. Check if port 8443 is exposed
echo "2. Checking if port 8443 is exposed..."
if docker-compose ps | grep -q "8443:443"; then
    echo "   ✓ Port 8443:443 is configured"
else
    echo "   ✗ Port 8443:443 is NOT configured"
    echo "   Check docker-compose.yml - make sure port mapping includes:"
    echo "     - \"8443:443\""
fi
echo ""

# 3. Check if Apache is listening on port 443
echo "3. Checking if Apache is listening on port 443..."
LISTENING=$(docker exec tsm-theme-wordpress netstat -tlnp 2>/dev/null | grep ":443" || echo "")
if [ -n "$LISTENING" ]; then
    echo "   ✓ Apache is listening on port 443"
    echo "   $LISTENING"
else
    echo "   ✗ Apache is NOT listening on port 443"
    echo "   SSL might not be enabled"
fi
echo ""

# 4. Check if SSL module is enabled
echo "4. Checking Apache SSL module..."
SSL_MODULE=$(docker exec tsm-theme-wordpress apache2ctl -M 2>/dev/null | grep ssl_module || echo "")
if [ -n "$SSL_MODULE" ]; then
    echo "   ✓ SSL module is enabled"
else
    echo "   ✗ SSL module is NOT enabled"
    echo "   Run: docker exec tsm-theme-wordpress a2enmod ssl"
fi
echo ""

# 5. Check if SSL site is enabled
echo "5. Checking SSL site configuration..."
SSL_SITE=$(docker exec tsm-theme-wordpress ls /etc/apache2/sites-enabled/ | grep ssl || echo "")
if [ -n "$SSL_SITE" ]; then
    echo "   ✓ SSL site is enabled"
    echo "   Found: $SSL_SITE"
else
    echo "   ✗ SSL site is NOT enabled"
    echo "   Run: docker exec tsm-theme-wordpress a2ensite ssl"
fi
echo ""

# 6. Check if certificates exist
echo "6. Checking SSL certificates..."
if [ -f "./certs/terryshaguy.local.crt" ] && [ -f "./certs/terryshaguy.local.key" ]; then
    echo "   ✓ Certificate files exist"
    
    # Check if they're mounted in container
    if docker exec tsm-theme-wordpress test -f /etc/ssl/certs/wordpress.crt 2>/dev/null; then
        echo "   ✓ Certificate is mounted in container"
    else
        echo "   ✗ Certificate is NOT mounted in container"
        echo "   Check docker-compose.yml volume mounts"
    fi
    
    if docker exec tsm-theme-wordpress test -f /etc/ssl/private/wordpress.key 2>/dev/null; then
        echo "   ✓ Private key is mounted in container"
    else
        echo "   ✗ Private key is NOT mounted in container"
        echo "   Check docker-compose.yml volume mounts"
    fi
else
    echo "   ✗ Certificate files not found"
    echo "   Run: ./scripts/setup-local-ssl.sh terryshaguy.local"
fi
echo ""

# 7. Check Apache error logs
echo "7. Recent Apache errors..."
docker exec tsm-theme-wordpress tail -10 /var/log/apache2/error.log 2>/dev/null | grep -i ssl || echo "   No recent SSL errors"
echo ""

# 8. Test HTTP connection
echo "8. Testing HTTP connection..."
HTTP_TEST=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 2>/dev/null || echo "failed")
if [ "$HTTP_TEST" = "200" ] || [ "$HTTP_TEST" = "301" ] || [ "$HTTP_TEST" = "302" ]; then
    echo "   ✓ HTTP (port 8080) is working"
else
    echo "   ✗ HTTP (port 8080) is NOT working"
fi
echo ""

# 9. Test HTTPS connection
echo "9. Testing HTTPS connection..."
HTTPS_TEST=$(curl -k -s -o /dev/null -w "%{http_code}" https://localhost:8443 2>/dev/null || echo "failed")
if [ "$HTTPS_TEST" = "200" ] || [ "$HTTPS_TEST" = "301" ] || [ "$HTTPS_TEST" = "302" ]; then
    echo "   ✓ HTTPS (port 8443) is working"
else
    echo "   ✗ HTTPS (port 8443) is NOT working (got: $HTTPS_TEST)"
fi
echo ""

echo "=== Quick Fixes ==="
echo ""
echo "If SSL is not working, try:"
echo ""
echo "1. Make sure docker-compose.yml has:"
echo "   ports:"
echo "     - \"8443:443\""
echo "   volumes:"
echo "     - ./certs/terryshaguy.local.crt:/etc/ssl/certs/wordpress.crt"
echo "     - ./certs/terryshaguy.local.key:/etc/ssl/private/wordpress.key"
echo ""
echo "2. Enable SSL in Apache:"
echo "   docker exec tsm-theme-wordpress a2enmod ssl"
echo "   docker exec tsm-theme-wordpress a2ensite ssl"
echo ""
echo "3. Restart WordPress:"
echo "   docker-compose restart wordpress"
echo ""
echo "4. Check Apache config:"
echo "   docker exec tsm-theme-wordpress apache2ctl configtest"
