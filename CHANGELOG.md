# CHANGELOG

# 2.0.0

## REMOVED

- Google API integration

## ADDED

- Ingest route
- Typed arrays, better typing throughout

## MODIFIED

- Removed janky custom autoloader for composer standard
- Updated all dependencies/code to work on PHP 8.2

# 1.1.6

## MODIFIED

- White background for stories
- Date now an actual date, centered
- Back button on story (for list)

# 1.1.5

## MODIFIED

- pubDate now capitalized properly

# 1.1.4

## MODIFIED

- Fixed problem where only filter was being used

# 1.1.3

## MODIFIED

- Front-end styling

# 1.1.2

## ADDED

- Author in RSS

# 1.1.1

## ADDED

- Twig filter for timestamp
- Date, GUID to RSS feed

## MODIFIED

- RSS description now trims whitespace

## REMOVED

- Trigger.php (no longer needed after we solved the session issue)

# 1.1.0

## MODIFIED

- Removed GMail's dependency on session for values
- Moved trigger outside the public, will not rely on HTML connection
- User a controller, not a model
- User now has a couple of functions for loading auth values for gmail

# 1.0.3

## ADDED

- Trigger.php (because Flight is not playing well with curl or wget)

## MODIFIED

- Story list pages should work now (instead of incrementing a single story)

## MODIFIED

- /uUpdate now redirects to the list after completion

# 1.0.2

## MODIFIED

- Moved htaccess to correct folder

# 1.0.1

## ADDED

- htacces file

# 1.0.0

## ADDED

- Everything to get it to a basic level
