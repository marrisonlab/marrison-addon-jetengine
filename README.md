# Marrison Addon JetEngine (MAJ)

Marrison Addon JetEngine is a standalone fork of Marrison Addon 1.3.95 for sites that use JetEngine. It requires WordPress 6.0 or later and PHP 7.4 or later.

## Modules

| Module | What it adds | Additional dependency |
| --- | --- | --- |
| Listing Grid Title | Title, link, and styling controls on the JetEngine Listing Grid widget | Elementor |
| Listing Columns | A widget that lets visitors choose the number of columns in one or more Listing Grids | Elementor |
| Query Fallback | A second Posts Query for an empty or partially filled Listing Grid | Elementor |
| Dynamic SVG | Dynamic Field callbacks for sanitized inline SVGs, with an optional current-color variant | — |
| Recently Viewed Products | A macro that returns the IDs of recently viewed WooCommerce products | WooCommerce |
| Read More | Expandable excerpts for JetEngine Dynamic Field and Elementor Text Editor widgets | Elementor |
| Content Ticker | Scrolling content from JetEngine Query Builder and macros, with support for manually entered items | Elementor |

## Setup

1. Install the ZIP from **Plugins → Add New → Upload Plugin** and activate it.
2. Activate JetEngine and any additional dependency for the modules you plan to use.
3. Open **Marrison Addon JetEngine** in WordPress admin. Select the modules you need and save the settings. All modules start disabled.

The two plugins have independent module settings. To move a module to MAJ, disable it in the original Marrison Addon and then enable it in MAJ. Both plugins can be active at the same time. The original takes priority for a module enabled in both editions. Existing widget IDs, control keys, macro names, and SVG callbacks are retained.

Query Fallback is designed for single-page Listing Grids using Posts Queries. It pauses when filters are applied or when the grid uses pagination, Load More, Lazy Load, archive mode, or randomized item counts. Listing Columns supports regular grids. Slider and masonry layouts are excluded.

## Updates

WordPress checks the latest stable release in [marrisonlab/marrison-addon-jetengine](https://github.com/marrisonlab/marrison-addon-jetengine/releases). Install version 1.0.1 or later once via **Plugins → Add New → Upload Plugin** to enable this check. If version 1.0.0 is already installed, use **Replace current with uploaded**.

For each release, use a `vX.Y.Z` tag matching the plugin version and attach `marrison-addon-jetengine-X.Y.Z.zip`. The ZIP must contain the plugin under `marrison-addon-jet/` to preserve the installed plugin path. GitHub provides the attached ZIP's SHA-256 digest, which the updater checks before installing it.

## Languages

English is the source language. Ukrainian (`uk`) and Arabic (`ar`) load according to the WordPress locale, including the dashboard user’s preferred language. The `languages` directory contains the POT template and the editable PO and compiled MO files.

To refresh the template and compile an edited catalog with WP-CLI:

```sh
wp i18n make-pot . languages/marrison-addon-jet.pot --domain=marrison-addon-jet
wp i18n make-mo languages
```

This plugin inherits the GPL-3.0-or-later license from Marrison Addon.
