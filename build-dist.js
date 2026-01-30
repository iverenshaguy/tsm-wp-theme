#!/usr/bin/env node

/**
 * Build script to copy deployable files to dist/ folder
 * This creates a clean deployment package with only files needed for WordPress
 */

const fs = require('fs');
const path = require('path');

const distDir = path.join(__dirname, 'dist');

// Files and folders to copy
const filesToCopy = [
  // Core WordPress theme files (must be at root)
  'style.css',
  'index.php',
  'functions.php',
  'header.php',
  'footer.php',
  'sidebar.php',
  'comments.php',
  'searchform.php',
  
  // Template files
  '404.php',
  'archive.php',
  'archive-book.php',
  'archive-mission.php',
  'single.php',
  'single-book.php',
  'single-mission.php',
  'page.php',
  'page-about.php',
  'page-contact.php',
  'page-how-to-know-jesus.php',
  'page-our-ministries.php',
  'page-partners.php',
  'page-prayer-requests.php',
  'front-page.php',
  'search.php',
  
  // Theme screenshot (optional but recommended)
  'screenshot.png',
  'screenshot.jpg',
  
  // Directories
  'assets',
  'functions',
  'template-parts',
];

// Files/folders to exclude from assets
const excludePatterns = [
  /\.gitkeep$/,
  /\.DS_Store$/,
  /node_modules/,
  /\.git/,
  /input\.css$/, // Don't copy source CSS, only compiled main.css
];

function copyFile(src, dest) {
  const destDir = path.dirname(dest);
  if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
  }
  fs.copyFileSync(src, dest);
}

function copyDirectory(src, dest) {
  if (!fs.existsSync(dest)) {
    fs.mkdirSync(dest, { recursive: true });
  }
  
  const entries = fs.readdirSync(src, { withFileTypes: true });
  
  for (const entry of entries) {
    const srcPath = path.join(src, entry.name);
    const destPath = path.join(dest, entry.name);
    
    // Skip excluded patterns
    if (excludePatterns.some(pattern => pattern.test(srcPath))) {
      continue;
    }
    
    if (entry.isDirectory()) {
      copyDirectory(srcPath, destPath);
    } else {
      copyFile(srcPath, destPath);
    }
  }
}

function buildDist() {
  console.log('🧹 Cleaning dist folder...');
  if (fs.existsSync(distDir)) {
    fs.rmSync(distDir, { recursive: true, force: true });
  }
  fs.mkdirSync(distDir, { recursive: true });
  
  console.log('📦 Copying files to dist/...');
  
  for (const item of filesToCopy) {
    const srcPath = path.join(__dirname, item);
    const destPath = path.join(distDir, item);
    
    if (!fs.existsSync(srcPath)) {
      console.warn(`⚠️  Warning: ${item} not found, skipping...`);
      continue;
    }
    
    const stat = fs.statSync(srcPath);
    if (stat.isDirectory()) {
      copyDirectory(srcPath, destPath);
      console.log(`✓ Copied directory: ${item}/`);
    } else {
      copyFile(srcPath, destPath);
      console.log(`✓ Copied file: ${item}`);
    }
  }
  
  console.log('\n✅ Build complete! Deployable files are in dist/');
  console.log('📁 You can now deploy the contents of dist/ to wp-content/themes/tsm-theme/');
}

buildDist();
