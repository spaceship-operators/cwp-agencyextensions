title: Theme colors
summary: Information on configuration around the theme color picker

# Theme color picker

The theme color picker is a way of providing CMS users to adjust the colors of different areas of their site without requiring developer intervention.

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

TODO: Adding the new/different color to the scss in the theme
