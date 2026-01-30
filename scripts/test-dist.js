#!/usr/bin/env node

/**
 * Script to build and test dist files locally
 * 
 * Usage:
 *   node scripts/test-dist.js          # Build and show file structure
 *   node scripts/test-dist.js --serve  # Build and serve with a local server
 *   node scripts/test-dist.js --check  # Build and verify all files exist
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const distDir = path.join(__dirname, '../dist');
const args = process.argv.slice(2);
const shouldServe = args.includes('--serve') || args.includes('-s');
const shouldCheck = args.includes('--check') || args.includes('-c');

function runCommand(command, description) {
  console.log(`\n📦 ${description}...`);
  try {
    execSync(command, { stdio: 'inherit', cwd: path.join(__dirname, '..') });
    return true;
  } catch (error) {
    console.error(`❌ Error: ${description} failed`);
    process.exit(1);
  }
}

function checkFileExists(filePath, description) {
  const fullPath = path.join(distDir, filePath);
  if (fs.existsSync(fullPath)) {
    const stats = fs.statSync(fullPath);
    console.log(`  ✅ ${description}: ${filePath} (${(stats.size / 1024).toFixed(2)} KB)`);
    return true;
  } else {
    console.log(`  ❌ ${description}: ${filePath} - MISSING!`);
    return false;
  }
}

function checkDirectoryExists(dirPath, description) {
  const fullPath = path.join(distDir, dirPath);
  if (fs.existsSync(fullPath)) {
    const files = fs.readdirSync(fullPath);
    console.log(`  ✅ ${description}: ${dirPath} (${files.length} items)`);
    return true;
  } else {
    console.log(`  ❌ ${description}: ${dirPath} - MISSING!`);
    return false;
  }
}

function verifyBuild() {
  console.log('\n🔍 Verifying build output...\n');
  
  let allGood = true;
  
  // Check critical files
  allGood = checkFileExists('style.css', 'Theme stylesheet') && allGood;
  allGood = checkFileExists('functions.php', 'Functions file') && allGood;
  allGood = checkFileExists('index.php', 'Index template') && allGood;
  
  // Check assets
  allGood = checkFileExists('assets/css/main.css', 'Main CSS') && allGood;
  allGood = checkFileExists('assets/js/main.js', 'Main JS') && allGood;
  allGood = checkDirectoryExists('assets/images', 'Images directory') && allGood;
  
  // Check directories
  allGood = checkDirectoryExists('functions', 'Functions directory') && allGood;
  allGood = checkDirectoryExists('template-parts', 'Template parts') && allGood;
  
  // Count total files
  function countFiles(dir) {
    let count = 0;
    const items = fs.readdirSync(dir, { withFileTypes: true });
    for (const item of items) {
      if (item.isDirectory()) {
        count += countFiles(path.join(dir, item.name));
      } else {
        count++;
      }
    }
    return count;
  }
  
  const totalFiles = countFiles(distDir);
  console.log(`\n📊 Total files in dist/: ${totalFiles}`);
  
  return allGood;
}

function showFileStructure() {
  console.log('\n📁 Dist folder structure:\n');
  
  function printTree(dir, prefix = '', isLast = true) {
    const items = fs.readdirSync(dir, { withFileTypes: true })
      .sort((a, b) => {
        // Directories first, then files
        if (a.isDirectory() && !b.isDirectory()) return -1;
        if (!a.isDirectory() && b.isDirectory()) return 1;
        return a.name.localeCompare(b.name);
      });
    
    items.forEach((item, index) => {
      const isLastItem = index === items.length - 1;
      const currentPrefix = isLast ? '└── ' : '├── ';
      const nextPrefix = isLast ? '    ' : '│   ';
      
      console.log(`${prefix}${currentPrefix}${item.name}`);
      
      if (item.isDirectory()) {
        printTree(
          path.join(dir, item.name),
          prefix + nextPrefix,
          isLastItem
        );
      }
    });
  }
  
  printTree(distDir);
}

function serveDist() {
  console.log('\n🌐 Starting local server...\n');
  console.log('Note: This serves static files only. For full WordPress testing, use Docker.');
  console.log('Press Ctrl+C to stop the server.\n');
  
  try {
    // Try to use Python's http.server (most common)
    execSync(`python3 -m http.server 8000`, {
      stdio: 'inherit',
      cwd: distDir
    });
  } catch (error) {
    try {
      // Fallback to Node's http-server if available
      execSync(`npx http-server -p 8000`, {
        stdio: 'inherit',
        cwd: distDir
      });
    } catch (error2) {
      console.error('❌ Could not start server. Install http-server: npm install -g http-server');
      console.error('   Or use Python: python3 -m http.server');
      process.exit(1);
    }
  }
}

// Main execution
console.log('🚀 Testing dist files\n');
console.log('=' .repeat(50));

// Build dist files
runCommand('npm run build', 'Building dist files');

// Show structure
showFileStructure();

// Verify build
const buildValid = verifyBuild();

if (!buildValid) {
  console.log('\n⚠️  Some files are missing! Please check the build output above.');
  process.exit(1);
}

console.log('\n✅ Build verification complete!');

if (shouldCheck) {
  console.log('\n✨ All checks passed!');
  process.exit(0);
}

if (shouldServe) {
  serveDist();
} else {
  console.log('\n💡 Tip: Use --serve or -s to start a local server');
  console.log('   Use --check or -c to only verify files without serving\n');
}
