#!/bin/bash

# Get the directory of the current script
SCRIPT_DIR="$(dirname "$(realpath "$0")")"

# Navigate to the root of the module
MODULE_ROOT="$(realpath "$SCRIPT_DIR/..")"

# Build project
cd "$MODULE_ROOT" || exit
yarn
yarn build

mkdir -p "$MODULE_ROOT/build/WhiteLabel"

# copy required files only
rsync -arh --no-links "$MODULE_ROOT/" \
  --exclude 'build' \
  --exclude '.commitlintrc.json' \
  --exclude '.editorconfig' \
  --exclude '.git' \
  --exclude '.github' \
  --exclude '.gitignore' \
  --exclude '.husky' \
  --exclude 'node_modules' \
  --exclude 'postcss.config.js' \
  --exclude '/scripts' \
  --exclude 'tailwind.config.js' \
  --exclude 'vendor' \
  --exclude '.versionrc.json' \
  --exclude 'vite.config.js' \
  . "$MODULE_ROOT/build/WhiteLabel/"

(cd "$MODULE_ROOT/build" && zip -r WhiteLabel.zip WhiteLabel)
