title: Theme colors
summary: Information on configuration around the theme color picker

# Theme color picker

The theme color picker is a way of providing CMS users to adjust the colors of different areas of their site without requiring developer intervention. For a more CMS admin friendly approach, ensure the ColorPalette module is installed:

```bash
$ composer require heyday/silverstripe-colorpalette
```

## Enabling

By default the theme color picker is disabled, to enable this you can adjust your YAML configuration. E.g. in `_config/config.yml`:

```yml
SilverStripe\SiteConfig\SiteConfig:
  enable_theme_color_picker: true
```

## Adjusting/adding colors

The theme colors are all configurable, so via YAML configuration you can adjust existing colors or add new ones to the theme color picker. see CWPSiteConfigExtension#theme_colors for a list of the default colors.

```yml
SilverStripe\SiteConfig\SiteConfig:
  enable_theme_color_picker: true
  theme_colors:
    # Edit existing pink color
    pink:
      Color: '#C12099'
    # Add new brown color
    brown:
      Title: 'Brown'
      CSSClass: 'brown'
      Color: '#594116'
```

Now you can add the matching color to your scss. Assuming your project is using a custom theme which imports watea's `main.scss` file, create a `$custom-theme-colors` as follows:

```scss
// themes/customtheme/scss/main.scss

// Ensure this variable is set before importing watea scss
$custom-theme-colors: (
  'pink': #C12099, // Adjusting existing pink color
  'brown': #594116 // Adding new brown color
);

@import '../../../watea/src/scss/main';
```
