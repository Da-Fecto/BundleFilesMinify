#BundleFilesMinify

**Note:** This module is only written for the forum post about [pluimveeweb](http://processwire.com/talk/topic/5432-launch-wwwpluimveewebnl/?hl=pluimveeweb). It's should be functional as it is, but it's not in production right now. I do use a similar technique in Pluimveeweb.nl, but it's not containing the module part.

Bundle files minify is a module, class & template file to minify and concatenate all scripts and styles in a FilenameArray and server one file back to the browser.

There are several great minifiers already for ProcessWire, but my needs for Pluimveeweb.nl were a little different. I can't know what blocks of markup get rendered and thus, what CSS or JS to use.

This module allows me to use the FilenameArray, and be sure only the needed scripts and styles are pushed to the client.

##How to install

1. Copy all the files from this module into: /site/modules/BundleFilesMinify/
2. Login to your admin and go to Modules > Check for new modules. Click install for BundleFilesMinify.
3. Move the **bundled_files.php** template file to the /site/templates/ directory if not already done by the installer.

##How to use

We have to build a URL that is needed for minify template. The **Bundle Files Minify** module will handle this for us.

```
/**
 * CSS
 *
 * $config->styles = the FilenameArray containing the styles
 *
 */
 
$url = $modules->get("BundleFilesMinify")->load($config->styles)->url;
echo  "<link rel='stylesheet' href='$url'>";


/**
 * Scripts
 *
 * $config->scripts = the FilenameArray containing the scripts
 *
 */

// Javascript 
echo  "<script src='" . $modules->get("BundleFilesMinify")->load($config->scripts)->url . "'></scripts>";

```

