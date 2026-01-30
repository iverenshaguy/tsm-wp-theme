#!/usr/bin/env node

/**
 * Minify JavaScript files for production
 * Minifies src/assets/js/*.js and outputs to assets/js/ or dist/assets/js/
 */

const fs = require('fs');
const path = require('path');
const { minify } = require('terser');

const srcDir = path.join(__dirname, '../src/assets/js');
const outputDir = process.argv.includes('--dist') 
  ? path.join(__dirname, '../dist/assets/js')
  : path.join(__dirname, '../src/assets/js');

// Ensure output directory exists
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

async function minifyFile(filePath) {
  const fileName = path.basename(filePath);
  const code = fs.readFileSync(filePath, 'utf8');
  
  try {
    const result = await minify(code, {
      compress: {
        drop_console: false, // Keep console logs for debugging
        drop_debugger: true,
      },
      format: {
        comments: false,
      },
    });
    
    if (result.error) {
      console.error(`❌ Error minifying ${fileName}:`, result.error);
      return false;
    }
    
    const outputPath = path.join(outputDir, fileName);
    fs.writeFileSync(outputPath, result.code);
    console.log(`✓ Minified: ${fileName}`);
    return true;
  } catch (error) {
    console.error(`❌ Error processing ${fileName}:`, error.message);
    return false;
  }
}

async function buildJs() {
  console.log('🔨 Minifying JavaScript files...');
  
  if (!fs.existsSync(srcDir)) {
    console.warn(`⚠️  Source directory ${srcDir} not found`);
    return;
  }
  
  const files = fs.readdirSync(srcDir).filter(file => file.endsWith('.js'));
  
  if (files.length === 0) {
    console.warn('⚠️  No JavaScript files found to minify');
    return;
  }
  
  const results = await Promise.all(files.map(file => {
    const filePath = path.join(srcDir, file);
    return minifyFile(filePath);
  }));
  
  const successCount = results.filter(r => r).length;
  console.log(`\n✅ Minified ${successCount}/${files.length} files`);
}

buildJs().catch(console.error);
