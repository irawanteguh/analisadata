<div class="col-xl-12">
    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6 fa-fade d-none" id="noticeTargetKurang">
        <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"></rect>
                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"></rect>
            </svg>
        </span>
        <div class="d-flex flex-stack flex-grow-1">
            <div class="fw-bold">
                <h4 class="text-gray-900 fw-bolder">Perhatian!</h4>
                <div class="fs-6 text-gray-700">
                    Rencana Target Pendapatan Harian
                    <span class="fw-bolder text-danger">belum mencapai</span>
                    Target Pendapatan Harian.
                </div>
            </div>
        </div>
    </div>

    <div class="card card-xxl-stretch mb-5 mb-xl-10" style="background-color: #CBD4F4">
        <div class="card-body d-flex flex-column">
            <div class="d-flex flex-column mb-7">
                <a href="#" class="text-dark text-hover-primary fw-bolder fs-3">PPC <?php echo $_SESSION['namaunit']?></a>
            </div>

            <div class="row g-0">

                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <div class="symbol-label bg-white bg-opacity-50">
                                <span class="svg-icon svg-icon-1 svg-icon-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M22 8H8L12 4H19C19.6 4 20.2 4.39999 20.5 4.89999L22 8ZM3.5 19.1C3.8 19.7 4.4 20 5 20H12L16 16H2L3.5 19.1ZM19.1 20.5C19.7 20.2 20 19.6 20 19V12L16 8V22L19.1 20.5ZM4.9 3.5C4.3 3.8 4 4.4 4 5V12L8 16V2L4.9 3.5Z" fill="black"></path>
                                        <path d="M22 8L20 12L16 8H22ZM8 16L4 12L2 16H8ZM16 16L12 20L16 22V16ZM8 8L12 4L8 2V8Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fs-5 text-dark fw-bolder lh-1">Rp. <?php echo number_format($_SESSION['target'], 0, ',', '.'); ?></div>
                            <div class="fs-7 text-gray-600 fw-bold">Target Tahunan RS</div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <div class="symbol-label bg-white bg-opacity-50">
                                <span class="svg-icon svg-icon-1 svg-icon-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M8 22C7.4 22 7 21.6 7 21V9C7 8.4 7.4 8 8 8C8.6 8 9 8.4 9 9V21C9 21.6 8.6 22 8 22Z" fill="black"></path>
                                        <path opacity="0.3" d="M4 15C3.4 15 3 14.6 3 14V6C3 5.4 3.4 5 4 5C4.6 5 5 5.4 5 6V14C5 14.6 4.6 15 4 15ZM13 19V3C13 2.4 12.6 2 12 2C11.4 2 11 2.4 11 3V19C11 19.6 11.4 20 12 20C12.6 20 13 19.6 13 19ZM17 16V5C17 4.4 16.6 4 16 4C15.4 4 15 4.4 15 5V16C15 16.6 15.4 17 16 17C16.6 17 17 16.6 17 16ZM21 18V10C21 9.4 20.6 9 20 9C19.4 9 19 9.4 19 10V18C19 18.6 19.4 19 20 19C20.6 19 21 18.6 21 18Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fs-5 text-dark fw-bolder lh-1">Rp. <?php echo number_format($_SESSION['targetppc'], 0, ',', '.'); ?></div>
                            <div class="fs-7 text-gray-600 fw-bold">Target Tahunan PPC</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <div class="symbol-label bg-white bg-opacity-50">
                                <span class="svg-icon svg-icon-1 svg-icon-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M11.425 7.325C12.925 5.825 15.225 5.825 16.725 7.325C18.225 8.825 18.225 11.125 16.725 12.625C15.225 14.125 12.925 14.125 11.425 12.625C9.92501 11.225 9.92501 8.825 11.425 7.325ZM8.42501 4.325C5.32501 7.425 5.32501 12.525 8.42501 15.625C11.525 18.725 16.625 18.725 19.725 15.625C22.825 12.525 22.825 7.425 19.725 4.325C16.525 1.225 11.525 1.225 8.42501 4.325Z" fill="black"></path>
                                        <path d="M11.325 17.525C10.025 18.025 8.425 17.725 7.325 16.725C5.825 15.225 5.825 12.925 7.325 11.425C8.825 9.92498 11.125 9.92498 12.625 11.425C13.225 12.025 13.625 12.925 13.725 13.725C14.825 13.825 15.925 13.525 16.725 12.625C17.125 12.225 17.425 11.825 17.525 11.325C17.125 10.225 16.525 9.22498 15.625 8.42498C12.525 5.32498 7.425 5.32498 4.325 8.42498C1.225 11.525 1.225 16.625 4.325 19.725C7.425 22.825 12.525 22.825 15.625 19.725C16.325 19.025 16.925 18.225 17.225 17.325C15.425 18.125 13.225 18.225 11.325 17.525Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fs-5 text-dark fw-bolder lh-1">Rp. <?php echo number_format($_SESSION['targetharian'], 0, ',', '.'); ?></div>
                            <div class="fs-7 text-gray-600 fw-bold">Target Pendapatan Harian</div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <div class="symbol-label bg-white bg-opacity-50">
                                <span class="svg-icon svg-icon-1 svg-icon-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M2 11.7127L10 14.1127L22 11.7127L14 9.31274L2 11.7127Z" fill="black"></path>
                                        <path opacity="0.3" d="M20.9 7.91274L2 11.7127V6.81275C2 6.11275 2.50001 5.61274 3.10001 5.51274L20.6 2.01274C21.3 1.91274 22 2.41273 22 3.11273V6.61273C22 7.21273 21.5 7.81274 20.9 7.91274ZM22 16.6127V11.7127L3.10001 15.5127C2.50001 15.6127 2 16.2127 2 16.8127V20.3127C2 21.0127 2.69999 21.6128 3.39999 21.4128L20.9 17.9128C21.5 17.8128 22 17.2127 22 16.6127Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fs-5 text-dark fw-bolder lh-1 totalrencanapendapatan">0</div>
                            <div class="fs-7 text-gray-600 fw-bold">Rencana Target Pendapatan Harian</div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>