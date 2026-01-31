#!/bin/bash

# Complete SSL setup script - does everything automatically
# Usage: ./scripts/complete-ssl-setup.sh <domain>
# Example: ./scripts/complete-ssl-setup.sh terryshaguy.local

if [ -z "$1" ]; then
    echo "Usage: $0 <domain>"
    echo "Example: $0 terryshaguy.local"
    exit 1
fi

DOMAIN="$1"
CERT_DIR="./certs"
KEY_FILE="$CERT_DIR/$DOMAIN.key"
CERT_FILE="$CERT_DIR/$DOMAIN.crt"
SSL_CONF="$CERT_DIR/ssl.conf"

echo "=== Complete SSL Setup for $DOMAIN ==="
echo ""

# Run the initial setup script
./scripts/setup-local-ssl.sh "$DOMAIN"

if [ $? -ne 0 ]; then
    echo "Initial setup failed. Please check errors above."
    exit 1
fi

echo ""
echo "=== Completing Docker Configuration ==="
echo ""

# Update docker-compose.yml
echo "1. Updating docker-compose.yml..."
echo ""

# Check if SSL port already exists
if grep -q "8443:443" docker-compose.yml; then
    echo "   ✓ SSL port already configured"
else
    # Add SSL port
    sed -i.bak '/- "8080:80"/a\
      - "8443:443"' docker-compose.yml
    
    echo "   ✓ Added SSL port 8443:443"
fi

# Check if certificate volumes exist
if grep -q "wordpress.crt" docker-compose.yml; then
    echo "   ✓ Certificate volumes already configured"
else
    # Add certificate volumes
    sed -i.bak '/- \.\/src:\/var\/www\/html\/wp-content\/themes\/tsm-theme/a\
      - ./certs/'"$DOMAIN"'.crt:/etc/ssl/certs/wordpress.crt\
      - ./certs/'"$DOMAIN"'.key:/etc/ssl/private/wordpress.key' docker-compose.yml
    
    echo "   ✓ Added certificate volume mounts"
fi

echo ""

# Copy SSL config and enable it
echo "2. Configuring Apache SSL..."
echo ""

# Wait for container to be ready
sleep 2

# Copy SSL config
docker cp "$SSL_CONF" tsm-theme-wordpress:/etc/apache2/sites-available/ssl.conf 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✓ Copied SSL configuration"
else
    echo "   ⚠ Could not copy SSL config (container might not be running)"
    echo "   Run this manually after containers start:"
    echo "   docker cp $SSL_CONF tsm-theme-wordpress:/etc/apache2/sites-available/ssl.conf"
fi

# Enable SSL module and site
docker exec tsm-theme-wordpress a2enmod ssl 2>/dev/null
docker exec tsm-theme-wordpress a2ensite ssl 2>/dev/null

echo "   ✓ Enabled SSL module and site"
echo ""

# Restart containers
echo "3. Restarting containers..."
docker-compose restart wordpress 2>/dev/null
sleep 3

echo "   ✓ Containers restarted"
echo ""

# Update WordPress URLs
echo "4. Updating WordPress URLs..."
echo ""

# Get current URLs
CURRENT_URL=$(docker exec tsm-theme-db mysql -uwordpress -pwordpress wordpress -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'siteurl';" 2>/dev/null)

if [ -n "$CURRENT_URL" ]; then
    # Extract domain/port from current URL
    NEW_URL="https://$DOMAIN:8443"
    
    docker exec tsm-theme-db mysql -uwordpress -pwordpress wordpress -e "
        UPDATE terrysha_options SET option_value = '$NEW_URL' WHERE option_name = 'siteurl';
        UPDATE terrysha_options SET option_value = '$NEW_URL' WHERE option_name = 'home';
    " 2>/dev/null
    
    echo "   ✓ Updated WordPress URLs to: $NEW_URL"
else
    echo "   ⚠ Could not update URLs automatically"
    echo "   Run manually:"
    echo "   ./scripts/update-wordpress-urls.sh $CURRENT_URL https://$DOMAIN:8443"
fi

echo ""
echo "=== Setup Complete! ==="
echo ""
echo "Your WordPress site is now available at:"
echo "  https://$DOMAIN:8443"
echo ""
echo "⚠ IMPORTANT: Trust the certificate in your browser:"
echo ""
echo "1. Open: https://$DOMAIN:8443"
echo "2. You'll see a security warning (this is normal for self-signed certs)"
echo "3. Click 'Advanced' → 'Proceed to $DOMAIN' (or 'Accept the Risk')"
echo ""
echo "To trust permanently (Mac):"
echo "  1. Open Keychain Access"
echo "  2. Drag $CERT_FILE into 'login' keychain"
echo "  3. Double-click the certificate"
echo "  4. Expand 'Trust' → Set to 'Always Trust'"
echo ""
echo "To trust permanently (Windows):"
echo "  1. Double-click $CERT_FILE"
echo "  2. Click 'Install Certificate'"
echo "  3. Choose 'Local Machine' → 'Place all certificates in the following store'"
echo "  4. Browse → Select 'Trusted Root Certification Authorities'"
echo ""
