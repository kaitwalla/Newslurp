# 1.1.1
## ADDED
- Twig filter for timestamp
- Date, GUID to RSS feed
## MODIFIED
- RSS description now trims whitespace
## REMOVED
- Trigger.php (no longer needed after we solved the session issue)

# 1.1.0
##MODIFIED
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