#!/usr/bin/env node

/**
 * Watch CSS files and content files for changes
 * Rebuilds CSS whenever any watched file changes
 */

const chokidar = require('chokidar');
const { exec } = require('child_process');
const path = require('path');

const cssInputFile = path.join(__dirname, '../src/assets/css/input.css');
const cssOutputFile = path.join(__dirname, '../src/assets/css/main.css');
const srcDir = path.join(__dirname, '../src');

console.log('👀 Watching CSS files and content for changes...');
console.log(`📁 CSS Input: ${cssInputFile}`);
console.log(`📁 CSS Output: ${cssOutputFile}`);
console.log(`📁 Watching content: ${srcDir}/**/*.{html,js,php}\n`);

let buildTimeout;
let isBuilding = false;

function buildCSS() {
  if (isBuilding) {
    return; // Skip if already building
  }

  isBuilding = true;
  console.log('🔨 Building CSS...');

  exec(`npx tailwindcss -i "${cssInputFile}" -o "${cssOutputFile}"`, (error, stdout, stderr) => {
    isBuilding = false;

    if (error) {
      console.error(`❌ Build error: ${error.message}`);
      return;
    }

    if (stderr) {
      console.error(`⚠️  Build warning: ${stderr}`);
    }

    console.log('✅ CSS build complete\n');
  });
}

// Watch CSS input file
const cssWatcher = chokidar.watch(`${srcDir}/assets/css/**/*.css`, {
  ignored: /node_modules/,
  persistent: true,
  ignoreInitial: false,
});

// Watch content files (PHP, JS, HTML) that Tailwind scans
const contentWatcher = chokidar.watch(
  [`${srcDir}/**/*.php`, `${srcDir}/**/*.js`, `${srcDir}/**/*.html`],
  {
    ignored: [
      /node_modules/,
      /\.git/,
      new RegExp(cssOutputFile.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')), // Ignore output file
    ],
    persistent: true,
    ignoreInitial: false,
  }
);

function handleChange(filePath) {
  const relativePath = path.relative(process.cwd(), filePath);
  console.log(`📝 File changed: ${relativePath}`);

  // Debounce: wait 100ms before building (in case multiple files change at once)
  clearTimeout(buildTimeout);
  buildTimeout = setTimeout(() => {
    buildCSS();
  }, 100);
}

cssWatcher
  .on('change', handleChange)
  .on('add', handleChange)
  .on('ready', () => {
    console.log('✅ CSS file watcher ready');
  })
  .on('error', (error) => {
    console.error(`❌ CSS watcher error: ${error}`);
  });

contentWatcher
  .on('change', handleChange)
  .on('add', handleChange)
  .on('ready', () => {
    console.log('✅ Content file watcher ready');
    console.log('🚀 Initial build...\n');
    buildCSS();
  })
  .on('error', (error) => {
    console.error(`❌ Content watcher error: ${error}`);
  });

// Keep process alive
process.on('SIGINT', () => {
  console.log('\n👋 Stopping CSS watcher...');
  cssWatcher.close();
  contentWatcher.close();
  process.exit(0);
});
