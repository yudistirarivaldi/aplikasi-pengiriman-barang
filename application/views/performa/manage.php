<link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/datepicker/datepicker3.css')?>">
<script src="<?php echo base_url('assets/template/plugins/datepicker/bootstrap-datepicker.js')?>"></script>

<script>
$(function(){
	
	$("form[name='save']").on("keyup keypress", function(e) {
	  var code = e.keyCode || e.which; 
	  if (code  == 13) {               
		e.preventDefault();
		$(this).blur();
		return false;
	  }
	});
	
	$('#ref-table-kurir').on('show.bs.modal', function(e) {
		selectedmodal = 3;
		$(".table-ajax-kurir").empty();
		getKurir(1);
	});

	$('#ref-table-mobil').on('show.bs.modal', function(e) {
		selectedmodal = 2;
		$(".table-ajax-mobil").empty();
		getMobil(1);
	});

	$('#ref-table-rate').on('show.bs.modal', function(e) {
		selectedmodal = 1;
		$(".table-ajax-rate").empty();
		getRate(1);
	});
		
	$(".search-mobil").click(function(){
		getMobil(1);
	});

	$(".search-kurir").click(function(){
		getKurir(1);
	});

	$(".search-rate").click(function(){
		getRate(1);
	});

	$("body").on('click', '#ref-table-kurir .pagination a', function (e) {
		getKurir(getUrlVars(e.target.href)['page']);
		return false;
    });

	$("body").on('click', '#ref-table-mobil .pagination a', function (e) {
		getMobil(getUrlVars(e.target.href)['page']);
		return false;
    });

	$("body").on('click', '#ref-table-rate .pagination a', function (e) {
		getRate(getUrlVars(e.target.href)['page']);
		return false;
    });
	
	$("input[name='tanggal']").datepicker();
	
	
	$("form[name='save']").submit(function( event ) {
		
		if($("input[name='id_mobil']").val() == "")
		{
				$(".error-wrapper").html("<div class='alert alert-danger'>"
				 + "<a href='#' class='close' data-dismiss='alert'>&times;</a>"
				 + " <strong>Error!</strong> Pilih mobil dahulu"
				 + "</div>");
			return false;
		}
	 });
	
});

function getMobil(page)
{
	$.ajax({
		  dataType: "html",
		  url: "<?php echo site_url("api/ajax/getTableMobil");?>",
		  data:{"keyword":$("#keyword-mobil").val(),'page':page},
		  success:function(d){
			  $(".table-ajax-mobil").empty();
			  $(".table-ajax-mobil").html(d);
			}
	});	
}

function pilih(id,nama,other,other2)
{
	if(selectedmodal == 2)
	{
		$("input[name='id_mobil']").val(id);
		$("input[name='mobil']").val(nama);
	}
}

function getUrlVars(url) {
        var vars = [], hash;
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
}

</script>

<div class="content-wrapper master">
	<section class="content-header">
	  <h1>
		<?php echo $title?>
	  </h1>
	</section>
	<div class="error-wrapper">
		<?php
			 $msg_err = $this->session->flashdata('admin_save_error');
			 $msg_succes = $this->session->flashdata('admin_save_success');
		?>
		<?php if(!empty($msg_err)): ?>
		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Error!</strong> <?php echo $msg_err;?>
		</div>
		<?php endif; ?>
		<?php if(!empty($msg_succes)): ?>
		<div class="alert alert-success">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Succes!</strong> <?php echo $msg_succes;?>	
		</div>
	<?php endif; ?>
	</div>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<form  class="form-horizontal" method="post" action="<?php echo site_url("performa/save")?>"  >
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_performa; ?>" >
						<div class="box-body">
							<div class="form-group">
								<label for="id_performa" class="col-sm-2 control-label">ID</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="id_performa"  name="id_performa" value="<?php echo $data->id_performa == "" ? $data->autocode : $data->id_performa; ?>"  readonly   >
								</div>
							</div>
							<div class="form-group">
								<label for="tanggal" class="col-sm-2 control-label">Tanggal</label>
								<div class="col-sm-4">
								  <input type="text" required="required" class="form-control datepicker" id="tanggal" data-date-format="dd/mm/yyyy" placeholder="select tanggal" name="tanggal" value="<?php echo $data->tanggal != "" ? date("d/m/Y",strtotime($data->tanggal)) : date("d/m/Y"); ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="id_mobil" class="col-sm-2 control-label">Mobil</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="id_mobil" placeholder="pilih mobil" name="id_mobil" value="<?php echo $data->id_mobil; ?>" readonly  />
								</div>
								<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#ref-table-mobil" href="#"><span class="glyphicon glyphicon-search"></span></a>
							</div>
							<div class="form-group">
								<label for="mobil" class="col-sm-2 control-label">Plat Mobil</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="mobil"  name="mobil" value="<?php echo $data->mobil; ?>" readonly  />
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
								<div class="col-sm-4">
								  <input type="text" required="required" class="form-control datepicker" id="keterangan" placeholder="input keterangan" name="keterangan"  value="<?php echo $data->keterangan == "" ? $data->keterangan : $data->keterangan ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="id_categ" class="col-sm-2 control-label">Status</label>
								<div class="col-sm-7">
								   <select class="form-control input-sm" name="kondisi">
									  <option value="1" <?php echo $data->kondisi == "1" ? ' selected' : '';?> >Bagus</option>
									  <option value="2" <?php echo $data->kondisi == "2" ? ' selected' : '';?> >Perlu Di Service</option>
									  
									</select>
								</div>
							</div>
						</div>
						
						<div class="row">
				
				</div>
						
						<div class="box-footer">
							<button type="submit" class="btn btn-primary" name="action" value="save">save</button>
							<button type="submit" class="btn btn-success" name="action" value="saveexit">save & exit</button>
							<button type="reset" class="btn btn-warning">reset</button>
							<a  href="<?php echo site_url("performa")?>" class="btn btn-danger">cancel</a>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="ref-table-mobil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ajax-modal" style="margin-top:100px;">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Pilih Mobil</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-filter-modal">
						<form class="form-inline" method="get" action="#" >
							<div class="form-group">
								<input type="text" class="form-control input-sm" id="keyword-mobil" placeholder="Keyword" name="keyword" value="">
							</div>
							<button type="button" class="btn btn-primary btn-sm search-mobil">Search</button>
						</form>
					</div>
				</div>
				
					<div class="table-ajax-mobil">
					</div>
				
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger danger delete" data-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>
