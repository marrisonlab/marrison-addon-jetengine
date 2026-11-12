# Marrison Addon JetEngine

**Seven free modules for JetEngine listings, queries, and dynamic fields.**

Marrison Addon JetEngine (MAJ) is a standalone fork focused on JetEngine. Its original, more comprehensive plugin is also free. [Marrison Addon](https://github.com/marrisonlab/marrison-addon)

[Download the installable ZIP](marrison-addon-jetengine.zip) · [GitHub releases](https://github.com/marrisonlab/marrison-addon-jetengine/releases) · [Module guide](#module-guide)

> **Zero payload from disabled modules.** MAJ loads a module's PHP files and registers its hooks only when the module is enabled and its dependencies are available. A disabled module adds no CSS or JavaScript to the site. The MAJ core handles the settings panel and updates.

## Installation

**Requirements** · WordPress 6.0 or later, PHP 7.4 or later, and an active JetEngine installation. Some modules also require Elementor or WooCommerce.

1. Download the installable ZIP. In WordPress, go to **Plugins → Add New → Upload Plugin**, select the ZIP, and activate **Marrison Addon JetEngine**.
2. Open **MAJ** in the WordPress menu. All modules start disabled.
3. Enable the modules you need and save. Each module card shows any missing dependency.

MAJ and Marrison Addon can run side by side with independent module settings. To move a module to MAJ, disable it in Marrison Addon, then enable it in MAJ. Existing widgets and saved configurations use the same identifiers. If a module is enabled in both plugins, Marrison Addon takes precedence.

## Module guide

| Module | What it adds | Additional requirement |
| --- | --- | --- |
| [Listing Grid Title](#listing-grid-title) | A title above a grid | Elementor |
| [Listing Columns](#listing-columns) | Buttons for changing the number of columns | Elementor |
| [Query Fallback](#query-fallback) | A backup query for a grid | Elementor |
| [Dynamic SVG](#dynamic-svg) | Inline SVG from a dynamic field | PHP DOM extension |
| [Recently Viewed Products](#recently-viewed-products) | Products recently viewed in the visitor's browser | WooCommerce |
| [Read More](#read-more) | Collapsible text with an expand button | Elementor |
| [Content Ticker](#content-ticker) | Scrolling text from a query or manually entered items | Elementor |

Before setting up a module, enable it in **MAJ** and save.

### Listing Grid Title

1. In Elementor, select the **Listing Grid** widget and open **Content → Listing Grid Title**.
2. Turn on **Show title** and enter a title or select a dynamic tag. Choose an HTML tag such as `H2` and add a link if needed.
3. Open **Style → Listing Grid Title** to adjust typography, colors, and spacing. The title appears above the grid.

### Listing Columns

1. Select a regular **Listing Grid**. Under **Advanced → CSS ID**, enter `catalog`.
2. Add the **Listing Columns** widget near the grid. Enter `#catalog` in **Listing CSS selector**. Use a shared CSS class to control several grids with one widget.
3. Set the minimum, maximum, and initial number of columns for each device. Enable **Remember selection** to save the visitor's choice in their browser.
4. Customize the buttons and icons, then try switching columns on the published page. Below **Minimum viewport width**, the grid uses its own responsive layout. The module supports regular grids. Slider and masonry layouts are excluded.

### Query Fallback

1. In **JetEngine → Query Builder**, save two distinct **Posts Query** configurations. For example, use products in the current category as the primary query and featured products as the fallback.
2. In Elementor's **Listing Grid**, open **Custom Query**, turn on **Use Custom Query**, and select the primary query.
3. Turn on **Enable Query Fallback** and select the backup in **Fallback Query**.
4. Choose **Only when empty** to use the backup when the primary query returns zero items. Choose **Fill missing items** to add unique results up to the primary query's item limit.

Use this module with single-page grids. The fallback pauses when filters are applied or when the grid uses pagination, Load More, Lazy Load, archive mode, or a randomized item count.

### Dynamic SVG

1. Select a local SVG from the WordPress Media Library in a JetEngine **Media** field configured to store an attachment ID or URL.
2. Add a **Dynamic Field** to the listing template and select that field as its source.
3. Turn on **Filter field output**. Under **Callback**, choose **Marrison – Inline SVG** to keep the original colors or **Marrison – Inline SVG (Current Color)** to inherit the text color.
4. Check the result on the published page. The module validates and sanitizes the file before rendering it inline. [Dynamic Field guide](https://crocoblock.com/knowledge-base/jetengine/dynamic-field-widget-overview/)

### Recently Viewed Products

1. In **JetEngine → Query Builder**, create a **Posts Query** and set **Post Type** to **Products**. Turn off **Cache Query**, since the results depend on the visitor's cookie.
2. In **Post & Page → Post In**, select the **Recently Viewed Products** macro. By default, **Limit** is `12` and **Exclude current product** is set to **Yes**.
3. Under **Order By**, select the option that preserves the ID order from **Post In**. This displays the most recently viewed product first.
4. In the **Listing Grid**, select a product listing template and connect the query under **Custom Query → Use Custom Query**. [Query Builder macros guide](https://crocoblock.com/knowledge-base/jetengine/macros-for-query-builder/)

To test it, visit three different product pages in the same browser. With the grid on the third page and the current product excluded, the second product appears first, followed by the first product. The grid shows zero products when the visitor's history is empty.

### Read More

1. In Elementor, open a **Text Editor** or **Dynamic Field** widget that contains a long block of text.
2. In **Content → Marrison — Read More**, turn on **Enable Read More** and set how many lines to show when collapsed.
3. Set the expand and collapse labels, such as “Read more” and “Read less.” Customize the button in **Style → Read More**.
4. Check the published page at different widths. The button appears when the text exceeds the number of visible lines.

### Content Ticker

1. Add the **Ticker** widget in Elementor and open **Content → Source**.
2. With **Manual (Repeater)** selected, add entries under **Ticker Items**. Each entry can have text and an optional link.
3. To use dynamic content, choose **JetEngine Query** and select a query. Set the field or macro for the text and link. For a list of posts, use `post_title` and `permalink`.
4. Set the separator icon, scroll direction, animation duration, and pause-on-hover behavior. Check the preview before publishing.

## Languages and license

English is the source language. The plugin includes Italian, Ukrainian, and Arabic translations. WordPress uses the site locale or the user's preferred dashboard language as appropriate. [Translation catalogs](marrison-addon-jetengine/languages/)

To update the catalogs with WP-CLI, run these commands from the repository root.

```sh
cd marrison-addon-jetengine
wp i18n make-pot . languages/marrison-addon-jet.pot --domain=marrison-addon-jet
wp i18n make-mo languages
```

MAJ inherits its license from Marrison Addon. [GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.html)
