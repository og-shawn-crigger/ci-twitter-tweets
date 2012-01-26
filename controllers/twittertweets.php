<?php
/**
 * Display tweets
 * 
 */
class Twittertweets extends CI_Controller
{
	private $feed = 'https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&trim_user=1';
	private $data;
	
	function __construct()
	{
		parent::__construct();
		// load libraries
		// u need to autoload the database library in config/autoload.php
		$this->load->library(array('session','form_validation','driver'));
		// Load helpers
		$this->load->helper(array('html','url','form'));
		// Load language file
		$this->load->language('twittertweets');
		// load model
		$this->load->model('twittertweetsmodel');
		// run to install the tables
		$this->twittertweetsmodel->install();
		// load cache driver
        $this->load->driver('cache', array('adapter' => 'file'));
		// Simple menu 
		$this->data->menu = array(
		anchor(base_url().'ci-twitter-tweets', 'Examples' ,array('title' => 'Examples')),
		anchor(base_url().'ci-twitter-tweets/hashtag', 'Example #hashtag' ,array('title' => 'Example #hashtag')),
		anchor(base_url().'ci-twitter-tweets/admin', 'Admin' ,array('title' => 'Admin'))
		);
		// Footer menu 
		$this->data->footer = array(
		anchor('http://code.websoftwar.es/ci-twitter-tweets', 'Download Bitbucket' ,array('title' => 'Download Bitbucket', 'target' => '_blank')),
		anchor('https://github.com/websoftwares/ci-twitter-tweet', 'Download Github' ,array('title' => 'Download Github', 'target' => '_blank')),
		anchor('http://www.famfamfam.com/lab/icons/silk', 'FAMFAMFAM Silk icons' ,array('title' => 'FAMFAMFAM Silk icons', 'target' => '_blank')),
		anchor('https://twitter.com/#!/websoftwares', 'Follow me.' ,array('title' => 'Follow me', 'target' => '_blank'))
		);
	}
	public function index()
	{
		//widget title
		$this->data->title = 'screen names';
		//twitter patterns extraction
		$patterns = array(
			// Detect URL's
			'((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)' => '<a href="$0" target="_blank">$0</a>',
			// Detect Email
			'|[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,6}|i' => '<a href="mailto:$0">$0</a>',
			// Detect Twitter @usernames
			'|@([a-z0-9-_]+)|i' => '<a href="http://twitter.com/$1" target="_blank">$0</a>',
			// Detect Twitter #tags
			'|#([a-z0-9-_]+)|i' => '<a href="http://twitter.com/search?q=%23$1" target="_blank">$0</a>'
		);
				
		// get tweets from cache
        $this->data->tweets = $this->cache->get('screen_name_tweets');
		if ( ! $this->data->tweets)
        {
        	// get twitter screen names to fetch and process rows
        	foreach ($this->twittertweetsmodel->get_screen_names()->result() as $key => $row)
			{
			    //find out if the screen name is published
				if($row->published == '1')
				{
					$screen_names[] = $row->value;
				}
			
			}
		// modified http://arguments.callee.info/2010/02/21/multiple-curl-requests-with-php/	
		$ch = array();
   		$mh = curl_multi_init();
		$timeout = 0; 
    	
    	// build the requests
    	for ($i = 0; $i < count($screen_names); $i++)
    	{
	        $screen_name = $screen_names[$i];
	        $ch[$i] = curl_init();
	        curl_setopt($ch[$i], CURLOPT_URL, $this->feed.'&screen_name='.$screen_name);
	        curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch[$i], CURLOPT_CONNECTTIMEOUT, $timeout);
	        curl_multi_add_handle($mh, $ch[$i]);
    	}
		// execute the requests simultaneously
    	$running = 0;
    	do {
        	curl_multi_exec($mh, $running);
    	   }
    	   while ($running > 0);
		
		// prepare the results array
    	for ($i = 0; $i < count($screen_names); $i++)
    	{
        	$results = reset(json_decode(curl_multi_getcontent($ch[$i]), true));
			
			$this->data->tweets[] = $results;
			$this->data->tweets[$i]['text'] = str_replace($screen_names[$i] . ': ', '', $this->data->tweets[$i]['text']);
			$this->data->tweets[$i]['text'] = preg_replace(array_keys($patterns), $patterns, $this->data->tweets[$i]['text']);
			$this->data->tweets[$i]['screen_name'] = $screen_names[$i];
			//create ts property http://stackoverflow.com/a/5414470
			$this->data->tweets[$i]['ts'] = strtotime($this->data->tweets[$i]['created_at']);
		}
		//sort by date
		usort($this->data->tweets, function($a, $b) { return $b['ts'] - $a['ts']; });
		// save to cache
		$this->cache->save('screen_name_tweets',$this->data->tweets, 300);
        }
		//widget view
		$this->data->widget = $this->load->view('twitter_latest_tweets',$this->data, TRUE);
		// content view
		$this->data->content = $this->load->view('content',$this->data, TRUE);
		// load view
		$this->load->view('index',$this->data);
	}

	public function hashtags()
	{
		// widget title
		$this->data->title = '#hashtags';

		//twitter patterns extraction
		$patterns = array(
			// Detect URL's
			'((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)' => '<a href="$0" target="_blank">$0</a>',
			// Detect Email
			'|[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,6}|i' => '<a href="mailto:$0">$0</a>',
			// Detect Twitter @usernames
			'|@([a-z0-9-_]+)|i' => '<a href="http://twitter.com/$1" target="_blank">$0</a>',
			// Detect Twitter #tags
			'|#([a-z0-9-_]+)|i' => '<a href="http://twitter.com/search?q=%23$1" target="_blank">$0</a>'
		);
				
		// get tweets from cache
        $this->data->tweets = $this->cache->get('hashtags_tweets');
		if ( ! $this->data->tweets)
        {
        	// get twitter screen names to fetch and process rows
        	foreach ($this->twittertweetsmodel->get_hashtags()->result() as $key => $row)
			{
			    //find out if the hashtags is published
				if($row->published == '1')
				{
					$hashtags[] = $row->value;
				}
			}
			$q = implode(' ',$hashtags);
			//setup feed
			$this->feed = 'http://search.twitter.com/search.json?result_type=recent&rpp=5&q='.urlencode($q);
			// using curl since my host has restrictions on some functions for remote urls
			$ch = curl_init(); 
			$timeout = 0; 
			curl_setopt ($ch, CURLOPT_URL, $this->feed);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch); 
			curl_close($ch);
			
			$this->data->tweets =  json_decode($file_contents);
			
			for ($i=0; $i < count($this->data->tweets->results); $i++)
			{
				$this->data->tweets->results[$i]->text = preg_replace(array_keys($patterns), $patterns, $this->data->tweets->results[$i]->text);
			}
		// save to cache
		$this->cache->save('hashtags_tweets',$this->data->tweets, 300);
		}
		//widget view
		$this->data->widget = $this->load->view('twitter_latest_tweets_hashtag',$this->data, TRUE);
		// content view
		$this->data->content = $this->load->view('content',$this->data, TRUE);
		// load view
		$this->load->view('index',$this->data);
	}
}