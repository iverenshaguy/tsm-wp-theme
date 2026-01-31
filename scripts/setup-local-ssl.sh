#!/bin/bash

# Script to set up custom domain with SSL for local WordPress development
# Usage: ./scripts/setup-local-ssl.sh <domain>
# Example: ./scripts/setup-local-ssl.sh terryshaguy.local

if [ -z "$1" ]; then
    echo "Usage: $0 <domain>"
    echo "Example: $0 terryshaguy.local"
    echo ""
    echo "This will:"
    echo "  1. Add domain to /etc/hosts"
    echo "  2. Generate SSL certificate"
    echo "  3. Configure Docker to use HTTPS"
    echo "  4. Update WordPress URLs"
    exit 1
fi

DOMAIN="$1"
CERT_DIR="./certs"
KEY_FILE="$CERT_DIR/$DOMAIN.key"
CERT_FILE="$CERT_DIR/$DOMAIN.crt"

echo "=== Setting up SSL for $DOMAIN ==="
echo ""

# 1. Create certs directory
echo "1. Creating certificates directory..."
mkdir -p "$CERT_DIR"
echo "   ✓ Created $CERT_DIR"
echo ""

# 2. Generate SSL certificate
echo "2. Generating self-signed SSL certificate..."
echo ""

if [ -f "$KEY_FILE" ] && [ -f "$CERT_FILE" ]; then
    echo "   ⚠ Certificate already exists. Regenerating..."
fi

openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout "$KEY_FILE" \
    -out "$CERT_FILE" \
    -subj "/C=US/ST=State/L=City/O=Development/CN=$DOMAIN" \
    -addext "subjectAltName=DNS:$DOMAIN,DNS:*.$DOMAIN,IP:127.0.0.1,IP:::1"

if [ $? -eq 0 ]; then
    echo "   ✓ Certificate generated: $CERT_FILE"
    echo "   ✓ Private key generated: $KEY_FILE"
else
    echo "   ✗ Failed to generate certificate"
    exit 1
fi
echo ""

# 3. Add domain to /etc/hosts
echo "3. Adding $DOMAIN to /etc/hosts..."
echo ""

if grep -q "$DOMAIN" /etc/hosts 2>/dev/null; then
    echo "   ⚠ $DOMAIN already exists in /etc/hosts"
else
    echo "127.0.0.1    $DOMAIN" | sudo tee -a /etc/hosts > /dev/null
    if [ $? -eq 0 ]; then
        echo "   ✓ Added $DOMAIN to /etc/hosts"
    else
        echo "   ✗ Failed to add to /etc/hosts (you may need to run manually)"
        echo "   Add this line to /etc/hosts:"
        echo "   127.0.0.1    $DOMAIN"
    fi
fi
echo ""

# 4. Update docker-compose.yml to use SSL
echo "4. Updating docker-compose.yml for SSL..."
echo ""

# Check if docker-compose.yml exists
if [ ! -f "docker-compose.yml" ]; then
    echo "   ✗ docker-compose.yml not found"
    exit 1
fi

# Create backup
cp docker-compose.yml docker-compose.yml.backup

# Check if SSL is already configured
if grep -q "443:443" docker-compose.yml; then
    echo "   ⚠ SSL port already configured in docker-compose.yml"
else
    # Add SSL port and volume mounts
    # This is a bit complex, so we'll provide instructions
    echo "   ⚠ docker-compose.yml needs manual update"
    echo ""
    echo "   Add these to the wordpress service in docker-compose.yml:"
    echo ""
    echo "   ports:"
    echo "     - \"8080:80\""
    echo "     - \"8443:443\"  # Add this line"
    echo ""
    echo "   volumes:"
    echo "     - wordpress_data:/var/www/html"
    echo "     - ./src:/var/www/html/wp-content/themes/tsm-theme"
    echo "     - ./certs/$DOMAIN.crt:/etc/ssl/certs/wordpress.crt  # Add this"
    echo "     - ./certs/$DOMAIN.key:/etc/ssl/private/wordpress.key  # Add this"
    echo ""
fi

# 5. Create Apache SSL configuration
echo "5. Creating Apache SSL configuration..."
echo ""

SSL_CONF="$CERT_DIR/ssl.conf"
cat > "$SSL_CONF" <<EOF
<VirtualHost *:443>
    ServerName $DOMAIN
    DocumentRoot /var/www/html

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/wordpress.crt
    SSLCertificateKeyFile /etc/ssl/private/wordpress.key

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

<VirtualHost *:80>
    ServerName $DOMAIN
    Redirect permanent / https://$DOMAIN/
</VirtualHost>
EOF

echo "   ✓ Created Apache SSL config: $SSL_CONF"
echo ""

# 6. Instructions for completing setup
echo "=== Next Steps ==="
echo ""
echo "1. Update docker-compose.yml:"
echo "   Add SSL port and certificate volumes to wordpress service:"
echo ""
echo "   ports:"
echo "     - \"8080:80\""
echo "     - \"8443:443\""
echo ""
echo "   volumes:"
echo "     - wordpress_data:/var/www/html"
echo "     - ./src:/var/www/html/wp-content/themes/tsm-theme"
echo "     - ./certs/$DOMAIN.crt:/etc/ssl/certs/wordpress.crt"
echo "     - ./certs/$DOMAIN.key:/etc/ssl/private/wordpress.key"
echo ""
echo "2. Copy SSL config to WordPress container:"
echo "   docker cp $SSL_CONF tsm-theme-wordpress:/etc/apache2/sites-available/ssl.conf"
echo ""
echo "3. Enable SSL in Apache:"
echo "   docker exec tsm-theme-wordpress a2enmod ssl"
echo "   docker exec tsm-theme-wordpress a2ensite ssl"
echo ""
echo "4. Restart containers:"
echo "   docker-compose restart"
echo ""
echo "5. Update WordPress URLs:"
echo "   ./scripts/update-wordpress-urls.sh localhost:8080 https://$DOMAIN:8443"
echo ""
echo "6. Trust the certificate in your browser:"
echo "   - Open https://$DOMAIN:8443"
echo "   - Click 'Advanced' → 'Proceed to $DOMAIN'"
echo "   - Or add certificate to Keychain (Mac) / Certificate Store (Windows)"
echo ""
echo "Certificate files created:"
echo "   - $KEY_FILE"
echo "   - $CERT_FILE"
echo ""
echo "⚠ Add $CERT_DIR/ to .gitignore to avoid committing certificates!"
