#!/usr/bin/env node

/**
 * Script to build dist files and test them in a browser with Docker
 * 
 * Usage:
 *   node scripts/test-dist-browser.js          # Build and mount dist to Docker
 *   node scripts/test-dist-browser.js --stop  # Stop Docker after testing
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const distDir = path.join(__dirname, '../dist');
const args = process.argv.slice(2);
const shouldStop = args.includes('--stop') || args.includes('-s');

function runCommand(command, description, cwd = path.join(__dirname, '..')) {
  console.log(`\n📦 ${description}...`);
  try {
    execSync(command, { stdio: 'inherit', cwd });
    return true;
  } catch (error) {
    console.error(`❌ Error: ${description} failed`);
    return false;
  }
}

function checkDockerRunning() {
  try {
    execSync('docker ps', { stdio: 'pipe' });
    return true;
  } catch (error) {
    return false;
  }
}

function checkContainerRunning(containerName) {
  try {
    const output = execSync(`docker ps --filter "name=${containerName}" --format "{{.Names}}"`, {
      encoding: 'utf-8',
      stdio: 'pipe'
    });
    return output.trim() === containerName;
  } catch (error) {
    return false;
  }
}

function stopContainer(containerName) {
  console.log(`\n🛑 Stopping ${containerName}...`);
  try {
    execSync(`docker stop ${containerName}`, { stdio: 'inherit' });
    return true;
  } catch (error) {
    console.error(`⚠️  Could not stop ${containerName}`);
    return false;
  }
}

function startContainer(containerName) {
  console.log(`\n🚀 Starting ${containerName}...`);
  try {
    execSync(`docker start ${containerName}`, { stdio: 'inherit' });
    return true;
  } catch (error) {
    console.error(`⚠️  Could not start ${containerName}`);
    return false;
  }
}

function updateDockerCompose() {
  const dockerComposePath = path.join(__dirname, '../docker-compose.yml');
  const dockerComposeContent = fs.readFileSync(dockerComposePath, 'utf-8');
  
  // Check if it's already using dist
  if (dockerComposeContent.includes('./dist:/var/www/html/wp-content/themes/tsm-theme')) {
    console.log('✅ Docker Compose already configured to use dist/');
    return true;
  }
  
  // Backup original
  const backupPath = dockerComposePath + '.backup';
  if (!fs.existsSync(backupPath)) {
    fs.copyFileSync(dockerComposePath, backupPath);
    console.log('📋 Created backup of docker-compose.yml');
  }
  
  // Update to use dist instead of src
  const updatedContent = dockerComposeContent.replace(
    /\.\/src:\/var\/www\/html\/wp-content\/themes\/tsm-theme/g,
    './dist:/var/www/html/wp-content/themes/tsm-theme'
  );
  
  fs.writeFileSync(dockerComposePath, updatedContent);
  console.log('✅ Updated docker-compose.yml to use dist/');
  return true;
}

function restoreDockerCompose() {
  const dockerComposePath = path.join(__dirname, '../docker-compose.yml');
  const backupPath = dockerComposePath + '.backup';
  
  if (fs.existsSync(backupPath)) {
    fs.copyFileSync(backupPath, dockerComposePath);
    console.log('✅ Restored original docker-compose.yml');
    return true;
  }
  return false;
}

// Main execution
console.log('🌐 Testing dist files in browser\n');
console.log('=' .repeat(50));

// Check Docker
if (!checkDockerRunning()) {
  console.error('❌ Docker is not running. Please start Docker Desktop first.');
  process.exit(1);
}

// Build dist files
if (!runCommand('npm run build', 'Building dist files')) {
  process.exit(1);
}

// Verify dist exists
if (!fs.existsSync(distDir)) {
  console.error('❌ dist/ folder not found after build!');
  process.exit(1);
}

// Update docker-compose.yml to use dist
updateDockerCompose();

const containerName = 'tsm-theme-wordpress';
const isRunning = checkContainerRunning(containerName);

if (isRunning) {
  console.log(`\n🔄 Container ${containerName} is already running. Restarting to apply changes...`);
  stopContainer(containerName);
}

// Start/restart Docker containers
if (!runCommand('docker-compose up -d', 'Starting Docker containers')) {
  console.error('❌ Failed to start Docker containers');
  restoreDockerCompose();
  process.exit(1);
}

// Wait a moment for containers to start
console.log('\n⏳ Waiting for WordPress to start...');
setTimeout(() => {
  console.log('\n✅ WordPress is ready!');
  console.log('\n🌐 Access your theme in the browser:');
  console.log('   WordPress Admin: http://localhost:8080/wp-admin');
  console.log('   Site: http://localhost:8080');
  console.log('\n📝 Note: The dist/ folder is now mounted to your WordPress theme directory.');
  console.log('   Any changes to dist/ will require rebuilding and restarting Docker.');
  
  if (shouldStop) {
    console.log('\n⏳ Waiting 30 seconds before stopping...');
    setTimeout(() => {
      stopContainer(containerName);
      restoreDockerCompose();
      console.log('\n✅ Testing complete. Docker restored to use src/');
    }, 30000);
  } else {
    console.log('\n💡 Tip: Use --stop to automatically stop Docker after testing');
    console.log('   Run: npm run test:dist:browser -- --stop');
  }
}, 5000);
