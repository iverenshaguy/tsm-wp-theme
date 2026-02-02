#!/bin/bash

# Script to export database from Docker (without URL conversion)
# Usage: ./scripts/export-database.sh [output-file]
# Example: ./scripts/export-database.sh database-export.sql

OUTPUT_FILE="${1:-database-export.sql}"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Exporting database from Docker..."
echo ""

# Export the database
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
    # Check if file contains actual SQL (not just errors)
    if grep -q "^-- MySQL dump\|^CREATE TABLE\|^INSERT INTO" "$OUTPUT_FILE" 2>/dev/null; then
        echo ""
        echo "✓ Database exported successfully!"
        echo ""
        echo "Output file: $OUTPUT_FILE"
        echo "File size: $(du -h "$OUTPUT_FILE" | cut -f1)"
        echo ""
        echo "Next steps:"
        echo "1. Import this file into cPanel phpMyAdmin"
        echo "2. Update URLs in cPanel phpMyAdmin SQL tab if needed"
        echo ""
        echo "To export with URL conversion, use:"
        echo "  ./scripts/export-database-with-urls.sh $OUTPUT_FILE localhost:8080 terryshaguy.org"
    else
        echo ""
        echo "✗ Export failed! The output file doesn't contain valid SQL."
        if [ -n "$EXPORT_ERROR" ]; then
            echo "Error output: $EXPORT_ERROR"
        fi
        rm -f "$OUTPUT_FILE"
        exit 1
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
    echo "Make sure Docker containers are running:"
    echo "  docker-compose ps"
    exit 1
fi
