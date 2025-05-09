document.addEventListener("DOMContentLoaded", function () {
    let currentDate = new Date();
    let selectedDay = null;

    // Fungsi render kalender
    function renderCalendar() {
        const monthNames = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        const daysInMonth = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() + 1,
            0
        ).getDate();
        const firstDay = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            1
        ).getDay();

        document.getElementById("month-name").textContent = `${
            monthNames[currentDate.getMonth()]
        } ${currentDate.getFullYear()}`;

        const daysContainer = document.getElementById("days");
        daysContainer.innerHTML = "";

        const today = new Date();
        const tenDaysLater = new Date(today);
        tenDaysLater.setDate(today.getDate() + 10);

        // Menampilkan hari-hari di kalender
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("text-center", "text-xs");
            daysContainer.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.classList.add(
                "text-center",
                "py-1",
                "px-2",
                "rounded-lg",
                "cursor-pointer",
                "text-xs",
                "h-8",
                "flex",
                "items-center",
                "justify-center"
            );

            const currentDay = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                day
            );
            const dayOfWeek = currentDay.getDay();
            const isWeekend = dayOfWeek === 0;
            // const isPastDate =
            //     currentDay < today && currentDay.getMonth() === today.getMonth();
            const isPastDate = currentDay < today;

            const isWithinNextTenDays =
                currentDay <= tenDaysLater && currentDay > today;
            const isToday =
                day === today.getDate() &&
                currentDate.getMonth() === today.getMonth() &&
                currentDate.getFullYear() === today.getFullYear();

            // const isPreviousMonth = currentDay.getMonth() < today.getMonth() || (currentDay.getMonth() === today.getMonth() && currentDay.getDate() < today.getDate());

            if (isToday) {
                dayCell.classList.add("bg-violet-400", "text-white");
                dayCell.style.pointerEvents = "none";
            } else if (isPastDate || isWithinNextTenDays || isWeekend) {
                dayCell.classList.add(
                    "bg-gray-300",
                    "text-gray-500",
                    "cursor-not-allowed"
                );
                dayCell.style.pointerEvents = "none";
            } else {
                dayCell.addEventListener("click", () => {
                    selectedDay = day;

                    const bookingButton =
                        document.getElementById("booking-button");
                    bookingButton.classList.remove(
                        "bg-gray-300",
                        "cursor-not-allowed"
                    );
                    bookingButton.classList.add(
                        "bg-blue-500",
                        "cursor-pointer"
                    );
                    bookingButton.disabled = false;

                    const allDayCells = document.querySelectorAll("#days div");
                    allDayCells.forEach((cell) => {
                        if (
                            cell !== dayCell &&
                            cell.classList.contains("bg-blue-500")
                        ) {
                            cell.classList.remove("bg-blue-500", "text-white");
                            if (!cell.classList.contains("bg-gray-300")) {
                                cell.classList.remove("bg-white");
                                cell.classList.add("bg-white", "text-black");
                            }
                        }
                    });

                    dayCell.classList.remove("bg-white");
                    dayCell.classList.add("bg-blue-500", "text-white");
                });
            }

            dayCell.textContent = day;
            daysContainer.appendChild(dayCell);
        }

        const bookingButton = document.getElementById("booking-button");
        if (selectedDay === null) {
            bookingButton.classList.remove("bg-blue-500", "cursor-pointer");
            bookingButton.classList.add("bg-gray-300", "cursor-not-allowed");
            bookingButton.disabled = true;
        }
    }

    // Menampilkan kalender bulan berikutnya
    document.getElementById("next-month").addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Menampilkan kalender bulan sebelumnya
    document.getElementById("prev-month").addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    // Menangani klik pada tombol booking
    document.getElementById("booking-button").addEventListener("click", () => {
        if (selectedDay === null) {
            alert("Harap pilih tanggal terlebih dahulu!");
        } else {
            const bookingDate = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                selectedDay
            ).toLocaleDateString("en-CA"); // Format YYYY-MM-DD (ISO 8601)

            // Menampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: `Apakah Anda yakin ingin register tanggal ${selectedDay} ${new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth()
                ).toLocaleString("id-ID", {
                    month: "long",
                })} ${currentDate.getFullYear()}?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Register Pelatihan!",
                cancelButtonText: "Batal",
                html: `
                <label for="training-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-red-500">Pilih Pelatihan:</label>
                <select id="training-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block mx-auto w-[300px] p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="TKPK1">TKPK 1</option>
                    <option value="TKPK2">TKPK 2</option>
                    <option value="TKBT1">TKBT 1</option>
                    <option value="TKBT2">TKBT 2</option>
                    <option value="BE">Basic Electrical</option>
                    <option value="P3K">First Aid(P3K)</option>
                    <option value="AK3U">AK3U</option>
                </select>

               <label class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-red-500">Tempat Pelatihan:</label>
               <div class="flex items-center justify-center min-h-5">
                <div class="flex flex-wrap items-center justify-center border border-gray-300 rounded-lg h-12 w-[200px]">
                <div class="flex me-4 ">
                        <input id="red-radio" type="radio" value="Online" name="colored-radio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="red-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Online</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="green-radio" type="radio" value="Offline" name="colored-radio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="green-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Offline</label>
                    </div>
                </div>
                </div>
                </div>
            `,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ambil jenis pelatihan yang dipilih
                    const trainingType =
                        document.getElementById("training-select").value;
                    const formattedDate = bookingDate; // Format date untuk dikirim\
                    const progres = "1";
                    const trainingPlace = document.querySelector(
                        'input[name="colored-radio"]:checked'
                    )?.value;

                    if (!trainingPlace) {
                        Swal.fire({
                            icon: "warning",
                            title: "Tempat Pelatihan Belum Dipilih!",
                            text: "Silakan pilih 'Online' atau 'Offline' sebelum melanjutkan.",
                            confirmButtonText: "OK",
                        });
                        return;
                    }
                    // Tampilkan informasi booking untuk konfirmasi lebih lanjut
                    Swal.fire({
                        title: "Konfirmasi Jadwal Pelatihan",
                        html: `Tanggal Pelatihan: <strong> ${selectedDay} ${new Date(
                            currentDate.getFullYear(),
                            currentDate.getMonth()
                        ).toLocaleString("id-ID", {
                            month: "long",
                        })} ${currentDate.getFullYear()}?</strong><br>Jenis Pelatihan: <strong>${trainingType}</strong>`,
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "OK (10)",
                        cancelButtonText: "Batal",
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            // Lakukan proses booking ke server
                            $.ajax({
                                url: "/dashboard/booking",
                                method: "POST",
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                    date: formattedDate,
                                    activity: trainingType, // Kirim jenis pelatihan yang dipilih
                                    isprogress: progres,
                                    place: trainingPlace,
                                },
                                success: function (response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Booking Berhasil!",
                                            text: `Jadwal booking untuk tanggal ${selectedDay} ${new Date(
                                                currentDate.getFullYear(),
                                                currentDate.getMonth()
                                            ).toLocaleString("default", {
                                                month: "long",
                                            })} ${currentDate.getFullYear()} berhasil dibuat!`,
                                        }).then(() => {
                                            setTimeout(() => {
                                                window.location.href =
                                                    "/dashboard/user/training/form/" +
                                                    response.id;
                                            }, 1000);
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Gagal membuat booking",
                                            text: "Terjadi kesalahan saat membuat jadwal booking.",
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error("Error:", error);
                                    Swal.fire({
                                        icon: "error",
                                        title: "Terjadi kesalahan",
                                        text: "Silakan coba lagi.",
                                    });
                                },
                            });
                        }
                    });

                    // Hitung mundur detik
                    let countdown = 10;
                    const confirmButton = Swal.getConfirmButton();

                    // Update tombol OK dengan countdown
                    const countdownInterval = setInterval(() => {
                        if (countdown > 0) {
                            confirmButton.textContent = `OK (${countdown})`; // Update tombol OK dengan sisa detik
                            countdown--;
                        } else {
                            clearInterval(countdownInterval); // Berhenti menghitung mundur setelah 0
                            confirmButton.click(); // Otomatis klik tombol OK setelah 10 detik
                        }
                    }, 1000); // Update setiap detik
                }
            });
        }
    });

    renderCalendar();
});
