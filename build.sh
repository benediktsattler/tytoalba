#!/usr/bin/env bash
# Builds adminer.css from src/*.css. Run after editing anything in src/.
# zz-legacy.css is intentionally excluded — see that file for why.
set -euo pipefail
cd "$(dirname "$0")"

out="adminer.css"
files=(src/00-tokens.css src/10-fonts.css src/20-reset.css src/30-elements.css \
       src/40-page-layout.css src/50-sidebar.css src/60-forms.css src/70-tables.css)

cat "${files[@]}" > "$out"
echo "Built $out ($(wc -l < "$out" | tr -d ' ') lines from ${#files[@]} files)"
