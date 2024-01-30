<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rate extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("rate_model");
		// $this->cekLoginStatus("acs",true);
    }
	
	public function index()
	{
		$data['title'] = "DATA RATE";
		$data['layout'] = "rate/index";
			
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->rate_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("rate?");
		$config['total_rows'] = $total;
		$config['per_page'] = $limit;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers']  = TRUE;
		$config['page_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		$this->load->view('template',$data);
	}
	
	public function manage($id = "")
	{
		$data['title'] = "FORM RATE";
		$data['layout'] = "rate/manage";

		$data['data'] = new StdClass();
		$data['data']->id_rate = "";
		$data['data']->rate = "";
		$data['data']->dari = "";
		$data['data']->wilayah = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->rate_model->get_by("id_rate",$id,true);
			if(!empty($dt))
				$data['data'] = $dt;
		}
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			$error = array();
			$id = $post['id'];
			
			if(!empty($post['id_rate']))
				$data['id_rate'] = $post['id_rate'];
			else
				$error[] = "id tidak boleh kosong"; 
				
			if(!empty($post['rate']))
				$data['rate'] = $post['rate'];
			else
				$error[] = "rate tidak boleh kosong"; 
			
			if(!empty($post['dari']))
				$data['dari'] = $post['dari'];
			else
				$error[] = "dari tidak boleh kosong"; 
			
			if(!empty($post['wilayah']))
				$data['wilayah'] = $post['wilayah'];
			else
				$error[] = "wilayah tidak boleh kosong"; 
				
			if(empty($error))
			{
				if(empty($id))
				{
					$cekrate = $this->rate_model->get_by("id_rate",$post['id_rate']);
					if(!empty($cekrate))
						$error[] = "id sudah terdaftar"; 
					
					$cek = $this->rate_model->get_by("rate",$post['rate']);
					if(!empty($cek))
						$error[] = "rate sudah terdaftar"; 
				}
				else
				{
					$cek = $this->rate_model->cekName($id,$post['rate']);
					if(!empty($cek))
						$error[] = "rate sudah terdaftar";
				}	
			}
			
			if(empty($error))
			{
				$save = $this->rate_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("rate/manage/".$id);
				else
					redirect("rate");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("rate/manage/".$id);
			}
		}
		else
		  redirect("rate");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->rate_model->get_by("id_rate",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("rate");
			}
			else
			{
					$this->rate_model->remove($id);		
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("rate");
			}
		}
		else
			redirect("rate");
	}
	
	public function generate_code()
	{
		$prefix = "RTE";
		$code = "0001";
		
		$last = $this->rate_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_rate,3,4) +1;
			$code = str_pad($number, 4, "0", STR_PAD_LEFT);
		}
		return $prefix.$code;
	}
	
}
