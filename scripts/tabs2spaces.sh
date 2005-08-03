#!/bin/bash

find . \( -name "*.php" -or -name "*.inc" \) -exec ./scripts/convert_tabs.php {} \;
