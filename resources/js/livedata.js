function fetchTrainingDataAdmin() {
    return fetch("/admin/training/live")
        .then((response) => response.json())
        .then((response) => {
            const trainings = response.data.reverse();
            let html = "";
            const maxDisplay = 10;
            const displayTrainings = trainings.slice(0, maxDisplay);

            displayTrainings.forEach((training, index) => {
                const numberLetter = training.noLetter || "-";
                const namePic = training.name_pic || "-";
                const nameCompany = training.name_company || "-";
                const activity = training.activity || "";
                const statusFail =
                    training.reason_fail !== null ? training.reason_fail : "";

                const dateStart = training.date
                    ? new Date(training.date)
                    : null;
                const dateEnd = training.date_end
                    ? new Date(training.date_end)
                    : null;

                let formattedDate = "";
                if (dateStart && dateEnd) {
                    const dayStart = dateStart.getDate();
                    const dayEnd = dateEnd.getDate();
                    const monthStart = dateStart.toLocaleDateString("id-ID", {
                        month: "long",
                    });
                    const monthEnd = dateEnd.toLocaleDateString("id-ID", {
                        month: "long",
                    });
                    const yearStart = dateStart.getFullYear();
                    const yearEnd = dateEnd.getFullYear();

                    if (yearStart !== yearEnd) {
                        formattedDate = `${dayStart} ${monthStart} ${yearStart} - ${dayEnd} ${monthEnd} ${yearEnd}`;
                    } else if (dateStart.getMonth() === dateEnd.getMonth()) {
                        formattedDate = `${dayStart}-${dayEnd} ${monthStart} ${yearStart}`;
                    } else {
                        formattedDate = `${dayStart} ${monthStart} - ${dayEnd} ${monthEnd} ${yearStart}`;
                    }
                }

                const progressMap = {
                    1: { percent: 10, color: "bg-red-600" },
                    2: { percent: 30, color: "bg-orange-500" },
                    3: { percent: 50, color: "bg-yellow-400" },
                    4: { percent: 75, color: "bg-[#e6e600]" },
                    5: { percent: 100, color: "bg-green-600" },
                };

                const progress = progressMap[training.isprogress] || {
                    percent: 0,
                    color: "bg-gray-400",
                };

                let badgeHTML = "";
                if (training.isNew) {
                    badgeHTML = `<img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">`;
                } else if (training.isUpdated) {
                    badgeHTML = `<img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">`;
                }
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

                const isprogress = Number(training.isprogress);
                const isFinish = Number(training.isfinish);
                if (isprogress === 5 && isFinish === 1) {
                    statusText = statusMap.selesai.label;
                    statusBgClass = statusMap.selesai.bgColor;
                } else if (isprogress === 5 && isFinish === 0) {
                    statusText = statusMap.menunggu.label;
                    statusBgClass = statusMap.menunggu.bgColor;
                } else if (isprogress === 5 && isFinish === 2) {
                    statusText = statusMap.ditolak.label;
                    statusBgClass = statusMap.ditolak.bgColor;
                } else if ([1, 2, 3, 4].includes(isprogress)) {
                    statusText = statusMap.proses.label;
                    statusBgClass = statusMap.proses.bgColor;
                } else {
                    statusText = "Unknown";
                    statusBgClass =
                        "bg-gray-400 text-white font-semibold rounded";
                }

                html += `
                    <tr onclick="window.location='/dashboard/admin/training/${
                        training.id
                    }'"
                        class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose text-[12px]">
                        <td>${index + 1}</td>
                        <td class="max-w-[120px] truncate whitespace-nowrap" title="${numberLetter}">${numberLetter}${badgeHTML}</td>
                        <td class="max-w-[50px] truncate whitespace-nowrap" title="${namePic}">${namePic}</td>
                        <td class="max-w-[150px] truncate whitespace-nowrap" title="${nameCompany}">${nameCompany} </td>
                        <td>${activity}</td>
                        <td class="relative p-1 pr-1 truncate whitespace-nowrap">
                            <span 
                                class="${statusBgClass} text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center truncate"
                                title="${
                                    isFinish === 2 && training.statusFail
                                        ? training.statusFail
                                        : "nothing"
                                }"
                            >
                                ${statusText}
                            </span>
                        </td>

                        <td class="max-w-[160px] truncate whitespace-nowrap" title="${formattedDate}">${formattedDate}</td>
                        <td>
                            <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                                <div class="${
                                    progress.color
                                } text-[8px] font-medium text-white text-center leading-none rounded-full"
                                    style="width: ${
                                        progress.percent
                                    }%; height:8px">
                                    ${progress.percent}%
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });

            if (trainings.length > maxDisplay) {
                html += `
                    <tr>
                        <td colspan="8" class="text-center py-2">
                            <a href="/dashboard/admin/training/alltraining" class="text-blue-600 hover:underline font-semibold">
                                Tampilkan Lebih Banyak
                            </a>
                        </td>
                    </tr>`;
            }

            document.getElementById("live-training-body").innerHTML = html;
        });
}

function startPolling() {
    const pollingInterval = 20000; // 20 detik
    fetchTrainingDataAdmin()
        .then(() => {
            setTimeout(startPolling, pollingInterval);
        })
        .catch((error) => {
            setTimeout(startPolling, pollingInterval * 2);
        });
}

// DOM Ready hanya untuk trigger awal
$(document).ready(function () {
    startPolling();
});
