import { cityList } from "./cities.js";

$(document).ready(function () {
    let currentDate = new Date();
    let selectedDay = null;

    function getMonthNames() {
        return [
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
    }

    function getTodayAndTenDaysLater() {
        const today = new Date();
        const tenDaysLater = new Date(today);
        tenDaysLater.setDate(today.getDate() + 10);
        return { today, tenDaysLater };
    }

    function renderCalendar() {
        const monthNames = getMonthNames();
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

        const $daysContainer = $("#days");
        $daysContainer.empty();

        // Set nama bulan
        $("#month-name").text(
            `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`
        );

        const today = new Date();
        const tenDaysLater = new Date(today);
        tenDaysLater.setDate(today.getDate() + 10);

        // Isi tanggal penuh (kuota sudah 2) dari backend
        const fullQuotaDates = window.fullQuotaDates || []; // Format: ['2025-07-10', ...]

        // Spasi awal sebelum tanggal 1
        for (let i = 0; i < firstDay; i++) {
            $("<div>").addClass("text-center text-xs").appendTo($daysContainer);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = $("<div>").addClass(
                "text-center border border-black py-1 px-2 rounded-lg cursor-pointer text-xs h-8 flex items-center justify-center"
            );

            const currentDay = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                day
            );
            const dateString =
                currentDay.getFullYear() +
                "-" +
                String(currentDay.getMonth() + 1).padStart(2, "0") +
                "-" +
                String(currentDay.getDate()).padStart(2, "0");
            const dayOfWeek = currentDay.getDay();
            const isWeekend = dayOfWeek === 0;
            const isPastDate = currentDay < today;
            const isWithinNextTenDays =
                currentDay <= tenDaysLater && currentDay > today;
            const isToday =
                day === today.getDate() &&
                currentDate.getMonth() === today.getMonth() &&
                currentDate.getFullYear() === today.getFullYear();

            const isFullQuota = fullQuotaDates.includes(dateString);

            // Logika tampilan kalender
            if (isToday) {
                dayCell.addClass("bg-violet-400 text-white");
                dayCell.css("pointer-events", "none");
            } else if (isPastDate || isWithinNextTenDays || isWeekend) {
                dayCell.addClass(
                    "bg-gray-300 text-gray-500 cursor-not-allowed"
                );
                dayCell.css("pointer-events", "none");
            } else {
                dayCell.on("click", function () {
                    selectedDay = day;
                    enableBookingButton();

                    $("#days div.bg-blue-500")
                        .removeClass("bg-blue-500 text-white")
                        .addClass("bg-white text-black");

                    dayCell
                        .removeClass("bg-white")
                        .addClass("bg-blue-500 text-white");
                });
            }

            dayCell.text(day);
            $daysContainer.append(dayCell);
        }

        if (selectedDay === null) {
            disableBookingButton();
        }
    }

    function enableBookingButton() {
        const $button = $("#booking-button");
        $button.removeClass("bg-gray-300 cursor-not-allowed");
        $button.addClass("bg-blue-500 cursor-pointer");
        $button.prop("disabled", false);
    }

    function disableBookingButton() {
        const $button = $("#booking-button");
        $button.removeClass("bg-blue-500 cursor-pointer");
        $button.addClass("bg-gray-300 cursor-not-allowed");
        $button.prop("disabled", true);
    }

    function showBookingDialog() {
        if (selectedDay === null) {
            alert("Harap pilih tanggal terlebih dahulu!");
            return;
        }
        const bookingDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            selectedDay
        ).toLocaleDateString("en-CA");

        Swal.fire({
            title: `Konfirmasi pendaftaran untuk tanggal ${selectedDay} ${
                getMonthNames()[currentDate.getMonth()]
            } ${currentDate.getFullYear()}?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Register Pelatihan!",
            cancelButtonText: "Batal",
            html: `
            <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-700">Pilih Pelatihan:</label>
            <div class="border shadow-lg rounded-lg overflow-y-auto max-h-44 bg-white">
                <div id="training-list" class="flex flex-col gap-2 mx-auto w-[300px] mt-2 mb-2">
                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="TKPK1"
                        style="background-image: url('/img/tkpk1_training.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                        <div class="relative z-10 text-white">
                            TKPK 1
                            <div class="text-xs text-white">Pelatihan Tenaga Kerja Pada Ketinggian Tingkat I</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="TKPK2"
                        style="background-image: url('/img/tkpk2_training.png'); background-size: cover; background-position: center center; background-repeat: no-repeat; height: 80px;">

                        <div class="relative z-10 text-white">
                            TKPK 2
                            <div class="text-xs text-white">Pelatihan Tenaga Kerja Pada Ketinggian Tingkat II</div>
                        </div>

                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>


                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="TKBT1"
                        style="background-image: url('/img/tkbt1_training.png'); background-size: cover; background-position: center;">
                        <div class="relative z-10 text-white">
                            TKBT 1
                            <div class="text-xs text-white">Pelatihan Tenaga Kerja Bangunan Tinggi I</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="TKBT2"
                        style="background-image: url('/img/tkbt2_training.png'); background-size: cover; background-position: center;">
                        <div class="relative z-10 text-white">
                            TKBT 2
                            <div class="text-xs text-white">Pelatihan Tenaga Kerja Bangunan Tinggi II</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="BE"
                        style="background-image: url('/img/be_training.png'); background-size: cover; background-position: center;">
                        <div class="relative z-10 text-white">
                            Basic Electrical
                            <div class="text-xs text-white">Pelatihan Basic Electrical</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="P3K"
                        style="background-image: url('/img/p3k_training.png'); background-size: cover; background-position: center;">
                        <div class="relative z-10 text-white">
                            First Aid (P3K)
                            <div class="text-xs text-white">Pelatihan Pertolongan Pertama Pada Kecelakaan (P3K)</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                    <div class="training-option px-4 py-2 border rounded-lg cursor-pointer text-white relative overflow-hidden"
                        data-value="AK3U"
                        style="background-image: url('/img/ak3u_training.png'); background-size: cover; background-position: center;">
                        <div class="relative z-10 text-white">
                            AK3U
                            <div class="text-xs text-white">Pelatihan Ahli K3 Umum</div>
                        </div>
                        <div class="absolute inset-0 bg-black/50 z-0"></div>
                    </div>

                </div>
            </div>

            <div class="border shadow-lg rounded-lg overflow-y-auto max-h-44 bg-white mt-2 mb-2">
            <label class="block mt-2 mb-2 text-sm font-bold text-gray-900 dark:text-gray-700">Tempat
                Pelatihan:</label>
            <div class="flex items-center justify-center min-h-5">
                <div class="flex items-center me-4">
                    <input id="radio-online" type="radio" value="Online" name="training-mode"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" />
                    <label for="radio-online"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">Online</label>
                </div>
                <div class="flex items-center me-4">
                    <input id="radio-offline" type="radio" value="On-Site" name="training-mode"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" />
                    <label for="radio-offline"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">On-Site</label>
                </div>
                <div class="flex items-center me-4">
                    <input id="radio-blended" type="radio" value="Blended" name="training-mode"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" />
                    <label for="radio-blended"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">Blended</label>
                </div>
            </div>
            

            <div id="city-select-container" class="mt-4 hidden">
            <label for="select-city" class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-700">
                Pilih Kota Pelatihan (Blended):
            </label>
            <div class="flex justify-center">
            <select id="select-city" class="block w-[300px] text-sm font-bold mb-2 px-2 py-1 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Kota --</option>
            </select>
            </div>

        </div>
        </div>
            `,
            didOpen: () => {
                $("#select-city").html(
                    '<option value="">-- Pilih Kota --</option>' +
                        cityList
                            .map(
                                (city) =>
                                    `<option value="${city}">${city}</option>`
                            )
                            .join("")
                );
                // Handler untuk memilih training
                $("#training-list .training-option").on("click", function () {
                    $("#training-list .training-option").removeClass(
                        "ring-2 ring-red-700 animate-pulse"
                    );
                    $(this).addClass("ring-2 ring-red-700 animate-pulse");
                    $("#training-list").data("selected", $(this).data("value"));
                    updateRadioStateCustom($(this).data("value"));
                });

                $('input[name="training-mode"]').on("change", function () {
                    const selectedMode = $(this).val();
                    if (selectedMode === "Blended") {
                        $("#city-select-container").removeClass("hidden");
                    } else {
                        $("#city-select-container").addClass("hidden");
                        $("#select-city").val(""); // reset pilihan kota jika bukan blended
                    }
                });

                // Set default (pilih pertama)
                const $first = $("#training-list .training-option").first();
                $first.addClass("ring-2 ring-red-700 animate-pulse");
                $("#training-list").data("selected", $first.data("value"));
                updateRadioStateCustom($first.data("value"));
            },
            preConfirm: () => {
                const selectedCity =
                    document.getElementById("select-city")?.value || null;
                return {
                    city: selectedCity,
                };
            },
        }).then(handleBookingDialogConfirm(bookingDate));
    }

    function updateRadioStateCustom(training) {
        const online = document.getElementById("radio-online");
        const offline = document.getElementById("radio-offline");
        const blended = document.getElementById("radio-blended");

        online.disabled = false;
        offline.disabled = false;
        blended.disabled = false;

        online.checked = false;
        offline.checked = false;
        blended.checked = false;

        if (["TKPK1", "TKPK2", "TKBT1", "TKBT2"].includes(training)) {
            blended.checked = true;
            online.disabled = true;
            offline.disabled = true;
            $(blended).trigger("change");
        } else if (training === "AK3U") {
            online.checked = true;
            offline.disabled = true;
            blended.disabled = true;
            $(online).trigger("change");
        } else {
            $(online).trigger("change");
        }
    }

    function handleBookingDialogConfirm(bookingDate) {
        return (result) => {
            if (result.isConfirmed) {
                const trainingType = $("#training-list").data("selected");
                const progres = "1";
                const trainingPlace = $(
                    'input[name="training-mode"]:checked'
                ).val();
                const city = $("#select-city").val(); // ambil kota jika ada

                if (!trainingPlace) {
                    Swal.fire({
                        icon: "warning",
                        title: "Tempat Pelatihan Belum Dipilih!",
                        text: "Silakan pilih 'Online', 'Offline', atau 'Blended' sebelum melanjutkan.",
                        confirmButtonText: "OK",
                    });
                    return;
                }

                confirmBooking(
                    trainingType,
                    bookingDate,
                    progres,
                    trainingPlace,
                    city
                );
            }
        };
    }

    function confirmBooking(
        trainingType,
        formattedDate,
        progres,
        trainingPlace,
        city
    ) {
        const typesWithCity = ["TKPK1", "TKPK2", "TKBT1", "TKBT2"];
        Swal.fire({
            title: "Konfirmasi Jadwal Pelatihan",
            html: `Tanggal Pelatihan: <strong>${selectedDay} ${
                getMonthNames()[currentDate.getMonth()]
            } ${currentDate.getFullYear()}</strong><br>Jenis Pelatihan: <strong>${trainingType}</strong> ${
                typesWithCity.includes(trainingType)
                    ? `<br>Kota: <strong>${city || "Belum memilih"}</strong>`
                    : ""
            }
            `,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "OK",
            cancelButtonText: "Batal",
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                processBooking(
                    trainingType,
                    formattedDate,
                    progres,
                    trainingPlace,
                    city
                );
                startConfirmationCountdown();
            }
        });
    }

    function processBooking(
        trainingType,
        formattedDate,
        progres,
        trainingPlace,
        city
    ) {
        Swal.fire({
            title: "Memproses...",
            html: "Mohon tunggu sebentar",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: "/dashboard/booking",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                date: formattedDate,
                activity: trainingType,
                isprogress: progres,
                place: trainingPlace,
                city: city,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Pendaftaran Berhasil!",
                        text: `Jadwal Pelatihan ${trainingType} untuk tanggal ${selectedDay} ${
                            getMonthNames()[currentDate.getMonth()]
                        } ${currentDate.getFullYear()} berhasil dibuat!`,
                    }).then(() => {
                        setTimeout(() => {
                            window.location.href =
                                "/dashboard/user/training/form/" + response.id;
                        }, 1000);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Membuat Jadwal Pelatihan",
                        text:
                            response.message ||
                            "Terjadi kesalahan saat membuat jadwal booking.",
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan",
                    text: "Silakan coba lagi.",
                });
            },
        });
    }
    function startConfirmationCountdown() {
        let countdown = 30;
        const confirmButton = Swal.getConfirmButton();
        const countdownInterval = setInterval(() => {
            if (countdown > 0) {
                confirmButton.textContent = `OK (${countdown})`;
                countdown--;
            } else {
                clearInterval(countdownInterval);
                confirmButton.click();
            }
        }, 1000);
    }

    function loadLiveTrainings() {
        fetch("/dashboard/user/live-data")
            .then((response) => response.json())
            .then((result) => {
                if (!result.success) return;
                const $tableBody = $("tbody");
                $tableBody.empty();
                result.data.forEach((item) => {
                    const $tr = $("<tr>")
                        .addClass(
                            "odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose"
                        )
                        .click(() => (window.location = item.url)).html(`
                <td>${item.no}</td>
                <td>
                    ${item.activity}
                    ${
                        item.isNew
                            ? '<img src="/img/gif/new.gif" class="w-5 h-3 -mt-3 inline-block">'
                            : ""
                    }
                    ${
                        !item.isNew && item.isUpdated
                            ? '<img src="/img/gif/update.gif" class="w-5 h-3 -mt-3 inline-block">'
                            : ""
                    }
                </td>
                <td>${item.status}</td>
                <td>${item.date}</td>
                <td>
                    <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                        <div class="${
                            item.progress_color
                        } text-[8px] font-medium text-white text-center leading-none rounded-full"
                            style="width: ${
                                item.progress_percent
                            }%; height:8px">
                            ${item.progress_percent}%
                        </div>
                    </div>
                </td>
                `);
                    $tableBody.append($tr);
                });
            })
            .catch((err) => console.error("Gagal memuat data:", err));
    }

    // ========== INIT & EVENTS ==========
    $("#next-month").on("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    $("#prev-month").on("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    $("#booking-button").on("click", function () {
        showBookingDialog();
    });

    renderCalendar();
    loadLiveTrainings();
    setInterval(loadLiveTrainings, 230000); // setiap ~4 menit
});
