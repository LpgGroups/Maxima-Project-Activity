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
    if (confirm("Hapus peserta ini?")) {
        $.ajax({
            url: `/dashboard/admin/training/delete-participant/${id}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                Accept: "application/json",
            },
            success: function (resp) {
                if (resp.success) {
                    $(`tr[data-participant-id="${id}"]`).remove();
                    $(`#detail-row-${id}`).remove();
                    showSuccessSwal("Berhasil", "Peserta berhasil dihapus!");
                } else {
                    showErrorSwal("Gagal", "Gagal menghapus peserta!");
                }
            },
            error: function () {
                showErrorSwal("Gagal", "Terjadi kesalahan saat menghapus!");
            },
        });
    }
}

// function deleteParticipant(id) {
//     Swal.fire({
//         title: "Konfirmasi Penghapusan",
//         text: "Peserta yang dihapus tidak dapat dikembalikan. Anda yakin ingin melanjutkan?",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#d33",
//         cancelButtonColor: "#3085d6",
//         confirmButtonText: "Ya, hapus!",
//         cancelButtonText: "Batal",
//         reverseButtons: true,
//     }).then((result) => {
//         if (result.isConfirmed) {
//             showLoadingSwal("Menghapus...", "Sedang menghapus data peserta.");

//             fetch(`/dashboard/admin/training/delete-participant/${id}`, {
//                 method: "DELETE",
//                 headers: {
//                     "X-CSRF-TOKEN": document.querySelector(
//                         'meta[name="csrf-token"]'
//                     ).content,
//                     Accept: "application/json",
//                     "Content-Type": "application/json",
//                 },
//             })
//                 .then(async (response) => {
//                     const data = await response.json();
//                     Swal.close();

//                     if (response.ok && data.success) {
//                         showSuccessSwal(
//                             "Berhasil",
//                             data.message || "Peserta berhasil dihapus."
//                         );
//                         const row = document.querySelector(
//                             `[data-participant-id="${id}"]`
//                         );
//                         if (row) row.remove();
//                     } else {
//                         showErrorSwal(
//                             "Gagal",
//                             data.message || "Gagal menghapus peserta."
//                         );
//                     }
//                 })
//                 .catch((error) => {
//                     Swal.close();
//                     console.error("Error:", error);
//                     showErrorSwal(
//                         "Kesalahan",
//                         "Terjadi kesalahan saat menghapus peserta."
//                     );
//                 });
//         }
//     });
// }

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

// Tampilkan notifikasi warning (optional)
function showWarningSwal(title = "Peringatan", text = "Ada yang salah.") {
    Swal.fire({
        icon: "warning",
        title: title,
        text: text,
    });
}

function initStatusReasonWatcher() {
    // Saat status berubah, enable/disable reason
    $(".participant-status").on("change", function () {
        var id = $(this).data("id");
        var val = $(this).val();
        var reasonInput = $("#reason-" + id);

        if (val == "2") {
            reasonInput.prop("disabled", false).focus();
            reasonInput.addClass("border-red-500");
        } else {
            reasonInput.prop("disabled", true).removeClass("border-red-500");
            reasonInput.val(""); // reset value jika bukan Ditolak
        }
    });
}

// Validasi sebelum simpan semua peserta
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

// ============ INIT ================
$(document).ready(function () {
    datePicker(); // Inisialisasi date picker
    updateEndDate(); // Hitung end date saat halaman dimuat
    setupConfirmationCheckbox(); // Aktifkan/Nonaktifkan tombol submit
    $("#submitBtn").click(updateForm1User); // Tombol submit
    $("#activity").on("change", updateEndDate); // Update end date saat activity berubah
    $("#submitParticipantBtn").on("click", updateForm2User);
    $("#submitFinish").on("click", updateTrainingFinish);
    initShowDetailParticipant();

    initStatusReasonWatcher();
    // Handler delete tombol, tetap pakai on (untuk baris yang dynamic)
    $(document).on("click", ".deleteButtonParticipant", function () {
        const id = $(this).data("id");
        deleteParticipant(id);
    });

    addParticipants();
});
