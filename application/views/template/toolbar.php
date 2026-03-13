<div class="toolbar" id="kt_toolbar">
	<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
		
		<?php
			$breadcrumb = APPPATH . 'modules/' . $this->uri->segment(1) . '/breadcrumb/' . $this->uri->segment(2) . ".php";
			echo '<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: \'#kt_content_container\', \'lg\': \'#kt_toolbar_container\'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">';
			if(file_exists($breadcrumb)){
				include_once($breadcrumb);
			}
			echo '</div>';
		?>

		<div class="d-flex align-items-center py-1">
			<?php
				$filetoolbar = APPPATH . 'modules/' . $this->uri->segment(1) . '/toolbar/' . $this->uri->segment(2) . ".php";
				$filefilter = APPPATH . 'modules/' . $this->uri->segment(1) . '/filter/' . $this->uri->segment(2) . ".php";

				if(file_exists($filetoolbar)){
					echo '<div class="me-4">';
					if(file_exists($filefilter)){
						echo '
							<a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
								<span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black" />
									</svg>
								</span>
								Filter
							</a>
							<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_61484bf6e3ff8">
								<div class="px-7 py-5">
									<div class="fs-5 text-dark fw-bolder">Filter Options</div>
								</div>
								<div class="separator border-gray-200"></div>
								<div class="px-7 py-5">
						';
						include_once($filefilter);
						echo '
									<div class="d-flex justify-content-end">
										<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
										<button type="button" class="btn btn-sm btn-primary btn-apply" data-kt-menu-dismiss="true">Apply</button>
									</div>
								</div>
							</div>
						';
					}
					echo '</div>';
					include_once($filetoolbar);
				} else {
					if(file_exists($filefilter)){
						echo '
							<a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
								<span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black" />
									</svg>
								</span>
								Filter
							</a>
							<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_61484bf6e3ff8">
								<div class="px-7 py-5">
									<div class="fs-5 text-dark fw-bolder">Filter Options</div>
								</div>
								<div class="separator border-gray-200"></div>
								<div class="px-7 py-5">
						';
						include_once($filefilter);
						echo '
									<div class="d-flex justify-content-end">
										<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
										<button type="button" class="btn btn-sm btn-primary btn-apply" data-kt-menu-dismiss="true">Apply</button>
									</div>
								</div>
							</div>
						';
					}
				}
			?>
		</div>
	</div>
</div>
