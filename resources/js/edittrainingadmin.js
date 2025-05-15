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

    // Set awal: disabled
    submitBtn.disabled = true;
    submitBtn.classList.add("bg-gray-400", "cursor-not-allowed");

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

    fetch(`/dashboard/admin/training/${trainingId}/update`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) =>
            response.ok
                ? response.json()
                : response.json().then((e) => Promise.reject(e))
        )
        .then((data) => {
            alert(data.message || "Data berhasil diperbarui.");
            location.reload(); // reload halaman
        })
        .catch((error) => {
            const errorMsg = error?.errors
                ? Object.values(error.errors).join("\n")
                : "Terjadi kesalahan.";
            alert(errorMsg);
        });
}

function initDropdowns() {
    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach((dropdown) => {
        const button = dropdown.querySelector(".dropdown-button");
        const menu = dropdown.querySelector(".dropdown-menu");
        const input = dropdown.querySelector(".selected-status");

        // Toggle dropdown
        const toggleMenu = (e) => {
            e.stopPropagation();
            // Tutup semua menu lain dulu
            closeAllDropdowns();
            menu.classList.toggle("hidden");
        };

        // Select an item
        const selectItem = (e) => {
            e.stopPropagation();
            const item = e.currentTarget;
            const selectedText = item.innerHTML;
            const selectedValue = item.getAttribute("data-value");
            input.value = selectedValue;
            button.querySelector("span").innerHTML = selectedText;
            menu.classList.add("hidden");
        };

        // Attach listeners
        button.addEventListener("click", toggleMenu);
        menu.querySelectorAll("li").forEach((item) => {
            item.addEventListener("click", selectItem);
        });
    });

    // Close all menus if click outside
    window.addEventListener("click", closeAllDropdowns);

    function closeAllDropdowns() {
        document.querySelectorAll(".dropdown-menu").forEach((menu) => {
            menu.classList.add("hidden");
        });
    }
}

function showAddParticipantSwal() {
    Swal.fire({
        title: "Tambah Peserta",
        input: "text",
        inputLabel: "Nama Peserta",
        inputPlaceholder: "Masukkan nama peserta",
        showCancelButton: true,
        confirmButtonText: "Simpan",
        cancelButtonText: "Batal",
        inputValidator: (value) => {
            if (!value) {
                return "Nama tidak boleh kosong!";
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const participantName = result.value;

            // Tambahkan peserta ke tabel (opsional: bisa langsung disimpan ke server via fetch/AJAX)
            appendParticipantRow(participantName);
        }
    });
}

function appendParticipantRow(name) {
    const tbody = document.querySelector("table tbody");
    const index = tbody.querySelectorAll("tr").length;

    const row = document.createElement("tr");
    row.innerHTML = `
            <td class="w-[10px]">${index + 1}</td>
            <td class="w-[100px]">${name}</td>
            <td class="w-[80px] text-gray-500 text-xs">Belum dipilih</td>
            <td class="w-[200px] text-gray-400 italic">-</td>
            <td class="text-sm">Pending Action</td>
        `;
    tbody.appendChild(row);
}

function updateForm2User(id, name) {
    fetch(`/admin/participants/${id}/update-name`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify({ name }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Gagal menyimpan data");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Berhasil update:", data.message);
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error.message);
        });
}

// ============ INIT ================
$(document).ready(function () {
    datePicker(); // Inisialisasi date picker
    updateEndDate(); // Hitung end date saat halaman dimuat
    setupConfirmationCheckbox(); // Aktifkan/Nonaktifkan tombol submit
    $("#submitBtn").click(updateForm1User); // Tombol submit
    $("#submitBtn2").click(updateForm2User(id, name)); // Tombol submit
    $("#activity").on("change", updateEndDate); // Update end date saat activity berubah
    $("#submitParticipation").click(showAddParticipantSwal);
    initDropdowns();
});
