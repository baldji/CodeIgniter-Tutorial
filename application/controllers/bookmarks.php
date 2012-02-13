<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * bookmarks
 * 
 * Description
 * 
 * @license		Copyright Mike Funk. All Rights Reserved.
 * @author		Mike Funk
 * @link		http://mikefunk.com
 * @email		mike@mikefunk.com
 * 
 * @file		bookmarks.php
 * @version		1.0
 * @date		02/08/2012
 * 
 * Copyright (c) 2012
 */

// --------------------------------------------------------------------------

/**
 * bookmarks class.
 * 
 * @extends CI_Controller
 */
class bookmarks extends CI_Controller
{
	// --------------------------------------------------------------------------
	
	/**
	 * _data
	 *
	 * holds all data for views
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_data;
	
	// --------------------------------------------------------------------------
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// for testing to work
		$fcpath = str_replace('application/third_party/CIUnit/', '', FCPATH);
		$apppath = str_replace($fcpath, '', APPPATH);
		
		// load resources
		require_once($fcpath.$apppath.'libraries/less_css/lessc.inc.php');
		$this->load->add_package_path($fcpath.$apppath.'third_party/carabiner');
		$this->load->library('carabiner');
		$this->config->load('carabiner', TRUE);
		
		// set style and script dirs
		$this->_data['style_dir'] = $fcpath . $this->config->item('style_dir', 'carabiner');
		$this->_data['script_dir'] = $fcpath . $this->config->item('script_dir', 'carabiner');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * list_bookmarks function.
	 * 
	 * @access public
	 * @return void
	 */
	public function list_bookmarks()
	{	
		$this->load->database();
		$this->load->model('bookmarks_model');
		
		// pagination
		$this->load->library('pagination');
		$this->config->load('pagination');
		$opts = $this->input->get();
		unset($opts['page']);
		$q = $this->bookmarks_model->bookmarks_table($opts);
		$config['base_url'] = 'list_bookmarks?';
		$config['total_rows'] = $this->data['total_rows'] = $q->num_rows();
		$this->pagination->initialize($config);
		
		// get bookmarks
		$opts = array_merge($this->input->get(), array('limit' => $this->config->item('per_page')));
		$this->_data['bookmarks'] = $this->bookmarks_model->bookmarks_table($opts);
		
		// load view
		$this->_data['header'] = $this->load->view('header_only_view', $this->_data, TRUE);
		$this->_data['content'] = $this->load->view('list_bookmarks_view', $this->_data, TRUE);
		$this->load->view('template_view', $this->_data);
	}
	
	// --------------------------------------------------------------------------
}
/* End of file bookmarks.php */
/* Location: ./bookymark/application/controllers/bookmarks.php */