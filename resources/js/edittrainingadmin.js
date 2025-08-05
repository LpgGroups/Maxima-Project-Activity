import { cityList } from "./cities.js";
import moment from "moment-timezone";
function datePicker() {
    flatpickr("#date", {
        dateFormat: "d-m-Y",
        onChange: updateEndDate, // otomatis hitung ulang end_date saat tanggal berubah
    });
}

function updateEndDate() {
    const dateInput = document.getElementById("date");
    const activityInput = document.getElementById("activity");
    const endDateInput = document.getElementById("end_date");

    const durationMap = {
        TKPK1: 6,
        TKPK2: 6,
        TKBT1: 4,
        TKBT2: 4,
        BE: 2,
        AK3U: 12,
        P3K: 3,
    };

    const startDateStr = dateInput.value;
    const activity = activityInput.value;

    if (!startDateStr || !activity) return;

    const parts = startDateStr.split("-");
    const startDate = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
    const duration = durationMap[activity] || 1;

    const endDate = new Date(startDate);
    endDate.setDate(startDate.getDate() + duration - 1);

    const day = String(endDate.getDate()).padStart(2, "0");
    const month = String(endDate.getMonth() + 1).padStart(2, "0");
    const year = endDate.getFullYear();

    endDateInput.value = `${day}-${month}-${year}`;
}

function setupConfirmationCheckbox() {
    const checkbox = document.getElementById("confirmEdit");
    const submitBtn = document.getElementById("submitBtn");
    const checkbox2 = document.getElementById("confirmEdit2");
    const submitBtn2 = document.getElementById("submitParticipantBtn");
    const checkbox3 = document.getElementById("confirmEdit3");
    const submitBtn3 = document.getElementById("submitFinish");
    // Set awal: disabled
    submitBtn.disabled = true;
    submitBtn.classList.add("bg-gray-400", "cursor-not-allowed");
    submitBtn2.disabled = true;
    submitBtn2.classList.add("bg-gray-400", "cursor-not-allowed");
    submitBtn3.disabled = true;
    submitBtn3.classList.add("bg-gray-400", "cursor-not-allowed");

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove("bg-gray-400", "cursor-not-allowed");
            submitBtn.classList.add(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.remove(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
            submitBtn.classList.add("bg-gray-400", "cursor-not-allowed");
        }
    });
    checkbox2.addEventListener("change", function () {
        if (checkbox2.checked) {
            submitBtn2.disabled = false;
            submitBtn2.classList.remove("bg-gray-400", "cursor-not-allowed");
            submitBtn2.classList.add(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
        } else {
            submitBtn2.disabled = true;
            submitBtn2.classList.remove(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
            submitBtn2.classList.add("bg-gray-400", "cursor-not-allowed");
        }
    });
    checkbox3.addEventListener("change", function () {
        if (checkbox3.checked) {
            submitBtn3.disabled = false;
            submitBtn3.classList.remove("bg-gray-400", "cursor-not-allowed");
            submitBtn3.classList.add(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
        } else {
            submitBtn3.disabled = true;
            submitBtn3.classList.remove(
                "bg-blue-600",
                "hover:bg-blue-700",
                "cursor-pointer"
            );
            submitBtn3.classList.add("bg-gray-400", "cursor-not-allowed");
        }
    });
}

function updateForm1User(event) {
    event.preventDefault(); // Hindari reload default form

    const form = document.getElementById("editFormbyAdmin");
    const formData = new FormData(form);
    const place = document.querySelector('input[name="colored-radio"]:checked');
    if (place) {
        formData.append("place", place.value);
    }

    const trainingId = form.dataset.trainingId;
    const csrfToken = document.querySelector('input[name="_token"]').value;

    showLoadingSwal(); // Tampilkan loading Swal

    fetch(`/dashboard/admin/training/${trainingId}/update`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then(async (response) => {
            const data = await response.json();

            Swal.close(); // Tutup loading Swal setelah dapat response

            if (response.ok && data.success) {
                showSuccessSwal(
                    "Berhasil",
                    "Data peserta berhasil diperbarui!"
                );
            } else {
                const message = data.message || "Gagal memperbarui data.";
                showErrorSwal("Gagal", message);
            }
        })
        .catch((error) => {
            Swal.close(); // Tutup loading meskipun error
            console.error("Error:", error);
            showErrorSwal("Kesalahan", "Terjadi kesalahan, coba lagi nanti.");
        });
}

function locationTraining() {
    $(document).ready(function () {
        var $prov = $("#provience");
        var $city = $("#city");
        var provSelected = $("#provSelected").val();
        var citySelected = $("#citySelected").val();

        $prov.html('<option value="">-- Pilih Provinsi --</option>');
        $.each(cityList, function (i, p) {
            $prov.append(
                $("<option>", { value: p.provinsi, text: p.provinsi })
            );
        });

        // Saat pilih provinsi, isi kota
        $prov.on("change", function () {
            var selectedProv = $(this).val();
            $city.html('<option value="">-- Pilih Kota --</option>');
            var provObj = cityList.find(function (p) {
                return p.provinsi === selectedProv;
            });
            if (provObj) {
                $.each(provObj.kota, function (i, kota) {
                    $city.append($("<option>", { value: kota, text: kota }));
                });
            }
            // Reset pilihan kota jika ganti provinsi
            $city.val("");
        });

        // Set selected provinsi (jika ada)
        if (provSelected) {
            $prov.val(provSelected).trigger("change");
            setTimeout(function () {
                if (citySelected) {
                    $city.val(citySelected);
                }
            }, 100);
        }
    });
}

function updateForm2User() {
    const form = document.getElementById("participantTableForm");
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(form);

    showLoadingSwal("Menyimpan...", "Mohon tunggu sebentar");

    fetch("/dashboard/admin/training/update-participant", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then(async (response) => {
            const data = await response.json();
            Swal.close();

            if (response.ok && data.success) {
                showSuccessSwal(
                    "Berhasil",
                    data.message || "Data peserta berhasil diperbarui!"
                );
            } else {
                showErrorSwal(
                    "Gagal",
                    data.message || "Gagal memperbarui data."
                );
            }
        })
        .catch((error) => {
            Swal.close();
            console.error("Error:", error);
            showErrorSwal("Kesalahan", "Terjadi kesalahan, coba lagi nanti.");
        });
}

function uploadFileForAdmin() {
    let form = $("#adminFileUploadForm")[0];
    let formData = new FormData(form);

    console.log("Mulai upload, formData:", formData);

    $("#uploadAdminFileStatus")
        .text("Uploading...")
        .removeClass("text-red-600 text-green-600");

    fetch("/dashboard/admin/upload-files", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            Accept: "application/json",
        },
        body: formData,
    })
        .then(async (res) => {
            console.log("Fetch response status:", res.status);
            const contentType = res.headers.get("content-type");
            console.log("Content-Type header:", contentType);

            let data;
            if (contentType && contentType.indexOf("application/json") !== -1) {
                data = await res.json();
            } else {
                data = { success: false, message: await res.text() };
            }

            if (res.ok && data.success) {
                $("#uploadAdminFileStatus")
                    .addClass("text-green-600")
                    .text("File berhasil diupload!");
                $("#budget_plan").val("");
                $("#letter_implementation").val("");
                $("#file_nobatch").val("");
            } else {
                $("#uploadAdminFileStatus")
                    .addClass("text-red-600")
                    .text(data.message || "Gagal upload file.");
                console.log(
                    "Gagal upload, status merah.",
                    data.message || data
                );
            }
        })
        .catch((err) => {
            console.error("Error di catch fetch:", err);
            $("#uploadAdminFileStatus")
                .addClass("text-red-600")
                .text("Gagal upload file. " + err);
        });
}

function updateTrainingFinish(event) {
    const id = event.target.dataset.formId;
    const csrfToken = document.querySelector('input[name="_token"]').value;

    Swal.fire({
        title: "Konfirmasi Penyelesaian",
        text: "Apakah Anda yakin data peserta dan pelatihan telah diperiksa dengan benar sebelum menyelesaikan pelatihan?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, selesai",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append("isprogress", 5);

            showLoadingSwal("Memproses", "Menyelesaikan pelatihan...");

            fetch(`/dashboard/admin/training/finish/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            })
                .then(async (response) => {
                    const data = await response.json();
                    Swal.close();

                    if (response.ok && data.success) {
                        showSuccessSwal(
                            "Pelatihan Diselesaikan",
                            data.message ||
                                "Data pelatihan berhasil diselesaikan."
                        );
                    } else {
                        showErrorSwal(
                            "Gagal",
                            data.message || "Gagal menyelesaikan pelatihan."
                        );
                    }
                })
                .catch((error) => {
                    Swal.close();
                    console.error("Terjadi kesalahan:", error);
                    showErrorSwal(
                        "Kesalahan",
                        "Terjadi kesalahan saat memproses permintaan."
                    );
                });
        }
    });
}

function addParticipants() {
    $("#submitParticipation").on("click", function () {
        Swal.fire({
            title: "Tambah Peserta Baru",
            input: "text",
            inputPlaceholder: "Nama Peserta",
            showCancelButton: true,
            confirmButtonText: "Tambah",
            preConfirm: (name) => {
                if (!name) {
                    Swal.showValidationMessage("Nama peserta wajib diisi!");
                    return false;
                }
                return name;
            },
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const name = result.value;
                const form_id = $("#updateParticipantsForm").data("form-id");

                // Tampilkan loading Swal sebelum request
                showLoadingSwal(
                    "Menambahkan Peserta",
                    "Mohon tunggu sebentar..."
                );

                fetch("/dashboard/admin/training/add-participant", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        form_id: form_id,
                        name: name,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            showSuccessSwal("Peserta berhasil ditambahkan!");
                        } else {
                            showErrorSwal(
                                "Gagal menambahkan peserta",
                                data.message || "Silakan coba lagi."
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showErrorSwal(
                            "Terjadi kesalahan",
                            "Silakan coba beberapa saat lagi."
                        );
                    });
            }
        });
    });
}

function initShowDetailParticipant() {
    // Reset semua dropdown detail row
    $(".showDetailBtn")
        .off("click")
        .on("click", function () {
            const id = $(this).data("id");
            const detailRow = $("#detail-row-" + id);

            // Hide semua detail-row kecuali yang diklik
            $(".detail-row").not(detailRow).addClass("hidden");
            detailRow.toggleClass("hidden");
        });
}

function deleteParticipant(id) {
    Swal.fire({
        title: "Konfirmasi Penghapusan",
        text: "Peserta yang dihapus tidak dapat dikembalikan. Anda yakin ingin melanjutkan?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingSwal("Menghapus...", "Sedang menghapus data peserta.");

            fetch(`/dashboard/admin/training/delete-participant/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            })
                .then(async (response) => {
                    const data = await response.json();
                    Swal.close();

                    if (response.ok && data.success) {
                        showSuccessSwal(
                            "Berhasil",
                            data.message || "Peserta berhasil dihapus."
                        );
                        const row = document.querySelector(
                            `[data-participant-id="${id}"]`
                        );
                        if (row) row.remove();
                    } else {
                        showErrorSwal(
                            "Gagal",
                            data.message || "Gagal menghapus peserta."
                        );
                    }
                })
                .catch((error) => {
                    Swal.close();
                    console.error("Error:", error);
                    showErrorSwal(
                        "Kesalahan",
                        "Terjadi kesalahan saat menghapus peserta."
                    );
                });
        }
    });
}

function showLoadingSwal(
    title = "Memproses...",
    text = "Mohon tunggu sebentar!!",
    duration = 10000
) {
    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
        timer: duration,
        showConfirmButton: false,
    });
}

// Tampilkan notifikasi sukses
function showSuccessSwal(title = "Berhasil", text = "Sukses") {
    Swal.fire({
        icon: "success",
        title: title,
        text: text,
        confirmButtonText: "Oke",
        timer: 10000,
        timerProgressBar: true,
        allowOutsideClick: false,
        willClose: () => {
            setTimeout(() => {
                location.reload();
            }, 200);
        },
    });
}

// Tampilkan notifikasi error
function showErrorSwal(title = "Gagal", text = "Terjadi kesalahan.") {
    Swal.fire({
        icon: "error",
        title: title,
        text: text,
    });
}

function showWarningSwal(title = "Peringatan", text = "Ada yang salah.") {
    Swal.fire({
        icon: "warning",
        title: title,
        text: text,
    });
}

function validateParticipantsBeforeSave() {
    var valid = true;
    $(".participant-status").each(function () {
        var id = $(this).data("id");
        var val = $(this).val();
        var reasonInput = $("#reason-" + id);
        if (val == "2" && reasonInput.val().trim() === "") {
            valid = false;
            reasonInput.addClass("border-red-500");
        } else {
            reasonInput.removeClass("border-red-500");
        }
    });
    return valid;
}

function toggleNIK(button) {
    const span = $(button).siblings(".nik-text");
    const full = String(span.data("full"));
    const isHidden = $(button).text().trim() === "👁️";

    if (isHidden) {
        span.text(full);
        $(button).text("🚫"); // ikon untuk sembunyikan
    } else {
        const masked = "*".repeat(full.length - 4) + full.slice(-4);
        span.text(masked);
        $(button).text("👁️"); // ikon untuk tampilkan
    }
}

function copyCode() {
    document.getElementById("copy-token").onclick = function () {
        const token = document.getElementById("token-value").innerText;
        navigator.clipboard.writeText(token).then(function () {
            const msg = document.getElementById("copied-msg");
            msg.style.opacity = 1;
            setTimeout(() => {
                msg.style.opacity = 0;
            }, 2000);
        });
    };

    document.getElementById("copy-letter").onclick = function () {
        const letter = document
            .getElementById("no-letter-value")
            .innerText.replace("No: ", "")
            .trim();
        navigator.clipboard.writeText(letter).then(function () {
            const msg = document.getElementById("copied-letter-msg");
            msg.style.opacity = 1;
            setTimeout(() => {
                msg.style.opacity = 0;
            }, 2000);
        });
    };
}

function timeDeadline() {
    const timeD = document.getElementById("time");
    const trainingDateStr = timeD?.dataset?.trainingDate;

    if (timeD && trainingDateStr) {
        const trainingDate = moment.tz(trainingDateStr, "Asia/Jakarta");
        const deadline = trainingDate.clone().subtract(5, "days");

        let intervalId;

        function showCountdown() {
            const now = moment.tz("Asia/Jakarta");
            const distance = deadline.diff(now);

            if (distance <= 0) {
                timeD.textContent = "⛔ Pendaftaran sudah ditutup.";
                timeD.classList.remove("hidden");

                timeD.classList.remove(
                    "text-blue-600",
                    "bg-white",
                    "border-gray-300"
                );
                timeD.classList.add(
                    "bg-red-100",
                    "text-red-800",
                    "border-red-400"
                );

                clearInterval(intervalId);
                return;
            }

            const duration = moment.duration(distance);
            const days = Math.floor(duration.asDays());
            const hours = duration.hours();
            const minutes = duration.minutes();
            const seconds = duration.seconds();

            timeD.textContent = `⏳ Waktu pendaftaran: ${days}h ${hours}j ${minutes}m ${seconds}d`;
            timeD.classList.remove("hidden");

            timeD.classList.add("text-blue-600", "bg-white", "border-gray-300");
            timeD.classList.remove(
                "text-red-800",
                "bg-red-100",
                "border-red-400"
            );
        }

        intervalId = setInterval(showCountdown, 1000);
        showCountdown();
    }
}

function initStatusReasonWatcher() {
    // Saat halaman dimuat, set awal reason input sesuai status
    $("select[name^='participants']").each(function () {
        updateReasonInput($(this));
    });

    // Handler perubahan status
    $(document).on("change", "select[name^='participants']", function () {
        updateReasonInput($(this));
    });

    // Fungsi helper
    function updateReasonInput($select) {
        // Ambil status (value select)
        const status = $select.val();
        const $reasonInput = $select
            .closest("tr")
            .find("input[name^='participants']");

        if (status == "2") {
            // 2 = Rejected
            $reasonInput.prop("disabled", false);
        } else {
            $reasonInput.val(""); // Kosongkan reason kalau bukan rejected
            $reasonInput.prop("disabled", true);
        }
    }
}

// ============ INIT ================
$(document).ready(function () {
    try {
        timeDeadline();
    } catch (e) {
        console.error("Error di timeDeadline:", e);
    }
    locationTraining();
    initStatusReasonWatcher();
    datePicker(); // Inisialisasi date picker
    updateEndDate(); // Hitung end date saat halaman dimuat
    setupConfirmationCheckbox(); // Aktifkan/Nonaktifkan tombol submit
    $("#submitBtn").click(updateForm1User); // Tombol submit
    $("#activity").on("change", updateEndDate); // Update end date saat activity berubah
    $("#submitParticipantBtn").on("click", updateForm2User);
    $("#submitFinish").on("click", updateTrainingFinish);
    initShowDetailParticipant();

    $("#uploadFileForAdminBtn").on("click", function (e) {
        e.preventDefault();
        uploadFileForAdmin(); // baru dipanggil waktu tombol diklik
    });
    // Handler delete tombol, tetap pakai on (untuk baris yang dynamic)
    $(document).on("click", ".deleteButtonParticipant", function () {
        const id = $(this).data("id");
        deleteParticipant(id);
    });

    $(document).ready(function () {
        $(".downloadAllBtn").on("click", function () {
            const participantId = $(this).data("id");
            // Lakukan aksi download
            window.location.href = `/participant/${participantId}/download-all`;
        });
    });

    addParticipants();
    $(document).on("click", ".toggle-nik-btn", function () {
        toggleNIK(this);
    });

    copyCode();
});
