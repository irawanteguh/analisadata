<!DOCTYPE html>
<html lang="en">
	<head>
		<?php          
			include_once(APPPATH."views/template/head.php");
		?>
	</head>
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
				<div id="kt_aside" class="aside aside-light aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
					<?php          
						include_once(APPPATH."views/template/aside.php");
					?>
				</div>
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<div id="kt_header" style="" class="header align-items-stretch">
						<?php          
							include_once(APPPATH."views/template/header.php");
						?>
					</div>
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<div class="toolbar" id="kt_toolbar">
							<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
								<?php          
									include_once(APPPATH."views/template/toolbar.php");
								?>
							</div>
						</div>
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<div id="kt_content_container" class="container-fluid fs-8">
								<?php
									if($this->uri->segment(1) == 'organization' || $this->uri->segment(1) == 'hr'){
										include_once(APPPATH . 'views/template/headorganization.php');
									}
									echo $contents;
								?>
							</div>
						</div>
					</div>
					<?php          
						include_once(APPPATH."views/template/footer.php");
					?>
				</div>
			</div>
		</div>
		<?php
			$drawer = APPPATH.'views/drawer/';
			if (is_dir($drawer)) {
				$files = glob($drawer . '*.php');
				foreach ($files as $file){
					include($file);
				}
			}

			$explore = APPPATH.'views/explore/';
			if (is_dir($explore)) {
				$files = glob($explore . '*.php');
				foreach ($files as $file){
					include($file);
				}
			}

			$dirmodal = APPPATH.'views/modal/';
			if (is_dir($dirmodal)) {
				$files = glob($dirmodal . '*.php');
				foreach ($files as $file){
					include($file);
				}
			}

			$directory = APPPATH.'modules/'.$this->uri->segment(1).'/drawer/'.$this->uri->segment(2).".php";
			if(file_exists($directory)){
				include($directory);
			}

			$directory = APPPATH.'modules/'.$this->uri->segment(1).'/explore/'.$this->uri->segment(2).".php";
			if(file_exists($directory)){
				include($directory);
			}

			$directory = APPPATH.'modules/'.$this->uri->segment(1).'/modal/'.$this->uri->segment(2).".php";
			if(file_exists($directory)){
				include($directory);
			}
		?>
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
				</svg>
			</span>
		</div>
		<?php          
			include_once(APPPATH."views/template/script.php");
		?>
	</body>
</html>