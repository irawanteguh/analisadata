<div class="row">
    <div class="col-xl-4">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Pengguna Sistem</span>
					<span class="text-muted mt-1 fw-bold fs-7">Daftar pengguna dan akun yang terdaftar</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="d-flex align-items-center position-relative my-1 mb-4">
					<span class="svg-icon svg-icon-1 position-absolute ms-6">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
							<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
						</svg>
					</span>
					<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Data" id="searchtable">
				</div>
				<div class="table-responsive mh-610px">
                    <table class="table align-middle table-row-dashed fs-8 gy-2" id="datauser_table">
                        <thead class="align-middle">
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="ps-4 rounded-start">#</th>
                                <th>Username</th>
                                <th>Nama User</th>
                                <th class="pe-4 text-end rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold" id="resultdatauser"></tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
    <div class="col-xl-8">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">List Modules</span>
					<span class="text-muted mt-1 fw-bold fs-7">-</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="scroll-y me-n5 pe-5" id="listmodules"></div>
			</div>
		</div>
	</div>
</div>