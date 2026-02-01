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

if docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$OUTPUT_FILE" 2>&1; then
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
    echo "✗ Export failed! Check the error above."
    echo ""
    echo "Make sure Docker containers are running:"
    echo "  docker-compose ps"
    exit 1
fi
