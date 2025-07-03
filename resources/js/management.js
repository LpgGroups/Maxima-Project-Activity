function showDetail(id) {
    $.getJSON(`/dashboard/management/detail/${id}`)
        .done(function (data) {
            const isApproved = Boolean(data.isfinish);
            const activityMap = {
                TKPK1: "Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 1",
                TKPK2: "Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 2",
                TKBT1: "Pelatihan Tenaga Kerja Bangunan Tinggi 1",
                TKBT2: "Pelatihan Tenaga Kerja Bangunan Tinggi 2",
                BE: "Pelatihan Basic Electrical",
                P3K: "Pelatihan Pertolongan Pertama Pada Kecelakaan (P3K)",
                AK3U: "Pelatihan Ahli K3 Umum (AK3U)",
            };
            const SwalHTML = `
                <div class="font-extrabold">Informasi Pendaftaran</div>
                <div class="text-sm bg-gray-50 border border-gray-300 rounded-md p-4 grid grid-cols-[120px,10px,1fr] gap-y-2">
                    <div class="text-right font-bold text-gray-700">Nama PIC</div><div>:</div><div>${
                        data.pic ?? "-"
                    }</div>
                    <div class="text-right font-bold text-gray-700">Perusahaan</div><div>:</div><div>${
                        data.company ?? "-"
                    }</div>
                    <div class="text-right font-bold text-gray-700">Email PIC</div><div>:</div><div>${
                        data.email ?? "-"
                    }</div>
                    <div class="text-right font-bold text-gray-700">No WhatsApp</div><div>:</div><div>${
                        data.phone ?? "-"
                    }</div>
                    <div class="text-right font-bold text-gray-700">Date</div><div>:</div><div>${
                        data.date
                    } - ${data.date_end}</div>
                    <div class="text-right font-bold text-gray-700">Peserta</div><div>:</div><div>${
                        data.participants
                    } Peserta</div>
                </div>

                <div class="font-extrabold mt-4">Kelengkapan Dokumen</div>
                <div class="text-sm bg-gray-50 border border-gray-300 rounded-md p-4 mt-1">
                    ${
                        data.files.length > 0
                            ? data.files
                                  .map((f) => {
                                      // Helper: dapatkan nama file (basename)
                                      const getFileName = (url) => {
                                          if (!url) return "";
                                          return url.split("/").pop();
                                      };

                                      // Array untuk menyimpan semua tipe dokumen
                                      const docs = [
                                          {
                                              url: f.file_approval,
                                              label: "File Persetujuan",
                                              color: "blue",
                                              icon: "/img/icon_pdf_mou.png",
                                          },
                                          {
                                              url: f.proof_payment,
                                              label: "Bukti Pembayaran",
                                              color: "green",
                                              icon: "/img/icon_pdf_mou.png",
                                          },
                                          {
                                              url: f.budget_plan,
                                              label: "Budget Plan",
                                              color: "indigo",
                                              icon: "/img/icon_pdf_mou.png",
                                          },
                                          {
                                              url: f.letter_implementation,
                                              label: "Surat Pelaksanaan",
                                              color: "pink",
                                              icon: "/img/icon_pdf_mou.png",
                                          },
                                      ];

                                      return docs
                                          .filter((doc) => doc.url)
                                          .map(
                                              (doc) => `
                <div class="flex items-center mb-3">
                    <img src="${
                        doc.icon
                    }" alt="Icon" class="w-8 h-8 mr-3 flex-shrink-0" />
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800 text-sm mb-0.5">${getFileName(
                            doc.url
                        )}</div>
                        <div class="text-xs text-gray-500 mb-1">${
                            doc.label
                        }</div>
                    </div>
                    <a href="${doc.url}" target="_blank"
                        class="bg-${doc.color}-600 hover:bg-${
                                                  doc.color
                                              }-700 text-white text-xs px-3 py-1.5 rounded shadow transition">
                        Download
                    </a>
                </div>
            `
                                          )
                                          .join("");
                                  })
                                  .join("")
                            : '<div class="text-gray-500 italic">Tidak ada file</div>'
                    }
                </div>
            `;

            Swal.fire({
                title: `<strong>${
                    activityMap[data.activity] || data.activity
                }</strong>`,
                html: SwalHTML,
                icon: "info",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: "Setujui",
                confirmButtonColor: "#3085d6",
                showDenyButton: true,
                denyButtonText: "Tolak",
                denyButtonColor: "#d33",
                cancelButtonText: "Tutup",
                allowOutsideClick: () => !Swal.isLoading(),
                allowEscapeKey: () => !Swal.isLoading(),
                preConfirm: () => {
                    const url = `/dashboard/management/approve/${id}`;
                    return fetch(url, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        body: JSON.stringify({ isfinish: 1 }), // nilai 1 = disetujui
                    })
                        .then((res) => {
                            if (!res.ok)
                                throw new Error("Gagal menyetujui data.");
                            return res.json();
                        })
                        .catch((err) => {
                            Swal.showValidationMessage(err.message);
                        });
                },
                preDeny: () => {
                    return Swal.fire({
                        title: "Alasan Penolakan",
                        input: "textarea",
                        inputLabel: "Wajib isi alasan mengapa ditolak",
                        inputPlaceholder: "Tuliskan alasan di sini...",
                        inputAttributes: {
                            "aria-label": "Tuliskan alasan di sini",
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return "Alasan penolakan wajib diisi!";
                            }
                        },
                        showCancelButton: true,
                        cancelButtonText: "Batal",
                        confirmButtonText: "Kirim Penolakan",
                        confirmButtonColor: "#d33",
                        preConfirm: (reason) => {
                            const url = `/dashboard/management/approve/${id}`;
                            return fetch(url, {
                                method: "PUT",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                body: JSON.stringify({
                                    isfinish: 2,
                                    reason_fail: reason,
                                }),
                            })
                                .then((res) => {
                                    if (!res.ok)
                                        throw new Error("Gagal menolak data.");
                                    return res.json();
                                })
                                .catch((err) => {
                                    Swal.showValidationMessage(err.message);
                                });
                        },
                    });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        "Disetujui!",
                        "Data telah disetujui.",
                        "success"
                    ).then(() => location.reload());
                } else if (result.isDenied) {
                    Swal.fire(
                        "Data Ditolak!",
                        "Data telah berhasil ditolak.",
                        "warning"
                    ).then(() => location.reload());
                }
            });
        })
        .fail(function () {
            Swal.fire("Error", "Gagal mengambil data detail.", "error");
        });
}

function filterSearch() {
    const toggleBtn = document.getElementById("filterToggleBtn");
    const filterPanel = document.getElementById("filterPanel");

    if (toggleBtn && filterPanel) {
        toggleBtn.addEventListener("click", function () {
            filterPanel.classList.toggle("hidden");
        });
    }
}
function liveSearch() {
    const query = $("#searchInput").val().toLowerCase();

    $(".training-card").each(function () {
        const activityText = $(this)
            .find("p.text-sm.font-semibold")
            .text()
            .toLowerCase();
        const infoText = $(this).find("p.text-xs").text().toLowerCase();

        const combinedText = activityText + " " + infoText;

        const isMatch = combinedText.includes(query);
        $(this).toggle(isMatch);
    });
}
function applyTrainingFilters() {
    const searchTerm = $("#searchInput").val().toLowerCase();
    const sortValue = $("#sortCompany").val();
    const start = $("#startDate").val()
        ? new Date($("#startDate").val())
        : null;
    const end = $("#endDate").val() ? new Date($("#endDate").val()) : null;

    const cards = $(".training-card");

    // Filtering
    cards.each(function () {
        const card = $(this);
        const companyText = card
            .find("p.text-zinc-800.text-xs")
            .text()
            .split(" - ")[1]
            ?.trim()
            .toLowerCase();
        const dateRangeText = card.find(".text-violet-400").text();
        const [startText, endText] = dateRangeText.split(" - ");

        const cardStart = new Date(startText);
        const cardEnd = new Date(endText);

        const matchesSearch = card.text().toLowerCase().includes(searchTerm);
        const matchesStart = !start || cardStart >= start;
        const matchesEnd = !end || cardEnd <= end;

        if (matchesSearch && matchesStart && matchesEnd) {
            card.show();
        } else {
            card.hide();
        }
    });

    // Sorting
    if (sortValue) {
        const container = $(".space-y-4");
        const visibleCards = cards
            .filter(function () {
                return $(this).is(":visible");
            })
            .get();

        visibleCards.sort(function (a, b) {
            const nameA = $(a)
                .find("p.text-zinc-800.text-xs")
                .text()
                .split(" - ")[1]
                ?.trim()
                .toLowerCase();
            const nameB = $(b)
                .find("p.text-zinc-800.text-xs")
                .text()
                .split(" - ")[1]
                ?.trim()
                .toLowerCase();
            return sortValue === "asc"
                ? nameA.localeCompare(nameB)
                : nameB.localeCompare(nameA);
        });

        // Append sorted cards back to the container
        $.each(visibleCards, function (_, card) {
            container.append(card);
        });
    }
}

function badgedUpdate() {
    $(".view-detail-btn").each(function () {
        const btn = this;
        const trainingId = btn.dataset.id;
        const updatedAt = btn.dataset.updated;

        const badge = document.querySelector(
            `.new-badge[data-badge-id="${trainingId}"]`
        );

        if (!badge) {
            console.warn(`Badge not found for training ID: ${trainingId}`);
            return;
        }

        const lastSeenKey = `training_last_seen_${trainingId}`;
        const lastSeen = localStorage.getItem(lastSeenKey);

        if (!lastSeen || new Date(updatedAt) > new Date(lastSeen)) {
            badge.classList.remove("hidden");
        }

        btn.addEventListener("click", function () {
            badge.classList.add("hidden");
            localStorage.setItem(lastSeenKey, updatedAt);
        });
    });
}

$(document).ready(function () {
    $(".view-detail-btn").on("click", function (e) {
        e.preventDefault();
        const trainingId = $(this).data("id");
        showDetail(trainingId);
    });
    $("#searchInput").on("input", function () {
        liveSearch();
    });
    filterSearch();
    // badgedUpdate();
});
