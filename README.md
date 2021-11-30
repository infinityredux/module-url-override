# Url Override
*infinityredux / module-url-override*

## Purpose
This module solves several related (potential) problems with Magento:
- First, it removes the potential for a URL to be generated with an ending of 
 `.html.html` or in patterns like `category.html/product`.
- In the event that URLs somehow wind up with incorrect values, this provides a
  command line function to completely regenerate the URLs.

Additionally, it enhances the existing URL handing with additional features: 
- Provides a mechanism that allows valid URLs to co-exist both with and without 
  a configurable suffix.


**TODO:** Do we need an installation section?


## Configuration
To enable the full functionality of this module, and before activating it in the 
store configuration, there are some additional steps required for this to work 
correctly. If this configuration is not done, some features will deliberately 
abort processing to avoid causing errors (such as potential for duplicate URL 
rewrites, preventing saving a product.)

Initially, navigate to the `Stores > Configuration > Catalog > Catalog > Search 
Engine Optimization` section, and remove the contents from both `Product URL 
Suffix` and `Category URL Suffix` (you will likely need to uncheck `Use system 
value` for each before editing them.) This step is necessary to prevent the 
Magento bug that causes excess `.html` in the URLs, but the suffix can be 
restored in module settings. 


**TODO:** Are there any other configuration steps required?


## Regenerating URLs 
**TODO:** Add instructions for regenerating URLs once we have the command line 
functions.


## Magento Compatibility
This code has been tested on the `2.4.3-p1` release of Magento, but should be 
compatible with future versions, as long as there are no significant changes to 
the `url_rewrites` table. 


**TODO:** Does this depend on other URL rewrite functionality or code?
