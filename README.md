# 2014 top Austin restaurants guide

[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)

This repo includes code and content for Matthew Odam's [2014 Austin top restaurant guide](http://projects.statesman.com/features/top-restaurants/).

The guide is a [Silex](http://silex.sensiolabs.org/) app that is fed by a [single JSON file](data/data.json) that contains all restaurant info. The JSON file is built from a Google Spreadhsheet using [a Python script](data/scraper.py). The CSS framework is Bootstrap with some style overrides.

## Deploying

Deploying the app requires setting up a few environment-specific settings and installing depencies that aren't present in the project repo:

1. Create an `.htaccess` file using [`.htaccess.sample`](.htaccess.sample) as an example. The RewriteBase on the server needs to point to the projet's `web/` directory.
2. Create a `web/env.json` file using [`web/env.json.sample`](web/env.json) as an example. Set the `debug` variable to `false` in production and `true` in dev, which will toggle Silex's debug mode and the inclusion of the LiveReload script. Set the `urlprefix` to the subdirectory the app is installed in or `/` if there is no subdirectory. For example, in the production deployment at `http://projects.statesman.com/features/top-restaurants/`, `urlprefix` is set to `/features/top-restaurants/`.
3. Install the PHP dependencies using [Composer](https://getcomposer.org/) with `composer install`.

## Updating

- **Restaurant content:** To update text content in the app, update the Google Spreadsheet and run the scraper in the `data` folder with `python scraper.py`. You'll need to create a `creds.cfg` file (see [`data/creds.cfg.sample`](data\creds.cfg.sample)) in `data/` with credentials for a Google account that has access to the spreadsheet.
- **Images**: can be updated by swawpping out files in the appropriate folders. `cover/` contains all home page images, which are 320px x 228px. `inside/` contains all images for the individual restaurant pages, which are 945px x 100%.
- **Restaurant page locator maps**: The locator maps are generated using [Google's Static Maps API](https://developers.google.com/maps/documentation/staticmaps/) based on the `data.json` file. Run `maps.py` in `data/` to download fresh maps.
- **Map markers**: There's a Photoshop template for map markers at [`assets/markers/marker.psd`](assets/markers/marker.psd). You'll need to download the [Tillium Web](http://www.google.com/fonts#UsePlace:use/Collection:Titillium+Web) font to update the numeric markers.
- **Anything not listed above.**: If you can't find it in one of the above, it's probably in the `.twig` templates in `web/views`

## Developing

JavaScript and CSS for distribution are built using [Grunt](http://gruntjs.com/), front-end packages are managed with [Bower](http://bower.io/) and PHP packages are managed with [Composer](https://getcomposer.org/) so you'll need all three to do any serious development on the project.

Once you have all three installed, be sure to run `npm install` in the project root to install the required Grunt modules. The `grunt watch` task will open a simple development server (with LiveReload) and transpile CSS, lint, JavaScript, etc. as changes are made.

## Copyright

Code and content are &copy; Statesman Media.
