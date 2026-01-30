#!/usr/bin/env node

/**
 * Watch JavaScript files for changes
 * For dev: WordPress loads unminified source directly (no rebuild needed)
 * Just notifies when files change
 */

const chokidar = require('chokidar');
const path = require('path');

const srcDir = path.join(__dirname, '../src/assets/js');

console.log('👀 Watching JavaScript files...');
console.log(`📁 Watching: ${srcDir}`);
console.log('💡 WordPress loads unminified source directly - just refresh browser\n');

const watcher = chokidar.watch(`${srcDir}/**/*.js`, {
  ignored: /node_modules/,
  persistent: true,
  ignoreInitial: true,
});

watcher
  .on('change', (filePath) => {
    const relativePath = path.relative(process.cwd(), filePath);
    console.log(`\n📝 File changed: ${relativePath}`);
    console.log('💡 Refresh your browser to see changes\n');
  })
  .on('ready', () => {
    console.log('✅ JavaScript watcher ready\n');
  })
  .on('error', (error) => {
    console.error(`❌ Watcher error: ${error}`);
  });

// Keep process alive
process.on('SIGINT', () => {
  console.log('\n👋 Stopping JavaScript watcher...');
  watcher.close();
  process.exit(0);
});
