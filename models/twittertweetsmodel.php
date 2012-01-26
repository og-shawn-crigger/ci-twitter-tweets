<?php
/**
 *  Handles the database functions
 */
class Twittertweetsmodel extends CI_Model {
	
	public function get_screen_names()
	{
		return $this->db->select('twitteruser.id, twitteruser.screen_name AS value, published.published,published.tbl')
				 	->from('twitter')
				 	->join('twitteruser','twitter.twitteruser = twitteruser.id')
				 	->join('published','published.id = twitteruser.id')
				 	->where("twitteruser.screen_name IS NOT NULL AND published.tbl = 'twitteruser'")
				 	->get();
	}

	public function get_user_ids()
	{
		return $this->db->select('twitteruser.id, twitteruser.user_id AS value, published.published,published.tbl')
				 	->from('twitter')
				 	->join('twitteruser','twitter.twitteruser = twitteruser.id')
				 	->join('published','published.id = twitteruser.id')
				 	->where("twitteruser.user_id IS NOT NULL AND published.tbl = 'twitteruser'")
				 	->get();
	}
	public function get_hashtags()
	{
		return $this->db->select('hashtag.id, hashtag.hashtag AS value, published.published,published.tbl')
				 	->from('twitter')
				 	->join('hashtag','twitter.hashtag = hashtag.id')
				 	->join('published','published.id = hashtag.id')
					->where('published.tbl','hashtag')
				 	->get();
	}
	public function get_keywords()
	{
		return $this->db->select('keyword.id, keyword.keyword AS value, published.published,published.tbl')
				 	->from('twitter')
				 	->join('keyword','twitter.keyword = keyword.id')
				 	->join('published','published.id = keyword.id')
					->where('published.tbl','keyword')
				 	->get();
	}
	/**
	 * install the tables in the database
	 */
	public function install()
	{		
		// start with blank array
		$queries =  array();
		
		// build queries
		$queries[] = "CREATE TABLE IF NOT EXISTS `hashtag` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `hashtag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

		$queries[] = "CREATE TABLE IF NOT EXISTS `keyword` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		
		$queries[] = "CREATE TABLE IF NOT EXISTS `twitter` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `keyword` int(11) DEFAULT NULL,
		  `hashtag` int(11) DEFAULT NULL,
		  `twitteruser` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;	";

		$queries[] = "CREATE TABLE IF NOT EXISTS `twitteruser` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `screen_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `user_id` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ; ";

		$queries [] = "CREATE TABLE IF NOT EXISTS `published` (
  		`id` int(11) DEFAULT NULL,
 		`tbl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  		`published` tinyint(1) DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		
		// run queries
		foreach($queries as $query)
		{
			$this->db->query($query);
		}
	}
}