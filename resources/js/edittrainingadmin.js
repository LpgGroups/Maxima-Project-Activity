function datePicker() {
    flatpickr("#date", {
        dateFormat: "d-m-Y",
    });
}

function updateForm1User() {
    const form = document.getElementById("editFormbyAdmin");
    const formData = new FormData(form);
    const place = document.querySelector('input[name="colored-radio"]:checked');
    if (place) {
        formData.append("place", place.value); // Menambahkan nilai 'place' ke form data
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
            location.reload();
        })
        .catch((error) => {
            const errorMsg = error?.errors
                ? Object.values(error.errors).join("\n")
                : "Terjadi kesalahan.";
            alert(errorMsg);
        });
        
}

$(document).ready(function () {
    datePicker();  // Inisialisasi date picker
    $("#submitBtn").click(updateForm1User);  // Setup event listener untuk submit button
});
