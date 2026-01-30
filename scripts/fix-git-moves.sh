#!/bin/bash

# Script to properly stage file moves from root to src/
# This tells Git these are moves, not deletions + new files

echo "🔧 Staging file moves properly..."
echo ""

# Enable rename detection
git config diff.renames true
git config diff.renameLimit 999999

# Remove old entries from Git index
echo "Removing old file entries from Git index..."
git rm --cached *.php 2>/dev/null || true
git rm --cached style.css screenshot.png 2>/dev/null || true
git rm --cached -r functions/ template-parts/ 2>/dev/null || true
git rm --cached assets/css/input.css assets/css/main.css assets/js/main.js 2>/dev/null || true
git rm --cached assets/images/* 2>/dev/null || true

# Stage all changes including the new src/ directory
echo "Staging source files from src/..."
git add src/

# Stage symlinks
echo "Staging symlinks..."
git add *.php style.css screenshot.png functions template-parts assets 2>/dev/null || true

# Stage other modified files
echo "Staging other modified files..."
git add .eslintrc.json .gitignore package.json scripts/ 2>/dev/null || true
git add *.md docker-compose.yml tailwind.config.js postcss.config.js 2>/dev/null || true

echo ""
echo "✅ Done! Checking status..."
echo ""
git status --short | head -20

echo ""
echo "💡 Git should now detect the moves. Check 'git status' to see if files show as 'renamed'"
