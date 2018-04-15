# WP Primary Terms #
**Contributors:**      Paresh  
**Donate link:**       http://pareshradadiya.github.io/wp-primary-terms  
**Tags:**  
**Requires at least:** 4.4  
**Tested up to:**      4.8.1 
**Stable tag:**        1.0.0  
**License:**           GPLv2  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html  

## Description ##

Allow to designate a primary category for posts and custom post types

### Manual Installation ###

1. Upload the entire `/wp-primary-terms` directory to the `/wp-content/plugins/` directory.
2. Activate WP Primary Terms through the 'Plugins' menu in WordPress.
## Screenshots ##

### Set/Reset Primary Terms ###
![Settings - Rating Images](http://g.recordit.co/q2hp7IKebJ.gif)

### Settings ###
![Settings](https://cldup.com/MUx0BoYdW0.png)

## Helpful Filter ##
##### wppt_get_primary_taxonomies
This filter allow you to toggle primary term support for the post's taxonomies. Applied to the list of taxonomies returned by the `wppt_get_primary_taxonomies` function. This filter receive two parameters:
  * $primary_taxonomies - The primary taxonomies name list which are enabled from plugin settings
  * $post_type - The post type of the current post

## Changelog ##

### 1.0.0 ###
* First release
