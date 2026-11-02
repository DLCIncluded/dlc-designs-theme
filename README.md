# DLC Designs Theme

A dark, terminal-inspired WooCommerce theme for the DLC Designs shop. Forked from [Underscores (_s)](https://underscores.me/).

## Setup

```sh
composer install
npm install
```

## omegamino-core

The omegamino-core plugin's popups and free-with notices read `--om-*` CSS variables. This theme sets them, plus popup overrides, in `sass/components/_omegamino.scss`. The giveaway disclosure is styled in `_site-footer.scss` and the checkout boxes in `plugins/woocommerce/_checkout.scss`.

## Release

`python release.py X.Y.Z` bumps every version string and builds `releases/dlc-designs-theme-X.Y.Z.zip`. Run `npm run compile:css` and `npm run compile:rtl` first.

## Available CLI commands

- `composer lint:wpcs` — checks all PHP files against PHP Coding Standards.
- `composer lint:php` — checks all PHP files for syntax errors.
- `composer make-pot` — generates a .pot file in the `languages/` directory.
- `npm run compile:css` — compiles SASS files to CSS.
- `npm run compile:rtl` — generates an RTL stylesheet.
- `npm run watch` — watches all SASS files and recompiles them to CSS on change.
- `npm run lint:scss` — checks all SASS files against CSS Coding Standards.
- `npm run lint:js` — checks all JavaScript files against JavaScript Coding Standards.
- `npm run bundle` — generates a .zip archive for distribution.
