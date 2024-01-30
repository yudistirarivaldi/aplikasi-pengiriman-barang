<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/Pdf.php';

class Jadwal extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("jadwal_model");
    }
	public function index()
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "DATA JADWAL KEBERANGKATAN MOBIL";
		$data['layout'] = "jadwal/index";
			
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->jadwal_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("jadwal?");
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
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "FORM JADWAL KEBERANGKATAN";
		$data['layout'] = "jadwal/manage";

		$data['data'] = new StdClass();
		$data['data']->id_keberangkatan = "";
		$data['data']->tanggal = "";
		$data['data']->id_kurir = "";
		$data['data']->kurir = "";
		$data['data']->id_mobil = "";
		$data['data']->mobil = "";
		$data['data']->id_rate = "";
		$data['data']->rate = "";
		$data['data']->wilayah = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->jadwal_model->get_by("j.id_keberangkatan",$id,true);
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
			
			if(!empty($post['id_keberangkatan']))
				$data['id_keberangkatan'] = $post['id_keberangkatan'];
			else
				$error[] = "id tidak boleh kosong"; 
				
			if(!empty($post['tanggal']))
				$data['tanggal'] =  DateTime::createFromFormat('d/m/Y', $post['tanggal'])->format('Y-m-d');
			else
				$error[] = "tanggal tidak boleh kosong"; 
			 
			
			if(!empty($post['id_kurir']))
				$data['id_kurir'] = $post['id_kurir'];
			else
				$error[] = "kurir tidak boleh kosong"; 

			if(!empty($post['id_mobil']))
				$data['id_mobil'] = $post['id_mobil'];
			else
				$error[] = "mobil tidak boleh kosong"; 

			if(!empty($post['id_rate']))
				$data['id_rate'] = $post['id_rate'];
			else
				$error[] = "rate tidak boleh kosong"; 
		
			if(empty($error))
			{
				if(empty($id))
				{
					$cekkategori = $this->jadwal_model->get_by("id_keberangkatan",$post['id_keberangkatan']);
					if(!empty($cekkategori))
						$error[] = "id sudah terdaftar";  
				}
			}
			
			if(empty($error))
			{
				$save = $this->jadwal_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("jadwal/manage/".$id);
				else
					redirect("jadwal");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("jadwal/manage/".$id);
			}
		}
		else
		  redirect("jadwal");
	}
	
	public function delete($id = "")
	{
		// $this->cekLoginStatus("koordinator",true);
		
		if(!empty($id))
		{
			$cek = $this->jadwal_model->get_by("j.id_keberangkatan",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("jadwal");
			}
			else
			{
				$this->jadwal_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("jadwal");
			}
		}
		else
			redirect("jadwal");
	}
	
	public function generate_code()
	{
    $prefix = "JDL" . date("Ymd");
    $code = "001";

    $last = $this->jadwal_model->get_last();

    if (!empty($last)) {
        // Mengambil nomor urutan terakhir
        $lastNumber = (int) substr($last->id_keberangkatan, -3);

        // Menghasilkan nomor urutan baru
        $number = $lastNumber + 1;

        // Menghasilkan kode dengan padding nol
        $code = str_pad($number, 3, "0", STR_PAD_LEFT);
    }

    return $prefix . $code;
	}



	
	public  function cetak($id)
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "CETAK jadwal";
		$data['layout'] = "jadwal/cetak";
		
		$this->load->library("qrcodeci");
		if($id)
		{
			$dt =  $this->jadwal_model->get_by("j.id_keberangkatan",$id,true);
			if($dt)
			{
				$this->qrcodeci->generate($dt->id_keberangkatan);
				$data['data'] = $dt;
				$this->load->view('blank',$data);
			}
			else
			{
				redirect("jadwal");
			}
			
		}
		else
		{
			redirect("jadwal");
		}
	}
	
	public function rekap()
    {
        // $this->cekLoginStatus("planner", true);

        $data['title'] = "Laporan Jadwal Keberangkatan";
        $data['layout'] = "jadwal/rekap";

        $action = $this->input->get('action');

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        if (!$from)
            $from = date('Y-m-d', strtotime("-30 days"));

        if (!$to)
            $to = date("Y-m-d");
       
        $filter = new StdClass();
        $filter->from = date('Y-m-d', strtotime($from));
        $filter->to = date('Y-m-d', strtotime($to));

        list($data['data'], $total) = $this->jadwal_model->getAll($filter, 0, 0, "j.id_keberangkatan", "desc");

        if ($action) {
            $this->export($action, $data['data'], $filter);
        } else {
            $this->load->view('template', $data);
        }
    }

     public function export($action, $data, $filter)
    {
        // $this->cekLoginStatus("planner", true);

        $title = "Laporan Data jadwal Barang";
        $file_name = $title . "_" . date("Y-m-d");
        $headerTitle = $title;

        if (empty($data)) {
            $this->session->set_flashdata('admin_save_error', "data tidak tersedia");
            redirect("jadwal/rekap?from=" . $filter->from . "&to=" . $filter->to);
        } else {
            if ($action == "pdf") {
                $pdf = new FPDF('L', 'mm','Letter');  // Gunakan kelas MyPDF yang sudah Anda ubah namanya
                $pdf->SetTitle($title);
				$pdf->AddPage();
        		$pdf->SetFont('Arial','B',16);
				$pdf->Cell(0,7,'Laporan Jadwal Keberangkatan',0,1,'C');
				$pdf->Cell(10,7,'',0,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(10,6,'No',1,0,'C');
				$pdf->Cell(50,6,'ID jadwal',1,0,'C');
				$pdf->Cell(40,6,'Tanggal',1,0,'C');
				$pdf->Cell(40,6,'Kurir',1,0,'C');
				$pdf->Cell(40,6,'Plat Mobil',1,0,'C');
				$pdf->Cell(20,6,'Rate',1,0,'C');
				$pdf->Cell(40,6,'Wilayah',1,1,'C');
				// $pdf->Cell(40,6,'Kurir',1,1,'C');
        		$pdf->SetFont('Arial','',10);
				$no = 0;
                foreach ($data as $row) {
                    $no++;
					$pdf->Cell(10,6,$no,1,0, 'C');
					$pdf->Cell(50,6,$row['id_keberangkatan'],1,0, 'C');
					$pdf->Cell(40,6,$row['tanggal'],1,0, 'C');
					$pdf->Cell(40,6,$row['kurir'],1,0, 'C');
					$pdf->Cell(40,6,$row['mobil'],1,0, 'C'); 
					$pdf->Cell(20,6,$row['rate'],1,0, 'C');
					$pdf->Cell(40,6,$row['wilayah'],1,1, 'C');
				}

                $pdf->Output($file_name . '.pdf', 'I');
            }
        }
    }
	
	
	
}
