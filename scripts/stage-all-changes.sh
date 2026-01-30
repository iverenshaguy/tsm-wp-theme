#!/bin/bash

# Script to properly stage all changes including moves and symlinks

echo "📦 Staging all changes..."
echo ""

# Stage the renames (Git already detected them)
echo "Staging file moves (renames)..."
git add -u

# Stage the new src/ directory (source files)
echo "Staging source files in src/..."
git add src/

# Stage the symlinks at root
echo "Staging symlinks..."
git add *.php style.css screenshot.png functions template-parts assets 2>/dev/null || true

# Stage other new/modified files
echo "Staging other changes..."
git add .eslintignore .github/ .husky/ docs/ scripts/ 2>/dev/null || true
git add .eslintrc.json .gitignore package.json 2>/dev/null || true

echo ""
echo "✅ All changes staged!"
echo ""
echo "Summary:"
git status --short | head -30
