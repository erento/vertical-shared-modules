#!/bin/bash

projects=("vertical-shared-modules" "mietedeinsportauto" "mietedeinelimo" "mietedeinezelte" "mietedeinenoldtimer")

for project in "${projects[@]}"
do
    printf "\n\n------------------\n\n"

    echo "Pushing changes for project: $project"
    cd "/Users/niko/Sites/GitHub/$project"
    git push cpanel
done

read -p "Press Enter to exit..."
