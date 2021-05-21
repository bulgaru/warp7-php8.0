# Changelog

## 7.3.37

### Added

- Added cookies field to commentform (WP)

### Changed

- Updated pagination compatibility (J)

### Fixed

- Fixed use ssl in article social buttons when forced in configuration (J)
- Fixed frontend editing with compression enabled (J)

## 7.3.36

### Fixed

- Fixed head.php (WP)

## 7.3.35

### Fixed

- Fixed html5 meta charset with cache enabled (J)
- Fixed enqueue scripts before they were registered (WP)
- Fixed missing return value in article register link (J)

## 7.3.34

### Fixed

- Fixed do not minify already compressed .js files
- Fixed com_search compatibility (J 3.7.3)

## 7.3.33

### Added

- Added Woocommerce 3.x gallery support

## 7.3.32

### Fixed

- Fixed thumbnail view for Woocommerce 2.7
- Fixed encoding html in menu title (WP)
- Fixed enqueue scripts before widgets loaded (WP)

## 7.3.31

### Changed

- Updated search results truncation (WP)

### Fixed

- Fixed language directory name 'ro-RO' (J)
- Fixed woocommerce style (WP)
- Fixed font selection in customizer

## 7.3.30

### Fixed

- Fixed PHP 5.3 compatibility (WP)

## 7.3.29

### Changed

- Updated UIkit to 2.27.2

### Fixed

- Fixed color picker in customizer
- Fixed font import in customizer + copy style
- Fixed widget assignment on woocommerce categories/tags (WP)
- Fixed breadcrumbs + sidebar widget (WP)

## 7.3.28

### Added

- Added filter for warp ajax search (WP)

### Changed

- Updated language files (J)

### Fixed

- Fixed frontpage posts pagination (WP)

## 7.3.27

### Changed

- Updated UIkit to 2.26.4
- Removed CssRtlFilter direction property

### Fixed

- Fixed redirect url after login on read more (J)
- Fixed duplicate items in breadcrumbs module (J)

## 7.3.26

### Added

- Added function getPageInfo() to SystemHelper

### Changed

- Updated language files

### Fixed

- Fixed widget assignment with WPML (WP)

## 7.3.25

### Changed

- Updated UIkit to 2.26.3

### Fixed

- Fixed font selection in customizer (IE)

## 7.3.24

### Changed

- Updated language files

### Fixed

- Fixed use of deprecated function add_object_page() (WP)

## 7.3.23

### Changed

- Updated UIkit to 2.26.2

### Fixed

- Fixed font selection in customizer
- Fixed menu settings backward compatibility (J 2.5)

## 7.3.22

### Changed

- Updated UIkit to 2.26.0

### Fixed

- Fixed sticky navbar not changing background-color (Chrome)

## 7.3.21

### Added

- Added Support for widgets count > 6 in a position

### Changed

- Updated Wordpress 4.5 compatibility
- Updated post layouts, render post image with srcset (WP)

### Fixed

- Fixed center login-form in offline page (J)
- Fixed font selection in customizer

## 7.3.20

### Added

- Added new font selection in customizer

### Changed

- Updated UIkit to 2.25.0
- Updated menu settings in template options (J 3.5)

### Fixed

- Fixed headings text-rendering attribute in bootstrap-fix.less (J)
- Fixed widget assignment on search results (WP)

## 7.3.19

### Added

- Added support for additional article meta information (J)

### Fixed

- Fixed search widget
- Fixed number of posts on frontpage setting (WP)
- Fixed article multi column in blog + featured view (J)

## 7.3.18

### Added

- Added class for articles in columns

### Fixed

- Fixed article icon links spacing
- Fixed tag_cloud widget (WP)
- Fixed widget nav settings
- Fixed detection of current menu item (J)

## 7.3.17

### Fixed

- Fixed scrollspy-nav issue

## 7.3.16

### Changed

- Updated UIkit to 2.24.3

### Fixed

- Fixed namespacing issue in JsCompressorFilter
- Fixed unstyled WooCommerce widgets on not WooCommerce pages
- Fixed scrollspy apply detection

## 7.3.15

### Added

- Added preventflip:y for navbar dropdowns
- Added automatically apply uk-scrollspy-nav on menus when needed
- Added theme documentation links to admin section

### Changed

- Updated UIkit to 2.24.2
- Removed unnecessary div from article.php (J)

### Fixed

- Fixed youtube iframes when search module is published (J)
- Fixed frontend editing layout (J)

## 7.3.14

### Changed

- Moved template cache to /media (J)
- Removed article meta information options (J)

## 7.3.13

### Added

- Added article meta information options (J)

### Changed

- Updated UIkit to 2.23.0
- Updated language files

## 7.3.12

### Added

- Added support for svgs as datauri

## 7.3.11

### Fixed

- Fixed missing bootstrap js if bootstrap loading is enabled
- Fixed icon selection in module settings

## 7.3.10

### Added

- Keep custom link classes (WP)

### Changed

- Updated UIkit to 2.22.0

### Fixed

- Fixed Wordpress 4.3 compatibility

## 7.3.9

### Changed

- Updated UIkit sticky.js component for the latest sticky navigation features

## 7.3.8

### Added

- Added Ukrainian translation file (J)

### Fixed

- Fixed keep current selected layout in template options (J)
- Fixed save failed on compile LESS
- Fixed archive override for custom post_type (WP)
- Fixed multisite check (W)
- Fixed option-set return value (W)

## 7.3.7

### Fixed

- Fixed missing closing <a> tag in article override (J)
- Fixed saving template settings failed (J 3.4.2)
- Fixed 'max_input_vars' error on save/close template settings (J)
- Fixed article links override (J)
- Fixed empty config saving

## 7.3.6

### Changed

- Updated UIkit to 2.21.0

### Fixed

- Fixed returnURL of hidden articles after user login
- Fixed MooTools hide issue

## 7.3.5

### Added

- Added post navigation (WP)

### Changed

- Updated UIkit to 2.20.3
- Removed Mootools in layout overrides (J)

### Fixed

- Fixed template hierarchy (WP)
- Fixed article navigation layout (J)
- Fixed menu rendering of separators in offcanvas menu
- Added grid layout last doubled

## 7.3.4

### Added

- Added option for WooCommerce products per page (WP)
- Added option for number of posts on frontpage (WP)

### Fixed

- Fixed render menu position only if menu assigned (WP)
- Fixed validation on front-end editing

## 7.3.3

### Added

- Added ARIA support for Navbar
- Added option to disable page title (WP)
- Added icon replacement for RTL mode

### Changed

- Updated UIkit to 2.17.0
- Updated language files (J)

### Fixed

- Fixed developer mode, trigger resize event after redraw
- Fixed .uk-link-reset in bootstrap-fix.less (J)
- Fixed pagination (J34)

## 7.3.2

### Added

- Added child theme support (WP)
- Added Widgetkit 2 support
- Added Less 1.5.1 for developer mode, to support compiling on IOS 8

### Changed

- Updated UIkit to 2.15.0

## 7.3.1

### Fixed

- Fixed input fields height in Woocommerce (WP)

## 7.3.0

### Changed

- Updated UIkit to 2.10.0
- Updated Bootstrap fix (J3)
- Updated Bootstrap layer (J3)
- Updated LESS compiler to 1.7.5
- Added Woocommerce support (WP)
- Added modules front-end edit (J)
- Added system output on offline page (J)
- Replaced all input[type=submit] with button[type="submit"]
- Changed title-rendering to wp_title with custom filter (WP)
- Removed search close button and loading spinner

### Fixed

- Fixed comments reply button (WP)
- Fixed widget assignments for custom post types (WP)

## 7.2.8

### Fixed

- Fixed pagination page-button issue

## 7.2.7

### Changed

- Updated UIkit to 2.8.0

## 7.2.6

### Added

- Added transform to CSS RTL conversion

### Changed

- Updated category override (J)

### Fixed

- Fixed cancel comment reply link (WP)
- Fixed dropdown navbar column width calculation


## 7.2.5

### Changed

- Updated facebook like button
- Added two factor authentication to offline site (J3)

### Fixed

- Fixed use default style.less from configured less folder
- Fixed search in widgets view (WP)
- Fixed more results in search
- Fixed unclosed element in recent comments widget (WP)
- Fixed spacing issue in author view (WP)

## 7.2.4

### Changed

- Updated UIkit to 2.6.0
- Updated LESS.js to 1.7.0

### Fixed

- Fixed multiple images as data uri
- Fixed title links in SmartSearch results (J32)
- Fixed Post Page-Links (WP)
- Removed pubdate attribute from time element

## 7.2.3

### Fixed

- Fixed default Gravatar in recent comments widget (WP)

## 7.2.2

### Changed

- Updated Customizer
- Updated UIkit to 2.3.1
- Changed default Gravatar (WP)

### Fixed

- Fixed Icon picker (J3)
- Fixed off-canvas for Windows phones
- Fixed Pagination (J3)

## 7.2.1

### Fixed

- Fixed linked titles within tags override (J32)
- Dropdown touch optimized
- Fixed Smart Search override (J25)

## 7.2.0

### Added

- Added comments in CSS files

### Changed

- Refactored style file structure
- Updated UIkit to 2.0.0
- Updated overrides according to Joomla 3.2.0 (J32)
- Added icons to pagination (J)
- Removed /custom folder

### Fixed

- Fixed leading article (J)

## 7.1.12

### Changed

- Updated less compiler to version 1.5.1
- Updated com_contact override (J25)

### Fixed

- Fixed calling get_header + get_footer actions (WP)
- Fixed template settings (J32)

## 7.1.11

### Changed

- Updated UIkit to 1.2.0
- Updated user overrides (J25)
- Updated markup for recent comments widget (WP)

### Fixed

- Fixed avatar class handling (WP)

## 7.1.10

### Fixed

- Fixed header data check on error pages (J)
- Fixed error page (WP)

## 7.1.9

### Fixed

- Fixed article meta display option
- Fixed default widget display option (J25)

## 7.1.8

### Changed

- Updated search results layout (J3)

### Added

- Fixed com_content archive overwrite (J3)
- Fixed subtitles in menu (WP)
- Fixed uk-nav-sub class in navbar renderer

## 7.1.7

### Changed

- Updated search results layout (J3)
- Updated article layout (J)
- Added post message for iframe on customizer update

### Fixed

- Fixed linked article title (J)
- Fixed widget rendering twice (J)
- Fixed customizer toogle advanced mode in safari (J)
- Fixed customizer less tree error (J)

## 7.1.6

### Added

- Added Bootstrap layer (J3)
- Added subtitle support for menu items

### Changed

- Updated UIkit to 1.1.0

### Fixed

- Fixed readmore markup (WP)
- Fixed author markup (WP)
- Fixed pagination override (J)

## 7.1.5

### Changed

- Updated UIkit to 1.0.2

### Fixed

- Menus settings will show items for multiple languages
- Fixed duplicate attribute class in search widget (WP)
- Fixed blog layout (J25)
- Truncate title in widget settings

## 7.1.4

### Changed

- Updated UIkit to 1.0.1

### Fixed

- Fixed customizer support (Safari 6+)
- Fixed compression settings
- Fixed search in offcanvas
- Fixed select elements on theme settings page (J 3.1.4)

## 7.1.3

### Changed

- Offcanvas menu refactored

### Fixed

- Settings show all modules and menus now, independent of access settings (J)
- Fixed multicolumn layouts in some views (J)

## 7.1.2

### Fixed

- Fixed grid for multicolumn layout (WP)

## 7.1.1

### Fixed

- Fixed RTL for PHP 5.3
- Fixed check for customizer browser compatibility

## 7.1.0

### Added

- Added style assignment for a page
- Added RTL asset filter in PHP
- Grid supports 6 columns now
- Assign icons/images to menu subitems

### Changed

- Refactored responsive module/widget behavior
- Moved styles to separate directory
- Refactored UIkit grid
- Updated UIkit
- Optimized customizer

### Fixed

- Fixed post size when compiling less
- Fixed js error on pages with comments (WP)
- Fixed comments layout (WP)

## 7.0.3

### Fixed

- Fixed textseparators in menu navbar
- Fixed multisite configuration (WP)

## 7.0.2

### Added

- Added body_class() filter call (WP)
- Added PHP 5.3+ compatibility check (J)

## 7.0.1

### Changed

- Widgets view does no longer show type of widget (WP)
- Changed name of "display" field to "assignment"
- Changed name of "devicedisplay" field to "display"

### Fixed

- Fixed Menu header items in nav menus
- Fixed comments error (WP)
- Fixed external links in customizer in Firefox
- Fixed customizer display in IE
- Fixed featured article override display error (J)
- Fixed tag override display error (J)

## 7.0.0

### Added

- Initial Release

* -> Security Fix
# -> Bug Fix
$ -> Language fix or change
+ -> Addition
^ -> Change
- -> Removed
! -> Note
