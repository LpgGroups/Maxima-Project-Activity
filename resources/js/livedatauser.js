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
                // Menentukan status label dan warna
                const statusMap = {
                    waiting: {
                        label: "Menunggu",
                        bgColor:
                            "bg-yellow-400 text-black font-semibold rounded",
                    },
                    selesai: {
                        label: "Selesai",
                        bgColor:
                            "bg-green-600 text-white font-semibold rounded",
                    },
                };

                let statusText = "";
                let statusBgClass = "";

                const isprogress = Number(item.isprogress); // konversi ke number

                if ([1, 2, 3, 4].includes(isprogress)) {
                    statusText = statusMap.waiting.label;
                    statusBgClass = statusMap.waiting.bgColor;
                } else if (isprogress === 5) {
                    statusText = statusMap.selesai.label;
                    statusBgClass = statusMap.selesai.bgColor;
                } else {
                    statusText = "Unknown";
                    statusBgClass =
                        "bg-gray-400 text-white font-semibold rounded";
                }

                let notifIcon = "";
                if (item.isNew) {
                    notifIcon = `<img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">`;
                } else if (item.isUpdated) {
                    notifIcon = `<img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">`;
                }

                const rowHtml = `
                    <tr onclick="window.location='${item.url}'"
                        class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
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
                                <div class="${item.progress_color} text-[8px] font-medium text-white text-center leading-none rounded-full"
                                    style="width: ${item.progress_percent}%; height:8px">
                                    ${item.progress_percent}%
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                $tbody.append(rowHtml);
            });
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

// Panggil fungsi ini setiap kali halaman ready, dan bisa di-set interval untuk auto-refresh
$(document).ready(function () {
    liveDataUser();
    setInterval(liveDataUser, 30000); // setiap ~4 menit
});
