<style>
	@media print {
		@page {
		  size: 8.267in 5.5in;
		  margin: 0;
		}
		.header-pt
		{
			font-weight:bold;
		}
	}
	.tbl-resi
	{
		font-size:11px;
	}
	.table-wrapper
	{
		border:1px solid gray;
		border-top:4px solid gray;
		height:180px;
		padding-top:5px;
	}
	.border-bottom
	{
		border-bottom:1px solid gray;
	}
	.border-top
	{
		border-top:1px solid gray;
	}
	.border-left
	{
		border-left:1px solid gray;
	}
	.img-qrcode
	{
		position:absolute;
		top:0;
		right:0;
	}
	.img-logo
	{
		position:absolute;
		top:10px;
		left:20px;
	}
</style>			
<div class="content-wrapper print resi">
	<table>
		<tr>
			<td width='450' align="center" valign='top'>
				<img class="img-logo" src="<?php echo base_url("assets/images")."/logo.jpg"; ?>" width="100" height="90" />
				<div class='header-pt'>PT. Triono Putra</div>
				<div class='header-address'>Jl. PLN Raya</div>
				<div class='header-address'>GANDUL - CINERE</div>
				<div class='header-address'>Depok 16512, Indonesia</div>
			</td>
			<td valign='top'>
				<div >
					Depok, <?php echo date('d M Y'); ?>
				</div>
				<div class='mt10'>
					KEPADA Yth.
				</div>
				<div>
					<?php echo $data->pelanggan; ?>
				</div>
				<div class='mt10'>
					<?php echo $data->alamat; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td rowspan="2">
				<div class='header-pt'>INVOICE NO. <?php echo $data->id_pengiriman; ?></div>
			</td>
		<tr>
	</table>
	<div class='table-wrapper'>
		<table style="width:100%"  style="mt10"  cellpadding='5' cellspacing='0'>
			<tr >
				<th class="border-bottom border-top " height="10">No</th>
				<th class="border-bottom border-top">Kode Barang</th>
				<th class="border-bottom border-top">Nama Barang</th>
				<th class="border-bottom border-top">Del No</th>
				<th class="border-bottom border-top">QTY</th>
				<th class="border-bottom border-top">Sat</th>
				<th class="border-bottom border-top">Harga</th>
			</tr>
			<tbody>
			<?php if ($data->barang != null): ?>
    <?php $barang = explode("===", $data->barang); ?>
    <?php $i = 1; ?>
    <?php $grand_total = 0; ?>
    <?php foreach ($barang as $br): ?>
        <?php $b = explode("|", $br) ?>
        <?php $harga = $b[6] * $b[4] ?>
        <tr class="tbl-resi">
            <td align="center" height="10"><?php echo $i; ?></td>
            <td align="center"><?php echo $b[0]; ?></td>
            <td align="center"><?php echo $b[1]; ?></td>
            <td align="center"><?php echo $b[5]; ?></td>
            <td align="center"><?php echo $b[4]; ?></td>
            <td align="center"><?php echo $b[3]; ?></td>
            <td align="center"><?php echo 'Rp ' . number_format($b[6], 0, ',', '.'); ?></td> 
        </tr>
        <?php $i++; ?>
        <?php $grand_total += floatval($harga); ?>
    <?php endforeach ?>
    <tr class="tbl-resi">
        <td colspan="6">Total</td>
        <td align="center"><?php echo 'Rp ' . number_format($grand_total, 0, ',', '.'); ?></td>
    </tr>
<?php endif ?>

		</tbody>
		</table>
	</div>
	<table style="width:100%">
		<tr>
			<td valign='top' style="width:55%" >
			</td>
			<td valign='top' style="width:30%">
				<div class='mt10'>
					Diterima Oleh: <?php echo $data->penerima; ?>
				</div>
			</td>
			<td valign='top' style="width:15%">
				<div class='mt10'>
					Terima Kasih <br> Hormat Kami
				</div>
			</td>
		</tr>
	</table>
	
</div>
<script>
	$(function(){
		window.print();
	});
</script>
