document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("booking-section")) {
        loadBookings("menunggu_konfirmasi");
    }
});

// booking-management.js
// Copy-paste semua function dari template booking ke sini

async function loadBookings(statusFilter = "menunggu_konfirmasi") {
    const container = document.getElementById("booking-container");
    if (!container) return;

    try {
        container.innerHTML = `
            <div style="text-align: center; padding: 50px; color: var(--text-light);">
                <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #6a0dad; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                <p>Memuat data booking...</p>
            </div>
        `;

        const response = await fetch("/api/admin/bookings");
        const data = await response.json();

        if (data.success) {
            renderBookings(data.bookings, statusFilter);
        } else {
            showBookingError("Gagal memuat data booking");
        }
    } catch (error) {
        console.error("Error:", error);
        showBookingError("Terjadi kesalahan saat memuat data");
    }
}

function renderBookings(bookings, statusFilter) {
    const container = document.getElementById("booking-container");
    if (!container) return;

    const filteredBookings =
        statusFilter === "semua"
            ? bookings
            : bookings.filter((b) => b.status === statusFilter);

    if (filteredBookings.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 80px 20px; color: var(--text-light);">
                <i class="fas fa-calendar-times" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
                <h3 style="margin-bottom: 10px; font-weight: 600;">Tidak ada data booking</h3>
                <p>Belum ada pengajuan booking dengan status ini</p>
            </div>
        `;
        return;
    }

    let html = "";

    filteredBookings.forEach((booking) => {
        const initials =
            booking.nama_penyewa
                .split(" ")
                .map((n) => n[0] || "")
                .join("")
                .toUpperCase()
                .substring(0, 2) || "GU";

        let badgeColor = "#FF9800";
        let badgeText = "Butuh Konfirmasi";

        if (booking.status === "diterima") {
            badgeColor = "#28a745";
            badgeText = "Diterima";
        } else if (booking.status === "menunggu_pembayaran") {
            badgeColor = "#2196F3";
            badgeText = "Tunggu Pembayaran";
        } else if (booking.status === "ditolak") {
            badgeColor = "#dc3545";
            badgeText = "Ditolak";
        } else if (booking.status === "dibatalkan") {
            badgeColor = "#6c757d";
            badgeText = "Dibatalkan";
        }

        const tanggalCheckin = new Date(
            booking.tanggal_checkin
        ).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });

        const formattedHarga = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(booking.total_harga);

        html += `
        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 20px;">
                <span style="background-color: ${badgeColor}; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    ${badgeText}
                </span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                    ${initials}
                </div>
                <div style="flex-grow: 1;">
                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">
                        ${booking.nama_penyewa}
                    </p>
                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">
                        ${booking.nama_kos}
                    </p>
                    <p style="color: var(--text-light); font-size: 14px;">
                        Kamar ${booking.nama_kamar} - Lantai ${booking.lantai}
                    </p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 25px;">
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">${tanggalCheckin}</p>
                </div>
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">
                        ${booking.durasi_sewa} ${booking.periode_sewa}
                    </p>
                </div>
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Total Harga:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">
                        ${formattedHarga}
                    </p>
                </div>
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Kode Booking:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">
                        ${booking.kode_checkin}
                    </p>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                ${
                    booking.status === "menunggu_konfirmasi"
                        ? `
                <button onclick="acceptBooking(${booking.id})" 
                        style="padding: 10px 30px; border: none; background-color: #28a745; border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px; transition: all 0.2s;">
                    <i class="fas fa-check" style="margin-right: 5px;"></i> Terima
                </button>
                <button onclick="rejectBooking(${booking.id})" 
                        style="padding: 10px 30px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px; transition: all 0.2s;">
                    <i class="fas fa-times" style="margin-right: 5px;"></i> Tolak
                </button>
                `
                        : ""
                }
                
                ${
                    booking.status === "diterima"
                        ? `
                <button onclick="markAsPaid(${booking.id})" 
                        style="padding: 10px 30px; border: none; background-color: #2196F3; border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px;">
                    Tandai Sudah Bayar
                </button>
                `
                        : ""
                }
                
                <button onclick="viewBookingDetail(${booking.id})" 
                        style="padding: 10px 30px; border: 2px solid #6a0dad; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #6a0dad; font-size: 14px;">
                    <i class="fas fa-info-circle" style="margin-right: 5px;"></i> Detail
                </button>
            </div>
        </div>
        `;
    });

    container.innerHTML = html;
}

function switchBookingTab(tabId) {
    document.querySelectorAll(".tab-button").forEach((btn) => {
        btn.style.backgroundColor = "";
        btn.style.color = "var(--text-light)";
        btn.style.boxShadow = "none";
    });

    const activeBtn = document.querySelector(
        `[onclick="switchBookingTab('${tabId}')"]`
    );
    if (activeBtn) {
        activeBtn.style.backgroundColor = "var(--card-background)";
        activeBtn.style.color = "var(--text-color)";
        activeBtn.style.boxShadow = "0 2px 4px rgba(0,0,0,0.1)";
    }

    const statusMap = {
        "butuh-konfirmasi": "menunggu_konfirmasi",
        "tunggu-pembayaran": "menunggu_pembayaran",
        terbayar: "diterima",
        ditolak: "ditolak",
    };

    loadBookings(statusMap[tabId]);
}

async function acceptBooking(bookingId) {
    if (confirm("Terima booking ini?")) {
        try {
            const response = await fetch(
                `/api/admin/booking/${bookingId}/accept`,
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        "Content-Type": "application/json",
                    },
                }
            );

            const data = await response.json();

            if (data.success) {
                alert("Booking diterima!");
                loadBookings("menunggu_konfirmasi");
            } else {
                alert(data.message || "Gagal menerima booking");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan");
        }
    }
}

async function rejectBooking(bookingId) {
    if (confirm("Tolak booking ini?")) {
        try {
            const response = await fetch(
                `/api/admin/booking/${bookingId}/reject`,
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        "Content-Type": "application/json",
                    },
                }
            );

            const data = await response.json();

            if (data.success) {
                alert("Booking ditolak!");
                loadBookings("menunggu_konfirmasi");
            } else {
                alert(data.message || "Gagal menolak booking");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan");
        }
    }
}

function markAsPaid(bookingId) {
    alert("Fitur Tandai Sudah Bayar belum diimplementasikan");
}

async function viewBookingDetail(bookingId) {
    alert("Fitur Detail Booking belum diimplementasikan");
}

function showBookingError(message) {
    const container = document.getElementById("booking-container");
    if (container) {
        container.innerHTML = `
            <div style="text-align: center; padding: 50px; color: #dc3545;">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                <p>${message}</p>
                <button onclick="loadBookings('menunggu_konfirmasi')" style="margin-top: 20px; padding: 10px 20px; background-color: #6a0dad; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Coba Lagi
                </button>
            </div>
        `;
    }
}

// Bridge functions untuk handle tombol lama
window.tolakBooking = function (id) {
    rejectBooking(id);
};

window.terimaBooking = function (id) {
    acceptBooking(id);
};
