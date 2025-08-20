
function getData(page = 1, q = "") {
    const perPage = 10;
    const url = new URL("/dashboard/finance/getdata", window.location.origin);
    url.searchParams.set("per_page", perPage);
    url.searchParams.set("page", page);
    if (q) url.searchParams.set("q", q);

    fetch(url)
        .then((response) => response.json())
        .then((response) => {
            
            const trainings = response.data;
            let html = "";
            const tbody = document.querySelector('[data-ref="tbody"]');

            if (trainings.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="8" class="py-6 text-gray-500">Tidak ada data.</td></tr>
                `;
                return;
            }

            trainings.forEach((training, index) => {
                const numberLetter = training.no_letter || "-";
                const namePic = training.pic || "-";
                const nameCompany = training.company || "-";
                const nameTraining = training.activity || "-";
                const location = `${training.provience || "-"} - ${
                    training.city || "-"
                }`;
                const date = training.date || "-";
                const progress = training.status_upload || "-";
                const isUploaded =
                    training.status_upload &&
                    training.status_upload.toLowerCase().includes("sudah");
                const progressColorClass = isUploaded
                    ? "bg-green-200 text-green-800"
                    : "bg-red-200 text-red-800";

                const progressHtml = `
                <div class="${progressColorClass} font-medium px-3 py-1 rounded text-center w-full min-w-[100px]">
                    ${progress}
                </div>
                `;
                const showUrl = `/dashboard/finance/${training.id}`;
                html += `
                <tr onclick="window.location.href='${showUrl}'" class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose text-[12px]">
                    <td>${training.no}</td>
                    <td class="max-w-[120px] truncate whitespace-nowrap" title="${numberLetter}">${numberLetter}</td>
                    <td class="max-w-[50px] truncate whitespace-nowrap" title="${namePic}">${namePic}</td>
                    <td class="max-w-[150px] truncate whitespace-nowrap" title="${nameCompany}">${nameCompany}</td>
                    <td class="max-w-[150px] truncate whitespace-nowrap" title="${nameTraining}">${nameTraining}</td>
                    <td class="max-w-[150px] truncate whitespace-nowrap" title="${location}">${location}</td>
                    <td class="max-w-[150px] truncate whitespace-nowrap" title="${date}">${date}</td>
                    <td class="max-w-[150px] px-2 py-2 truncate whitespace-nowrap">${progressHtml}</td>
                </tr>`;
            });

            tbody.innerHTML = html;
        })
        .catch((error) => {
            console.error("Gagal mengambil data:", error);
            document.querySelector('[data-ref="tbody"]').innerHTML = `
                <tr>
                    <td colspan="8" class="py-6 text-red-500">Gagal memuat data.</td>
                </tr>
            `;
        });
}

function setupSearchInput() {
    let searchTimeout = null;

    $('[data-ref="search"]').on("input", function () {
        const query = $(this).val();

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            getData(1, query);
        }, 400); // debounce 400ms
    });
}

$(document).ready(function () {
    getData(); // Ambil data awal
    setupSearchInput(); // Aktifkan pencarian
     setInterval(getData, 30000);
});
