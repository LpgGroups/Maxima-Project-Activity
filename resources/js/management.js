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

                <div class="font-extrabold mt-4">Data Persetujuan</div>
                <div class="text-sm bg-gray-50 border border-gray-300 rounded-md p-4 mt-1">
                    ${
                        data.files.length > 0
                            ? data.files
                                  .map(
                                      (f) => `
                                <div class="mb-4 flex items-start">
                                    <img src="/img/icon_pdf_mou.png" alt="PDF Icon" width="50" class="mr-4 mt-1" />
                                    <div>
                                        <div class="font-medium text-gray-800">${f.name}</div>
                                        <a href="${f.url}" target="_blank" class="inline-block mt-1 text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            `
                                  )
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
                showConfirmButton: !isApproved, // tombol setujui hanya muncul kalau isfinish = false
                confirmButtonText: "Setujui",
                confirmButtonColor: "#3085d6",
                showDenyButton: isApproved, // tombol tolak hanya muncul kalau isfinish = true
                denyButtonText: "Tolak",
                denyButtonColor: "#d33",
                cancelButtonText: "Tutup",
                allowOutsideClick: () => !Swal.isLoading(),
                allowEscapeKey: () => !Swal.isLoading(),
                preConfirm: () => {
                    if (isApproved) {
                        Swal.showValidationMessage(
                            "Data sudah disetujui, tidak bisa mengubah status menjadi setuju lagi."
                        );
                        return false;
                    }
                    const url = `/dashboard/management/approve/${id}`;
                    return fetch(url, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        body: JSON.stringify({ isfinish: true }),
                    })
                        .then((res) => {
                            if (!res.ok)
                                throw new Error("Gagal menyimpan perubahan.");
                            return res.json();
                        })
                        .catch((err) => {
                            Swal.showValidationMessage(err.message);
                        });
                },
                preDeny: () => {
                    if (!isApproved) {
                        Swal.showValidationMessage(
                            "Data belum disetujui, tidak bisa menolak."
                        );
                        return false;
                    }
                    const url = `/dashboard/management/approve/${id}`;
                    return fetch(url, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        body: JSON.stringify({ isfinish: false }),
                    })
                        .then((res) => {
                            if (!res.ok) throw new Error("Gagal menolak data.");
                            return res.json();
                        })
                        .catch((err) => {
                            Swal.showValidationMessage(err.message);
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
                        "Ditolak!",
                        "Data telah ditolak.",
                        "warning"
                    ).then(() => location.reload());
                }
            });
        })
        .fail(function () {
            Swal.fire("Error", "Gagal mengambil data detail.", "error");
        });
}

$(document).ready(function () {
    $(".view-detail-btn").on("click", function (e) {
        e.preventDefault();
        const trainingId = $(this).data("id");
        showDetail(trainingId);
    });
});
