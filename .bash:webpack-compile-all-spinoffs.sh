#!/bin/bash

# Get the directory of the bash script
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd -P)"

# Array of spinoff directories
SPINOFF_DIR_NAMES=("mietedeinsportauto" "mietedeinelimo" "mietedeinezelte" "mietedeinenoldtimer")

compile_scripts_with_webpack() {
    local spinoff_dir="$1"
    local scripts_file="${spinoff_dir}/wp-content/themes/theme/js/scripts.module.js"
    local webpack_config="${spinoff_dir}/wp-content/themes/theme/.webpack.config.js"

    # Check if the scripts file exists
    if [ -f "$scripts_file" ]; then
        echo "Compiling $scripts_file with webpack"
        webpack --config "$webpack_config"
        echo "Compilation complete."
    else
        echo "Error: Scripts file not found: $scripts_file"
    fi
}

# Loop through spinoff directories and compile scripts.js with webpack
for spinoff in "${SPINOFF_DIR_NAMES[@]}"; do
    spinoff_path="$(dirname "$SCRIPT_DIR")/${spinoff}"
    if [ -d "$spinoff_path" ]; then
        compile_scripts_with_webpack "$spinoff_path"
    else
        echo "Error: Spinoff directory not found: $spinoff_path"
    fi
done

read -p "Press Enter to exit..."
