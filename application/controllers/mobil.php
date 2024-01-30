<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class mobil extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("mobil_model");
		// $this->cekLoginStatus("acs",true);
    }
	
	public function index()
	{
		$data['title'] = "DATA MOBIL";
		$data['layout'] = "mobil/index";
			
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->mobil_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("mobil?");
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
		$data['title'] = "FORM mobil";
		$data['layout'] = "mobil/manage";

		$data['data'] = new StdClass();
		$data['data']->id_mobil = "";
		$data['data']->plat = "";
		$data['data']->jenis = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->mobil_model->get_by("id_mobil",$id,true);
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
			
			if(!empty($post['id_mobil']))
				$data['id_mobil'] = $post['id_mobil'];
			else
				$error[] = "id tidak boleh kosong"; 
				
			if(!empty($post['plat']))
				$data['plat'] = $post['plat'];
			else
				$error[] = "plat tidak boleh kosong"; 
			
			if(!empty($post['jenis']))
				$data['jenis'] = $post['jenis'];
			else
				$error[] = "jenis tidak boleh kosong"; 
				
			if(empty($error))
			{
				if(empty($id))
				{
					$cekmobil = $this->mobil_model->get_by("id_mobil",$post['id_mobil']);
					if(!empty($cekmobil))
						$error[] = "id sudah terdaftar"; 
					
					$cek = $this->mobil_model->get_by("plat",$post['plat']);
					if(!empty($cek))
						$error[] = "plat sudah terdaftar"; 
				}
				else
				{
					$cek = $this->mobil_model->cekName($id,$post['plat']);
					if(!empty($cek))
						$error[] = "plat sudah terdaftar";
				}	
			}
			
			if(empty($error))
			{
				$save = $this->mobil_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("mobil/manage/".$id);
				else
					redirect("mobil");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("mobil/manage/".$id);
			}
		}
		else
		  redirect("mobil");
	}
	
	public function delete($id = "")
	{
		if(!empty($id))
		{
			$cek = $this->mobil_model->get_by("id_mobil",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("mobil");
			}
			else
			{
					$this->mobil_model->remove($id);		
					$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
					redirect("mobil");
			}
		}
		else
			redirect("mobil");
	}
	
	public function generate_code()
	{
		$prefix = "MBL";
		$code = "01";
		
		$last = $this->mobil_model->get_last();
		if(!empty($last))
		{
			$number = substr($last->id_mobil,3,2) +1;
			$code = str_pad($number, 2, "0", STR_PAD_LEFT);
		}
		return $prefix.$code;
	}
	
}
