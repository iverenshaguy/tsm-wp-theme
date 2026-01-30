#!/bin/bash

# Script to fix Git staging issues with symlinks
# This stages files directly from src/ instead of through symlinks

echo "🔧 Fixing Git staging for symlinked files..."
echo ""

# Remove old files from Git index (they're now symlinks)
echo "Removing old file entries from Git index..."
git rm --cached functions/*.php functions/**/*.php 2>/dev/null || true
git rm --cached template-parts/*.php 2>/dev/null || true
git rm --cached *.php 2>/dev/null || true
git rm --cached style.css screenshot.png 2>/dev/null || true
git rm --cached assets/css/input.css assets/css/main.css assets/js/main.js assets/images/* 2>/dev/null || true

# Stage the actual source files from src/
echo "Staging source files from src/..."
git add src/

# Stage the symlinks themselves (Git will track them as symlinks)
echo "Staging symlinks..."
git add *.php style.css screenshot.png functions template-parts assets 2>/dev/null || true

echo ""
echo "✅ Done! You can now commit your changes."
echo ""
echo "Note: Git will track symlinks. When others clone the repo, they'll get the symlinks"
echo "and can create them if needed, or work directly from src/"
