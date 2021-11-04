##!/bin/sh
git add . --all
git commit -m "$(date)"
git pull https://github.com/sisols/auto-kruiz.git
