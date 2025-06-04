function showTabs() {
    const savedTab = maxTab;
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
            if (xhr.status === 422) {
                // Tangani error validasi Laravel
                var errors = xhr.responseJSON.errors;
                var messages = [];

                if (errors.file_approval) {
                    messages.push("File: " + errors.file_approval.join(", "));
                }

                showError(messages.join("<br>"));
            } else {
                showError("Terjadi kesalahan saat mengirim data.");
            }

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

function checkSubmitBtnDeadline() {
    const btnSelectors = ["#submitBtn", "#submitBtnForm2", "#submitBtnForm3"];

    btnSelectors.forEach((selector) => {
        const btn = $(selector);
        const trainingDateStr = btn.data("training-date");

        if (!trainingDateStr) return; // kalau ga ada tanggal, skip

        // pastikan parent tombol punya posisi relative agar tooltip absolute bisa pasang dengan benar
        const parent = btn.parent();
        if (parent.css("position") === "static") {
            parent.css("position", "relative");
        }

        const trainingDate = new Date(trainingDateStr);
        const now = new Date();

        const hMinus3 = new Date(trainingDate);
        hMinus3.setDate(trainingDate.getDate() - 5);

        if (now >= hMinus3) {
            // disable tombol dan ubah style
            btn.prop("disabled", true);
            btn.removeClass("bg-blue-500").addClass(
                "bg-gray-400 cursor-not-allowed"
            );
            btn.text("Pendaftaran Ditutup");

            // hilangkan tooltip kalau ada
            parent.find(".tooltip-btn").remove();
        } else {
            // buat tooltip, kalau sudah ada jangan duplikat
            if (parent.find(".tooltip-btn").length === 0) {
                const tooltip = $(`
                    <div class="tooltip-btn absolute bg-gray-800 text-white text-xs px-3 py-2 rounded shadow-md flex justify-between items-start gap-2 w-max max-w-[250px]">
                        <div>
                            <div class="font-semibold">Pendaftaran Dapat Diubah Hingga</div>
                            <div class="text-yellow-300" id="countdown-${selector.replace(
                                "#",
                                ""
                            )}"></div>
                        </div>
                        <button class="text-white hover:text-red-400 text-lg font-bold leading-none" style="line-height: 1;" data-tooltip-close>&times;</button>
                    </div>
                `);

                btn.after(tooltip);

                tooltip.css({
                    top: "100%",
                    left: 0,
                    marginTop: "8px",
                    position: "absolute",
                    zIndex: 50,
                });

                // fungsi close tooltip
                tooltip.find("[data-tooltip-close]").on("click", function () {
                    tooltip.remove();
                });

                // countdown update
                const countdownEl = tooltip.find(
                    `#countdown-${selector.replace("#", "")}`
                )[0];

                function updateCountdown() {
                    const now = new Date();
                    const distance = hMinus3 - now;

                    if (distance <= 0) {
                        tooltip.remove();
                        // disable tombol juga kalau lewat deadline
                        btn.prop("disabled", true);
                        btn.removeClass("bg-blue-500").addClass(
                            "bg-gray-400 cursor-not-allowed"
                        );
                        btn.text("Pendaftaran Ditutup");
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor(
                        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                    );
                    const minutes = Math.floor(
                        (distance % (1000 * 60 * 60)) / (1000 * 60)
                    );
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownEl.innerText = `Sisa waktu: ${days}h ${hours}j ${minutes}m ${seconds}d`;
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        }
    });
}

// function send data
$(document).ready(function () {
    showTabs();
    submitForm1();
    addInputField();
    checkSubmitBtnDeadline();
    $("#submitBtnForm2").click(function (e) {
        e.preventDefault(); // Mencegah form submit default
        sendForm2(); // Panggil fungsi sendForm2 saat tombol diklik
    });

    $("#submitBtnForm3").click(function (e) {
        e.preventDefault(); // Mencegah form submit default
        sendForm3(); // Panggil fungsi sendForm2 saat tombol diklik
    });
});
