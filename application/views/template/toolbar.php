<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><?= $pageTitle; ?></h1>
    <span class="h-20px border-gray-200 border-start mx-4"></span>
    <?php
        $directory = APPPATH.'modules/'.$this->uri->segment(1).'/breadcrumb/'.$this->uri->segment(2).".php";
        if(file_exists($directory)){
            include($directory);
        }

        $directory = APPPATH.'modules/'.$this->uri->segment(1).'/progress/'.$this->uri->segment(2).".php";
        if(file_exists($directory)){
            include($directory);
        }

        $directory = APPPATH.'modules/'.$this->uri->segment(1).'/team/'.$this->uri->segment(2).".php";
        if(file_exists($directory)){
            include($directory);
        }
    ?>
</div>

<div class="d-flex align-items-center py-1">
    <?php
        $directory = APPPATH.'modules/'.$this->uri->segment(1).'/filter/'.$this->uri->segment(2).".php";
        if (file_exists($directory)) {
            ob_start();
            include($directory);
            $filter_html = ob_get_clean();

            // Cek apakah hasil include memiliki HTML
            if (trim($filter_html) !== '' && preg_match('/<[^>]+>/', $filter_html)) {

                echo '
                    <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder"
                    data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end">

                        <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 8.5 15.1223 8.5 16.1819V19.5072C8.5 20.2189 9.2223 20.7028 9.8805 20.432L12.8805 19.1977C13.2553 19.0435 13.5 18.6783 13.5 18.273V13.8372C13.5 12.8089 13.8171 11.8056 14.408 10.964L18.8943 4.57465C19.3596 3.912 18.8856 3 18.0759 3Z"
                                    fill="black" />
                            </svg>
                        </span>

                        Filter
                    </a>

                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                        data-kt-menu="true"
                        id="kt_menu_61484bf6e3ff8">

                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bolder">
                                Filter Options
                            </div>
                        </div>

                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5">
                ';

                // Tampilkan HTML hasil include
                echo $filter_html;

                echo '
                            <div class="d-flex justify-content-end">
                                <button type="reset"
                                        class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">
                                    Reset
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-primary btn-apply"
                                        data-kt-menu-dismiss="true">
                                    Apply
                                </button>
                            </div>

                        </div>
                    </div>
                ';
            }
        }

        $directory = APPPATH.'modules/'.$this->uri->segment(1).'/toolbar/'.$this->uri->segment(2).".php";
        if(file_exists($directory)){
            include($directory);
        }
    ?>
</div>