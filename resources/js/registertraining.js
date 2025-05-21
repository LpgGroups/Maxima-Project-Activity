function showTabs() {
    const savedTab = "1";
    showTab(savedTab);
    document
        .getElementById("nextBtnform1")
        .addEventListener("click", function () {
            const isEnabled = this.dataset.enabled === "true";

            if (isEnabled) {
                // Lanjut ke tab 2
                showTab(2);
            } else {
                // Tampilkan pop-up jika belum lengkap
                Swal.fire({
                    icon: "warning",
                    title: "Data Belum Lengkap",
                    text: "Silakan lengkapi semua data di Form 1 terlebih dahulu sebelum melanjutkan.",
                });
            }
        });

    document
        .getElementById("nextBtnForm2")
        .addEventListener("click", function () {
            const isEnabled = this.dataset.enabled === "true";

            if (isEnabled) {
                // Lanjut ke tab 3 atau aksi berikutnya
                showTab(3);
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Link Belum Diisi",
                    text: "Silakan isi link persyaratan terlebih dahulu untuk melanjutkan.",
                });
            }
        });

    document
        .getElementById("prevBtnForm2")
        .addEventListener("click", function () {
            showTab(1);
        });

    document
        .getElementById("prevBtnForm3")
        .addEventListener("click", function () {
            showTab(2);
        });

    function showTab(tabIndex) {
        document.querySelectorAll(".tab-content").forEach(function (content) {
            content.classList.add("hidden");
        });

        document.querySelectorAll("ul li a").forEach(function (tab) {
            tab.classList.remove(
                "text-violet-400",
                "border-b-2",
                "border-violet-400",
                "bg-white"
            );
            tab.classList.add("text-gray-600");
        });

        document
            .getElementById("content" + tabIndex)
            .classList.remove("hidden");

        const activeTab = document.getElementById("tab" + tabIndex);
        activeTab.classList.add(
            "text-violet-400",
            "border-b-2",
            "border-violet-400",
            "bg-white"
        );
        activeTab.classList.remove("text-gray-600");

        const aside = document.getElementById("side-panel");
        if (aside) {
            if (tabIndex == 3) {
                aside.classList.remove("hidden");
            } else {
                aside.classList.add("hidden");
            }
        }
    }
}

function submitForm1() {
    $("#submitBtn").click(function (e) {
        e.preventDefault(); // Prevent default form submission
        const progress = "2";
        var formData = {
            _token: $('input[name="_token"]').val(),
            id: $("#trainingId").val(), // Menambahkan ID untuk update
            name_pic: $("#name_pic").val(),
            name_company: $("#name_company").val(),
            email_pic: $("#email_pic").val(),
            phone_pic: $("#phone_pic").val(),
            isprogress: progress,
        };

        console.log(formData); // Log the data being sent

        // Clear previous response message
        $("#responseMessage").removeClass("hidden").text("");
        showLoading();
        $.ajax({
            url: "/dashboard/user/training/form/save", // URL untuk save
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                Swal.close(); // Tutup loading saat sukses
                showSuccess(response.message, true);
                if (response.success) {
                    $("#responseMessage")
                        .addClass("text-green-500")
                        .removeClass("text-red-500")
                        .text("Data berhasil disimpan atau diperbarui!");
                } else {
                    $("#responseMessage")
                        .addClass("text-red-500")
                        .removeClass("text-green-500")
                        .text("Gagal menyimpan data. Silakan coba lagi.");
                }
            },
            error: function (xhr, status, error) {
                Swal.close(); // Tutup loading saat error

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Kosongkan semua error lama
                    $(".text-error").remove();

                    // Tampilkan semua error
                    $.each(errors, function (key, value) {
                        let inputField = $("#" + key);
                        inputField.after(
                            '<div class="text-red-500 text-sm text-error mt-1">' +
                                value[0] +
                                "</div>"
                        );
                    });
                } else {
                    $("#responseMessage")
                        .addClass("text-red-500")
                        .removeClass("text-green-500")
                        .text("Terjadi kesalahan. Coba lagi.");

                    showError(
                        "Gagal mengirim data ke server. Silakan coba lagi."
                    );
                }
            },
        });
    });
}

let form_id = 1; // Misalnya, ini adalah form_id yang dapat Anda ambil dari server atau halaman saat ini

function addInputField() {
    const newInputGroup = document.createElement("div");
    newInputGroup.classList.add("flex", "items-center", "space-x-2", "mb-2");

    // Create the input field
    const newInput = document.createElement("input");
    newInput.type = "text";
    newInput.classList.add(
        "form-control",
        "px-3",
        "py-2",
        "border",
        "border-gray-300",
        "rounded-md"
    );
    newInput.placeholder = "Nama Peserta";

    newInput.setAttribute("id", "input-participant-" + Date.now()); // unique id based on timestamp

    // Create the add button
    const newAddButton = document.createElement("button");
    newAddButton.classList.add(
        "text-lg",
        "font-bold",
        "text-blue-500",
        "px-3",
        "py-2",
        "border",
        "border-gray-300",
        "rounded-md",
        "hover:bg-gray-100"
    );
    newAddButton.textContent = "+";
    newAddButton.onclick = addInputField;

    window.onload = function () {
        document
            .querySelector("#input-fields-container button")
            .addEventListener("click", addInputField);
    };

    // Create the remove button
    const newRemoveButton = document.createElement("button");
    newRemoveButton.classList.add(
        "text-lg",
        "font-bold",
        "text-red-500",
        "px-3",
        "py-2",
        "border",
        "border-gray-300",
        "rounded-md",
        "hover:bg-gray-100"
    );
    newRemoveButton.textContent = "-";
    newRemoveButton.onclick = function () {
        newInputGroup.remove(); // Remove the entire input group when the remove button is clicked
    };

    // Append input, add button, and remove button to the new input group
    newInputGroup.appendChild(newInput);
    newInputGroup.appendChild(newAddButton);
    newInputGroup.appendChild(newRemoveButton);

    // Append the new input group to the container
    document
        .getElementById("input-fields-container")
        .appendChild(newInputGroup);
}

function sendForm2() {
    var formId = $("#form_id").val();
    var link = $("#link").val();

    var participants = [];
    $("#input-fields-container input").each(function () {
        participants.push({ name: $(this).val() });
    });

    showLoading();

    $.ajax({
        url: "/dashboard/user/training/form2/save",
        method: "POST",
        data: {
            form_id: formId,
            link: link, // kirim ke server
            participants: participants,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showSuccess(response.message, true);
        },
        error: function (xhr, status, error) {
            showError("Terjadi kesalahan saat mengirim data.");
        },
    });
}

function sendForm3() {
    var formData = new FormData($("#form3")[0]);
    showLoading();
    $.ajax({
        url: "/dashboard/user/training/form3/save",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            showSuccess(response.message, true);
        },
        error: function (xhr) {
            showError("Terjadi kesalahan saat mengirim data.");
            console.log(xhr.responseText);
        },
    });
}

function showLoading() {
    Swal.fire({
        title: "Mengirim data...",
        text: "Silakan tunggu sebentar",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

// Tampilkan pesan sukses
function showSuccess(message, reload = false, redirectUrl = null) {
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: message || "Data berhasil dikirim.",
    }).then(() => {
        if (reload) {
            location.reload();
        } else if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    });
}

// Tampilkan pesan error
function showError(message) {
    Swal.fire({
        icon: "error",
        title: "Gagal!",
        text: message || "Terjadi kesalahan saat mengirim data.",
    });
}

// Tampilkan alert peringatan
function showWarning(message) {
    Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: message || "Ada data yang belum diisi.",
    });
}
// function send data
$(document).ready(function () {
    showTabs();
    submitForm1();
    addInputField();
    $("#submitBtnForm2").click(function (e) {
        e.preventDefault(); // Mencegah form submit default
        sendForm2(); // Panggil fungsi sendForm2 saat tombol diklik
    });

    $("#submitBtnForm3").click(function (e) {
        e.preventDefault(); // Mencegah form submit default
        sendForm3(); // Panggil fungsi sendForm2 saat tombol diklik
    });
});
