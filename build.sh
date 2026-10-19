#!/usr/bin/env bash
# Builds adminer.css from src/*.css. Run after editing anything in src/
# (the pre-commit hook in .githooks/ runs it automatically on every commit).
# zz-legacy.css is intentionally excluded — see that file for why.
#
# - @VERSION@ / @DATE@ in the header are filled from the VERSION file and
#   today's date (= the commit date when run from the pre-commit hook).
# - Font files referenced as url("fonts/*.woff2") are inlined as base64
#   data URIs, so adminer.css is a single self-contained file.
set -euo pipefail
cd "$(dirname "$0")"

out="adminer.css"
files=(src/00-header.css src/00-tokens.css src/05-icons.css src/10-fonts.css \
       src/20-reset.css src/30-elements.css src/40-page-layout.css \
       src/50-sidebar.css src/60-forms.css src/70-tables.css)

version="$(tr -d '[:space:]' < VERSION)"
date="$(date +%F)"

cat "${files[@]}" | VERSION="$version" DATE="$date" python3 -c '
import base64, os, re, sys
css = sys.stdin.read()
css = css.replace("@VERSION@", os.environ["VERSION"]).replace("@DATE@", os.environ["DATE"])
def inline(m):
    data = base64.b64encode(open(m.group(1), "rb").read()).decode()
    return f"url(\"data:font/woff2;base64,{data}\")"
sys.stdout.write(re.sub(r"url\(\"(fonts/[^\"]+\.woff2)\"\)", inline, css))
' > "$out"
echo "Built $out v$version ($date): $(wc -l < "$out" | tr -d ' ') lines, $(( $(wc -c < "$out") / 1024 )) KB from ${#files[@]} files"
