#!/bin/bash

# Stop at the first failure: without this a failed build still zipped the
# module, and a release went out with no dist/ in it.
set -euo pipefail

# Get the directory of the current script
SCRIPT_DIR="$(dirname "$(realpath "$0")")"

# Navigate to the root of the module
MODULE_ROOT="$(realpath "$SCRIPT_DIR/..")"

# Build project. npm, not yarn: the module is built inside an InvoiceShelf
# 2.x checkout whose package.json names pnpm as its packageManager, which
# makes Yarn 1 refuse to run.
cd "$MODULE_ROOT"
npm install --no-audit --no-fund
npm run build
test -f dist/style.css || { echo "The build produced no dist/style.css" >&2; exit 1; }

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
