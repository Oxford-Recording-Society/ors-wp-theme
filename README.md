# ORS WordPress Theme
(based on Twenty Twenty-One)

## Installation / Contributing
Clone this repository into your wp-content/themes directory, then activate it in the WP admin interface.

**Required plugins/repos:**
- WP User Frontend (because we rewrite the 'Edit post' link to link to WPUF's page)
- ors-wp-static (some segments assume this repo is in the web root)

**Supported plugins:**
(i.e. contains custom styling for)
- WP Ultimate Member
- display-posts (if you pass the right classnames into the shortcode)

**Note on CSS/Sass:** Twenty Twenty-One's CSS is actually generated from a bunch of Sass files in assets/sass, whereas we've modified style.css directly. It might be worth migrating the modifications over there in the future (by getting the diff of our changes and finding which scss file they're in), depending on how confident the rest of the team is with installing/running sass.