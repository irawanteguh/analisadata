<div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Actual Pendapatan</span>
				</h3>
				<div class="card-toolbar m-0">
					<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<span class="svg-icon svg-icon-3 svg-icon-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
									<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
									<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
									<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
								</g>
							</svg>
						</span>
					</button>
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
						<div class="menu-item px-3">
							<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">More Actions</div>
						</div>
						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_addplan">Rencana Pendapatan</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive mh-610px" style="overflow-x:auto; white-space:nowrap;">
					<table class="table align-middle table-row-dashed fs-8 gy-2">
						<thead class="align-middle">
							<tr class="fw-bolder text-muted bg-light">
                                <th class="ps-4 rounded-start">#</th>
                                <th>Pendapatan Layanan Operasional</th>
								<th class="text-end">Volume</th>
                                <th>Satuan</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Created By</th>
                                <th class="pe-4 rounded-end text-end">Actions</th>
							</tr>
						</thead>
						<tbody class="text-gray-600 fw-bold" id="resultdataactualpendapatan"></tbody>
                        <tfoot class="align-middle">
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="ps-4 rounded-start" colspan="5">Total Actual Pendapatan</th>
                                <th class="text-end totalactualpendapatan">0</th>
                                <th></th>
                                <th></th>
                                <!-- <th class="pe-4 rounded-end text-end"></th> -->
                            </tr>
                        </tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>