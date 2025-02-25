#! /bin/bash

# Get the directory of the current script
SCRIPT_DIR="$(dirname "$(realpath "$0")")"

# Navigate to the root of the module
MODULE_ROOT="$(realpath "$SCRIPT_DIR/..")"

# Build project
cd "$MODULE_ROOT" || exit
yarn
yarn build

# Generate artifact excluding everything that's not necessary for the correct operation of the module
zip -r "$MODULE_ROOT/../Whitelabel.zip" . -x ".git/*" ".husky/*" "node_modules/*" "scripts/*" ".commitlintrc.json" ".editorconfig" ".gitignore" ".versionrc.json" "postcss.config.js" "tailwind.config.js" "vite.config.js"
