#!/bin/bash

# Array of Spinoff projects
projects=("vertical-sportauto" "vertical-limo" "vertical-zelt" "vertical-oldtimer")

# Loop through each project
for project in "${projects[@]}"
do
    echo "Cloning project: $project"
    # Run SSH command for each project
    ssh erento@erento.smarthost.eu -p 5739 "/home/erento/repositories/$project"
done
