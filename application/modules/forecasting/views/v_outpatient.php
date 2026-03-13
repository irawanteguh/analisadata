<div class="row gy-5 g-xl-8 mb-xl-8">

	<div class="row g-4">

		<!-- MAE -->
		<div class="col-xl-4 col-md-6">
			<div class="card bg-info text-white card-xl-stretch">
				<div class="card-body py-3 px-4 pb-50">

					<div class="d-flex justify-content-between align-items-center">
						<div>
							<div class="fw-bolder fs-1 text-white">MAE</div>
							<div class="text-white small">Mean Absolute Error</div>
						</div>

						<i class="fa-solid fa-ruler fa-3x text-white"></i>
					</div>

					<div class="text-center my-3">
						<div class="fw-bolder text-white" style="font-size:3rem;" id="forecast_mae">0</div>
					</div>

					<div class="text-white small fst-italic text-center">
						Rata-rata selisih absolut antara nilai prediksi dan aktual
					</div>

				</div>
			</div>
		</div>


		<!-- RMSE -->
		<div class="col-xl-4 col-md-6">
			<div class="card bg-success text-white card-xl-stretch">
				<div class="card-body py-3 px-4 pb-4">

					<div class="d-flex justify-content-between align-items-center">
						<div>
							<div class="fw-bolder fs-1 text-white">RMSE</div>
							<div class="text-white small">Root Mean Square Error</div>
						</div>

						<i class="fa-solid fa-square-root-variable fa-3x text-white"></i>
					</div>

					<div class="text-center my-3">
						<div class="fw-bolder text-white" style="font-size:3rem;" id="forecast_rmse">0</div>
					</div>

					<div class="text-white small fst-italic text-center">
						Mengukur besar kesalahan prediksi dengan penekanan pada error besar
					</div>

				</div>
			</div>
		</div>


		<!-- MAPE -->
		<div class="col-xl-4 col-md-6">
			<div class="card bg-warning text-white card-xl-stretch">
				<div class="card-body py-3 px-4 pb-4">

					<div class="d-flex justify-content-between align-items-center">
						<div>
							<div class="fw-bolder fs-1 text-white">MAPE</div>
							<div class="text-white small">Mean Absolute Percentage Error</div>
						</div>

						<i class="fa-solid fa-percent fa-3x text-white"></i>
					</div>

					<div class="text-center my-3">
						<div class="fw-bolder text-white" style="font-size:3rem;" id="forecast_mape">0</div>
					</div>

					<div class="text-white small fst-italic text-center">
						Persentase rata-rata kesalahan antara prediksi dan nilai aktual
					</div>

				</div>
			</div>
		</div>

	</div>

	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-body">
				<div id="grafikforecastingoutatient"></div>
			</div>
		</div>
	</div>
</div>