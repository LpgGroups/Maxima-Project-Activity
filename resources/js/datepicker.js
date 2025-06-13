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

        $("#month-name").text(
            `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`
        );

        const $daysContainer = $("#days");
        $daysContainer.empty();

        const { today, tenDaysLater } = getTodayAndTenDaysLater();

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
            const dayOfWeek = currentDay.getDay();
            const isWeekend = dayOfWeek === 0;
            const isPastDate = currentDay < today;
            const isWithinNextTenDays =
                currentDay <= tenDaysLater && currentDay > today;
            const isToday =
                day === today.getDate() &&
                currentDate.getMonth() === today.getMonth() &&
                currentDate.getFullYear() === today.getFullYear();

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
            title: `Apakah Anda yakin ingin register tanggal ${selectedDay} ${
                getMonthNames()[currentDate.getMonth()]
            } ${currentDate.getFullYear()}?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Register Pelatihan!",
            cancelButtonText: "Batal",
            html: `
                <label for="training-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-700">Pilih Pelatihan:</label>
                <select id="training-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block mx-auto w-[300px] p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="TKPK1">TKPK 1</option>
                    <option value="TKPK2">TKPK 2</option>
                    <option value="TKBT1">TKBT 1</option>
                    <option value="TKBT2">TKBT 2</option>
                    <option value="BE">Basic Electrical</option>
                    <option value="P3K">First Aid(P3K)</option>
                    <option value="AK3U">AK3U</option>
                </select>
                <label class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-gray-700">Tempat Pelatihan:</label>
                <div class="flex items-center justify-center min-h-5">
                    <div class="flex items-center me-4">
                        <input id="radio-online" type="radio" value="Online" name="training-mode" class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"/>
                        <label for="radio-online" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">Online</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="radio-offline" type="radio" value="Offline" name="training-mode" class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"/>
                        <label for="radio-offline" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">Offline</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="radio-blended" type="radio" value="Blended" name="training-mode" class="w-4 h-4 text-blue-600 bg-gray-100 border border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"/>
                        <label for="radio-blended" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">Blended</label>
                    </div>
                </div>
            `,
            didOpen: setTrainingRadioStateHandler,
        }).then(handleBookingDialogConfirm(bookingDate));
    }

    function setTrainingRadioStateHandler() {
        const select = document.getElementById("training-select");
        const online = document.getElementById("radio-online");
        const offline = document.getElementById("radio-offline");
        const blended = document.getElementById("radio-blended");

        function updateRadioState(training) {
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
            } else if (training === "AK3U") {
                online.checked = true;
                offline.disabled = true;
                blended.disabled = true;
            }
        }
        updateRadioState(select.value);
        select.addEventListener("change", function () {
            updateRadioState(this.value);
        });
    }

    function handleBookingDialogConfirm(bookingDate) {
        return (result) => {
            if (result.isConfirmed) {
                const trainingType = $("#training-select").val();
                const progres = "1";
                const trainingPlace = $(
                    'input[name="training-mode"]:checked'
                ).val();
                if (!trainingPlace) {
                    Swal.fire({
                        icon: "warning",
                        title: "Tempat Pelatihan Belum Dipilih!",
                        text: "Silakan pilih 'Online' atau 'Offline' sebelum melanjutkan.",
                        confirmButtonText: "OK",
                    });
                    return;
                }
                confirmBooking(
                    trainingType,
                    bookingDate,
                    progres,
                    trainingPlace
                );
            }
        };
    }

    function confirmBooking(
        trainingType,
        formattedDate,
        progres,
        trainingPlace
    ) {
        Swal.fire({
            title: "Konfirmasi Jadwal Pelatihan",
            html: `Tanggal Pelatihan: <strong>${selectedDay} ${
                getMonthNames()[currentDate.getMonth()]
            } ${currentDate.getFullYear()}</strong><br>Jenis Pelatihan: <strong>${trainingType}</strong>`,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "OK (10)",
            cancelButtonText: "Batal",
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                processBooking(
                    trainingType,
                    formattedDate,
                    progres,
                    trainingPlace
                );
                startConfirmationCountdown();
            }
        });
    }

    function processBooking(
        trainingType,
        formattedDate,
        progres,
        trainingPlace
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
