# Newslurp 2.0

A utility that takes newsletters from your GMail account and sends them to the RSS reader of your choice

## DESCRIPTION

This small program will go to your GMail account, grab all email messages under the label "Newsletters" and output an
RSS feed for you to use.

## SUPPORT

This a small, personal side project. I'm happy to let others use it if they find it helpful, but not super interested in
tracking down edge cases. So — email me if you have issues, but no guarantees I'm going to devote hours to your problem.

## REQUIREMENTS

- A GMail account
- PHP 8.3
- Composer
- Sqlite PDO driver + php extension
- Top-level domain or subdomain (not designed for subdirectory installs) for installation

## INSTALLATION

- Rename env.sample.php to env.php and set the appropriate variables
- Install dependencies using Composer
- Set up a filter in your Gmail account to label the emails you want to catch as "Newsletters." You can archive them,
  but do not delete them (the program will trash them after processing)
- Copy this Google Apps Script and change your newsletter label (if necessary) and URL where you installed this project
- Set up a trigger for the Apps Script on whatever schedule you want (I recommend at least daily)

## USAGE

- Visit /list to view all stories
- Use /rss to subscribe in your favorite RSS reader. By default the last week of stories is in the RSS feed
