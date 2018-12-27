# Gustavo Saiani Wordpress Theme

This is the theme for [blog.gustavosaiani.com](http://blog.gustavosaiani.com)

It is a child theme of [Highwind](https://wordpress.org/themes/highwind/).

### Dependencies

* Sass

### Develop

To compile .sass files into .css, first install Sass: `yarn global add sass`

When developing, an easy if not very robust way to have the files compiled as you update the style files is by going to the theme root folder in your terminal and running `sass --watch sass/style.sass:./style.css --watch --poll`

### Install

First, install [Highwind](https://wordpress.org/themes/highwind/) in your themes admin page.

Publish a zip file of this theme by going to the theme root directory in your terminal and running `zip -r gusaiani.zip . -x *.git*`.

In your Wordpress admin › Appearance, upload `gusaiani.zip` and activate `Gustavo Saiani Blog`.


