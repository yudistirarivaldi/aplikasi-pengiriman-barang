<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/Pdf.php';

class performa extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("performa_model");
    }
	public function index()
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "DATA Performa Kendaraan";
		$data['layout'] = "performa/index";
			
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->performa_model->getAll($filter,$limit,$offset,$orderBy,$orderType);
		
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
		
		$data['title'] = "FORM PERFORMA KENDARAAN";
		$data['layout'] = "performa/manage";

		$data['data'] = new StdClass();
		$data['data']->id_performa = "";
		$data['data']->tanggal = "";
		$data['data']->id_mobil = "";
		$data['data']->keterangan = "";
		$data['data']->kondisi = "";
		$data['data']->mobil = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->performa_model->get_by("j.id_performa",$id,true);
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
			
			if(!empty($post['id_performa']))
				$data['id_performa'] = $post['id_performa'];
			else
				$error[] = "id tidak boleh kosong"; 
				
			if(!empty($post['tanggal']))
				$data['tanggal'] =  DateTime::createFromFormat('d/m/Y', $post['tanggal'])->format('Y-m-d');
			else
				$error[] = "tanggal tidak boleh kosong"; 
			 

			if(!empty($post['id_mobil']))
				$data['id_mobil'] = $post['id_mobil'];
			else
				$error[] = "mobil tidak boleh kosong"; 

			if(!empty($post['keterangan']))
				$data['keterangan'] = $post['keterangan'];
			else
				$error[] = "keterangan tidak boleh kosong"; 

			if(!empty($post['kondisi']))
				$data['kondisi'] = $post['kondisi'];
			else
				$error[] = "kondisi tidak boleh kosong"; 
		
			if(empty($error))
			{
				if(empty($id))
				{
					$cekkategori = $this->performa_model->get_by("id_performa",$post['id_performa']);
					if(!empty($cekkategori))
						$error[] = "id sudah terdaftar";  
				}
			}
			
			if(empty($error))
			{
				$save = $this->performa_model->save($id,$data,false);
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("performa/manage/".$id);
				else
					redirect("performa");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("performa/manage/".$id);
			}
		}
		else
		  redirect("performa");
	}
	
	public function delete($id = "")
	{
		// $this->cekLoginStatus("koordinator",true);
		
		if(!empty($id))
		{
			$cek = $this->performa_model->get_by("j.id_performa",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("performa");
			}
			else
			{
				$this->performa_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("performa");
			}
		}
		else
			redirect("performa");
	}
	
	public function generate_code()
	{
    $prefix = "PRFRM" . date("Ymd");
    $code = "001";

    $last = $this->performa_model->get_last();

    if (!empty($last)) {
        // Mengambil nomor urutan terakhir
        $lastNumber = (int) substr($last->id_performa, -3);

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
		
		$data['title'] = "CETAK performa kendaraan";
		$data['layout'] = "performa/cetak";
		
		$this->load->library("qrcodeci");
		if($id)
		{
			$dt =  $this->performa_model->get_by("j.id_performa",$id,true);
			if($dt)
			{
				$this->qrcodeci->generate($dt->id_performa);
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

        $data['title'] = "Laporan Performa Kendaraan";
        $data['layout'] = "performa/rekap";

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

        list($data['data'], $total) = $this->performa_model->getAll($filter, 0, 0, "j.id_performa", "desc");

        if ($action) {
            $this->export($action, $data['data'], $filter);
        } else {
            $this->load->view('template', $data);
        }
    }

    public function export($action, $data, $filter)
    {
        // $this->cekLoginStatus("planner", true);

        $title = "Laporan Data Performa Kendaraan";
        $file_name = $title . "_" . date("Y-m-d");
        $headerTitle = $title;

        if (empty($data)) {
            $this->session->set_flashdata('admin_save_error', "data tidak tersedia");
            redirect("performa/rekap?from=" . $filter->from . "&to=" . $filter->to);
        } else {
            if ($action == "pdf") {
                $pdf = new FPDF('L', 'mm','Letter');  // Gunakan kelas MyPDF yang sudah Anda ubah namanya
				$pdf->AddPage();
        		
				$pdf->Image('assets/images/massindo.png', 10,6,40,24);
				$pdf->SetFont('Times','B','20');
				$pdf->Cell(0,5,'PT MASSINDO SOLARIS NUSANTARA BANJARMASIN',0,1,'C');
				$pdf->SetFont('Times','I','12');
				$pdf->Cell(0,5,'Jl. A. Yani KM.21 Pergudangan LIK NO 6B Banjarbaru - Kalimantan Selatan',0,1,'C');
				$pdf->Cell(0,5,'Telp. 0812-5158-2818',0,15,'C');
				$pdf->Cell(0,20,'',0,15,'C');

				$pdf->SetLineWidth(1);
				$pdf->Line(10,36,250,36);
				$pdf->SetLineWidth(0);
				$pdf->Line(10,37,250,37);

				$pdf->SetFont('Times','B',14);
				$pdf->Cell(0,5,'Laporan Jadwal Keberangkatan ' ,0,5,'C');
				$pdf->SetFont('Times','I','10');
				$pdf->Cell(0,5,'dicetak pada tanggal : ' . date('d M y'),0,15,'C');
				
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(20,6,'No',1,0,'C');
				$pdf->Cell(50,6,'ID',1,0,'C');
				$pdf->Cell(40,6,'Tanggal',1,0,'C');
				$pdf->Cell(40,6,'Plat Mobil',1,0,'C');
				$pdf->Cell(50,6,'Keterangan',1,0,'C');
				$pdf->Cell(40,6,'Kondisi',1,1,'C');
				// $pdf->Cell(40,6,'Kurir',1,1,'C');
        		$pdf->SetFont('Arial','',10);
				$no = 0;
                foreach ($data as $row) {
                    $no++;
					$pdf->Cell(20,6,$no,1,0, 'C');
					$pdf->Cell(50,6,$row['id_performa'],1,0, 'C');
					$pdf->Cell(40,6,$row['tanggal'],1,0, 'C');
					$pdf->Cell(40,6,$row['mobil'],1,0, 'C'); 
					$pdf->Cell(50,6,$row['keterangan'],1,0, 'C');
					$pdf->Cell(40, 6, ($row['kondisi'] == 1 ? 'Bagus' : ($row['kondisi'] == 2 ? 'Perlu Di Service' : 'Tidak Diketahui')), 1, 1, 'C');

				}

                $pdf->Output($file_name . '.pdf', 'I');
            }
        }
    }
	
	
	
}
