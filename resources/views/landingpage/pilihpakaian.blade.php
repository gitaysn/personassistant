<section class="py-5" id="pilihpakaian">
    <div class="bg-holder d-none d-sm-block" style="background-image:url(assets/img/illustrations/bg.png);background-position:top left;background-size:225px 755px;margin-top:-17.5rem;"></div>

    <div class="container my-5">
        <form action="{{ route('proses.rekomendasi') }}" method="POST">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold fs-3 fs-lg-5 lh-sm">Pilih Pakaian Anda</h5>
                        <p class="text-muted">Untuk mendapatkan rekomendasi pakaian yang paling sesuai dengan gaya dan kebutuhan Anda, silakan isi beberapa pertanyaan berikut.</p>
                    </div>

                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5 bg-light">
                            <!-- Progress Bar -->
                            <div class="progress mb-4">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 20%;" id="progressBar"></div>
                            </div>

                            <!-- STEP 1 -->
                            <div class="step active" id="step-1">
                                <h5 class="fw-bold mb-3">Pilih jenis acara yang akan kamu hadiri...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="acara1" value="Formal" name="jenis_acara">
                                    <label class="form-check-label" for="acara1">Formal</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="acara2" value="NonFormal" name="jenis_acara">
                                    <label class="form-check-label" for="acara2">Non Formal</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="acara3" value="Casual" name="jenis_acara">
                                    <label class="form-check-label" for="acara3">Casual</label>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button>
                            </div>

                            <!-- STEP 2 -->
                            <div class="step" id="step-2">
                                <h5 class="fw-bold mb-3">Tentukan rentang harga pakaian...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="harga1" value="80000-100000" name="harga">
                                    <label class="form-check-label" for="harga1">Rp80.000 - 100.000</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="harga2" value="101000-150000" name="harga">
                                    <label class="form-check-label" for="harga2">Rp101.000 - 150.000</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="harga3" value="151000-200000" name="harga">
                                    <label class="form-check-label" for="harga3">Rp151.000 - 200.000</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="harga4" value="201000-300000" name="harga">
                                    <label class="form-check-label" for="harga4">Rp201.000 - 300.000</label>
                                </div>
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Kembali</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button>
                            </div>

                            <!-- STEP 3 -->
                            <div class="step" id="step-3">
                                <h5 class="fw-bold mb-3">Pilih jenis pakaian...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="pakaian1" value="Dress" name="jenis_pakaian">
                                    <label class="form-check-label" for="pakaian1">Dress</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="pakaian2" value="Blouse" name="jenis_pakaian">
                                    <label class="form-check-label" for="pakaian2">Blouse</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="pakaian3" value="Cardigan" name="jenis_pakaian">
                                    <label class="form-check-label" for="pakaian3">Cardigan</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="pakaian4" value="Rok" name="jenis_pakaian">
                                    <label class="form-check-label" for="pakaian4">Rok</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="pakaian5" value="Celana" name="jenis_pakaian">
                                    <label class="form-check-label" for="pakaian5">Celana</label>
                                </div>
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Kembali</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button>
                            </div>

                            <!-- STEP 4 -->
                            <div class="step" id="step-4">
                                <h5 class="fw-bold mb-3">Pilih warna pakaian...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="warna1" value="Dingin" name="warna">
                                    <label class="form-check-label" for="warna1">Warna Dingin</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="warna2" value="Panas" name="warna">
                                    <label class="form-check-label" for="warna2">Warna Panas</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="warna3" value="Netral" name="warna">
                                    <label class="form-check-label" for="warna3">Warna Netral</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="warna4" value="Lembut" name="warna">
                                    <label class="form-check-label" for="warna4">Warna Lembut</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="warna5" value="Pastel" name="warna">
                                    <label class="form-check-label" for="warna5">Warna Pastel</label>
                                </div>
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Kembali</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button>
                            </div>

                            <!-- STEP 5 -->
                            <div class="step" id="step-5">
                                <h5 class="fw-bold mb-3">Pilih kondisi cuaca...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="cuaca1" value="Cerah" name="cuaca">
                                    <label class="form-check-label" for="cuaca1">Cerah</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="cuaca2" value="Berawan" name="cuaca">
                                    <label class="form-check-label" for="cuaca2">Berawan</label>
                                </div>
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Kembali</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button>
                            </div>

                            <!-- STEP 6 -->
                            <div class="step" id="step-6">
                                <h5 class="fw-bold mb-3">Pilih lokasi acara...</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="lokasi1" value="Indoor" name="lokasi">
                                    <label class="form-check-label" for="lokasi1">Indoor</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="lokasi2" value="Outdoor" name="lokasi">
                                    <label class="form-check-label" for="lokasi2">Outdoor</label>
                                </div>
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Kembali</button>
                                <button type="submit" class="btn btn-success">Hasil Rekomendasi</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 6;

        function showStep(step) {
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById(`step-${i}`).classList.remove('active');
            }
            document.getElementById(`step-${step}`).classList.add('active');
            updateProgressBar(step);
        }

        function nextStep() {
            const radios = document.querySelectorAll(`#step-${currentStep} input[type="radio"]`);
            const isAnswered = Array.from(radios).some(radio => radio.checked);

            if (!isAnswered) {
                alert("Silakan isi pertanyaan ini terlebih dahulu.");
                return;
            }

            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function updateProgressBar(step) {
            const progressBar = document.getElementById('progressBar');
            progressBar.style.width = (step / totalSteps) * 100 + '%';
        }

        showStep(currentStep);
    </script>

    <div id="hasil-rekomendasi" style="margin-top: 20px;"></div>
</section>
