#!/bin/bash
set -euo pipefail
base_dir="$(cd "$(dirname "$0")" && pwd)"
parts_dir="$base_dir/parts"
output="$base_dir/../install_master.sql"

cat "$parts_dir"/000_preamble.sql \
    "$parts_dir"/001_core.sql \
    "$parts_dir"/150_taxonomy_and_keywords.sql \
    "$parts_dir"/200_addons_marketplace.sql \
    "$parts_dir"/210_jobs_addon.sql \
    "$parts_dir"/900_modern_extensions.sql \
    "$parts_dir"/999_postamble.sql > "$output"

cp "$output" "$base_dir/../../public/assets/install.sql"

echo "install_master.sql rebuilt from parts and mirrored to public/assets/install.sql"
