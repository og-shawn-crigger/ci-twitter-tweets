<?php
$route['ci-twitter-tweets'] = 'twittertweets';
$route['ci-twitter-tweets/hashtag'] = 'twittertweets/hashtags';
$route['ci-twitter-tweets/admin'] = 'twittertweets/admin/admin/index';
$route['ci-twitter-tweets/admin/add'] = 'twittertweets/admin/add';
$route['ci-twitter-tweets/admin/publish/(:any)'] = 'twittertweets/admin/publish/$1/$2';