/**
 * Some published variants of yargs@17.x omit the compiled CommonJS bundle
 * under `build/index.cjs`, but Laravel Mix still requires it when invoking
 * `require('yargs/yargs')`. This postinstall hook recreates the missing file
 * so `npm run production`/`npm run build` can execute without throwing.
 */
const fs = require('fs');
const path = require('path');

const buildDir = path.resolve(__dirname, '../../node_modules/yargs/build');
const buildFile = path.join(buildDir, 'index.cjs');
const shim = "module.exports = require('../index.cjs');\n";

try {
  if (!fs.existsSync(buildDir)) {
    fs.mkdirSync(buildDir, { recursive: true });
  }

  if (!fs.existsSync(buildFile)) {
    fs.writeFileSync(buildFile, shim, 'utf8');
    console.log('[postinstall] Created yargs build shim.');
  }
} catch (error) {
  console.warn('[postinstall] Unable to create yargs build shim:', error.message);
}

