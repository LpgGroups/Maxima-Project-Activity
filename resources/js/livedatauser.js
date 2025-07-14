function liveDataUser() {
    fetch("/dashboard/user/live-data")
        .then((response) => response.json())
        .then((result) => {
            if (!result.success) return;

            var $tbody = $("table tbody");
            $tbody.empty();

            if (!result.data.length) {
                $tbody.append(
                    '<tr><td colspan="5" class="text-gray-500 py-2">Belum ada data pelatihan</td></tr>'
                );
                return;
            }

            result.data.forEach((item) => {
                console.log("Training date:", item.date);

                // Menentukan status label dan warna
                const statusMap = {
                    selesai: {
                        label: "Selesai",
                        bgColor:
                            "bg-green-600 text-white font-semibold rounded",
                    },
                    menunggu: {
                        label: "Menunggu",
                        bgColor:
                            "bg-yellow-400 text-black font-semibold rounded",
                    },
                    ditolak: {
                        label: "Ditolak",
                        bgColor: "bg-red-600 text-white font-semibold rounded",
                    },
                    proses: {
                        label: "Diproses",
                        bgColor: "bg-blue-400 text-white font-semibold rounded",
                    },
                };

                let statusText = "";
                let statusBgClass = "";

                const isprogress = Number(item.isprogress);
                const isFinish = Number(item.isfinish);

                if (isprogress === 5 && isFinish === 1) {
                    statusText = statusMap.selesai.label;
                    statusBgClass = statusMap.selesai.bgColor;
                } else if (isprogress === 5 && isFinish === 0) {
                    statusText = statusMap.menunggu.label;
                    statusBgClass = statusMap.menunggu.bgColor;
                } else if (isprogress === 5 && isFinish === 2) {
                    statusText = statusMap.menunggu.label;
                    statusBgClass = statusMap.menunggu.bgColor;
                } else if ([1, 2, 3, 4].includes(isprogress)) {
                    statusText = statusMap.proses.label;
                    statusBgClass = statusMap.proses.bgColor;
                } else {
                    statusText = "Unknown";
                    statusBgClass =
                        "bg-gray-400 text-white font-semibold rounded";
                }

                let progressColor = "";
                let progressPercent = item.progress_percent;
                progressColor = item.progress_color;

                let notifIcon = "";
                if (item.isNew) {
                    notifIcon = `<img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">`;
                } else if (item.isUpdated) {
                    notifIcon = `<img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">`;
                }

                const rowHtml = `
                                        <tr 
                       data-training-date="${item.training_date_raw}"" 
                        onclick="window.location='${item.url}'"
                        class="training-row odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose relative py-6"
                        >
                        <td>${item.no}</td>
                        <td>
                            ${item.activity}
                            ${notifIcon}
                        </td>
                        <td>
                            <span class="${statusBgClass} text-[10px] mt-2 px-2 py-[2px] rounded inline-block w-[70px] text-center truncate">
                                ${statusText}
                            </span>
                        </td>
                        <td>${item.date}</td>
                        <td>
                            <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                                <div class="${progressColor} text-[8px] font-medium text-white text-center leading-none rounded-full"
                                    style="width: ${progressPercent}%; height:8px">
                                    ${progressPercent}%
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                $tbody.append(rowHtml);
            });
            checkTableTooltipDeadline();
        })
        .catch((err) => {
            var $tbody = $("table tbody");
            $tbody.empty();
            $tbody.append(
                '<tr><td colspan="5" class="text-red-500 py-2">Gagal memuat data pelatihan</td></tr>'
            );
            console.error("Gagal memuat data:", err);
        });
}

function checkTableTooltipDeadline() {
    $(".training-row").each(function () {
        const row = $(this);
        const trainingDateStr = row.data("training-date");
        if (!trainingDateStr) return;

        const trainingDate = new Date(trainingDateStr);
        const now = new Date();

        const hminus5 = new Date(trainingDate);
        hminus5.setDate(trainingDate.getDate() - 5);

        const hminus2 = new Date(trainingDate);
        hminus2.setDate(trainingDate.getDate() - 7);

        if (now >= hminus2 && now <= trainingDate) {
            if (row.find(".tooltip-row").length === 0) {
                const tooltip = $(`
            <div class="tooltip-row absolute left-[90%] top-4 ml-2 mt-2 border bg-red-600 text-white text-xs px-3 py-2 rounded shadow-md flex justify-between items-start gap-2 w-max max-w-[250px] z-50">
                <div>
                    <div class="font-semibold">⚠️ Deadline Segera</div>
                    <div class="text-yellow-300 countdown-timer"></div>
                </div>
                <button class="text-white hover:text-red-400 text-lg font-bold leading-none" data-tooltip-close>&times;</button>
            </div>
        `);
                row.css("position", "relative");
                row.append(tooltip);

                // Close handler
                tooltip.find("[data-tooltip-close]").on("click", function (e) {
                    e.stopPropagation(); // ← ini wajib kalau baris punya onclick
                    tooltip.remove();
                });

                const countdownEl = tooltip.find(".countdown-timer")[0];

                function updateCountdown() {
                    const now = new Date();
                    const distance = hminus5 - now;

                    if (distance <= 0) {
                        tooltip.remove();
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

// Panggil fungsi ini setiap kali halaman ready, dan bisa di-set interval untuk auto-refresh
$(document).ready(function () {
    liveDataUser();
    setInterval(liveDataUser, 30000); // setiap ~4 menit
});
