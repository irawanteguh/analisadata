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
				<?php 
					include_once(APPPATH."views/template/aside.php");  
				?>
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<?php 
						include_once(APPPATH."views/template/header.php");  
					?>
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<?php 
							include_once(APPPATH."views/template/toolbar.php");  
						?>
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<div id="kt_content_container" class="container-fluid">
								<?php
									$segment   = $this->uri->segment(1);
									$directory = APPPATH.'modules/'.$segment.'/notification/'.$this->uri->segment(2).".php";
									if(file_exists($directory)){
										include($directory);
									}

									if($this->uri->segment(1) === "ebitda"){
										include(APPPATH."views/template/ebitda.php");
									}

									echo $contents
								?>
							</div>
						</div>
					</div>
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<div class="text-dark order-2 order-md-1">
								<div class="text-muted"> 2024 &copy; Copyright Data Analyst For Use <a href="https://rsudpasarminggu.jakarta.go.id/" target="_blank">RSUD Pasar Minggu</a> | Page rendered in <strong>{elapsed_time}</strong> seconds. | Ip Address : <strong><?php echo $_SERVER['REMOTE_ADDR']?></strong></div>
							</div>
							<div><a href="#">Privacy Policy</a>&middot;<a href="#">Terms &amp; Conditions</a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php

			$directory = APPPATH.'modules/'.$this->uri->segment(1).'/modal/'.$this->uri->segment(2).".php";
			if(file_exists($directory)){
				include($directory);
			}	
			
			$directory = APPPATH.'modules/'.$this->uri->segment(1).'/drawer/'.$this->uri->segment(2).".php";
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