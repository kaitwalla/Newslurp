# Newslurp

A utility that takes newsletters from your GMail account and sends them to the RSS reader of your choice

## DESCRIPTION

This small program will go to your GMail account, grab all email messages under the label "Newsletters" and output an RSS feed for you to use.

## SUPPORT

This a small, personal side project. I'm happy to let others use it if they find it helpful, but not super interested in tracking down edge cases. So — email me if you have issues, but no guarantees I'm going to devote hours to your problem.

## REQUIREMENTS

• A GMail account 
• >= PHP 7.0  
• Composer  
• Mailparse PHP extension (not found on many shared servers, sorry)  
• Top-level domain or subdomain (not designed for subdirectory installs) for installation  
• MySQL or MariaDB  

## INSTALLATION
• Rename env.sample.php to env.php and set the appropriate variables  
• Install dependencies using Composer  
• Use the SQL file in install.sql to create your database  
• Set up your [Google API OAuth 2 Authenticaton](https://developers.google.com/identity/protocols/OAuth2WebServer). Download the client secret JSON file, rename it "client_secret.json" and put it in the project root   
• Navigate to your URL and authenticate using your credentials  
• Set up a filter in your Gmail account to label the emails you want to catch as "Newsletters." You can archive them, but do not delete them (the program will trash them after procesing)  
• Visit /update once to get it started, then set up a cron to hit that URL/page however frequently you'd like 

## USAGE
• Visit /list to view all stories  
• Use /rss to subscribe in your favorite RSS reader. By default the last week of stories is in the RSS feed
