#!/bin/bash

# Debug script to check missions and years
# Usage: ./scripts/debug-missions.sh

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "=== Missions Debug Info ==="
echo ""

# Total missions
echo "1. Total missions:"
TOTAL=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'mission' AND post_status = 'publish';" 2>/dev/null)
echo "   $TOTAL missions"
echo ""

# Missions by year
echo "2. Missions by year (from post_date):"
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
SELECT YEAR(post_date) as year, COUNT(*) as count 
FROM terrysha_posts 
WHERE post_type = 'mission' 
AND post_status = 'publish' 
GROUP BY YEAR(post_date) 
ORDER BY year DESC;
" 2>/dev/null
echo ""

# Available years from function
echo "3. Available years (from tsm_get_mission_years function logic):"
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
SELECT DISTINCT YEAR(post_date) as year
FROM terrysha_posts
WHERE post_type = 'mission'
AND post_status = 'publish'
AND post_date != ''
ORDER BY year DESC
LIMIT 10;
" 2>/dev/null
echo ""

# Check archived count logic
echo "4. Recent vs Archive breakdown:"
CURRENT_YEAR=$(date +%Y)
echo "   Current year: $CURRENT_YEAR"
echo "   Recent threshold: $((CURRENT_YEAR - 3))"
echo ""

# Sample mission titles and dates
echo "5. Sample missions (title, date, year):"
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
SELECT post_title, post_date, YEAR(post_date) as year
FROM terrysha_posts
WHERE post_type = 'mission'
AND post_status = 'publish'
ORDER BY post_date DESC
LIMIT 10;
" 2>/dev/null
echo ""

echo "=== Filter Logic Check ==="
echo ""
echo "Filter pills should show if:"
echo "  - Total missions >= 6"
echo "  - Total missions != archived missions"
echo ""
echo "Year pills show:"
echo "  - Recent years (>= current_year - 3), max 4"
echo "  - Archives (if any years < oldest recent year)"
