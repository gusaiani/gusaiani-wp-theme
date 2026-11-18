# Gustavo Saiani Wordpress Theme

This is the theme for [blog.gustavosaiani.com](http://blog.gustavosaiani.com)

It is a child theme of [Highwind](https://wordpress.org/themes/highwind/).

## Design

The blog carries the same design system as
[gustavosaiani.com](https://gustavosaiani.com): the Iro palette, the Plau
typefaces, and the fixed dark bar / dark footer that bookend the page.

**Type.** Two self-hosted Plau variable fonts in `fonts/`, declared as
`@font-face` in `sass/base.sass` and preloaded from `functions.php`:

| File | Family | Axes used | Where |
| --- | --- | --- | --- |
| `KimuraSansVF.woff2` | Kimura Sans | `wght 100-900`, `opsz 12-72` | Everything. `font-optical-sizing: auto` lets display sizes pick up the tighter cut on their own. |
| `CarbonaVF.woff2` | Carbona | `wght 200-900`, `MONO 100` | `code`, `pre`, `kbd`. |

**Colour.** Tokens live in `sass/mixins/colors.sass` and are re-exported as CSS
custom properties on `:root`. Colour lives in planes and marks, never in body
text: vermilion `#e23a2e` is identity (the wordmark, the section squares, the
link rules), gold `#c8a03c` is interaction (hover states), everything else is
ink on paper.

**Buttons.** Every button (form submits, the search button, core
`.wp-element-button`, `.wp-block-button__link`, and the file block's Download
button) shares the `%pigment-block` placeholder in `sass/base.sass` with the
top bar wordmark: vermilion fill, zero radius, uppercase Kimura at 700. Hover
darkens to `$hot_dark` `#c22e23` over 0.15s, with no underline. Selectors
inside `.article-content` are qualified with `a.` so they beat the post link
rule's red underline.

**Layout.** Highwind floats `.content` and `.sidebar` as percentage columns.
`sass/min-769px/base.sass` turns `.content-wrapper` into a grid instead and
unwinds those widths. The header also carries `.content-wrapper`, so every
layout override is scoped with `:not(.header)`.

Three pieces of markup are added on top of Highwind, all defined in
`framework/gusaiani-template.php` and hooked up in `functions.php`:

| Piece | Hook | Notes |
| --- | --- | --- |
| `gusaiani_top_bar()` | `highwind_body_top` | Fixed bar, vermilion "Gustavo Saiani Blog" wordmark (hardcoded, linking to the blog home), links back to the sections of the main site. The links are dropped below 768px so the wordmark never wraps. Replaces Highwind's skip link and slide-out nav, both of which are unhooked. |
| `highwind_site_title()` | `highwind_header` | Overridden to render the hero (kicker, blurb, contact row, avatar). Index only, the way the main site carries its hero on its one page. |
| `gusaiani_index_section_header()` | `highwind_content_top` | The label / title pair above the post list; just the kicker on archives and search. |
| `highwind_post_meta()` | `highwind_entry_bottom` | Overridden to print the category chips as bare links with no separator (Highwind's `the_category( ', ' )` leaves literal commas, and an empty separator makes core emit a `<ul>`). Chips are spaced 8px apart by a flex gap on `li.categories`, and do not underline on hover. Index and single posts share it. |
| `gusaiani_site_footer()` | `highwind_body_bottom` | The dark footer bar, outside `.outer-wrap` so it runs full bleed. |

Posts render as cards on the index, archives and search (two columns above
769px, one below); single posts get a 680px measure.

There is no build step for the fonts or the markup. The only build is Sass.

## Dependencies

* Sass

## Develop

To compile .sass files into .css, first install Sass: `yarn global add sass`

When developing, an easy if not very robust way to have the files compiled as you update the style files is by going to the theme root folder in your terminal and running `sass --watch sass/style.sass:./style.css --watch --poll`

Note that `style.css` is **not** tracked by git (see `.gitignore`), so a deploy
has to recompile it on the server.

## Install

First, install [Highwind](https://wordpress.org/themes/highwind/) in your themes admin page.

Publish a zip file of this theme by going to the theme root directory in your terminal and running `zip -r gusaiani.zip . -x *.git*`.

In your Wordpress admin › Appearance › Themes › Add New › Upload Theme, upload `gusaiani.zip` and activate `Gustavo Saiani Blog`.

## Deploy

The live copy is a git checkout at
`/var/www/blog.gustavosaiani.com/wp-content/themes/gusaiani-wp-theme` on
`poe.ma`. To ship a change:

```sh
cd /var/www/blog.gustavosaiani.com/wp-content/themes/gusaiani-wp-theme
sudo -u www-data git pull
sudo -u www-data sass --no-source-map sass/style.sass:style.css
```

Bump the version passed to `wp_enqueue_style( 'gusaiani-styles', ... )` in
`functions.php` when the stylesheet changes, so browsers pick it up.
