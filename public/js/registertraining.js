document.addEventListener("DOMContentLoaded", function () {
    // Ambil elemen-elemen yang dibutuhkan
    const addButton = document.getElementById("addButton");
    const calendarModal = document.getElementById("calendarModal");
    const closeModalButton = document.getElementById("closeModal");
    const calendarContainer = document.getElementById("calendarContainer");

    // Variabel untuk menyimpan tanggal yang dipilih
    let selectedDate = null;
    
    // Fungsi untuk menampilkan kalender
    function renderCalendar() {
        // Buat elemen untuk kalender
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        calendarContainer.innerHTML = ""; // Bersihkan konten kalender sebelumnya

        // Menampilkan nama bulan dan tahun
        const monthName = today.toLocaleString("default", { month: "long" });
        const yearName = today.getFullYear();
        const monthHeader = document.createElement("h4");
        monthHeader.classList.add("text-center", "text-xl", "mb-4");
        monthHeader.textContent = `${monthName} ${yearName}`;
        calendarContainer.appendChild(monthHeader);

        // Membuat grid kalender
        const daysGrid = document.createElement("div");
        daysGrid.classList.add("grid", "grid-cols-7", "gap-2");

        // Menampilkan nama hari
        const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        daysOfWeek.forEach(day => {
            const dayHeader = document.createElement("div");
            dayHeader.textContent = day;
            dayHeader.classList.add("text-center", "font-bold");
            daysGrid.appendChild(dayHeader);
        });

        // Menambahkan tanggal kosong sebelum tanggal pertama bulan ini
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("text-center", "text-xs");
            daysGrid.appendChild(emptyCell);
        }

        // Menambahkan tanggal bulan ini
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.textContent = day;
            dayCell.classList.add("text-center", "cursor-pointer", "py-2", "rounded", "hover:bg-blue-500", "hover:text-white", "transition");

            // Menambahkan event listener untuk memilih tanggal
            dayCell.addEventListener("click", function () {
                selectedDate = new Date(currentYear, currentMonth, day);
                dayCell.classList.add("bg-blue-500", "text-white");
                alert(`Tanggal yang dipilih: ${selectedDate.toLocaleDateString()}`);
            });

            daysGrid.appendChild(dayCell);
        }

        calendarContainer.appendChild(daysGrid);
    }

    // Tampilkan kalender saat tombol "Tambah" diklik
    addButton.addEventListener("click", function () {
        calendarModal.classList.remove("hidden");
        renderCalendar();
    });

    // Menutup modal kalender saat tombol "Tutup" diklik
    closeModalButton.addEventListener("click", function () {
        calendarModal.classList.add("hidden");
    });
});
