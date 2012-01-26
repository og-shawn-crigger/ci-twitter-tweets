<?php
/**
 *  tweet manager
 */
class Admin extends CI_Controller
{
	private $data;
	private $validation_rules = array(
		array(
			'field' => 'value',
			'label' => 'value',
			'rules' => 'trim|required'
		));
		
	function __construct()
	{
		parent::__construct();
		// load libraries
		// U need to autoload the database library in config/autoload.php
		$this->load->library(array('session','form_validation'));
		// Load helpers
		$this->load->helper(array('html','url'));
		// Load language file
		$this->load->language('twittertweets');
		//load model
		$this->load->model('admin/twittertweetsmodel');
		//run to install the tables
		$this->twittertweetsmodel->install();
		// Simple menu 
		$this->data->menu = array(
		anchor(base_url().'ci-twitter-tweets/admin', 'Admin' ,array('title' => 'Admin')),
		anchor(base_url().'ci-twitter-tweets/admin/add', 'Add tweets' ,array('title' => 'Add tweets')),
		anchor(base_url().'ci-twitter-tweets', 'Examples' ,array('title' => 'Examples'))
		);
		// Footer menu 
		$this->data->footer = array(
		anchor('http://code.websoftwar.es/codeigniter-privacy-settings-example', 'Download Bitbucket' ,array('title' => 'Download Bitbucket', 'target' => '_blank')),
		anchor('https://github.com/websoftwares/Codeigniter-privacy-settings-example', 'Download Github' ,array('title' => 'Download Github', 'target' => '_blank')),
		anchor('http://www.famfamfam.com/lab/icons/silk', 'FAMFAMFAM Silk icons' ,array('title' => 'FAMFAMFAM Silk icons', 'target' => '_blank')),
		anchor('https://twitter.com/#!/websoftwares', 'Follow me.' ,array('title' => 'Follow me', 'target' => '_blank'))
		);
	}
	
	/**
	 * Listing for the added twitter options
	 */	
	public function index()
	{
		//Get data
		$this->data->results = array(
		'screen_names' => $this->twittertweetsmodel->get_screen_names()->result(),
		'user_ids' => $this->twittertweetsmodel->get_user_ids()->result(),
		'hashtags' => $this->twittertweetsmodel->get_hashtags()->result(),
		'keywords' => $this->twittertweetsmodel->get_keywords()->result()
		 );
		 
		// Loop over results and process them
		foreach ($this->data->results as $key => $data)
		{
			for ($i=0; $i < count($data); $i++)
			{
				// create anchor  
				$this->data->results[$key][$i]->anchor = anchor(
				base_url().'ci-twitter-tweets/admin/publish/'.$this->data->results[$key][$i]->tbl.'/'.$this->data->results[$key][$i]->id,
				($this->data->results[$key][$i]->published == '1') ? 'Published' : '<del>Published</del>',
				array('title' => 'Publish/Unpublish'
				));
				
				// remove not needed Property Values
				unset($this->data->results[$key][$i]->id);
				unset($this->data->results[$key][$i]->published);
				unset($this->data->results[$key][$i]->tbl);
			}
		}

		$this->data->content = $this->load->view('admin/list',$this->data, TRUE);
		$this->load->view('admin', $this->data);
	}
	
	/**
	 * Add new keywords, hashtags or twitter user options values
	 */
	public function add()
	{
		//form atrributes
		$this->data->attributes = array('id'=>'add_form');
		// setup submit button
		$this->data->submitoptions = array('name' => 'submit', 'value' => 'Save', 'class' => 'submit_btn button green gradient');
        // setup form validation						
		$this->form_validation->set_rules($this->validation_rules);
		// validation has not been passed
		if ($this->form_validation->run() == FALSE)
		{
			// load the form
			$this->data->content = $this->load->view('admin/add',$this->data, TRUE);
		}
		else
		{
			// post variables
			$type	 = $this->input->post('type');
			$value	 = $this->input->post('value');			
			// start with blank array
			$form_data = array();
			// process post variables
			if($type == 'twitteruser')
			{   
				// see if value is int
				if($this->form_validation->integer($value))
				{
					$form_data['user_id'] = $value;
				}
				// not int
				else
				{   
					$form_data['screen_name'] = $value;
				}				
			}
			else
			{
				//data array
				$form_data = array($type => $value); 
			}
		//insert into database
		$lastid = $this->twittertweetsmodel->insert($type,$form_data);
		$this->twittertweetsmodel->insert('twitter',array($type => $lastid));
		$this->twittertweetsmodel->insert('published',array('id' => $lastid,'tbl' => $type,'published' => '1'));
		// redirect back to admin with success message
		$this->session->set_flashdata('success','Successfully saved.');
		redirect('/ci-twitter-tweets/admin');
		}
		$this->load->view('admin', $this->data);
	}
	/*
	 * change published/unpublished
	 */
	function publish()
	{
		$tbl = $this->uri->segment(4);
		$id  = $this->uri->segment(5);
		
		// check if the field is alpha and integer
		if($this->form_validation->alpha($tbl) && $this->form_validation->integer($id))
		{
			// get record
 			$result = $this->db->get_where('published', array('id' => $id,'tbl' => $tbl))->row();
			// publish/unpublish
			$result->published = ($result->published == '1') ? $result->published = 0 : $result->published = 1;
			// 
			$this->db->update('published',$result,array('id' => $id,'tbl' => $tbl));
			//set message and redirect
			$this->session->set_flashdata('success','Successfully changed.');
			redirect(base_url().'ci-twitter-tweets/admin');
		}
		else
		{
			//set message and redirect
			$this->session->set_flashdata('error','Error changing setting.');
			redirect(base_url().'ci-twitter-tweets/admin');
		}
	}
}