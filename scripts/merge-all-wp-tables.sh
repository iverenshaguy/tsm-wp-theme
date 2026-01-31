#!/bin/bash

# Script to merge ALL data from wp_ tables into terrysha_ tables
# Usage: ./scripts/merge-all-wp-tables.sh [--dry-run] [--backup]

DRY_RUN=false
BACKUP=false

# Parse arguments
for arg in "$@"; do
    case $arg in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --backup)
            BACKUP=true
            shift
            ;;
        *)
            ;;
    esac
done

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"
BACKUP_FILE="backup_before_merge_all_$(date +%Y%m%d_%H%M%S).sql"

echo "=== WordPress Complete Table Merger ==="
echo ""
echo "This will merge ALL data from wp_* tables into terrysha_* tables"
echo ""

if [ "$DRY_RUN" = true ]; then
    echo "⚠ DRY RUN MODE - No changes will be made"
    echo ""
fi

# Get all wp_ tables
echo "1. Discovering wp_ tables..."
echo ""
WP_TABLES=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SHOW TABLES LIKE 'wp_%';" 2>/dev/null)

if [ -z "$WP_TABLES" ]; then
    echo "No wp_ tables found. Nothing to merge."
    exit 0
fi

WP_TABLE_COUNT=$(echo "$WP_TABLES" | wc -l | tr -d ' ')
echo "Found $WP_TABLE_COUNT wp_ tables to process"
echo ""

# Create backup if requested
if [ "$BACKUP" = true ] && [ "$DRY_RUN" = false ]; then
    echo "2. Creating backup..."
    docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "   ✓ Backup created: $BACKUP_FILE"
    else
        echo "   ✗ Backup failed!"
        exit 1
    fi
    echo ""
fi

# Process each table
echo "3. Processing tables..."
echo ""

MERGED_COUNT=0
SKIPPED_COUNT=0
ERROR_COUNT=0
RENAMED_COUNT=0

for wp_table in $WP_TABLES; do
    # Get corresponding terrysha_ table name
    terrysha_table=$(echo "$wp_table" | sed 's/^wp_/terrysha_/')
    
    # Check if terrysha_ table exists
    TABLE_EXISTS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SHOW TABLES LIKE '$terrysha_table';" 2>/dev/null)
    
    if [ -z "$TABLE_EXISTS" ]; then
        # No terrysha_ table exists - rename wp_ table to terrysha_
        echo "   ↻ Renaming $wp_table → $terrysha_table..."
        
        if [ "$DRY_RUN" = false ]; then
            RENAME_RESULT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "RENAME TABLE \`$wp_table\` TO \`$terrysha_table\`;" 2>&1)
            
            if [ $? -eq 0 ]; then
                echo "      ✓ Renamed successfully"
                RENAMED_COUNT=$((RENAMED_COUNT + 1))
            else
                echo "      ✗ Failed to rename: $(echo "$RENAME_RESULT" | head -1)"
                ERROR_COUNT=$((ERROR_COUNT + 1))
            fi
        else
            echo "      [DRY RUN] Would rename $wp_table → $terrysha_table"
            RENAMED_COUNT=$((RENAMED_COUNT + 1))
        fi
        continue
    fi
    
    # Get row count
    WP_COUNT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM \`$wp_table\`;" 2>/dev/null)
    
    if [ "$WP_COUNT" -eq 0 ]; then
        echo "   ⊘ Skipping $wp_table (empty)"
        SKIPPED_COUNT=$((SKIPPED_COUNT + 1))
        continue
    fi
    
    echo "   → Merging $wp_table → $terrysha_table ($WP_COUNT rows)..."
    
    if [ "$DRY_RUN" = false ]; then
        # Get table structure to determine merge strategy
        # First, try to get column names
        COLUMNS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SHOW COLUMNS FROM \`$wp_table\`;" 2>/dev/null | awk '{print $1}' | tr '\n' ',' | sed 's/,$//')
        
        if [ -z "$COLUMNS" ]; then
            echo "      ✗ Failed to get table structure"
            ERROR_COUNT=$((ERROR_COUNT + 1))
            continue
        fi
        
        # Check for primary key or unique identifier
        # Try common ID columns
        ID_COLUMN=""
        for col in id ID ID_column; do
            if echo "$COLUMNS" | grep -qi "\b$col\b"; then
                ID_COLUMN=$(echo "$COLUMNS" | tr ',' '\n' | grep -i "^$col$" | head -1)
                break
            fi
        done
        
        # For tables with ID columns, use INSERT IGNORE or ON DUPLICATE KEY UPDATE
        # For tables without, use simple INSERT with NOT EXISTS check
        
        if [ -n "$ID_COLUMN" ]; then
            # Table has an ID column - use INSERT IGNORE
            MERGE_SQL="INSERT IGNORE INTO \`$terrysha_table\` SELECT * FROM \`$wp_table\`;"
        else
            # No ID column - need to check for duplicates differently
            # Get first column as identifier
            FIRST_COL=$(echo "$COLUMNS" | cut -d',' -f1)
            
            # Try INSERT with NOT EXISTS check on first column
            MERGE_SQL="INSERT INTO \`$terrysha_table\` SELECT * FROM \`$wp_table\` wp 
                WHERE NOT EXISTS (
                    SELECT 1 FROM \`$terrysha_table\` ts 
                    WHERE ts.\`$FIRST_COL\` = wp.\`$FIRST_COL\`
                );"
        fi
        
        # Execute merge
        RESULT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "$MERGE_SQL" 2>&1)
        
        if [ $? -eq 0 ]; then
            AFFECTED=$(echo "$RESULT" | grep -i "affected" | awk '{print $2}' || echo "?")
            echo "      ✓ Merged ($AFFECTED rows affected)"
            MERGED_COUNT=$((MERGED_COUNT + 1))
        else
            # If that failed, try simpler approach - just copy all rows
            SIMPLE_RESULT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "INSERT IGNORE INTO \`$terrysha_table\` SELECT * FROM \`$wp_table\`;" 2>&1)
            
            if [ $? -eq 0 ]; then
                AFFECTED=$(echo "$SIMPLE_RESULT" | grep -i "affected" | awk '{print $2}' || echo "?")
                echo "      ✓ Merged using simple method ($AFFECTED rows affected)"
                MERGED_COUNT=$((MERGED_COUNT + 1))
            else
                echo "      ✗ Failed: $(echo "$SIMPLE_RESULT" | head -1)"
                ERROR_COUNT=$((ERROR_COUNT + 1))
            fi
        fi
    else
        echo "      [DRY RUN] Would merge $WP_COUNT rows"
        MERGED_COUNT=$((MERGED_COUNT + 1))
    fi
done

echo ""
echo "=== Summary ==="
echo ""
echo "Tables processed: $WP_TABLE_COUNT"
echo "Successfully merged: $MERGED_COUNT"
echo "Renamed (no target table): $RENAMED_COUNT"
echo "Skipped (empty): $SKIPPED_COUNT"
echo "Errors: $ERROR_COUNT"
echo ""

if [ "$DRY_RUN" = false ]; then
    echo "✓ Merge completed!"
    echo ""
    echo "Next steps:"
    echo "1. Verify data in WordPress admin"
    echo "2. Check for any errors above"
    echo "3. If everything looks good, you can remove wp_ tables:"
    echo "   ./scripts/remove-wp-tables.sh --confirm"
    echo ""
    if [ "$BACKUP" = true ]; then
        echo "Backup saved to: $BACKUP_FILE"
    fi
else
    echo "This was a dry run. To actually merge, run:"
    echo "  $0 --backup"
fi
