<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/Pdf.php';

class Pengiriman extends Admin_Controller {
	
	public function __construct()
    {
        parent::__construct();

		$this->load->model("pengiriman_model");
    }
	public function index()
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "DATA PENGIRIMAN";
		$data['layout'] = "pengiriman/index";
			
		$filter = new StdClass();
		$filter->keyword = trim($this->input->get('keyword'));
		
		$orderBy = $this->input->get('orderBy');
		$orderType = $this->input->get('orderType');
		$page = $this->input->get('page');
		
		$limit = 15;
		if(!$page)
			$page = 1;
		
		$offset = ($page-1) * $limit;
		
		list($data['data'],$total) = $this->pengiriman_model->getAll($filter,$limit,$offset,$orderBy,$orderType);

		$this->load->library('pagination');
		$config['base_url'] = site_url("pengiriman?");
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
		
		$data['title'] = "FORM PENGIRIMAN";
		$data['layout'] = "pengiriman/manage";

		$data['data'] = new StdClass();
		$data['data']->id_pengiriman = "";
		$data['data']->tanggal = "";
		$data['data']->id_kategori = "";
		$data['data']->kategori = "";
		$data['data']->barang = "";
		$data['data']->id_pelanggan = "";
		$data['data']->pelanggan = "";
		$data['data']->alamat = "";
		$data['data']->id_kurir = "";
		$data['data']->kurir = "";
		$data['data']->status = "";
		$data['data']->keterangan = "";
		$data['data']->penerimaan = "";
		$data['data']->id_rate = "";
		$data['data']->rate = "";

		$data['data']->no_po = "";
		$data['data']->no_kendaraan = "";
		$data['data']->autocode = $this->generate_code();
		
		if($id)
		{
			$dt =  $this->pengiriman_model->get_by("pg.id_pengiriman",$id,true);
			if(!empty($dt))
				$data['data'] = $dt;
		}
		
		$this->load->view('template',$data);
	}
	
	public function save()
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data = array();
		$post = $this->input->post();
		
		if($post)
		{
			$error = array();
			$id = $post['id'];
			
			if(!empty($post['id_pengiriman']))
				$data['id_pengiriman'] = $post['id_pengiriman'];
			else
				$error[] = "id tidak boleh kosong"; 
				
			if(!empty($post['tanggal']))
				$data['tanggal'] =  DateTime::createFromFormat('d/m/Y', $post['tanggal'])->format('Y-m-d');
			else
				$error[] = "tanggal tidak boleh kosong"; 
			
			if(!empty($post['id_pelanggan']))
				$data['id_pelanggan'] = $post['id_pelanggan'];
			else
				$error[] = "pelanggan tidak boleh kosong";
			
			if(!empty($post['id_kurir']))
				$data['id_kurir'] = $post['id_kurir'];
			else
				$error[] = "kurir tidak boleh kosong";
			
			if(!empty($post['id_rate']))
				$data['id_rate'] = $post['id_rate'];
			else
				$error[] = "rate  tidak boleh kosong";

			$data['status'] = 1;
			
			if(!empty($id))
			{
				if(!empty($post['status']))
					$data['status'] = $post['status'];
				else
					$error[] = "status tidak boleh kosong";
			}
			
			if($data['status'] != 1)
			{
				if(!empty($post['penerima']))
					$data['penerima'] = $post['penerima'];
				else
					$error[] = "keterangan tidak boleh kosong";
				
				if(!empty($post['keterangan']))
					$data['keterangan'] = $post['keterangan'];
				else
					$error[] = "keterangan tidak boleh kosong";
			}
			
			if(empty($error))
			{
				if(empty($id))
				{
					$cekpengiriman = $this->pengiriman_model->get_by("pg.id_pengiriman",$post['id_pengiriman']);
					if(!empty($cekpengiriman))
						$error[] = "id sudah terdaftar";
				}
			}
			
			if(empty($error))
			{
				$save = $this->pengiriman_model->save($id,$data,false);
				
				$datailkode = $post['detail']['id_barang'];
				$datailjumlah = $post['detail']['qty'];

				if(!empty($id))
				{
					$this->pengiriman_model->remove_detail($id);
				}
				
				foreach($datailkode as $key => $val)
				{
					
					if(empty($id))
						$detail['id_pengiriman'] = $data['id_pengiriman'];
					else
						$detail['id_pengiriman'] = $id;
						
					$detail['id_barang'] = $val;
					$detail['qty'] = $datailjumlah[$val];
					$this->pengiriman_model->save_detail($detail);
				}
				
				
				$this->session->set_flashdata('admin_save_success', "data berhasil disimpan");
				
				if($post['action'] == "save")
					redirect("pengiriman/manage/".$id);
				else
					redirect("pengiriman");
			}
			else
			{
				$err_string = "<ul>";
				foreach($error as $err)
					$err_string .= "<li>".$err."</li>";
				$err_string .= "</ul>";
				
				$this->session->set_flashdata('admin_save_error', $err_string);
				redirect("pengiriman/manage/".$id);
			}
		}
		else
		  redirect("pengiriman");
	}
	
	public function delete($id = "")
	{
		// $this->cekLoginStatus("koordinator",true);
		
		if(!empty($id))
		{
			$cek = $this->pengiriman_model->get_by("pg.id_pengiriman",$id,true);
			if(empty($cek))
			{
				$this->session->set_flashdata('admin_save_error', "ID tidak terdaftar");
				redirect("pengiriman");
			}
			else
			{
				$this->pengiriman_model->remove($id);
				$this->session->set_flashdata('admin_save_success', "data berhasil dihapus");
				redirect("pengiriman");
			}
		}
		else
			redirect("pengiriman");
	}
	
	public function generate_code()
	{
    $prefix = "KRM" . date("Ymd");
    $code = "001";

    $last = $this->pengiriman_model->get_last();

    if (!empty($last)) {
        // Mengambil nomor urutan terakhir
        $lastNumber = (int) substr($last->id_pengiriman, -3);

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
		
		$data['title'] = "CETAK PENGIRIMAN";
		$data['layout'] = "pengiriman/cetak";
		
		$this->load->library("qrcodeci");
		if($id)
		{
			$dt =  $this->pengiriman_model->get_by("pg.id_pengiriman",$id,true);
			if($dt)
			{
				$this->qrcodeci->generate($dt->id_pengiriman);
				$data['data'] = $dt;
				$this->load->view('blank',$data);
			}
			else
			{
				redirect("pengiriman");
			}
			
		}
		else
		{
			redirect("pengiriman");
		}
	}

	public  function invoice($id)
	{
		// $this->cekLoginStatus("koordinator",true);
		
		$data['title'] = "CETAK INVOICE";
		$data['layout'] = "pengiriman/invoice";
		
		$this->load->library("qrcodeci");
		if($id)
		{
			$dt =  $this->pengiriman_model->get_by("pg.id_pengiriman",$id,true);
			if($dt)
			{
				$this->qrcodeci->generate($dt->id_pengiriman);
				$data['data'] = $dt;
				$this->load->view('blank',$data);
			}
			else
			{
				redirect("pengiriman");
			}
			
		}
		else
		{
			redirect("pengiriman");
		}
	}
	
	public function rekap()
    {
        // $this->cekLoginStatus("planner", true);

        $data['title'] = "Laporan Pengiriman Barang";
        $data['layout'] = "pengiriman/rekap";

        $action = $this->input->get('action');

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $status = $this->input->get('status');

        if (!$from)
            $from = date('Y-m-d', strtotime("-30 days"));

        if (!$to)
            $to = date("Y-m-d");

        if (!$status)
            $status = "all";

        $filter = new StdClass();
        $filter->from = date('Y-m-d', strtotime($from));
        $filter->to = date('Y-m-d', strtotime($to));
        $filter->status = $status;

        list($data['data'], $total) = $this->pengiriman_model->getAll($filter, 0, 0, "pg.id_pengiriman", "desc");

        if ($action) {
            $this->export($action, $data['data'], $filter);
        } else {
            $this->load->view('template', $data);
        }
    }

	public function rekap_barang()
    {
        // $this->cekLoginStatus("planner", true);

        $data['title'] = "Laporan Barang di Kirim";
        $data['layout'] = "pengiriman/rekap_barang";

        $action = $this->input->get('action');

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $status = $this->input->get('status');

        if (!$from)
            $from = date('Y-m-d', strtotime("-30 days"));

        if (!$to)
            $to = date("Y-m-d");

        if (!$status)
            $status = "all";

        $filter = new StdClass();
        $filter->from = date('Y-m-d', strtotime($from));
        $filter->to = date('Y-m-d', strtotime($to));
        $filter->status = $status;

        list($data['data'], $total) = $this->pengiriman_model->getAll($filter, 0, 0, "pg.id_pengiriman", "desc");

        if ($action) {
            $this->export_barang($action, $data['data'], $filter);
        } else {
            $this->load->view('template', $data);
        }
    }

    public function export_barang($action, $data, $filter)
    {
        // $this->cekLoginStatus("planner", true);

        $title = "Laporan Data Pengiriman Barang";
        $file_name = $title . "_" . date("Y-m-d");
        $headerTitle = $title;

        if (empty($data)) {
            $this->session->set_flashdata('admin_save_error', "data tidak tersedia");
            redirect("pengiriman/rekap?from=" . $filter->from . "&to=" . $filter->to . "&status=" . $filter->status);
        } else {
            if ($action == "pdf") {
                $pdf = new FPDF('L', 'mm','Letter');  // Gunakan kelas MyPDF yang sudah Anda ubah namanya
                $pdf->SetTitle($title);
				$pdf->AddPage();
        		$pdf->SetFont('Arial','B',16);
				$pdf->Cell(0,7,'Laporan Barang di Kirim',0,1,'C');
				$pdf->Cell(10,7,'',0,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(10,6,'No',1,0,'C');
				$pdf->Cell(70,6,'Pelanggan',1,0,'C');
				$pdf->Cell(40,6,'Tanggal',1,0,'C');
				$pdf->Cell(50,6,'Barang',1,0,'C');
				$pdf->Cell(40,6,'Kategori',1,0,'C');
				$pdf->Cell(40,6,'Satuan',1,1,'C');
				
        		$pdf->SetFont('Arial','',10);
				$no = 0;
				
                foreach ($data as $dt) {
                    $no++;
					$pdf->Cell(10,6,$no,1,0, 'C');
					$pdf->Cell(70,6,$dt['pelanggan'],1,0, 'C');
					$pdf->Cell(40,6,$dt['tanggal'],1,0, 'C');
					$b = explode("|",$dt['barang']);
					$pdf->Cell(50,6,$b['1'],1,0, 'C');
					$pdf->Cell(40,6,$b['2'],1,0, 'C');
					$pdf->Cell(40,6,$b['3'],1,1, 'C');
					
				}

                $pdf->Output($file_name . '.pdf', 'I');
            }
        }
    }

	public function export($action, $data, $filter)
    {
        // $this->cekLoginStatus("planner", true);

        $title = "Laporan Data Pengiriman Barang";
        $file_name = $title . "_" . date("Y-m-d");
        $headerTitle = $title;

        if (empty($data)) {
            $this->session->set_flashdata('admin_save_error', "data tidak tersedia");
            redirect("pengiriman/rekap?from=" . $filter->from . "&to=" . $filter->to . "&status=" . $filter->status);
        } else {
            if ($action == "pdf") {
                $pdf = new FPDF('L', 'mm','Letter');  // Gunakan kelas MyPDF yang sudah Anda ubah namanya
                $pdf->SetTitle($title);
				$pdf->AddPage();
        		$pdf->SetFont('Arial','B',16);
				$pdf->Cell(0,7,'Laporan Pengiriman Barang',0,1,'C');
				$pdf->Cell(10,7,'',0,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(10,6,'No',1,0,'C');
				$pdf->Cell(40,6,'Tanggal',1,0,'C');
				$pdf->Cell(70,6,'Pelanggan',1,0,'C');
				$pdf->Cell(50,6,'Kurir',1,0,'C');
				

				$pdf->Cell(40,6,'Jam',1,0,'C');
				$pdf->Cell(40,6,'Wilayah',1,1,'C');
				
        		$pdf->SetFont('Arial','',10);
				$no = 0;
				
                foreach ($data as $dt) {
                    $no++;
					$pdf->Cell(10,6,$no,1,0, 'C');
					$pdf->Cell(40,6,$dt['tanggal'],1,0, 'C');
					$pdf->Cell(70,6,$dt['pelanggan'],1,0, 'C');
					$pdf->Cell(50,6,$dt['kurir'],1,0, 'C');
					$pdf->Cell(40,6,$dt['jam'],1,0, 'C');
					$pdf->Cell(40,6,$dt['wilayah'],1,1, 'C');
				}

                $pdf->Output($file_name . '.pdf', 'I');
            }
        }
    }
	
	public function generate_format($data)
	{
		$newdata = array();
		$grantotal = 0;
		foreach($data as $key => $dt)
		{
			
			$dat = array();
			$dat['ID Pengiriman'] = $dt['id_pengiriman'];
			$dat['Tanggal'] = date("d-m-Y",strtotime($dt['tanggal']));
			$dat['Pelanggan'] = $dt['pelanggan'];
			$dat['No. PO'] = $dt['no_po'];
			$dat['Kurir'] = $dt['kurir'];
			$dat['No. Kendaraan'] = $dt['no_kendaraan'];
			$dat['Penerima'] = $dt['penerima'];
			
			$status = "Dikirim";
			if($dt['status'] == 2)
				$status = "Diterima";
			else if($dt['status'] == 3)
				$status = "Ditolak";
			else if($dt['status'] == 4)
				$status = "Diterima sebagian";

			$dat['Status'] = $status;
			
			$newdata[] = $dat;
		}
		
		
		return $newdata;
	}
	
}
