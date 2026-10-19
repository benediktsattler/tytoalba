#!/usr/bin/env bash
# Builds adminer.css from src/*.css. Run after editing anything in src/.
# zz-legacy.css is intentionally excluded — see that file for why.
# Font files referenced as url("fonts/*.woff2") are inlined as base64 data
# URIs, so adminer.css is a single self-contained file.
set -euo pipefail
cd "$(dirname "$0")"

out="adminer.css"
files=(src/00-tokens.css src/05-icons.css src/10-fonts.css src/20-reset.css src/30-elements.css \
       src/40-page-layout.css src/50-sidebar.css src/60-forms.css src/70-tables.css)

cat "${files[@]}" | python3 -c '
import base64, re, sys
def inline(m):
    data = base64.b64encode(open(m.group(1), "rb").read()).decode()
    return f"url(\"data:font/woff2;base64,{data}\")"
sys.stdout.write(re.sub(r"url\(\"(fonts/[^\"]+\.woff2)\"\)", inline, sys.stdin.read()))
' > "$out"
echo "Built $out ($(wc -l < "$out" | tr -d ' ') lines, $(( $(wc -c < "$out") / 1024 )) KB from ${#files[@]} files)"
