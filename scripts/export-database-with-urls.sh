#!/bin/bash

# Script to export database and optionally convert URLs before export
# Usage: ./scripts/export-database-with-urls.sh [output-file] [local-url] [production-url]
# Example: ./scripts/export-database-with-urls.sh database-export.sql localhost:8080 terryshaguy.org

OUTPUT_FILE="${1:-database-export.sql}"
LOCAL_URL="${2:-localhost:8080}"
PRODUCTION_URL="${3:-terryshaguy.org}"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Exporting database..."
echo ""

# Check if user wants to convert URLs before export
if [ -n "$2" ] && [ -n "$3" ]; then
    echo "⚠️  Converting URLs from '$LOCAL_URL' to '$PRODUCTION_URL' before export..."
    echo ""
    read -p "Continue? (y/N): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Aborted."
        exit 1
    fi
    
    # First, convert URLs in the database
    SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$LOCAL_URL', '$PRODUCTION_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$LOCAL_URL', '$PRODUCTION_URL');
EOF
)
    
    echo "Updating URLs in database..."
    echo "$SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > /dev/null 2>&1
    
    if [ $? -ne 0 ]; then
        echo "✗ Error updating URLs. Exporting database with original URLs..."
    else
        echo "✓ URLs updated successfully!"
    fi
fi

# Export the database
echo ""
echo "Exporting database to $OUTPUT_FILE..."
echo "This may take a few minutes..."

# Export with proper error handling
# Use --no-tablespaces to avoid permission errors
# Use --single-transaction for consistent dump
# Redirect stdout (SQL) to file, stderr (warnings/errors) to temp file
TEMP_ERROR_FILE=$(mktemp)
docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASSWORD" --no-tablespaces --single-transaction --routines --triggers "$DB_NAME" > "$OUTPUT_FILE" 2> "$TEMP_ERROR_FILE"
EXPORT_EXIT_CODE=$?

# Check for real errors (not warnings)
EXPORT_ERROR=$(cat "$TEMP_ERROR_FILE" 2>/dev/null | grep -v "Warning\|Using a password" || true)
rm -f "$TEMP_ERROR_FILE"

if [ $EXPORT_EXIT_CODE -eq 0 ]; then
    echo ""
    # Check if file contains actual SQL (not just errors)
    if grep -q "^-- MySQL dump\|^CREATE TABLE\|^INSERT INTO" "$OUTPUT_FILE" 2>/dev/null; then
        echo "✓ Database exported successfully!"
        echo ""
        echo "Output file: $OUTPUT_FILE"
        echo "File size: $(du -h "$OUTPUT_FILE" | cut -f1)"
        echo ""
    else
        echo "✗ Export failed! The output file doesn't contain valid SQL."
        if [ -n "$EXPORT_ERROR" ]; then
            echo "Error output: $EXPORT_ERROR"
        fi
        rm -f "$OUTPUT_FILE"
        EXPORT_EXIT_CODE=1
    fi
fi

if [ $EXPORT_EXIT_CODE -eq 0 ]; then
    
    # If URLs were converted, revert them back to local URLs
    if [ -n "$2" ] && [ -n "$3" ]; then
        echo "Reverting URLs back to local URLs..."
        REVERT_SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$PRODUCTION_URL', '$LOCAL_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$PRODUCTION_URL', '$LOCAL_URL');
EOF
)
        
        echo "$REVERT_SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > /dev/null 2>&1
        
        if [ $? -eq 0 ]; then
            echo "✓ URLs reverted back to local URLs successfully!"
            echo ""
            echo "Your local database is ready to use with local URLs."
            echo "The exported file ($OUTPUT_FILE) contains production URLs."
        else
            echo "⚠️  Warning: Failed to revert URLs automatically."
            echo "   Please manually revert using:"
            echo "   ./scripts/update-wordpress-urls-to-dev.sh $PRODUCTION_URL $LOCAL_URL"
        fi
    else
        echo "To convert URLs in the exported file, you can:"
        echo "  1. Import the database"
        echo "  2. Use: ./scripts/update-wordpress-urls-to-dev.sh <production-url> <dev-url>"
    fi
else
    echo ""
    echo "✗ Export failed!"
    echo ""
    if [ -n "$EXPORT_ERROR" ]; then
        echo "Error details:"
        echo "$EXPORT_ERROR"
        echo ""
    fi
    
    # If URLs were converted but export failed, try to revert them anyway
    if [ -n "$2" ] && [ -n "$3" ]; then
        echo ""
        echo "Attempting to revert URLs back to local URLs..."
        REVERT_SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$PRODUCTION_URL', '$LOCAL_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$PRODUCTION_URL', '$LOCAL_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$PRODUCTION_URL', '$LOCAL_URL');
EOF
)
        echo "$REVERT_SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > /dev/null 2>&1
        if [ $? -eq 0 ]; then
            echo "✓ URLs reverted back to local URLs."
        else
            echo "⚠️  Failed to revert URLs. Please manually revert using:"
            echo "   ./scripts/update-wordpress-urls-to-dev.sh $PRODUCTION_URL $LOCAL_URL"
        fi
    fi
    
    exit 1
fi
