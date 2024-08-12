<?php
class Pengiriman_Model extends CI_Model
{
	var $table  = 'pengiriman';
	var $key  = 'id_pengiriman';
	function __construct()
    {
        parent::__construct();
    }
	function getAll($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
	  	if (isset($filter))
	  	{
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(pg.id_pengiriman) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(b.id_barang) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(b.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(k.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(pg.status) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(p.id_pelanggan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(p.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(kr.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(kr.id_kurir) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(k.id_kategori) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								)";
			}
			
			if (!empty($filter->status))
			{
				if(strtolower($filter->status) != "all")
					$cond[] = "(pg.status = '" . $this->db->escape_str(strtolower($filter->status)) . "')"; 
			}
			
			if (!empty($filter->from) || !empty($filter->to))
			{
				$cond[] = "(pg.tanggal >= '" . $this->db->escape_str($filter->from) . "' and pg.tanggal <= '" . $this->db->escape_str($filter->to) . "' )"; 
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
	    
		$limitOffset = "LIMIT $offset,$limit";
		if($limit == 0)
			$limitOffset = "";
		
		if(!$orderBy)
			$orderBy = $this->key;
		
		if(!$orderType)
			$orderType = "asc";
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pg.*, k.nama AS kategori, k.keterangan AS kategori_keterangan,
                            kr.nama AS kurir, p.nama AS pelanggan, p.alamat, r.rate AS rate,
							r.dari AS jam,
	 						r.wilayah AS wilayah,
                            GROUP_CONCAT(CONCAT(dp.id_barang,'|',b.nama,'|',k.nama,'|',b.satuan,'|',dp.qty,'|',b.del_no) ORDER BY b.nama SEPARATOR '===') AS barang
                            FROM ".$this->table." pg
                            LEFT JOIN detail_pengiriman dp ON dp.id_pengiriman = pg.id_pengiriman
                            LEFT JOIN barang b ON b.id_barang = dp.id_barang
                            LEFT JOIN kategori k ON k.id_kategori = b.id_kategori
                            LEFT JOIN kurir kr ON kr.id_kurir = pg.id_kurir
                            LEFT JOIN pelanggan p ON p.id_pelanggan = pg.id_pelanggan
							LEFT JOIN rate r ON r.id_rate = pg.id_rate
                            $where 
							GROUP BY pg.id_pengiriman, k.nama, k.keterangan, kr.nama, p.nama, p.alamat, r.rate, r.dari, r.wilayah
                            ORDER BY $orderBy $orderType $limitOffset");

		$result = $query->result_array();
		$query->free_result();
		
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;
		
		return array($result,$total);
	}

	function getAllSuratJalanDiTerima($filter = null,$limit = 20,$offset = 0, $orderBy, $orderType)
	{
		$where = "";
		$cond = array();
		$cond[] = "pg.status = 2";
	  	if (isset($filter))
	  	{
			if (!empty($filter->keyword))
			{
				  $cond[] = "(lower(pg.id_pengiriman) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(b.id_barang) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(b.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(k.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(pg.status) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(p.id_pelanggan) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(p.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(kr.nama) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(kr.id_kurir) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								 or lower(k.id_kategori) like '%" . $this->db->escape_str(strtolower($filter->keyword)) . "%'
								)";
			}
			
			if (!empty($filter->from) || !empty($filter->to))
			{
				$cond[] = "(pg.tanggal >= '" . $this->db->escape_str($filter->from) . "' and pg.tanggal <= '" . $this->db->escape_str($filter->to) . "' )"; 
				$cond[] = "pg.status = 2";
			}
			
			if(!empty($cond))
				$where = " where ". implode(" and ", $cond);
	  	}
	    
		$limitOffset = "LIMIT $offset,$limit";
		if($limit == 0)
			$limitOffset = "";
		
		if(!$orderBy)
			$orderBy = $this->key;
		
		if(!$orderType)
			$orderType = "asc";
			
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pg.*, k.nama AS kategori, k.keterangan AS kategori_keterangan,
                            kr.nama AS kurir, p.nama AS pelanggan, p.alamat, r.rate AS rate,
							r.dari AS jam,
	 						r.wilayah AS wilayah,
                            GROUP_CONCAT(CONCAT(dp.id_barang,'|',b.nama,'|',k.nama,'|',b.satuan,'|',dp.qty,'|',b.del_no) ORDER BY b.nama SEPARATOR '===') AS barang
                            FROM ".$this->table." pg
                            LEFT JOIN detail_pengiriman dp ON dp.id_pengiriman = pg.id_pengiriman
                            LEFT JOIN barang b ON b.id_barang = dp.id_barang
                            LEFT JOIN kategori k ON k.id_kategori = b.id_kategori
                            LEFT JOIN kurir kr ON kr.id_kurir = pg.id_kurir
                            LEFT JOIN pelanggan p ON p.id_pelanggan = pg.id_pelanggan
							LEFT JOIN rate r ON r.id_rate = pg.id_rate
                            $where 
							GROUP BY pg.id_pengiriman, k.nama, k.keterangan, kr.nama, p.nama, p.alamat, r.rate, r.dari, r.wilayah
                            ORDER BY $orderBy $orderType $limitOffset");

		$result = $query->result_array();
		$query->free_result();
		
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;
		
		return array($result,$total);
	}
	
	public function get_by($field, $value = "",$obj = false)
	{
		if(!$field)
			$field = $this->key;
			
		$where = "WHERE $field = '".$this->db->escape_str(strtolower($value))."'";
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pg.*, 
                                   k.nama AS kategori, 
                                   k.keterangan AS kategori_keterangan, 
                                   kr.nama AS kurir, 
                                   p.nama AS pelanggan, 
                                   p.alamat,
                                   p.email,
								   r.rate AS rate,
							 	   r.dari AS jam,
	 						 	   r.wilayah AS wilayah,
                                   GROUP_CONCAT(CONCAT(dp.id_barang, '|', b.nama, '|', k.nama, '|', b.satuan, '|', dp.qty, '|', b.del_no, '|', b.harga ) ORDER BY b.nama SEPARATOR '===') AS barang
                            FROM ".$this->table." pg
                            LEFT JOIN detail_pengiriman dp ON dp.id_pengiriman = pg.id_pengiriman
                            LEFT JOIN barang b ON b.id_barang = dp.id_barang
                            LEFT JOIN kategori k ON k.id_kategori = b.id_kategori
                            LEFT JOIN kurir kr ON kr.id_kurir = pg.id_kurir
                            LEFT JOIN pelanggan p ON p.id_pelanggan = pg.id_pelanggan
							LEFT JOIN rate r ON r.id_rate = pg.id_rate
                            $where GROUP BY pg.id_pengiriman, k.nama, k.keterangan, kr.nama, p.nama, p.alamat");

		
		if(!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();
			
		$query->free_result();
		
		return $result;
	}
	
	function remove($id)
    {
      if (!is_array($id))
		    $id = array($id);
			
		$this->db->where_in($this->key, $id)->delete($this->table);
    }
	
	// function save($id = "",$data = array(), $insert_id = false)
	// {
		
	// 	if (!empty($id))
	// 	{
	// 		$this->db->where($this->key, $id);
	// 		$this->db->update($this->table, $data);
	// 	}
	// 	else
	// 	{
	// 		$this->db->insert($this->table, $data);
	// 	}
		
	// 	return $this->db->affected_rows();
	// }

	function save($id = "", $data = array(), $insert_id = false)
{
    // Mulai transaksi
    $this->db->trans_start();

    try {
        // Pemeriksaan apakah data sudah ada
        if (!empty($id)) {
            $existing_data = $this->db->get_where($this->table, array($this->key => $id))->row_array();
            if ($existing_data) {
                // Data sudah ada, lakukan pembaruan
                $this->db->where($this->key, $id);
                $this->db->update($this->table, $data);
            } else {
                // Data tidak ada, lakukan penyisipan baru
                $this->db->insert($this->table, $data);
            }
        } else {
            // Data baru, lakukan penyisipan
            $this->db->insert($this->table, $data);
        }

        // Selesaikan transaksi
        $this->db->trans_complete();

        // Periksa apakah transaksi berhasil
        if ($this->db->trans_status() === false) {
            // Transaksi gagal, rollback perubahan
            $this->db->trans_rollback();
            return 0; // Atau Anda bisa melempar pengecualian atau menangani kesalahan sesuai kebutuhan
        } else {
            // Transaksi berhasil, commit perubahan
            $this->db->trans_commit();
            return $this->db->affected_rows();
        }
    } catch (Exception $e) {
        // Tangani kesalahan (misalnya log kesalahan atau melempar kembali pengecualian)
        $this->db->trans_rollback();
        return 0;
    }
}

	public function get_last()
	{
		$query = $this->db->query("SELECT  * FROM ".$this->table." order by ".$this->key." desc limit 0,1");
		$result = $query->row();
		$query->free_result();
		
		return $result;
	}

	function remove_detail($id)
    {
      if (!is_array($id))
		    $id = array($id);
			
		$this->db->where_in($this->key, $id)->delete("detail_pengiriman");
    }
	
	function save_detail($data = array())
	{
		$this->db->insert("detail_pengiriman", $data);	
		return $this->db->affected_rows();
	}
}