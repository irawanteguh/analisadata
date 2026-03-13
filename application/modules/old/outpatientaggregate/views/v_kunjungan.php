<div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Raw Data OutPatient</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive mh-660px scroll-y me-n5 pe-5">
					<table class="table align-middle table-row-dashed fs-8 gy-2" id="tableanalisaklpcm">
						<thead class="align-middle">
							<tr class="fw-bolder text-muted bg-light">
                                <th class="ps-4 rounded-start">No MR</th>
                                <th>Nama</th>
								<th>Jenis Kelamin</th>
								<th>Poliklinik</th>
                                <th>Nama Dokter</th>
                                <th>No Transaksi</th>
                                <th>Provider</th>
                                <th>Tgl Masuk</th>
                                <th>Tgl Keluar</th>
								<th class="pe-4 rounded-end">Diagnosa</th>
							</tr>
							<tr>
								<th colspan="9"><input id="filterDIAG" class="tagify form-control form-control-solid form-control-sm" placeholder="Diagnosa"></th>
							</tr>
						</thead>
						<tbody class="text-gray-600 fw-bold" id="resultdatakunjungan"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>