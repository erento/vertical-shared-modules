#!/bin/bash

# Get the directory of the bash script
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd -P)"

# Array of spinoff directories
SPINOFF_DIR_NAMES=("mietedeinsportauto" "mietedeinelimo" "mietedeinezelte" "mietedeinenoldtimer")

# Function to compile less to css
compile_less_to_css() {
    local spinoff_dir="$1"
    local less_file="${spinoff_dir}/wp-content/themes/theme/less/styles.less"
    local css_dir="${spinoff_dir}/wp-content/themes/theme/css"

    # Check if the less file exists
    if [ -f "$less_file" ]; then
        echo "Compiling $less_file to $css_dir"
        lessc --source-map --js --clean-css "$less_file" "$css_dir/styles.min.css"
        echo "Compilation complete."
    else
        echo "Error: Less file not found: $less_file"
    fi
}

# Loop through spinoff directories and compile less to css
for spinoff in "${SPINOFF_DIR_NAMES[@]}"; do
    spinoff_path="$(dirname "$SCRIPT_DIR")/${spinoff}"
    if [ -d "$spinoff_path" ]; then
        compile_less_to_css "$spinoff_path"
    else
        echo "Error: Spinoff directory not found: $spinoff_path"
    fi
done
