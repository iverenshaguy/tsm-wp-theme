#!/usr/bin/env node

/**
 * Watch JavaScript files and rebuild when they change
 * Watches "src/assets/js/../..".js and rebuilds to assets/js/
 */

const chokidar = require('chokidar');
const { exec } = require('child_process');
const path = require('path');

const srcDir = path.join(__dirname, '../src/assets/js');
const buildCommand = 'node scripts/minify-js.js';

console.log('👀 Watching JavaScript files...');
console.log(`📁 Watching: ${srcDir}`);
console.log('🔄 Changes will trigger JS rebuild\n');

const watcher = chokidar.watch(`${srcDir}/**/*.js`, {
  ignored: /node_modules/,
  persistent: true,
  ignoreInitial: true,
});

watcher
  .on('change', (filePath) => {
    console.log(`\n📝 File changed: ${path.relative(process.cwd(), filePath)}`);
    console.log('🔨 Rebuilding JavaScript...');

    exec(buildCommand, (error, stdout, stderr) => {
      if (error) {
        console.error(`❌ Error: ${error.message}`);
        return;
      }
      if (stderr) {
        console.error(`⚠️  ${stderr}`);
      }
      console.log('✅ JavaScript rebuilt successfully\n');
    });
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
