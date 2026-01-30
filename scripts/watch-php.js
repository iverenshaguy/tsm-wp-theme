#!/usr/bin/env node

/**
 * Watch PHP and HTML files for changes
 * Since files are mounted in Docker, changes are immediately reflected
 * This script just notifies when files change
 */

const chokidar = require('chokidar');
const path = require('path');

const patterns = [
  path.join(__dirname, '../src/**/*.php'),
  path.join(__dirname, '../src/**/*.html'),
];

console.log('👀 Watching PHP and HTML files...');
console.log('📁 Watching: src/**/*.php, src/**/*.html');
console.log('💡 Note: Files are mounted in Docker, changes are live immediately\n');

const watcher = chokidar.watch(patterns, {
  ignored: /node_modules|dist|vendor/,
  persistent: true,
  ignoreInitial: true,
});

watcher
  .on('change', (filePath) => {
    const relativePath = path.relative(process.cwd(), filePath);
    console.log(`📝 File changed: ${relativePath}`);
    console.log('💡 Refresh your browser to see changes\n');
  })
  .on('add', (filePath) => {
    const relativePath = path.relative(process.cwd(), filePath);
    console.log(`➕ File added: ${relativePath}\n`);
  })
  .on('unlink', (filePath) => {
    const relativePath = path.relative(process.cwd(), filePath);
    console.log(`➖ File removed: ${relativePath}\n`);
  })
  .on('ready', () => {
    console.log('✅ PHP/HTML watcher ready\n');
  })
  .on('error', (error) => {
    console.error(`❌ Watcher error: ${error}`);
  });

// Keep process alive
process.on('SIGINT', () => {
  console.log('\n👋 Stopping PHP/HTML watcher...');
  watcher.close();
  process.exit(0);
});
