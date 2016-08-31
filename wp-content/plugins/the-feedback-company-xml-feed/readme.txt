Plugin Name: Feedback Company XML feed
Depends: A Feedback Company account
Plugin URI: http://www.feedbackcompany.nl/
Description: Handige The Feedback Company XML integratie in WordPress
Version: 1.2.3
License: GPLv2


== Installation ==
1. Upload the `the-feedback-company-xml-feed` folder and all its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings
4. Enter te necessary fields
5. ...Go baby go...


== Changelog ==

= 1.2.3 =
10.08.2016 - Fixed a minor detail in cURL

= 1.2.2 =
04.07.2016 - Fixed a bug in the text truncate function

= 1.2.1 =
15.06.2016 - Fixed a php warning 

= 1.2 = 
15.05.2016 - Limit review size by user preference in review widget
05.04.2016 - Fetch feed with cURL
05.04.2016 - Option to randomize reviews in review widget
05.04.2016 - Based on &Basescore=5 or &Basescore=10 in XML-feed use 5 or 10 star calculation
05.04.2016 - Added multisite support through caching multiple XML-feeds based on blog ID

= 1.1 = 
27.11.2015 - Set a time-out on fetching the feed to prevent plugin from stalling if feed is down

= 1.0 = 
07.08.2015 - Launch