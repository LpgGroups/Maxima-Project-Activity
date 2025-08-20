let currentPage = 1;
let currentQuery = "";

function getData(page = 1, q = "") {
    currentPage = Number(page) || 1;
    currentQuery = q ?? "";

    const perPage = 10;
    const url = new URL("/dashboard/finance/getdata", window.location.origin);
    url.searchParams.set("per_page", perPage);
    url.searchParams.set("page", currentPage);
    if (currentQuery) url.searchParams.set("q", currentQuery);

    fetch(url)
        .then((r) => r.json())
        .then((response) => {
            const trainings = response.data || [];
            const tbody = document.querySelector('[data-ref="tbody"]');

            if (!trainings.length) {
                tbody.innerHTML = `<tr><td colspan="8" class="py-6 text-gray-500">Tidak ada data.</td></tr>`;
            } else {
                let html = "";
                trainings.forEach((training) => {
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
                        </div>`;

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
            }

            // --- Ambil meta pagination dengan berbagai kemungkinan bentuk ---
            const meta =
                response.meta &&
                (response.meta.current_page || response.meta.last_page)
                    ? {
                          current_page: Number(response.meta.current_page),
                          last_page: Number(response.meta.last_page),
                          per_page: Number(response.meta.per_page || perPage),
                          total: Number(response.meta.total || 0),
                      }
                    : typeof response.current_page !== "undefined" &&
                      typeof response.last_page !== "undefined"
                    ? {
                          current_page: Number(response.current_page),
                          last_page: Number(response.last_page),
                          per_page: Number(response.per_page || perPage),
                          total: Number(response.total || 0),
                      }
                    : null;

            renderPagination(meta);
        })
        .catch((error) => {
            console.error("Gagal mengambil data:", error);
            document.querySelector('[data-ref="tbody"]').innerHTML = `
                <tr><td colspan="8" class="py-6 text-red-500">Gagal memuat data.</td></tr>`;
            renderPagination(null); // bersihin pager kalau error
        });
}

function setupSearchInput() {
    let searchTimeout = null;
    $('[data-ref="search"]').on("input", function () {
        const query = $(this).val();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            getData(1, query); // reset ke halaman 1 saat cari
        }, 400);
    });
}

function renderPagination(meta) {
    const pager = document.querySelector('[data-ref="pager"]');
    if (!pager) return;

    // Bersihin kalau meta nggak valid
    if (!meta || !meta.last_page) {
        pager.innerHTML = "";
        return;
    }

    const current = Number(meta.current_page);
    const last = Number(meta.last_page);
    const total = Number(meta.total || 0);

    // Helper: bikin daftar halaman dengan ellipsis
    function buildPageList(current, last, delta = 1) {
        const range = [];
        const rangeWithDots = [];
        let l;

        // Selalu tampilkan 1, last, current ± delta
        for (let i = 1; i <= last; i++) {
            if (
                i === 1 ||
                i === last ||
                (i >= current - delta && i <= current + delta)
            ) {
                range.push(i);
            }
        }
        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l > 2) {
                    rangeWithDots.push("…");
                }
            }
            rangeWithDots.push(i);
            l = i;
        }
        return rangeWithDots;
    }

    const pages = buildPageList(current, last, 1);

    // Icon SVG
    const chevronLeft = `
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M12.78 15.53a.75.75 0 0 1-1.06 0l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 1 1 1.06 1.06L9.06 10l3.72 3.72a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd"/>
        </svg>`;
    const chevronRight = `
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M7.22 4.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L10.94 10 7.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
        </svg>`;

    // Info kiri (range / total) — tampil jika ada total
    const from = total ? (current - 1) * (meta.per_page || 10) + 1 : null;
    const to = total ? Math.min(current * (meta.per_page || 10), total) : null;
    const leftInfo = total
        ? `<div class="hidden sm:block text-xs text-gray-600">Menampilkan <span class="font-medium">${from}</span>–<span class="font-medium">${to}</span> dari <span class="font-medium">${total}</span> data</div>`
        : `<div class="hidden sm:block text-xs text-gray-600">Halaman <span class="font-medium">${current}</span> dari <span class="font-medium">${last}</span></div>`;

    // Prev button
    const prevBtn = `
      <button
        class="inline-flex items-center justify-center w-9 h-9 rounded-l-md ring-1 ring-inset ring-gray-300
               ${
                   current > 1
                       ? "bg-white hover:bg-gray-50 text-gray-700"
                       : "bg-gray-100 text-gray-400 cursor-not-allowed"
               }"
        ${current > 1 ? `data-page="${current - 1}"` : "disabled"}
        aria-label="Halaman sebelumnya"
      >${chevronLeft}</button>`;

    // Numbered buttons (desktop)
    const numberBtns = pages
        .map((p) => {
            if (p === "…") {
                return `<span class="hidden sm:inline-flex select-none items-center px-3 py-2 ring-1 ring-inset ring-transparent text-sm text-gray-500">…</span>`;
            }
            const isCurrent = Number(p) === current;
            return `
          <button
            class="hidden sm:inline-flex items-center px-3 py-2 ring-1 ring-inset text-sm font-medium
                   ${
                       isCurrent
                           ? "z-10 bg-red-600 text-white ring-red-600"
                           : "bg-white text-gray-700 hover:bg-gray-50 ring-gray-300"
                   }"
            ${isCurrent ? 'aria-current="page"' : `data-page="${p}"`}
          >${p}</button>`;
        })
        .join("");

    // Next button
    const nextBtn = `
      <button
        class="inline-flex items-center justify-center w-9 h-9 rounded-r-md ring-1 ring-inset ring-gray-300
               ${
                   current < last
                       ? "bg-white hover:bg-gray-50 text-gray-700"
                       : "bg-gray-100 text-gray-400 cursor-not-allowed"
               }"
        ${current < last ? `data-page="${current + 1}"` : "disabled"}
        aria-label="Halaman berikutnya"
      >${chevronRight}</button>`;

    // Wrapper responsif:
    // - Mobile: hanya prev / next (number disembunyikan)
    // - Desktop: prev + numbers + next
    const controls = `
      <nav class="flex items-center gap-3 w-full" role="navigation" aria-label="Pagination">
        ${leftInfo}
        <div class="ml-auto inline-flex isolate rounded-md shadow-sm">
            ${prevBtn}
            ${numberBtns}
            ${nextBtn}
        </div>
      </nav>
    `;

    pager.innerHTML = controls;

    // Event delegation: satu listener saja
    pager.addEventListener(
        "click",
        function (e) {
            const btn = e.target.closest("button[data-page]");
            if (!btn) return;
            const page = Number(btn.getAttribute("data-page"));
            if (Number.isFinite(page)) {
                getData(
                    page,
                    typeof currentQuery !== "undefined"
                        ? currentQuery
                        : $('[data-ref="search"]').val()
                );
            }
        },
        { once: true }
    ); // attach ulang setiap render untuk menjaga kebersihan
}

$(document).ready(function () {
    getData(); // load awal
    setupSearchInput();

    // refresh data berkala TANPA reset page & query
    setInterval(() => {
        getData(currentPage, currentQuery);
    }, 30000);
});
