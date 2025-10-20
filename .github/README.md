# Undertale/Deltarune Wiki skin

This skin is intended for use on the [Undertale Wiki](https://undertale.wiki/)
and [Deltarune Wiki](https://deltarune.wiki/). It is a fork of
[Vector 2022](https://www.mediawiki.org/wiki/Skin:Vector) which adds several
features to make it feel closer to Fandom's skin, such as:

- Top navigation bar
- Adjusted edit button styles
- Floating navigation bar that is always available
- Improved mobile responsiveness
- Top-of-page categories
- Section edit icons
- Floating site notice
- Rubik font
- etc.

## Installation

This skin should be installed under the `skins/UTVector` directory in your
MediaWiki installation, and then loaded using `wfLoadSkin( 'UTVector' );`. On
Undertale/Deltarune Wiki, we use the following Vector configuration to make our
skin work for us:

```php
$wgVectorNightMode = [
    'logged_in' => true,
    'logged_out' => true,
    'beta' => false,
];
$wgVectorLanguageInMainPageHeader = [
    'logged_in' => true,
    'logged_out' => true,
];
$wgVectorResponsive = true;
$wgVectorUseIconWatch = false;
$wgVectorFontSizeConfigurableOptions = [
    'exclude' => [
        'mainpage' => false,
        'pagetitles' => [],
        'namespaces' => [],
        'querystring' => [],
    ],
    'include' => [],
];
$wgDefaultUserOptions['vector-appearance-pinned'] = 0;
$wgDefaultUserOptions['vector-toc-pinned'] = 0;
$wgDefaultUserOptions['vector-limited-width'] = 1;
$wgDefaultUserOptions['vector-theme'] = 'night';
$wgDefaultUserOptions['vector-font-size'] = 1;
$wgDefaultUserOptions['vector-main-menu-pinned'] = 0;
```

This repository uses `REL_XX_utdr` branches for branches relating to our skin,
where `XX` is the MediaWiki version number. It is not safe to use `git pull` to
update this skin. You should always check out the latest version instead of
pulling.

## Support

Given that our development team isn't huge, we make no guarantees that this skin
will work for you. However, if you do run into issues installing or using the
skin, you may open an issue on this repository, and we'll see if we can assist
you.
