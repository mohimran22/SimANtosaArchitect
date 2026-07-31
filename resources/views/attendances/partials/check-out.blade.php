                            <div class="text-center">
                                <h2 class="text-success mb-3">
                                    ✅ Sudah Hadir
                                </h2>
                                <div class="row mt-4">

                                    <div class="col">
                                        <small class="text-secondary">Jam Masuk</small>
                                        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
                                    </div>

                                    <div class="col">
                                        <small class="text-secondary">Jam Pulang</small>
                                        <h4>--:--</h4>
                                    </div>

                                </div>

                                <button
                                    class="btn btn-danger btn-lg rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#checkOutModal">

                                    <i class="ti ti-logout me-2"></i>
                                    Pulang

                                </button>

                            </div>