# Tyto Alba — Clean theme for Adminer

![Tyto Alba theme for Adminer — overview](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/cover.svg)

## Introduction

Tyto Alba is a quiet, warm-toned theme for [Adminer](https://www.adminer.org/) — off-white and warm grey colour language. Built on the bones of Hassan Azzi's Simple Theme.
- warm off-white/grey palette with one accent color
- intuitive layout
- collapsible sidebar

### Installation

1. Get a copy of [Adminer](https://www.adminer.org/)
2. Download `adminer.css` from [here](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/adminer.css).
3. Put the file `adminer.css` alongside `adminer.php`, or in the same directory that contains Adminer on your web server.

> **_NOTE:_** Adminer is better used for development environments, not for production purposes.

### Preview

#### Select (filters + with & without pagination)
![Tyto Alba theme for Adminer - Select with pagination](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/select.svg)
![Tyto Alba theme for Adminer - Select filters](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/select-filter.svg)
![Tyto Alba theme for Adminer - Create table](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/create-table.svg)

#### Schema
![Tyto Alba theme for Adminer - Schema](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/schema.svg)

#### Tables
![Tyto Alba theme for Adminer - Tables](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/tables.svg)

#### Query
![Tyto Alba theme for Adminer - Query](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/query.svg)

#### RTL (right-to-left)
![Tyto Alba theme for Adminer - RTL support](https://raw.githubusercontent.com/benediktsattler/tytoalba/main/screenshots/rtl.svg)

### Development

`adminer.css` is a generated file, built from the sources in [`src/`](src/):

```
src/
  00-tokens.css        colors, spacing, font tokens (:root)
  10-fonts.css         self-hosted Fira Mono NF + icon glyphs
  20-reset.css         low-level resets
  30-elements.css      body, links, inputs, buttons, messages/errors
  40-page-layout.css   sidebar/content split, header, collapsible sidebar
  50-sidebar.css       nav, DB select, table list, sidebar icons
  60-forms.css         fieldsets, SQL editor, icon buttons, footer
  70-tables.css        table/thead/tbody, schema view, column sort
  zz-legacy.css        dead rules from Simple Theme, kept for reference,
                        NOT built into adminer.css
```

Edit the files in `src/`, then run `./build.sh` to regenerate `adminer.css`.
There's no separate overrides file to layer on top anymore — what used to be
`adminer-overrides.css` is folded into these sources as the actual rules.

### License

Tyto Alba theme for Adminer is licensed under the [MIT License](https://github.com/benediktsattler/tytoalba/blob/main/LICENSE).

### Credits

Based on [Simple Theme by Hassan Azzi](https://github.com/devknown/simple-theme/), which forked [Steve Copley's theme](https://github.com/stevecopley/adminer)."
