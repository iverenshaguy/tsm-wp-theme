#!/bin/bash

# Deployment script for TSM Theme to cPanel dev preview
# Usage: ./deploy-to-dev.sh [FTP_HOST] [FTP_USER] [REMOTE_PATH]

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 TSM Theme Deployment Script${NC}"
echo "================================"

# Step 1: Build for production (CSS + dist folder)
echo -e "\n${YELLOW}Step 1: Building production assets...${NC}"
if ! npm run build; then
    echo -e "${RED}❌ Build failed!${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Production build complete${NC}"

# Check if dist folder exists
if [ ! -d "dist" ]; then
    echo -e "${RED}❌ dist/ folder not found!${NC}"
    exit 1
fi

# Step 2: Check if rsync is available
if command -v rsync &> /dev/null; then
    echo -e "\n${YELLOW}Step 2: Preparing to sync files...${NC}"
    
    # Get FTP details from arguments or prompt
    if [ -z "$1" ]; then
        read -p "FTP Host (e.g., ftp.yourdomain.com or IP): " FTP_HOST
    else
        FTP_HOST=$1
    fi
    
    if [ -z "$2" ]; then
        read -p "FTP Username: " FTP_USER
    else
        FTP_USER=$2
    fi
    
    if [ -z "$3" ]; then
        read -p "Remote path (e.g., /home/user/public_html/wp-content/themes/tsm-theme): " REMOTE_PATH
    else
        REMOTE_PATH=$3
    fi
    
    echo -e "\n${YELLOW}Syncing files from dist/ to ${FTP_HOST}...${NC}"
    echo "Deploying clean production build (no dev files)"
    
    rsync -avz --progress \
        dist/ ${FTP_USER}@${FTP_HOST}:${REMOTE_PATH}/
    
    echo -e "\n${GREEN}✅ Files synced successfully!${NC}"
else
    echo -e "\n${YELLOW}rsync not found. Please upload files manually:${NC}"
    echo "1. Upload the contents of the dist/ folder"
    echo "2. Use FTP client (FileZilla, Cyberduck, etc.) or cPanel File Manager"
    echo "3. Upload to: wp-content/themes/tsm-theme/"
    echo ""
    echo "See docs/DEPLOY-CPANEL.md for detailed instructions"
fi

# Step 3: Reminders
echo -e "\n${GREEN}📋 Next Steps:${NC}"
echo "1. Log into WordPress admin on your dev preview site"
echo "2. Go to Appearance → Themes"
echo "3. Activate 'TSM Theme'"
echo "4. Verify the site is working correctly"
echo "5. Update URLs in WordPress if dev preview uses different domain"
echo ""
echo -e "${YELLOW}💡 Tip: Check docs/DEPLOY-CPANEL.md for troubleshooting${NC}"
