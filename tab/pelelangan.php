<!-- Layout Card
	
		<div class="card col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
			<div class="card-body">
				<h5 class="card-title">Nama Barang</h5>
				<h6 class="card-subtitle mb-2 text-muted">Harga</h6>
				<p class="card-text">Deskripsi Barang</p>
				<a class="btn btn-primary btn-block" href="#">Buka atau tutup lelang</a>
			</div>
		</div>

-->

<div class="jumbotron">
	<div class="row">
		<h3 class="display-4">Pelelangan</h3>
	</div>
	<hr>
	<div class="row">
		<?php
		require('connection.php');
		include 'utilities/tgl_indo.php';
		$query = mysqli_query($connection, "Select * From tb_lelang Where status='dibuka'");

		while ($data = mysqli_fetch_array($query))
		{
			$id_barang = $data['id_barang'];
			$query_barang = mysqli_query($connection, "Select * From tb_barang Where id_barang='$id_barang'");
			$data_barang = mysqli_fetch_assoc($query_barang);

			$id_user = null;
			if ($data['id_user'] != null)
			{
				$id_user = $data['id_user'];
			}


			echo '<div class="card col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">';
			echo '<div class="card-body">';
			echo '<h5 class="card-title">' . $data_barang['nama_barang'] . '</h5>';

			$harga_rupiah = "Rp" . number_format($data_barang['harga_awal'],2,',','.');
			echo '<h6 class="card-subtitle mb-2 text-muted">Harga awal: ' . $harga_rupiah . '</h6>';
			if ($id_user != null)
			{
				$harga_akhir_rupiah = "Rp" . number_format($data['harga_akhir'],2,',','.');
				echo '<h6 class="card-subtitle mb-2 text-muted">Penawaran tertinggi: ' . $harga_akhir_rupiah . '</h6>';
			}

			$tgl_akhir_lelang = tgl_indo($data['tgl_lelang']);
			echo '<h6 class="card-subtitle mb-2 text-muted">Tanggal akhir lelang: ' . $tgl_akhir_lelang . '</h6>';
			//echo '<p class="card-text">' . $data_barang['deskripsi_barang'] . '</p>';

			// Mengumpulkan data yang diperlukan untuk membuat button
			$id_lelang = $data['id_lelang'];
			$nama_barang = $data_barang['nama_barang'];
			$deskripsi_barang = $data_barang['deskripsi_barang'];
			$arguments = "'" . $nama_barang . "', '" . $deskripsi_barang . "', '" . $tgl_akhir_lelang . "'";
			$minimal_tawaran = $data['harga_akhir'];
			$harga_awal = $data_barang['harga_awal'];
			echo '<button class="btn btn-primary btn-block" name="tawarBtn" onclick="tawar(' . $id_lelang . ', ' . $arguments . ', ' . $id_barang . ', ' . $minimal_tawaran . ', ' . $harga_awal . ')">Ajukan tawaran</button>';

			echo '</div>';
			echo '</div>';
		}

		if (mysqli_num_rows($query) <= 0)
		{
			echo '<h5>Maaf saat ini sedang tidak ada barang yang dilelang</h5>';
		}

		?>
	</div>
</div>

<p id="nama_user" style="display: none;"><?php
	if (isset($_SESSION['name']))
	{
		echo $_SESSION['name'];
	}
	?></p>

<script>
function tawar(id, nama_barang, deskripsi_barang, tgl_lelang, id_barang, harga_minimal, harga_awal)
{
	var user = document.getElementById('nama_user').innerHTML;

	if (user.toString().length < 4)
	{
		alert('Anda harus login!');
		$('#loginModal').modal('show');
		return;
	}

	id_lelang = id;
	aturBarang(nama_barang, deskripsi_barang, tgl_lelang, id, user, id_barang, harga_minimal, harga_awal);
	$('#mainNav li:last-child a').tab('show');
}
</script>