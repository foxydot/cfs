== Changelog ==

= 1.2.3 June 20 2015=
* Escaped all necessary inputs, URLs, etc.

= 1.2.2 Feb 24, 2014 =
* Remove widont filter if Posts in Columns option is active.

= 1.2.1 June 25, 2013 =
* Removed deprecated functions and backward compatibility for versions prior to 3.4.

= 1.2 May 3 2013 =
* Added forward compatibility with 3.6.
* Removed calender widget title fix, after core bug was fixed in 3.5.
* Fixed an undefined constant notice.
* Uses get_posts() in content-gallery.php instead of get_children().
* Added a full-width page template.
* Removed theme-bundled version of Masonry, in favor of core-bundled script.
* New screenshot for HiDPI support.
* Remove mention of WordPress.com in the theme description - it works great on self-hosted sites, too.
* Only prints site description markup if a site description exists.

= 1.1.1 Jan 2 2013 =
* Avoid PHP warning in Appearance -> Header by restoring the forever_fonts() function which was accidentally removed.

= 1.1 Dez 28 2012 =
* Namespace and enqueue stylesheet.
* jQuery does not need to be enqueued when listed as a dependency.
* Use a callback to modify wp_title output.
* Make image resizing work.
* Temporarily remove the full-width-template tag.
* Don't display a "No Posts Found" message if there are featured posts and/or recent posts before the main loop.
* Fix margin on entry title (rtl)
* Fix wrong content_width value
* Add a missing pipe before the edit link when it comes directly after category links, also add a following space properly with unicode code point
* Remove the empty heading tag from the calendar widget
* Narrowing down the scope of the style rule for threaded comments because the class, children is used in some widgets
* Break the colophon into a template part so that child themes can override the theme name and the author name with minimum code duplications 
* Check is_ssl() to define a protocol for Google fonts instead of a protocol relative url.
* Add generic action-hooks to header and sidebar
* Enable extensions to set a custom featured image size.
* Force the Guestbook template to use custom text for the comment reply form. Although this goes against are normal practices, we feel that adding some context to the call to action above the comment form makes sense for the guestbook template.
* Break the masthead and homepage greeting into template parts
* Allow menu args to be filtered via child theme.
* Fix notices with the gallery post-format
* Modularize theme options.
* Move definition of forever_recent_four_posts() and forever_latest_post_checker_flusher() into inc/themeoptions.php.
* Access value of forever_recent_four_posts() via custom filter instead of calling function directly.
* Create template part "recent-posts" for the theme option.
* Add a conditional filter that enables child themes to completely turn off theme options.
* Add a getter function for image sizes.
* Adjust the "pre_get_posts" filter to not exclude recent posts if options are disabled.
* Rework forever_featured_posts(). Allow it to recognize custom image sizes registered by child themes.
* Sidebars should be registered during widgets_init.
* Allow custom image sizes to be filterable via custom hook.
* Update core custom header feature
* Move custom header code to inc/custom-header like we do in _s.
* Register custom header using WordPress 3.4 functionality.
* Add support for flexible height images.
* Center small header images.
* Use a thinner font-weight in the admin preview.
* Use protocol-relative url when registering the Raleway font. This helps cut down on a few insecure content warnings in Chrome.
* Updating to use the 3.4 method for registering default background colors
* Make sure attribute escaping occurs after printing.
* Set posts_per_page from -1 to a finite number in functions.php.
* All themes: fix overly general .attachment img selectors.
* Also remove svn:executable bit from a few files
* The comment reply script should be enqueued at wp_enqueue_scripts along with other scripts
* Add styling for HTML5 email inputs
* Make sure an image attachement to be scaled propotionally on image.php
* Fix debug notice from missing object in content-image.php.
* Post Flair and query_posts() fixes.
* Introduce forever_recent_four_posts() function to store transient data.
* Introduce forever_home_posts() to filter the main query.
* Query for all sticky posts in featured slider
* Use get_the_ID() instead for $post->ID
* Enable print styles
* Remove stray '>'

= 1.0 Mar 28 2012 =
* Initial release