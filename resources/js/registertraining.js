import { cityList } from "./cities.js";
import moment from "moment-timezone";
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
                    title: "Peserta Belum Didaftarkan",
                    text: "Silakan Tambahkan Peserta Minimal 1 untuk Melanjutkan Tab Berikutnya.",
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
        e.preventDefault();
        const progress = "2";
        var formData = {
            _token: $('input[name="_token"]').val(),
            id: $("#trainingId").val(),
            name_pic: $("#name_pic").val(),
            name_company: $("#name_company").val(),
            email_pic: $("#email_pic").val(),
            phone_pic: $("#phone_pic").val(),
            city: $("#city").val(),
            provience: $("#provience").val(),
            address: $("#address").val(),
            isprogress: progress,
        };

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
                if (response.success) {
                    showSuccess(response.message, true);
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
                Swal.close();

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
                        $city.append(
                            $("<option>", { value: kota, text: kota })
                        );
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
    locationTraining();
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
let form3Closed = false;
function checkSubmitBtnDeadline() {
    const btnSelectors = ["#submitBtn", "#submitBtnForm2", "#submitBtnForm3"];

    btnSelectors.forEach((selector) => {
        const btn = $(selector);
        const trainingDateStr = btn.data("training-date");

        if (!trainingDateStr) return;

        const parent = btn.parent();
        if (parent.css("position") === "static") {
            parent.css("position", "relative");
        }

        // Gunakan moment-timezone untuk pastikan waktu Jakarta
        const trainingDateJakarta = moment.tz(trainingDateStr, "Asia/Jakarta");
        const nowJakarta = moment.tz("Asia/Jakarta");
        const hMinus8 = trainingDateJakarta.clone().subtract(7, "days");

        if (nowJakarta.isSameOrAfter(hMinus8)) {
            btn.prop("disabled", true);
            btn.removeClass(
                "bg-blue-500 hover:bg-blue-600 cursor-pointer"
            ).addClass("bg-gray-400 cursor-not-allowed");
            btn.text("Pendaftaran Ditutup");
            parent.find(".tooltip-btn").remove();

            // Set flag hanya untuk form 3
            if (selector === "#submitBtnForm3") {
                form3Closed = true;
            }
        } else {
            // Reset flag jika belum deadline
            if (selector === "#submitBtnForm3") {
                form3Closed = false;
            }

            if (parent.find(".tooltip-btn").length === 0) {
                const tooltip = $(
                    `<div class="tooltip-btn absolute bg-gray-800 text-white text-xs px-3 py-2 rounded shadow-md flex justify-between items-start gap-2 w-max max-w-[250px]">
                        <div>
                            <div class="font-semibold">Pendaftaran Dapat Diubah Hingga</div>
                            <div class="text-yellow-300" id="countdown-${selector.replace(
                                "#",
                                ""
                            )}"></div>
                        </div>
                        <button class="text-white hover:text-red-400 text-lg font-bold leading-none" style="line-height: 1;" data-tooltip-close>&times;</button>
                    </div>`
                );

                btn.after(tooltip);

                tooltip.css({
                    top: "100%",
                    left: 0,
                    marginTop: "8px",
                    position: "absolute",
                    zIndex: 50,
                });

                tooltip.find("[data-tooltip-close]").on("click", function () {
                    tooltip.remove();
                });

                const countdownEl = tooltip.find(
                    `#countdown-${selector.replace("#", "")}`
                )[0];

                function updateCountdown() {
                    const nowJakarta = moment.tz("Asia/Jakarta");
                    const distance = moment.duration(hMinus8.diff(nowJakarta));

                    if (distance.asMilliseconds() <= 0) {
                        tooltip.remove();
                        btn.prop("disabled", true);
                        btn.removeClass(
                            "bg-blue-500 hover:bg-blue-600 cursor-pointer"
                        ).addClass("bg-gray-400 cursor-not-allowed");
                        btn.text("Pendaftaran Ditutup");

                        // Set flag jika sudah lewat deadline
                        if (selector === "#submitBtnForm3") {
                            form3Closed = true;
                        }
                        return;
                    }

                    const days = Math.floor(distance.asDays());
                    const hours = distance.hours();
                    const minutes = distance.minutes();
                    const seconds = distance.seconds();

                    countdownEl.innerText = `Sisa waktu: ${days} hari ${hours} jam ${minutes} menit ${seconds} detik`;
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        }
    });
}

function checkBtnSendForm3() {
    const fileApprovalInput = document.getElementById("file_approval");
    const proofPaymentInput = document.getElementById("proof_payment");
    const submitBtn = $("#submitBtnForm3");
    function checkFilesSelected() {
        // Cek juga deadline!
        if (form3Closed) {
            submitBtn.prop("disabled", true);
            submitBtn
                .removeClass("bg-blue-500 hover:bg-blue-600 cursor-pointer")
                .addClass("bg-gray-400 cursor-not-allowed");
            return;
        }

        if (
            fileApprovalInput.files.length > 0 ||
            proofPaymentInput.files.length > 0
        ) {
            submitBtn.prop("disabled", false);
            submitBtn
                .removeClass("bg-gray-400 cursor-not-allowed")
                .addClass("bg-blue-500 hover:bg-blue-600 cursor-pointer");
        } else {
            submitBtn.prop("disabled", true);
            submitBtn
                .removeClass("bg-blue-500 hover:bg-blue-600 cursor-pointer")
                .addClass("bg-gray-400 cursor-not-allowed");
        }
    }

    fileApprovalInput.addEventListener("change", checkFilesSelected);
    proofPaymentInput.addEventListener("change", checkFilesSelected);
    checkFilesSelected();
}
function cities() {
    var $select = $("#city");
    var selected = $("#citySelected").val() || "";
    if ($select.length) {
        var options = '<option value="">-- Pilih Kota --</option>';
        $.each(cityList, function (i, city) {
            options += `<option value="${city}"${
                city === selected ? " selected" : ""
            }>${city}</option>`;
        });
        $select.html(options);
    }
}

function scrollToElement(selector) {
    var element = $(selector);
    if (element.length) {
        $("html, body").animate(
            {
                scrollTop: element.offset().top,
            },
            600
        ); // 600ms scroll animation
    }
}

// function send data
$(document).ready(function () {
    showTabs();
    submitForm1();
    checkSubmitBtnDeadline();
    $("#submitBtnForm2").click(function (e) {
        e.preventDefault(); // Mencegah form submit default
        sendForm2(); 
    });

    $("#submitBtnForm3").click(function (e) {
        e.preventDefault(); 
        sendForm3(); 
    });
    checkBtnSendForm3();
    cities();
    $(document).ready(function () {
        scrollToElement("#tab2");
    });
});
