# 1. Uninstall the broken package
npm uninstall @websanova/vue-auth

# 2. Clean up everything
rm -rf node_modules package-lock.json
npm cache clean --force

# 3. Reinstall everything clean
npm install

# 4. Now install vue-auth at a working version
npm install @websanova/vue-auth@2.21.11-beta
