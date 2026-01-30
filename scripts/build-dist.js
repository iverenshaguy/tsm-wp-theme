#!/usr/bin/env node

/**
 * Post-build script to copy deployable files to dist/ folder
 * This creates a clean deployment package with only files needed for WordPress
 * Run after CSS/JS build processes
 * 
 * Copies PHP templates from root and src/, and built assets from assets/ to dist/
 */

const fs = require('fs');
const path = require('path');

const distDir = path.join(__dirname, '../dist');
const srcDir = path.join(__dirname, '../src');
const rootDir = path.join(__dirname, '..');

// PHP template files (WordPress requires these at root)
const phpTemplates = [
  'style.css',
  'index.php',
  'functions.php',
  'header.php',
  'footer.php',
  'sidebar.php',
  'comments.php',
  'searchform.php',
  '404.php',
  'archive.php',
  'archive-book.php',
  'archive-mission.php',
  'single.php',
  'single-book.php',
  'single-mission.php',
  'page.php',
  'page-about.php',
  'page-contact-us.php',
  'page-how-to-know-jesus.php',
  'page-our-ministries.php',
  'page-partners.php',
  'page-prayer-requests.php',
  'front-page.php',
  'search.php',
  'screenshot.png',
  'screenshot.jpg',
];

// Directories to copy
const directoriesToCopy = [
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
  
  // Copy PHP templates (check src/ first, then root)
  for (const file of phpTemplates) {
    const srcPath = path.join(srcDir, file);
    const rootPath = path.join(rootDir, file);
    const destPath = path.join(distDir, file);
    
    let sourcePath = null;
    if (fs.existsSync(srcPath)) {
      sourcePath = srcPath;
    } else if (fs.existsSync(rootPath)) {
      sourcePath = rootPath;
    }
    
    if (sourcePath && fs.existsSync(sourcePath)) {
      copyFile(sourcePath, destPath);
      console.log(`✓ Copied file: ${file}`);
    } else if (file.endsWith('.jpg')) {
      // Screenshot.jpg is optional
      continue;
    } else {
      console.warn(`⚠️  Warning: ${file} not found, skipping...`);
    }
  }
  
  // Copy directories (check src/ first, then root)
  for (const dir of directoriesToCopy) {
    const srcPath = path.join(srcDir, dir);
    const rootPath = path.join(rootDir, dir);
    const destPath = path.join(distDir, dir);
    
    let sourcePath = null;
    if (fs.existsSync(srcPath)) {
      sourcePath = srcPath;
    } else if (fs.existsSync(rootPath)) {
      sourcePath = rootPath;
    }
    
    if (sourcePath && fs.existsSync(sourcePath)) {
      copyDirectory(sourcePath, destPath);
      console.log(`✓ Copied directory: ${dir}/`);
    }
  }
  
  // Copy built assets from src/assets/ to dist/assets/
  // Note: CSS and JS are already built to dist/ by build:css:dist and build:js:dist
  // This step ensures images and any other assets are copied, and creates directory structure if needed
  const assetsSrc = path.join(srcDir, 'assets');
  const assetsDest = path.join(distDir, 'assets');
  
  if (fs.existsSync(assetsSrc)) {
    // Create dist/assets structure if it doesn't exist
    if (!fs.existsSync(assetsDest)) {
      fs.mkdirSync(assetsDest, { recursive: true });
    }
    
    // Ensure CSS directory exists (CSS is already built to dist/assets/css/main.css by build:css:dist)
    const cssDest = path.join(assetsDest, 'css');
    if (!fs.existsSync(cssDest)) {
      fs.mkdirSync(cssDest, { recursive: true });
    }
    
    // Only copy CSS from src/ if it doesn't exist in dist/ (fallback for dev builds)
    const cssSrc = path.join(assetsSrc, 'css');
    const distMainCss = path.join(cssDest, 'main.css');
    if (fs.existsSync(cssSrc) && !fs.existsSync(distMainCss)) {
      const mainCss = path.join(cssSrc, 'main.css');
      if (fs.existsSync(mainCss)) {
        copyFile(mainCss, distMainCss);
        console.log(`✓ Copied: assets/css/main.css`);
      }
    } else if (fs.existsSync(distMainCss)) {
      console.log(`✓ Using: assets/css/main.css (already built to dist/)`);
    }
    
    // Ensure JS directory exists (JS is already built to dist/assets/js/main.js by build:js:dist)
    const jsDest = path.join(assetsDest, 'js');
    if (!fs.existsSync(jsDest)) {
      fs.mkdirSync(jsDest, { recursive: true });
    }
    
    // Only copy JS from src/ if it doesn't exist in dist/ (fallback for dev builds)
    const jsSrc = path.join(assetsSrc, 'js');
    const distMainJs = path.join(jsDest, 'main.js');
    if (fs.existsSync(jsSrc) && !fs.existsSync(distMainJs)) {
      const mainJs = path.join(jsSrc, 'main.js');
      if (fs.existsSync(mainJs)) {
        copyFile(mainJs, distMainJs);
        console.log(`✓ Copied: assets/js/main.js`);
      }
    } else if (fs.existsSync(distMainJs)) {
      console.log(`✓ Using: assets/js/main.js (already built to dist/)`);
    }
    
    // Copy images (always needed)
    const imagesSrc = path.join(assetsSrc, 'images');
    const imagesDest = path.join(assetsDest, 'images');
    if (fs.existsSync(imagesSrc)) {
      copyDirectory(imagesSrc, imagesDest);
      console.log(`✓ Copied directory: assets/images/`);
    }
  }
  
  console.log('\n✅ Build complete! Deployable files are in dist/');
  console.log('📁 You can now deploy the contents of dist/ to wp-content/themes/tsm-theme/');
}

buildDist();
