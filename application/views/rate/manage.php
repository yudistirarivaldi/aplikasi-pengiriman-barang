<div class="content-wrapper master">
	<section class="content-header">
	  <h1>
		<?php echo $title?>
	  </h1>
	</section>
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
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<form  class="form-horizontal" method="post" action="<?php echo site_url("rate/save")?>"  >
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_rate; ?>" >
						<div class="box-body">
							<div class="form-group">
								<label for="id_rate" class="col-sm-2 control-label">ID rate</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="id_rate"  name="id_rate" value="<?php echo $data->id_rate == "" ? $data->autocode : $data->id_rate; ?>"  readonly   >
								</div>
							</div>
							<div class="form-group">
								<label for="rate" class="col-sm-2 control-label">Rate</label>
								<div class="col-sm-4">
								  <input type="text" class="form-control"  required="required" id="rate"  name="rate" placeholder="input rate" value="<?php echo $data->rate; ?>"  >
								</div>
							</div>
							<div class="form-group">
								<label for="dari" class="col-sm-2 control-label">Jam</label>
								<div class="col-sm-4">
								  <input type="time" class="form-control"  required="required" id="dari"  name="dari" placeholder="input dari" value="<?php echo $data->dari; ?>"  >
								</div>
							</div>
							<div class="form-group">
								<label for="wilayah" class="col-sm-2 control-label">Wilayah</label>
								<div class="col-sm-4">
								   <input type="text" class="form-control"  required="required" id="wilayah"  name="wilayah" placeholder="input wilayah" value="<?php echo $data->wilayah; ?>"  >
								</div>
							</div>
						</div>
						
						<div class="box-footer">
							<button type="submit" class="btn btn-primary" name="action" value="save">save</button>
							<button type="submit" class="btn btn-success" name="action" value="saveexit">save & exit</button>
							<button type="reset" class="btn btn-warning">reset</button>
							<a  href="<?php echo site_url("rate")?>" class="btn btn-danger">cancel</a>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</section>
</div>