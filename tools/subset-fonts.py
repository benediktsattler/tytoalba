"""Cut the text-only Fira Mono fonts out of the Fira Mono Nerd Font.

Drops every Private Use Area glyph (the Nerd Font icons, ~2.3 MB) and keeps
the rest (Latin, Greek, Cyrillic, punctuation, box drawing; ~60 KB). The
dotted zero is Fira Mono's own `zero` glyph, so it survives.

usage: python3 tools/subset-fonts.py <FiraMonoNerdFont-Regular.woff2> <out.woff2>
"""
import sys

from fontTools import subset
from fontTools.ttLib import TTFont


def main() -> None:
    src, out = sys.argv[1], sys.argv[2]
    font = TTFont(src)
    keep = [u for u in font.getBestCmap() if not (0xE000 <= u <= 0xF8FF or u >= 0xF0000)]
    opts = subset.Options()
    opts.flavor = "woff2"
    opts.layout_features = ["*"]
    sub = subset.Subsetter(opts)
    sub.populate(unicodes=keep)
    sub.subset(font)
    font.flavor = "woff2"
    font.save(out)


if __name__ == "__main__":
    main()
