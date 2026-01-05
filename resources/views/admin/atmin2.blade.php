<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszzz Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* ========================================================== */
        /* 1. VARIABEL DAN CSS DASAR */
        /* ========================================================== */
        :root {
            --primary-color: #6a0dad;
            --background-color: #f7f7f7;
            --card-background: #ffffff;
            --text-color: #333333;
            --active-bg: #f4e9ff;
            --success-bg: #e6ffe6;
            --text-light: #555;
            --border-color: #eeeeee;
            --button-primary: #6a0dad;
            --button-text: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--card-background);
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            padding: 0 20px 30px;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 8px;
            font-size: 28px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .nav-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .nav-item:hover {
            background-color: var(--border-color);
        }

        .nav-item.active {
            background-color: var(--active-bg);
            color: var(--primary-color);
            border-right: 3px solid var(--primary-color);
        }

        .dropdown-icon {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s;
        }

        .dropdown-icon.rotated {
            transform: rotate(180deg);
        }

        .main-content {
            flex-grow: 1;
            padding: 0 30px 30px 30px;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .chat {
            margin-right: 20px;
            color: var(--text-light);
            cursor: pointer;
        }

        .user-profile {
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .profile-icon {
            font-size: 30px;
            margin-right: 8px;
        }

        /* ========================================================== */
        /* 2. CSS SPESIFIK UNTUK DASHBOARD HOME */
        /* ========================================================== */

        .dashboard-page {
            background-color: var(--card-background);
            min-height: 60vh;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .greeting-section {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .greeting-box {
            background-color: var(--card-background);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex-basis: 35%;
        }

        .greeting-box h2 {
            font-size: 28px;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .greeting-box p {
            color: var(--text-light);
        }

        .data-box {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex: 1;
            margin-bottom: 10px;
        }

        .monthly-revenue {
            background-color: var(--active-bg);
        }

        .total-revenue {
            background-color: #e6ffe6;
        }

        .data-box p {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .data-box h3 {
            font-size: 20px;
            font-weight: bold;
        }

        .waiting-section {
            margin-bottom: 30px;
        }

        .waiting-items {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .waiting-card {
            flex: 1;
            padding: 25px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            position: relative;
            text-align: left;
            color: var(--text-color);
        }

        .waiting-card:hover {
            background-color: var(--border-color);
        }

        .dot {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
        }

        .management-section {
            margin-bottom: 20px;
        }

        .management-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 15px;
        }

        .management-card {
            padding: 30px 20px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            color: var(--text-color);
        }

        .management-card:hover {
            background-color: var(--border-color);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* ========================================================== */
        /* 3. CSS UNTUK FORM (Langkah-Langkah Pengisian Data Kos) */
        /* ========================================================== */

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .progress-bar {
            display: flex;
            gap: 5px;
            margin-bottom: 40px;
        }

        .progress-bar-segment {
            height: 6px;
            width: 15%;
            background-color: var(--border-color);
            border-radius: 3px;
        }

        .progress-bar-segment.active {
            background-color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group input[type='text'],
        .form-group input[type='number'],
        .form-group textarea {
            width: 85%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group textarea {
            resize: none;
            height: 100px;
            width: 100%;
        }

        .gender-options {
            display: flex;
            gap: 10px;
        }

        .gender-button {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .gender-button.active {
            background-color: var(--active-bg);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-actions {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-top: 50px;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            border: none;
        }

        .btn-secondary {
            background-color: var(--card-background);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .btn-primary {
            background-color: var(--button-primary);
            color: var(--button-text);
        }

        .rules-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 20px;
        }

        .rules-column {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .address-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
        }


        /* Tambahkan di bagian CSS untuk foto profil */
        .profile-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Atau gunakan style inline seperti di template di atas */

        /* PENTING: Perubahan CSS untuk Checkbox Kustom */
        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 30px;
            margin-bottom: 5px;
            cursor: pointer;
            /* Pastikan kursor pointer untuk klik */
            font-size: 16px;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }

        .checkbox-container:hover input~.checkmark {
            background-color: #ccc;
        }

        .checkbox-container input:checked~.checkmark {
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        /* Akhir Perubahan CSS */

        /* ========================================================== */
        /* 4. CSS SPESIFIK UNTUK KOS SAYA DASHBOARD (Diperbarui) */
        /* ========================================================== */
        .dashboard-card {
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
        }

        .empty-state h3 {
            color: var(--text-light);
            margin-bottom: 10px;
        }

        /* Kartu untuk detail setiap kos */
        .kos-detail-card {
            border: 1px solid var(--border-color);
            padding: 25px;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            gap: 20px;
        }

        .kos-detail-info {
            flex-grow: 1;
        }

        .kos-detail-card h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 20px;
        }

        .kos-detail-item {
            margin-bottom: 10px;
            padding-left: 15px;
            border-left: 3px solid var(--active-bg);
        }

        .kos-detail-item strong {
            display: inline-block;
            width: 150px;
            font-weight: 600;
        }

        /* Style khusus untuk Harga */
        .kos-detail-item.price-info strong {
            width: 180px;
        }

        .kos-detail-item.price-info span {
            font-size: 1.1em;
            font-weight: bold;
            color: green;
        }

        .kos-actions {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .btn-danger {
            background-color: #d9534f;
            color: var(--button-text);
            border-radius: 8px;
            padding: 10px 25px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        /* Style untuk Foto Depan Kos di Dashboard */
        .kos-photo-display {
            width: 200px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid var(--border-color);
        }

        .kos-photo-display img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CSS untuk Galeri Foto Step 4 */
        .photo-gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }

        .photo-card {
            width: 150px;
            height: 100px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .photo-card.add-new-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 2px dashed var(--border-color);
            background-color: #fafafa;
            transition: background-color 0.2s;
        }

        .photo-card.add-new-card:hover {
            background-color: var(--active-bg);
            border-color: var(--primary-color);
        }

        .photo-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            z-index: 10;
        }

        /* Style untuk Detail View */
        .detail-group {
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .detail-group h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        /* Style untuk form harga dengan checkbox */
        .price-form-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--card-background);
        }

        .price-form-row label {
            margin-bottom: 0;
            flex-shrink: 0;
            min-width: 150px;
        }

        .price-form-row input {
            width: 200px;
            padding: 8px 12px;
        }

        /* ========================================================== */
        /* 5. CSS UNTUK DROPDOWN MANAJEMEN */
        /* ========================================================== */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: var(--card-background);
            margin-left: 20px;
            border-left: 2px solid var(--border-color);
        }

        .dropdown-menu.show {
            max-height: 200px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 20px 10px 40px;
            color: var(--text-light);
            text-decoration: none;
            transition: background-color 0.2s;
            border-left: 3px solid transparent;
        }

        .dropdown-item:hover {
            background-color: var(--border-color);
            color: var(--primary-color);
        }

        .dropdown-item.active {
            background-color: var(--active-bg);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* ========================================================== */
        /* 6. CSS UNTUK MODAL/POPUP PROFESIONAL */
        /* ========================================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-container {
            background-color: var(--card-background);
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: var(--text-color);
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-light);
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .modal-close:hover {
            background-color: var(--border-color);
        }

        .modal-body {
            padding: 25px;
            color: var(--text-color);
            font-size: 16px;
            line-height: 1.5;
        }

        .modal-footer {
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .modal-btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .modal-btn-primary:hover {
            background-color: #5a0cb5;
        }

        .modal-btn-secondary {
            background-color: var(--card-background);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .modal-btn-secondary:hover {
            background-color: var(--border-color);
        }

        /* Modal tipe success, warning, error */
        .modal-success .modal-header {
            border-bottom-color: #4CAF50;
        }

        .modal-warning .modal-header {
            border-bottom-color: #FF9800;
        }

        .modal-error .modal-header {
            border-bottom-color: #f44336;
        }

        .modal-success .modal-header h3 {
            color: #4CAF50;
        }

        .modal-warning .modal-header h3 {
            color: #FF9800;
        }

        .modal-error .modal-header h3 {
            color: #f44336;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Style untuk tombol periode sewa */
        .periode-btn {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            background-color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .periode-btn:hover {
            background-color: var(--active-bg);
            border-color: var(--primary-color);
        }

        .periode-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Style untuk dropdown periode */
        .periode-dropdown {
            position: relative;
            width: 100%;
        }

        .periode-dropdown-btn {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            background-color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .periode-dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }

        .periode-dropdown-content.show {
            display: block;
        }

        .periode-option {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .periode-option:hover {
            background-color: var(--active-bg);
        }

        .periode-option.selected {
            background-color: var(--active-bg);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Style untuk tombol kontrak */
        .kontrak-btn {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
            margin-bottom: 10px;
        }

        .kontrak-btn:hover {
            background-color: var(--active-bg);
            border-color: var(--primary-color);
        }

        .kontrak-btn.danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .kontrak-btn.danger:hover {
            background-color: #f8d7da;
            border-color: #c9302c;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 10px 0;
            }

            .nav-menu {
                display: flex;
                flex-direction: column;
            }

            .nav-item {
                padding: 10px 15px;
                border-right: none;
                border-bottom: 3px solid transparent;
            }

            .nav-item.active {
                border-right: none;
                border-bottom: 3px solid var(--primary-color);
            }

            .logo {
                display: none;
            }

            .main-content {
                padding: 15px;
            }

            .management-grid,
            .rules-grid,
            .address-form-grid {
                grid-template-columns: 1fr;
            }

            .form-group input[type="text"],
            .form-group input[type="number"] {
                width: 100%;
            }

            .greeting-section {
                flex-direction: column;
                gap: 10px;
            }

            .greeting-box {
                flex-basis: auto;
            }

            /* Responsive untuk kartu detail kos */
            .kos-detail-card {
                flex-direction: column;
            }

            .kos-photo-display {
                width: 100%;
                height: 200px;
            }

            /* Responsive untuk form harga */
            .price-form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .price-form-row input {
                width: 100%;
            }

            /* Responsive untuk dropdown */
            .dropdown-menu {
                margin-left: 10px;
            }

            .dropdown-item {
                padding: 8px 15px 8px 30px;
            }

            /* Responsive untuk modal */
            .modal-container {
                width: 95%;
                margin: 10px;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }

            /* Responsive untuk periode dropdown */
            .periode-dropdown-btn {
                padding: 10px 12px;
                font-size: 13px;
            }
        }

        .modal-custom .form-groups {
            margin-bottom: 15px;
        }

        .modal-custom .form-groups label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .modal-custom .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .modal-custom textarea.input {
            resize: vertical;
            min-height: 80px;
        }

        /* Map Styles */
        .map-container-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        #mapContainer {
            width: 100%;
            min-height: 400px;
        }

        .leaflet-container {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            border-radius: 8px;
            z-index: 1;
        }

        .coordinate-info {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            margin-top: 15px;
            font-size: 14px;
        }

        .coordinate-info i {
            color: #dc3545;
            margin-right: 8px;
        }

        .map-search-btn {
            margin-top: 8px;
        }

        .map-search-btn button {
            padding: 5px 12px;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .map-container-card {
                margin-top: 20px;
            }

            #mapContainer {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .grid-cols-1 {
                display: flex;
                flex-direction: column;
            }

            .lg\:order-2.order-1 {
                order: 1;
            }

            .lg\:order-1.order-2 {
                order: 2;
                margin-top: 20px;
            }

            #mapContainer {
                height: 300px;
            }
        }

        /* Map Loading Styles */
        .map-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .map-loading p {
            margin-top: 10px;
            color: #666;
        }

        /* Sembunyikan loading saat map ready */
        .map-loaded .map-loading {
            display: none;
        }

        #dropdown-menu {
            z-index: 9999 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }


        /* Chat overlay */
        #chat-overlay .absolute.inset-y-0 {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* Backdrop animation */
        #chat-backdrop {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Better scrollbar */
        #chat-overlay div[h-calc]::-webkit-scrollbar {
            width: 6px;
        }

        #chat-overlay div[h-calc]::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        /* Glass effect backdrop */
        #chat-backdrop {
            background: rgba(0, 0, 0, 0.4);
            /* 40% opacity - kurang pekat */
            backdrop-filter: blur(2px);
            /* subtle blur */
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <!-- TAMBAHKAN DIV WRAPPER INI -->
            <div class="brand-container"
                style="display: flex; align-items: center; padding: 20px 0 20px 30px; border-bottom: 1px solid #eee;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill=#6a0dad class="bi bi-houses-fill"
                    viewBox="0 0 16 16">
                    <path
                        d="M7.207 1a1 1 0 0 0-1.414 0L.146 6.646a.5.5 0 0 0 .708.708L1 7.207V12.5A1.5 1.5 0 0 0 2.5 14h.55a2.5 2.5 0 0 1-.05-.5V9.415a1.5 1.5 0 0 1-.56-2.475l5.353-5.354z" />
                    <path
                        d="M8.793 2a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708z" />
                </svg>
                <span class="brand-name" style="margin-left: 15px; font-size: 1.5rem; font-weight: bold; color: #333;">
                    Koszzz
                </span>
            </div>

            <nav class="nav-menu" id="nav-menu">
                <a href="#" class="nav-item active" data-page="home">
                    <i class="fas fa-house"></i> Home
                </a>
                <a href="#" class="nav-item" data-page="data_kos_step1">
                    <i class="fas fa-building"></i> Kos Saya
                </a>
                <a href="#" class="nav-item dropdown-toggle" id="management-toggle" data-page="management">
                    <i class="fas fa-wrench"></i> Manajemen
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu" id="management-dropdown">
                    <a href="#" class="dropdown-item" data-page="booking">
                        <i class="fas fa-calendar-check"></i> Booking
                    </a>
                    <!-- <a href="#" class="dropdown-item" data-page="tagihan">
                        <i class="fas fa-file-invoice-dollar"></i> Kelola Tagihan
                    </a>
                    <a href="#" class="dropdown-item" data-page="penyewa">
                        <i class="fas fa-users"></i> Penyewa
                    </a> -->
                </div>
                <a href="#" class="nav-item" data-page="account">
                    <i class="fas fa-user"></i> Akun
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header gap-8">
                <button onclick="showChat()" class="btn btn-outline btn-xs text-md text-black font-medium">
                    Chat
                </button>

                <!-- OVERLAY -->
                <div id="chat-overlay" class="fixed inset-0 z-50 hidden">
                    <div id="chat-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="hideChat()">
                    </div>

                    <div
                        class="absolute inset-y-0 right-0 w-96 bg-white shadow-2xl transform transition-all duration-300 ease-out">
                        <!-- HEADER -->
                        <div class="flex items-center justify-between p-4 border-b">
                            <div class="flex items-center">
                                <!-- Back button untuk detail chat (tersembunyi di view daftar) -->
                                <button id="back-to-list" class="mr-3 p-1 w-8 h-8 hidden" onclick="showChatList()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 512 512">
                                        <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="42" d="M244 400L100 256l144-144M120 256h292" />
                                    </svg>
                                </button>
                                <p id="chat-title" class="text-2xl font-bold text-black">Chat</p>
                            </div>
                            <button onclick="hideChat()" class="p-2 hover:bg-gray-100 w-10 h-10 rounded-full">
                                <i class="fas fa-times text-gray-600"></i>
                            </button>
                        </div>

                        <!-- DAFTAR CHAT -->
                        <div id="chat-list" class="p-4 h-[calc(100vh-140px)] overflow-y-auto">
                            <div class="space-y-4">

                                <hr class="my-2 border-gray-300 ms-14">

                            </div>
                        </div>

                        <!-- DETAIL CHAT -->
                        <div id="chat-detail" class="hidden h-[calc(100vh-140px)] flex flex-col">
                            <!-- HEADER -->
                            <div class="flex items-center justify-between p-4 border-b">
                                <div class="flex items-center">
                                    <div id="chat-avatar"
                                        class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 mr-3">
                                    </div>
                                    <div>
                                        <p id="chat-contact-name" class="font-semibold text-black"></p>
                                        <p id="chat-contact-role" class="text-sm text-gray-500"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- CHAT -->
                            <div class="p-4 h-[calc(100vh-140px)] overflow-y-auto">
                                <div class="space-y-4">
                                    <!-- Pesan dari user -->
                                    <div class="flex justify-end">
                                        <div class="max-w-xs">
                                            <div
                                                class="bg-[#5C00CC] text-white text-sm p-3 rounded-2xl rounded-br-none">
                                                Halo Pak, ada kamar kosong?
                                            </div>
                                            <p class="text-xs text-gray-500 text-right mt-1">12:30 • Terkirim</p>
                                        </div>
                                    </div>

                                    <!-- Pesan dari pemilik -->
                                    <div class="flex justify-start">
                                        <div class="max-w-xs">
                                            <div class="bg-gray-100 text-black text-sm p-3 rounded-2xl rounded-bl-none">
                                                Masih ada, bisa datang lihat langsung
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">12:32 • Dibaca</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- INPUT -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 border-t bg-white">
                                <div class="flex gap-2 items-center text-black">
                                    <input type="text" placeholder="Ketik pesan..."
                                        class="flex-1 border rounded-full px-4 py-2 focus:outline-none border-gray-300" />
                                    <button
                                        class="flex justify-center items-center bg-[#DCDCDC] text-white p-3 rounded-full w-10 h-10 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 30 32">
                                            <path fill="#000"
                                                d="M2.078 3.965c-.407-1.265.91-2.395 2.099-1.801l24.994 12.495c1.106.553 1.106 2.13 0 2.684L4.177 29.838c-1.188.594-2.506-.536-2.099-1.801L5.95 16.001zm5.65 13.036L4.347 27.517l23.037-11.516L4.346 4.485L7.73 15H19a1 1 0 1 1 0 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-profil relative inline-block">
                    <button id="dropdown-menu-icon" type="button"
                        class="btn btn-circle p-0 shadow-none flex items-center gap-2 open-modal"
                        onclick="toggleDropdown(this)">
                        <i class="fas fa-user-circle profile-icon"></i>
                        <p class="font-medium">{{ auth()->user()->name }}</p>
                    </button>
                    <div id="dropdown-menu"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                        <div class="py-1">
                            <button id="account-btn"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Profil
                            </button>
                            <button onclick="handleLogout()"
                                class="w-full text-left px-4 py-2 mt-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Logout
                            </button>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div id="content-area">
            </div>
        </main>
    </div>

    <!-- Modal/Popup Profesional -->
    <div id="modalOverlay" class="modal-overlay">
        <div id="modalContainer" class="modal-container">
            <div class="modal-header">
                <h3 id="modalTitle">Pemberitahuan</h3>
                <button id="modalClose" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Pesan akan muncul di sini.</p>
            </div>
            <div class="modal-footer">
                <button id="modalCancel" class="modal-btn modal-btn-secondary" style="display: none;">Batal</button>
                <button id="modalConfirm" class="modal-btn modal-btn-primary">OK</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/atmin-script.js') }}"></script>
    <script>
        // Kirim data dari Laravel ke JavaScript
        window.dashboardData = {
            monthlyRevenue: {{ $monthlyRevenue }},
            totalRevenue: {{ $totalRevenue }},
            userName: "{{ auth()->user()->name }}"
        };
    </script>
    <script>
        // ==================== VARIABEL GLOBAL ====================
        let chatVisible = false;
        let currentRoomId = null;
        let authUser = null;
        let pollingInterval = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // ==================== FUNGSI INISIALISASI ====================
        function initAuthUser() {
            return window.authUser && window.authUser.id;
        }

        // ==================== FUNGSI UTAMA ====================
        function showChat() {
            // 1. CEK LOGIN
            if (!initAuthUser()) {
                alert('Silakan login terlebih dahulu untuk mengakses chat');
                return;
            }

            authUser = window.authUser;

            // 2. BUKA OVERLAY & LOAD CHAT ROOMS
            openChatOverlay();
            loadChatRooms();
        }

        // ==================== FUNGSI LOAD DAFTAR CHAT ROOMS ====================
        async function loadChatRooms() {
            try {
                const response = await fetch('/chat/rooms', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) throw new Error('Failed to load chat rooms');

                const data = await response.json();
                renderChatRooms(data.rooms);

            } catch (error) {
                console.error('Error loading chat rooms:', error);
                showErrorInChatList('Gagal memuat percakapan');
            }
        }

        // ==================== FUNGSI RENDER DAFTAR CHAT ROOMS ====================
        function renderChatRooms(rooms) {
            const chatList = document.getElementById('chat-list');
            if (!chatList) return;

            if (!rooms || rooms.length === 0) {
                chatList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-3xl mb-3 text-gray-300"></i>
                        <p class="font-medium">Belum ada percakapan</p>
                        <p class="text-sm mt-1">Mulai chat dari halaman detail kos</p>
                    </div>
                `;
                return;
            }

            let html = '';

            rooms.forEach(room => {
                // Tentukan nama kontak berdasarkan role user
                let contactName = '';
                let lastMessage = 'Belum ada pesan';
                let lastTime = '';
                let avatarChar = 'K';

                if (authUser.role === 'penyewa') {
                    // Penyewa: lihat nama kos/pemilik
                    if (room.kos) {
                        contactName = room.kos.nama_kos || 'Kos';
                        avatarChar = contactName.charAt(0).toUpperCase();
                    }
                } else {
                    // Pemilik: lihat nama penyewa
                    if (room.user) {
                        contactName = room.user.name || 'Penyewa';
                        avatarChar = contactName.charAt(0).toUpperCase();
                    }
                }

                // Ambil pesan terakhir
                if (room.messages && room.messages.length > 0) {
                    const lastMsg = room.messages[0];
                    lastMessage = lastMsg.pesan || '';

                    if (lastMessage.length > 30) {
                        lastMessage = lastMessage.substring(0, 30) + '...';
                    }

                    // Format waktu
                    if (lastMsg.created_at) {
                        lastTime = formatTimeFromDB(lastMsg.created_at);
                    }
                }

                html += `
                    <button class="chat-contact flex items-center w-full p-3 hover:bg-gray-50 rounded-lg transition"
                            onclick="openChatRoom(${room.id})">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 
                                    flex items-center justify-center text-white font-semibold flex-shrink-0">
                            ${avatarChar}
                        </div>
                        <div class="ml-3 text-left flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <p class="text-black font-semibold truncate">${contactName}</p>
                                <span class="text-xs text-gray-400 flex-shrink-0 ml-2">${lastTime}</span>
                            </div>
                            <p class="text-sm text-gray-500 truncate mt-1">${lastMessage}</p>
                        </div>
                    </button>
                    <hr class="border-gray-100">
                `;
            });

            chatList.innerHTML = html;
        }


        // ==================== FUNGSI BUKA CHAT ROOM ====================
        async function openChatRoom(roomId) {
            try {
                currentRoomId = roomId;

                const response = await fetch(`/chat/rooms/${roomId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('Failed to load chat');
                const data = await response.json();

                const msgResponse = await fetch(`/chat/rooms/${roomId}/messages`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });
                if (!msgResponse.ok) throw new Error('Failed to load messages');
                const msgData = await msgResponse.json();

                if (data.success && msgData.success) {
                    // Update header dengan nama pemilik
                    updateDetailHeader(data.room);
                    // Tampilkan messages
                    showChatDetail(roomId, msgData.messages);
                }
                // Tampilkan detail chat
                // showChatDetail(roomId, data.messages);

            } catch (error) {
                console.error('Error opening chat room:', error);
                alert('Gagal membuka percakapan');
            }
        }

        function updateDetailHeader(room) {
            // Tentukan nama kontak berdasarkan role user
            let contactName = '';
            let contactRole = '';
            let avatarChar = '';

            if (authUser.role === 'penyewa') {
                // Penyewa chat dengan pemilik kos
                if (room.kos && room.kos.owner) {
                    contactName = room.kos.owner.name || 'Pemilik Kos';
                    contactRole = 'Pemilik Kos';
                } else if (room.kos) {
                    contactName = room.kos.nama_kos || 'Kos';
                    contactRole = 'Pemilik Kos';
                }
            } else {
                // Pemilik chat dengan penyewa
                if (room.user) {
                    contactName = room.user.name || 'Penyewa';
                    contactRole = 'Penyewa';
                }
            }

            // Ambil huruf pertama untuk avatar
            if (contactName) {
                avatarChar = contactName.charAt(0).toUpperCase();
            }

            // Update UI elements
            const nameEl = document.getElementById('chat-contact-name');
            const roleEl = document.getElementById('chat-contact-role');
            const avatarEl = document.getElementById('chat-avatar');

            if (nameEl) nameEl.textContent = contactName;
            if (roleEl) roleEl.textContent = contactRole;
            if (avatarEl) {
                avatarEl.textContent = avatarChar;
                // Jika avatar kosong, sembunyikan
                avatarEl.classList.add('flex', 'items-center', 'justify-center', 'text-white', 'font-semibold');
            }

            console.log('Header updated:', { contactName, contactRole, avatarChar });
        }

        // ==================== FUNGSI TAMPILKAN DETAIL CHAT ====================
        async function showChatDetail(roomId, messages = null) {
            // 1. Switch view ke detail
            document.getElementById('chat-list').classList.add('hidden');
            document.getElementById('chat-detail').classList.remove('hidden');
            document.getElementById('back-to-list').classList.remove('hidden');

            // 2. Jika belum ada messages, fetch dari server
            if (!messages) {
                try {
                    const response = await fetch(`/chat/rooms/${roomId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    if (data.success) messages = data.messages;
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // 3. Render messages
            if (messages) {
                renderMessagesInDetail(messages);
            }

            // 4. Mulai polling untuk real-time
            startMessagePolling(roomId);
        }

        // ==================== FUNGSI RENDER MESSAGES DI DETAIL ====================
        function renderMessagesInDetail(messages) {
            const messagesContainer = document.querySelector('#chat-detail .space-y-4');
            if (!messagesContainer) return;

            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <p>Belum ada pesan</p>
                        <p class="text-sm mt-1">Mulai percakapan sekarang!</p>
                    </div>
                `;
                return;
            }

            let html = '';
            messages.forEach(msg => {
                const isCurrentUser = msg.sender_id == authUser.id;
                const timeStr = formatTimeFromDB(msg.created_at);

                html += `
                    <div class="${isCurrentUser ? 'flex justify-end' : 'flex justify-start'}">
                        <div class="max-w-xs">
                            <div class="${isCurrentUser
                        ? 'bg-[#5C00CC] text-white p-3 rounded-2xl rounded-br-none'
                        : 'bg-gray-100 text-black p-3 rounded-2xl rounded-bl-none'} text-sm">
                                ${msg.pesan}
                            </div>
                            <p class="text-xs text-gray-500 ${isCurrentUser ? 'text-right' : ''} mt-1">
                                ${timeStr} • ${isCurrentUser ? 'Terkirim' : 'Dibaca'}
                            </p>
                        </div>
                    </div>
                `;
            });

            messagesContainer.innerHTML = html;

            // Scroll ke bawah
            setTimeout(() => {
                const container = document.querySelector('#chat-detail .overflow-y-auto');
                if (container) container.scrollTop = container.scrollHeight;
            }, 100);
        }

        // ==================== FUNGSI KIRIM PESAN ====================
        async function sendMessage() {
            if (!currentRoomId) return;

            const input = document.querySelector('#chat-detail input[type="text"]');
            const message = input.value.trim();

            if (message === '') return;

            const tempMsgId = 'temp-' + Date.now();

            try {
                // Tampilkan pesan loading
                const messagesContainer = document.querySelector('#chat-detail .space-y-4');
                if (messagesContainer) {
                    const tempMsg = `
                        <div id="${tempMsgId}" class="flex justify-end">
                            <div class="max-w-xs">
                                <div class="bg-[#5C00CC] text-white p-3 rounded-2xl rounded-br-none text-sm opacity-80">
                                    ${message}
                                </div>
                                <p class="text-xs text-gray-500 text-right mt-1">
                                    Mengirim...
                                </p>
                            </div>
                        </div>
                    `;
                    messagesContainer.innerHTML += tempMsg;

                    const container = document.querySelector('#chat-detail .overflow-y-auto');
                    if (container) container.scrollTop = container.scrollHeight;
                }

                // Kirim ke server
                const response = await fetch(`/chat/rooms/${currentRoomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ pesan: message })
                });

                const data = await response.json();

                // Hapus loading
                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();

                if (data.success) {
                    input.value = '';
                    // Refresh messages
                    await showChatDetail(currentRoomId);
                } else {
                    alert('Gagal mengirim pesan: ' + (data.error || 'Unknown error'));
                }

            } catch (error) {
                console.error('Error sending message:', error);
                alert('Terjadi kesalahan saat mengirim pesan');

                const tempElement = document.getElementById(tempMsgId);
                if (tempElement) tempElement.remove();
            }
        }

        // ==================== FUNGSI HELPER ====================
        function formatTimeFromDB(dateString) {
            if (!dateString) return 'Baru saja';

            try {
                // Parse sebagai UTC dan konversi ke lokal
                const isoDate = dateString.includes(' ')
                    ? dateString.replace(' ', 'T') + 'Z'
                    : dateString;

                const date = new Date(isoDate);

                if (isNaN(date.getTime())) {
                    if (dateString.includes(' ')) {
                        const timePart = dateString.split(' ')[1];
                        const [hour, minute] = timePart.split(':');
                        return `${hour}:${minute}`;
                    }
                    return 'Baru saja';
                }

                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

            } catch (e) {
                return 'Baru saja';
            }
        }

        function startMessagePolling(roomId) {
            if (pollingInterval) clearInterval(pollingInterval);

            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/chat/rooms/${roomId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    if (data.success && currentRoomId === roomId) {
                        renderMessagesInDetail(data.messages);
                    }
                } catch (error) {
                    console.log('Polling error:', error);
                }
            }, 3000);
        }

        function showErrorInChatList(message) {
            const chatList = document.getElementById('chat-list');
            if (chatList) {
                chatList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-3 text-red-300"></i>
                        <p>${message}</p>
                        <button onclick="loadChatRooms()" class="mt-3 px-4 py-2 bg-[#5C00CC] text-white 
                            rounded-lg text-sm hover:bg-purple-700 transition">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        function openChatOverlay() {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            overlay.classList.remove('hidden');

            setTimeout(() => {
                panel.style.transform = 'translateX(0)';
            }, 10);

            chatVisible = true;
            document.body.style.overflow = 'hidden';
        }

        function showChatList() {
            document.getElementById('chat-list').classList.remove('hidden');
            document.getElementById('chat-detail').classList.add('hidden');
            document.getElementById('back-to-list').classList.add('hidden');
            currentRoomId = null;

            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }

            // Reload chat rooms
            loadChatRooms();
        }

        function hideChat() {
            const overlay = document.getElementById('chat-overlay');
            const panel = overlay.querySelector('.absolute.inset-y-0');

            panel.style.transform = 'translateX(100%)';

            setTimeout(() => {
                overlay.classList.add('hidden');
                chatVisible = false;
                document.body.style.overflow = '';

                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }, 300);
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', () => {
            // User data dari Blade
            @auth
                window.authUser = {
                    id: {{ auth()->id() }},
                        name: '{{ auth()->user()->name }}',
                            role: '{{ auth()->user()->role }}',
                                email: '{{ auth()->user()->email }}'
                };
            @endauth

            // Enter to send
            const input = document.querySelector('#chat-detail input[type="text"]');
            if (input) {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        sendMessage();
                    }
                });
            }

            // Tombol send
            const sendBtn = document.querySelector('#chat-detail button');
            if (sendBtn && !sendBtn.onclick) {
                sendBtn.addEventListener('click', sendMessage);
            }
        });

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && chatVisible) hideChat();
        });
    </script>
    <script>
        function toggleDropdown(button) {
            const menu = document.getElementById('dropdown-menu');
            const isHidden = menu.classList.contains('hidden');

            // Toggle visibility
            if (isHidden) {
                menu.classList.remove('hidden');
                menu.classList.add('block');
                button.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                menu.classList.remove('block');
                button.setAttribute('aria-expanded', 'false');
            }
        }

        // Close kalau klik di luar
        document.addEventListener('click', function (event) {
            const menu = document.getElementById('dropdown-menu');
            const button = document.getElementById('dropdown-menu-icon');

            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
                menu.classList.remove('block');
                button.setAttribute('aria-expanded', 'false');
            }
        });

        function handleLogout() {
            const logoutForm = document.getElementById('logout-form');
            logoutForm.submit();
        }
    </script>
    <script>
        // ==========================================================
        // 1. FUNGSI MODAL/POPUP PROFESIONAL
        // ==========================================================

        /**
         * Menampilkan modal/popup profesional
         */
        function showModal({
            title = "Pemberitahuan",
            message = "",
            type = "info",
            onConfirm = null,
            onCancel = null,
            showCancel = false,
            confirmText = "OK",
            cancelText = "Batal"
        } = {}) {
            const modalOverlay = document.getElementById('modalOverlay');
            const modalContainer = document.getElementById('modalContainer');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalConfirm = document.getElementById('modalConfirm');
            const modalCancel = document.getElementById('modalCancel');
            const modalClose = document.getElementById('modalClose');

            const newConfirm = modalConfirm.cloneNode(true);
            const newCancel = modalCancel.cloneNode(true);
            const newClose = modalClose.cloneNode(true);

            modalConfirm.parentNode.replaceChild(newConfirm, modalConfirm);
            modalCancel.parentNode.replaceChild(newCancel, modalCancel);
            modalClose.parentNode.replaceChild(newClose, modalClose);

            const modalConfirmNew = newConfirm;
            const modalCancelNew = newCancel;
            const modalCloseNew = newClose;

            // Set konten modal
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalConfirmNew.textContent = confirmText;
            modalCancelNew.textContent = cancelText;

            // Set tipe modal (untuk styling)
            modalContainer.className = 'modal-container';
            if (type) {
                modalContainer.classList.add(`modal-${type}`);
            }

            // Tampilkan/sembunyikan tombol batal
            modalCancel.style.display = showCancel ? 'inline-block' : 'none';

            // Tampilkan modal
            modalOverlay.style.display = 'flex';

            let currentConfirmHandler = null;
            let currentCancelHandler = null;
            let currentCloseHandler = null;
            let currentOverlayHandler = null;

            // Event listener untuk tombol konfirmasi
            const confirmHandler = () => {
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
                closeModal();
            };

            // Event listener untuk tombol batal
            const cancelHandler = () => {
                if (typeof onCancel === 'function') {
                    onCancel();
                }
                closeModal();
            };

            // Event listener untuk tombol close
            const closeHandler = () => {
                if (typeof onCancel === 'function') {
                    onCancel();
                }
                closeModal();
            };

            // Fungsi untuk menutup modal
            const closeModal = () => {
                modalOverlay.style.display = 'none';
                // Hapus event listener lama
                if (currentConfirmHandler) {
                    modalConfirmNew.removeEventListener('click', currentConfirmHandler);
                }
                if (currentCancelHandler) {
                    modalCancelNew.removeEventListener('click', currentCancelHandler);
                }
                if (currentCloseHandler) {
                    modalCloseNew.removeEventListener('click', currentCloseHandler);
                }
                if (currentOverlayHandler) {
                    modalOverlay.removeEventListener('click', currentOverlayHandler);
                }
            };

            // Event listener untuk klik di luar modal
            const overlayClickHandler = (e) => {
                if (e.target === modalOverlay) {
                    closeHandler();
                }
            };

            currentConfirmHandler = confirmHandler;
            currentCancelHandler = cancelHandler;
            currentCloseHandler = closeHandler;
            currentOverlayHandler = overlayClickHandler;

            // Tambahkan event listener baru
            modalConfirmNew.addEventListener('click', currentConfirmHandler);
            if (showCancel) {
                modalCancelNew.addEventListener('click', currentCancelHandler);
            }
            modalCloseNew.addEventListener('click', currentCloseHandler);
            modalOverlay.addEventListener('click', currentOverlayHandler);
            // Fokus ke tombol OK untuk aksesibilitas
            setTimeout(() => modalConfirmNew.focus(), 100);
        }

        /**
         * Fungsi helper untuk modal konfirmasi (menggantikan confirm())
         */
        function showConfirm({
            title = "Konfirmasi",
            message = "Apakah Anda yakin?",
            confirmText = "Ya",
            cancelText = "Tidak",
            onConfirm = null,
            onCancel = null
        } = {}) {
            showModal({
                title,
                message,
                type: "warning",
                showCancel: true,
                confirmText,
                cancelText,
                onConfirm,
                onCancel
            });
        }

        // ==========================================================
        // 2. KUMPULAN DATA DAN LOKAL STORAGE MANAGEMENT (MULTI-KOS LOGIC)
        // ==========================================================

        const KOS_LIST_KEY = 'daftarKos'; // Kunci untuk array daftar kos
        const IMAGE_PLACEHOLDER = 'https://via.placeholder.com/200x150?text=Foto+Kos';

        let currentKosId = null; // ID kos yang sedang diedit (null = kos baru)
        let isManagementDropdownOpen = false; // Status dropdown manajemen
        let isEditMode = false;

        /**
         * Mengambil daftar kos dari LocalStorage.
         */
        function getKosList() {
            const data = localStorage.getItem(KOS_LIST_KEY);
            try {
                return data ? JSON.parse(data) : [];
            } catch (e) {
                console.error("Local Storage data corrupted, returning empty list.", e);
                return [];
            }
        }

        /**
         * Menyimpan daftar kos ke LocalStorage.
         */
        function saveKosList(list) {
            localStorage.setItem(KOS_LIST_KEY, JSON.stringify(list));
        }

        /**
         * Mengambil data satu kos berdasarkan ID.
         */
        // function getKosData(id) {
        //     if (id === null) return null;
        //     const list = getKosList();
        //     return list.find(kos => kos.id === id);
        // }
        async function getKosDataFromDB(kosId) {
            try {
                console.log('=== DEBUG getKosDataFromDB START ===');
                console.log('kosId:', kosId);
                console.log('isEditMode:', isEditMode);

                const kosList = getKosList();
                const existingKos = kosList.find(k => k.id === kosId);
                console.log('kosList length:', kosList.length);
                console.log('kosList:', kosList);
                console.log('existingKos found:', existingKos);
                console.log('existingKos?.database_id:', existingKos?.database_id);

                if (existingKos && !existingKos.database_id) {
                    console.log('📝 CREATE MODE: Using localStorage data for kosId:', kosId);
                    console.log('Calling transformKosFromLocal...');
                    return transformKosFromLocal(existingKos);
                    console.log('transformKosFromLocal result:', result);
                    console.log('=== DEBUG END ===');
                }
                console.log('📡 EDIT MODE: Fetching from API for kosId:', kosId);
                console.log('=== DEBUG END ===');
                const response = await fetch(`/api/get-kos/${kosId}`);

                if (!response.ok) {
                    throw new Error(`API returned ${response.status}`);
                }
                const dbKos = await response.json();
                return transformKosFromDB(dbKos);
            } catch (error) {
                console.error('Error loading kos data:', error);
                const kosList = getKosList();
                const localKos = kosList.find(k => k.database_id === kosId || k.id === kosId);

                if (localKos) {
                    console.log('⚠️ Using fallback data from localStorage');
                    return transformKosFromLocal(localKos);
                }
                console.log('❌ No data found anywhere');
                return null;
            }
        }


        async function deleteKosFromDatabase(localStorageId, kosName) {
            // Ambil data kos untuk dapat database_id
            const kosList = getKosList();
            const kos = kosList.find(k => k.id === localStorageId || k.database_id === localStorageId);

            if (!kos) {
                alert('Kos tidak ditemukan');
                return;
            }

            // 🎯 PAKAI database_id, BUKAN nama!
            const databaseId = kos.database_id;

            if (!databaseId) {
                alert('Kos ini belum tersimpan');
                return;
            }

            showConfirm({
                title: "Konfirmasi Hapus",
                message: `Apakah Anda yakin ingin menghapus ${kosName} ini?`,
                confirmText: "Hapus",
                cancelText: "Batal",
                onConfirm: async function () {
                    try {
                        // 🎯 PAKAI ROUTE DELETE BY ID
                        const response = await fetch(`/api/delete-kos/${databaseId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Hapus dari localStorage
                            let list = getKosList();
                            list = list.filter(k => k.id !== localStorageId);
                            saveKosList(list);

                            showModal({
                                title: "Sukses",
                                message: result.message,
                                type: "success",
                                onConfirm: () => loadKosDashboard()
                            });
                        } else {
                            showModal({
                                title: "Gagal",
                                message: result.message,
                                type: "error"
                            });
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        showModal({
                            title: "Kesalahan",
                            message: "Terjadi kesalahan: " + error.message,
                            type: "error"
                        });
                    }
                }
            });
        }

        function formatRupiah(number) {
            try {
                // Convert ke number
                const num = parseFloat(number) || 0;

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(num).replace('Rp', '').trim();
            } catch (error) {
                console.error('Error in formatRupiah:', error);
                return '0';
            }
        }

        /**
         * Helper untuk mengubah format Rupiah (tanpa "Rp") menjadi angka.
         */
        function parseRupiahToNumber(rupiahString) {
            if (!rupiahString) return 0;
            return parseInt(rupiahString.replace(/[^0-9]/g, ''), 10) || 0;
        }

        /**
         * Mengambil data dari form step 1 dan menyimpannya ke array.
         */
        function handleStep1Save() {
            const namaKos = document.getElementById('nama_kos').value.trim();
            const deskripsiKos = document.getElementById('deskripsi_kos').value.trim();
            const tipeKosElement = document.querySelector('#tipe_kos_selection .gender-button.active');

            // Validasi
            let errorMessages = [];
            if (!namaKos) errorMessages.push("• Nama Kos belum diisi");
            if (!tipeKosElement) errorMessages.push("• Tipe Kos belum dipilih");

            if (errorMessages.length > 0) {
                showModal({
                    title: "Form Belum Lengkap",
                    message: "Mohon lengkapi:\n\n" + errorMessages.join("\n"),
                    type: "warning"
                });
                return;
            }

            let list = getKosList();
            const isEditing = currentKosId !== null;

            if (isEditing) {
                // EDIT
                const kosIndex = list.findIndex(kos =>
                    kos.database_id === currentKosId || kos.id === currentKosId);
                if (kosIndex > -1) {
                    list[kosIndex] = {
                        ...list[kosIndex],
                        nama_kos: namaKos,
                        tipe_kos: tipeKosElement.dataset.tipe || tipeKosElement.textContent.trim().toLowerCase(),
                        deskripsi: deskripsiKos,
                    };
                    saveKosList(list);
                    loadContent('data_kos_step2', 2);
                }
            } else {
                // CREATE BARU
                const newKos = {
                    id: Date.now(),
                    nama_kos: namaKos,
                    tipe_kos: tipeKosElement.dataset.tipe || tipeKosElement.textContent.trim().toLowerCase(),
                    deskripsi: deskripsiKos,
                    completed: false,
                    rules: [],
                    alamat: '',
                    fasilitas: [],
                    images: { bangunan: [], kamar: [] },
                    total_kamar: 0,
                    kamar_tersedia: 0,
                    price: { monthly: 0, daily: 0, weekly: 0 }
                };
                list.push(newKos);
                currentKosId = newKos.id;
                saveKosList(list);
                loadContent('data_kos_step2', 2);
            }
        }


        function handleStep2Save() {
            // AMBIL SEMUA RULES YANG DICENTANG
            const rules = [];
            document.querySelectorAll('.rules-form input[type="checkbox"]:checked').forEach(checkbox => {
                const ruleText = checkbox.parentElement.textContent.replace('✓', '').trim();
                rules.push(ruleText);
            });

            console.log('✅ Step 2 Rules saved:', rules);

            // UPDATE KOS DATA
            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);
            if (kosIndex > -1) {
                list[kosIndex].rules = rules;
                saveKosList(list);
            }

            loadContent('data_kos_step3', 3);
        }


        /**
         * Mengambil data dari form step 3 (Alamat) dan menyimpannya.
         */
        function handleStep3Save() {
            const alamat = document.getElementById('alamat').value.trim();
            const provinsi = document.getElementById('provinsi').value.trim();
            const kabupaten = document.getElementById('kabupaten').value.trim();
            const kecamatan = document.getElementById('kecamatan').value.trim();
            const catatan = document.getElementById('catatan').value.trim();
            const lat = document.getElementById('lat').value;
            const lon = document.getElementById('lon').value;

            // Validasi
            if (!alamat || !provinsi || !kabupaten || !kecamatan) {
                alert('Alamat, provinsi, kabupaten, kecamatan wajib!');
                return;
            }

            // Opsional: Validasi koordinat
            if (!lat || !lon) {
                if (!confirm('Koordinat belum dipilih di peta. Lanjutkan tanpa koordinat?')) {
                    return;
                }
            }

            // UPDATE LOCALSTORAGE
            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                list[kosIndex].address = {
                    alamat,
                    provinsi,
                    kabupaten,
                    kecamatan,
                    catatan,
                    lat: lat || null,
                    lon: lon || null
                };
                saveKosList(list);
                console.log('✅ Step 3 Address saved with coordinates!');
            }

            loadContent('data_kos_step4', 4);  // LANJUT STEP 4
        }

        function handleStep5Save() {
            const fasilitasByCategory = {
                "fasilitasUmum": [],
                "fasilitasKamar": [],
                "fasilitasKMandi": [],
                "parkir": []
            };

            // GROUP BY CATEGORY
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                const label = cb.closest('.checkbox-container');
                const category = label.dataset.category;
                const name = label.textContent.trim();

                if (fasilitasByCategory[category]) {
                    fasilitasByCategory[category].push(name);
                }
            });

            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                list[kosIndex].fasilitas = fasilitasByCategory;
                saveKosList(list);
                console.log('✅ Step 5 Fasilitas by category:', fasilitasByCategory);
            }

            loadContent('data_kos_step6', 6);
        }


        /**
         * Mengambil data dari form step 6 (Ukuran dan Ketersediaan) dan menyimpannya.
         */
        function handleStep6Save() {
            const activeSizeButton = document.querySelector('#room_size_selection .gender-button.active');
            const totalRooms = document.getElementById('jumlah_total_kamar').value;
            const availableRooms = document.getElementById('jumlah_kamar_tersedia').value;
            const customWidth = document.getElementById('custom_width').value;
            const customLength = document.getElementById('custom_length').value;

            // Validasi form
            let errorMessages = [];

            if (!activeSizeButton) {
                errorMessages.push("• Ukuran kamar belum dipilih");
            }

            if (!totalRooms || parseInt(totalRooms) <= 0) {
                errorMessages.push("• Jumlah total kamar harus lebih dari 0");
            }

            if (!availableRooms || parseInt(availableRooms) < 0 || parseInt(availableRooms) > parseInt(totalRooms)) {
                errorMessages.push("• Jumlah kamar tersedia harus antara 0 sampai Jumlah Total Kamar");
            }

            const sizeType = activeSizeButton ? activeSizeButton.textContent.trim() : '';
            if (sizeType === 'Kustom') {
                if (!customWidth || !customLength || parseFloat(customWidth) <= 0 || parseFloat(customLength) <= 0) {
                    errorMessages.push("• Dimensi Kustom harus lebih dari 0");
                }
            }

            if (errorMessages.length > 0) {
                showModal({
                    title: "Form Belum Lengkap",
                    message: "Mohon perbaiki data berikut:\n\n" + errorMessages.join("\n"),
                    type: "warning"
                });
                return;
            }

            const sizeData = {
                type: sizeType,
                custom_w: customWidth,
                custom_l: customLength
            };

            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                list[kosIndex] = {
                    ...list[kosIndex],
                    size: sizeData,
                    total_rooms: parseInt(totalRooms),
                    available_rooms: parseInt(availableRooms),
                };
                saveKosList(list);
                loadContent('data_kos_step7', 7);

            } else {
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan.",
                    type: "error"
                });
            }
        }

        /**
         * Mengambil data dari form step 7 (Harga Sewa) dan menyimpannya.
         */
        function handleStep7Save() {
            const monthlyChecked = document.getElementById('harga_bulan_check').checked;
            const monthlyPrice = parseRupiahToNumber(document.getElementById('harga_bulan').value);

            // Validasi: jika checkbox bulanan dicentang, harganya harus > 0
            if (monthlyChecked && monthlyPrice <= 0) {
                showModal({
                    title: "Peringatan",
                    message: "Harga bulanan wajib diisi dan harus lebih dari Rp 0.",
                    type: "warning"
                });
                return;
            }

            // Jika checkbox tidak dicentang, set harga bulanan ke 0
            const finalMonthlyPrice = monthlyChecked ? monthlyPrice : 0;

            // Ambil data checkbox dan input lainnya
            const dailyChecked = document.getElementById('harga_hari_check').checked;
            const weeklyChecked = document.getElementById('harga_minggu_check').checked;
            const threeMonthlyChecked = document.getElementById('harga_3bulan_check').checked;
            const sixMonthlyChecked = document.getElementById('harga_6bulan_check').checked;
            const yearlyChecked = document.getElementById('harga_tahun_check').checked;
            const fineChecked = document.getElementById('denda_check').checked;

            // Ambil nilai harga lain dan denda
            const dailyPrice = dailyChecked ? parseRupiahToNumber(document.getElementById('harga_hari_input').value) : 0;
            const weeklyPrice = weeklyChecked ? parseRupiahToNumber(document.getElementById('harga_minggu_input').value) : 0;
            const threeMonthlyPrice = threeMonthlyChecked ? parseRupiahToNumber(document.getElementById('harga_3bulan_input').value) : 0;
            const sixMonthlyPrice = sixMonthlyChecked ? parseRupiahToNumber(document.getElementById('harga_6bulan_input').value) : 0;
            const yearlyPrice = yearlyChecked ? parseRupiahToNumber(document.getElementById('harga_tahun_input').value) : 0;

            const fineAmount = fineChecked ? parseRupiahToNumber(document.getElementById('denda_jumlah').value) : 0;
            const fineLimit = fineChecked ? document.getElementById('batas_waktu_hari').value : 0;

            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                list[kosIndex] = {
                    ...list[kosIndex],
                    price: {
                        monthly: finalMonthlyPrice,
                        daily: dailyPrice,
                        weekly: weeklyPrice,
                        three_monthly: threeMonthlyPrice,
                        six_monthly: sixMonthlyPrice,
                        yearly: yearlyPrice,
                        set_fine: fineChecked,
                        fine_amount: fineAmount,
                        fine_limit: parseInt(fineLimit),
                    }
                };
                saveKosList(list);
                loadContent('data_kos_step8', 8);

            } else {
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan.",
                    type: "error"
                });
            }
        }

        /**
         * Mengambil data dari form step 6 Detail (Per kamar) dan menyimpannya.
         */
        function handleStep6DetailSave() {
            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                const totalRooms = list[kosIndex].total_rooms;
                const newRoomDetails = [];
                let availableCount = 0;

                for (let i = 1; i <= totalRooms; i++) {
                    const nomorKamarInput = document.getElementById(`nomor_kamar_${i}`);
                    const lantaiInput = document.getElementById(`lantai_${i}`);
                    const terisiCheckbox = document.getElementById(`terisi_kamar_${i}`);

                    if (nomorKamarInput) {
                        const isFilled = terisiCheckbox ? terisiCheckbox.checked : false;

                        if (!isFilled) {
                            availableCount++;
                        }

                        newRoomDetails.push({
                            id: i,
                            nomor: nomorKamarInput.value || `Kamar ${i}`,
                            lantai: lantaiInput.value || '1',
                            terisi: isFilled,
                        });
                    }
                }

                // Simpan data detail kamar dan update jumlah kamar tersedia
                list[kosIndex] = {
                    ...list[kosIndex],
                    room_details: newRoomDetails,
                    available_rooms: availableCount,
                };

                saveKosList(list);

                showModal({
                    title: "Sukses",
                    message: `Detail kamar berhasil disimpan dan jumlah kamar tersedia diperbarui menjadi ${availableCount} kamar!`,
                    type: "success",
                    onConfirm: () => {
                        loadContent('data_kos_step6', 6);
                    }
                });
            } else {
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan.",
                    type: "error"
                });
            }
        }

        function removeKosDraftFromLocalStorage(kosId) {
            const list = getKosList();
            const filteredList = list.filter(k => k.id !== kosId);
            saveKosList(filteredList);
            console.log('🗑️ Removed draft from localStorage');
        }

        async function finalizeKosSubmission() {
            let list = getKosList();
            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);

            if (kosIndex > -1) {
                let kosData = list[kosIndex]; // ganti const jadi let

                // ========== ✅ TAMBAH INI DI SINI ==========
                // FIX: BUAT room_details JIKA KOSONG
                if (!kosData.room_details || !Array.isArray(kosData.room_details) || kosData.room_details.length === 0) {
                    const totalKamar = kosData.total_rooms || kosData.total_kamar || 2;
                    kosData.room_details = [];

                    for (let i = 1; i <= totalKamar; i++) {
                        kosData.room_details.push({
                            nomor: `Kamar ${i}`,
                            lantai: 1,
                            terisi: false
                        });
                    }

                    // Update juga di LocalStorage
                    list[kosIndex].room_details = kosData.room_details;
                    console.log(`✅ Buat ${totalKamar} kamar otomatis`);
                } else {
                    console.log(`✅ Pakai ${kosData.room_details.length} kamar dari Step 6`);
                }
                // ========== SAMPE SINI ==========

                // 🎯 1. TANDAI SEBAGAI SELESAI DI LOCALSTORAGE
                list[kosIndex].completed = true;
                saveKosList(list);

                // ... sisanya TETAP SAMA seperti kode kamu
                // 🎯 2. TAMPILKAN LOADING
                showModal({
                    title: "Menyimpan...",
                    message: "Sedang menyimpan data ke server...",
                    type: "info",
                    showCancel: false,
                    showConfirm: false
                });

                try {
                    const dataToSend = { ...kosData };

                    // Jika kos sudah punya database_id, kirim untuk UPDATE
                    // Jika tidak ada database_id, berarti KOS BARU
                    if (kosData.database_id) {
                        dataToSend.database_id = kosData.database_id;
                        console.log(`🔄 Mode UPDATE untuk database_id: ${kosData.database_id}`);
                    } else {
                        console.log('🆕 Mode CREATE kos baru');
                        // Untuk kos baru, pastikan tidak ada database_id
                        delete dataToSend.database_id;
                    }

                    // 🎯 3. KIRIM KE DATABASE LARVEL (PAKAI ROUTE YANG UDAH ADA!)
                    const response = await fetch('/api/save-local-kos', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(dataToSend) // ← Kirim dataToSend, bukan kosData langsung
                    });

                    const result = await response.json();
                    console.log('Response dari server:', result);

                    if (result.success) {
                        // 🎯 4. UPDATE DATA DI LOCALSTORAGE DENGAN INFO DARI SERVER
                        list[kosIndex].synced = true;
                        list[kosIndex].database_id = result.kos_id;
                        list[kosIndex].is_new = result.is_new;
                        saveKosList(list);

                        // 🎯 5. TAMPILKAN SUKSES
                        showModal({
                            title: "✅ " + (result.is_new ? "Kos Baru Berhasil!" : "Data Diperbarui!"),
                            message: result.message,
                            type: "success",
                            onConfirm: () => {
                                removeKosDraftFromLocalStorage(currentKosId);
                                loadKosDashboard();
                            }
                        });

                    } else {
                        throw new Error(result.message || 'Server gagal menyimpan');
                    }

                } catch (error) {
                    console.error('❌ Error save ke database:', error);

                    // 🎯 6. FALLBACK: TANDAI BELUM SYNC
                    list[kosIndex].synced = false;
                    list[kosIndex].error = error.message;
                    saveKosList(list);

                    // 🎯 7. TAMPILKAN WARNING
                    showModal({
                        title: "⚠️ Peringatan",
                        message: `Data kos disimpan di browser, tapi gagal ke server:<br>
                         <small style="color: #666;">${error.message}</small><br><br>
                         Klik "Simpan ke Database" nanti untuk coba lagi.`,
                        type: "warning",
                        onConfirm: () => {
                            loadKosDashboard();
                        }
                    });
                }
            } else {
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan di LocalStorage.",
                    type: "error"
                });
            }
        }

        /**
         * Fungsi untuk inisialisasi form step 1 dengan data yang sudah ada (untuk Edit).
         */
        function initializeStep1Form(data) {
            const namaInput = document.getElementById('nama_kos');
            const deskripsiTextarea = document.getElementById('deskripsi_kos');
            const genderButtons = document.querySelectorAll('#tipe_kos_selection .gender-button');
            const charCount = document.querySelector('#kos_form_step1 small');

            if (!namaInput || !deskripsiTextarea || genderButtons.length === 0 || !charCount) {
                console.error('Form step 1 elements not found!');
                return;
            }


            // Reset event listeners
            genderButtons.forEach(btn => {
                btn.onclick = null;
            });

            // Isi data jika ada
            if (data) {
                namaInput.value = data.nama_kos || '';
                deskripsiTextarea.value = data.deskripsi || '';

                const length = deskripsiTextarea.value.length;
                charCount.textContent = `${length}/200`;

                genderButtons.forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.dataset.tipe === data.tipe_kos) {
                        btn.classList.add('active');
                    }
                });
            } else {
                // Reset tampilan jika kos baru
                namaInput.value = '';
                deskripsiTextarea.value = '';
                charCount.textContent = `0/200`;
                genderButtons.forEach(btn => btn.classList.remove('active'));
                if (genderButtons.length > 0) {
                    genderButtons[0].classList.add('active'); // Set 'Putra' default
                }
            }

            // Tambahkan event listener untuk gender buttons dan deskripsi
            genderButtons.forEach(btn => {
                btn.onclick = function () {
                    genderButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                };
            });

            // Hapus event listener lama jika ada
            const oldTextarea = deskripsiTextarea.cloneNode(true);
            deskripsiTextarea.parentNode.replaceChild(oldTextarea, deskripsiTextarea);

            // Tambahkan event listener baru
            const newTextarea = document.getElementById('deskripsi_kos');
            newTextarea.addEventListener('input', () => {
                const length = newTextarea.value.length;
                if (length <= 200) {
                    charCount.textContent = `${length}/200`;
                } else {
                    newTextarea.value = newTextarea.value.substring(0, 200);
                    charCount.textContent = `200/200`;
                }
            });
        }

        /**
         * Fungsi untuk inisialisasi form step 3 (Alamat) dengan data yang sudah ada (untuk Edit).
         */
        function initializeStep3Form(data) {
            if (!data || !data.address) return;
            document.getElementById('alamat').value = data.address.alamat || '';
            document.getElementById('catatan').value = data.address.catatan || '';
            document.getElementById('provinsi').value = data.address.provinsi || '';
            document.getElementById('kabupaten').value = data.address.kabupaten || '';
            document.getElementById('kecamatan').value = data.address.kecamatan || '';
        }

        /**
         * Fungsi untuk inisialisasi form step 4 (Unggah Gambar) dengan data yang sudah ada.
         */
        function initializeStep4Form(data) {
            // Jika data kos tidak punya properti images, kita inisialisasi
            if (data && !data.images) {
                data.images = { bangunan: [], kamar: [] };
            }
            if (!data || !data.images) return;

            const renderImages = (containerId, imagesArray, type) => {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                // Ensure imagesArray is an array
                const validImages = Array.isArray(imagesArray) ? imagesArray : [];

                validImages.forEach((url, index) => {
                    const card = document.createElement('div');
                    card.className = 'photo-card';
                    card.innerHTML = `
                        <img src="${url}" alt="Foto Kos" class="photo-preview">
                        <button class="remove-btn" onclick="removeImage('${type}', ${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(card);
                });

                // Tambahkan tombol 'Tambah Foto' hanya jika belum mencapai batas maksimal
                let maxPhotos;
                if (type === 'bangunan') {
                    maxPhotos = 1;
                } else if (type === 'kamar') {
                    maxPhotos = 10;
                } else {
                    maxPhotos = 5;
                }

                if (validImages.length < maxPhotos) {
                    const addBtn = document.createElement('div');
                    addBtn.className = 'photo-card add-new-card';
                    addBtn.onclick = () => document.getElementById(`upload-input-${type}`).click();
                    addBtn.innerHTML = `
                        <i class="fas fa-camera" style="font-size: 30px; color: var(--text-light); margin-bottom: 10px;"></i>
                        <p style="color: var(--text-light);">+ Tambah Foto</p>
                    `;
                    container.appendChild(addBtn);
                }
            };

            renderImages('galeri_bangunan', data.images.bangunan, 'bangunan');
            renderImages('galeri_kamar', data.images.kamar, 'kamar');

            // Re-attach event listeners for file inputs (PENTING)
            document.getElementById('upload-input-bangunan').onchange = (e) => handleImageUpload(e.target, 'bangunan');
            document.getElementById('upload-input-kamar').onchange = (e) => handleImageUpload(e.target, 'kamar');
        }

        function initStep5Form(data) {
            if (!data?.fasilitas) return;

            // FLATTEN FASILITAS
            const allFasilitas = Object.values(data.fasilitas).flat();

            // CHECK MATCHING CHECKBOX
            document.querySelectorAll('.checkbox-container input[type="checkbox"]').forEach(cb => {
                const labelText = cb.parentElement.childNodes[0].textContent.trim();
                if (allFasilitas.includes(labelText)) {
                    cb.checked = true;
                }
            });
        }


        /**
         * Fungsi untuk inisialisasi form step 6 dengan data yang sudah ada (untuk Edit).
         */
        function initializeStep6Form(data) {
            const totalInput = document.getElementById('jumlah_total_kamar');
            const availableInput = document.getElementById('jumlah_kamar_tersedia');

            if (data) {
                totalInput.value = data.total_rooms || '';
                availableInput.value = data.available_rooms || '';
                setupRoomSizeSelection(data);
            } else {
                totalInput.value = '';
                availableInput.value = '';
                setupRoomSizeSelection({ size: { type: '3 x 4', custom_w: '3', custom_l: '4' } });
            }
        }

        /**
         * Fungsi untuk inisialisasi form step 7 (Harga Sewa) dengan data yang sudah ada (untuk Edit).
         */
        function initializeStep7Form(data) {
            if (!data || !data.price) return;
            const priceData = data.price;

            // 1. Harga Bulanan dengan checkbox
            document.getElementById('harga_bulan_check').checked = priceData.monthly > 0;

            const monthlyInput = document.getElementById('harga_bulan');
            if (monthlyInput) {
                monthlyInput.value = formatRupiah(priceData.monthly);
                monthlyInput.addEventListener('input', function () {
                    this.value = formatRupiah(parseRupiahToNumber(this.value));
                });

                toggleMonthlyPriceInput();
            }

            // 2. Harga Lain
            document.getElementById('harga_hari_check').checked = priceData.daily > 0;
            document.getElementById('harga_minggu_check').checked = priceData.weekly > 0;
            document.getElementById('harga_3bulan_check').checked = priceData.three_monthly > 0;
            document.getElementById('harga_6bulan_check').checked = priceData.six_monthly > 0;
            document.getElementById('harga_tahun_check').checked = priceData.yearly > 0;

            const prices = [
                { id: 'harga_hari_input', value: priceData.daily },
                { id: 'harga_minggu_input', value: priceData.weekly },
                { id: 'harga_3bulan_input', value: priceData.three_monthly },
                { id: 'harga_6bulan_input', value: priceData.six_monthly },
                { id: 'harga_tahun_input', value: priceData.yearly },
            ];

            prices.forEach(p => {
                const inputEl = document.getElementById(p.id);
                if (inputEl) {
                    inputEl.value = formatRupiah(p.value);
                    inputEl.addEventListener('input', function () {
                        this.value = formatRupiah(parseRupiahToNumber(this.value));
                    });
                }
            });

            // 3. Denda
            document.getElementById('denda_check').checked = priceData.set_fine;
            document.getElementById('denda_jumlah').value = formatRupiah(priceData.fine_amount);
            document.getElementById('batas_waktu_hari').value = priceData.fine_limit;

            const fineInput = document.getElementById('denda_jumlah');
            if (fineInput) {
                fineInput.addEventListener('input', function () {
                    this.value = formatRupiah(parseRupiahToNumber(this.value));
                });
            }

            togglePriceInputVisibility('harga_hari_check', 'harga_hari_input');
            togglePriceInputVisibility('harga_minggu_check', 'harga_minggu_input');
            togglePriceInputVisibility('harga_3bulan_check', 'harga_3bulan_input');
            togglePriceInputVisibility('harga_6bulan_check', 'harga_6bulan_input');
            togglePriceInputVisibility('harga_tahun_check', 'harga_tahun_input');
            toggleFineInputVisibility();
        }

        /**
         * Mengaktifkan/menonaktifkan input harga bulanan
         */
        function toggleMonthlyPriceInput() {
            const checkbox = document.getElementById('harga_bulan_check');
            const input = document.getElementById('harga_bulan');

            if (checkbox && input) {
                const updateInput = () => {
                    input.disabled = !checkbox.checked;
                    input.style.backgroundColor = checkbox.checked ? 'white' : '#f0f0f0';
                    if (!checkbox.checked) {
                        input.value = '0';
                    }
                };

                updateInput();
                checkbox.onchange = updateInput;
            }
        }

        /**
         * Helper untuk mengaktifkan/menonaktifkan input harga non-bulanan
         */
        function togglePriceInputVisibility(checkboxId, inputId) {
            const checkbox = document.getElementById(checkboxId);
            const input = document.getElementById(inputId);

            if (checkbox && input) {
                const updateInput = () => {
                    input.disabled = !checkbox.checked;
                    input.style.backgroundColor = checkbox.checked ? 'white' : '#f0f0f0';
                    input.value = checkbox.checked ? input.value : '0';
                    if (!checkbox.checked) input.value = '0';
                };

                updateInput();
                checkbox.onchange = updateInput;
            }
        }

        /**
         * Helper untuk mengaktifkan/menonaktifkan input denda
         */
        function toggleFineInputVisibility() {
            const checkbox = document.getElementById('denda_check');
            const amountInput = document.getElementById('denda_jumlah');
            const limitSelect = document.getElementById('batas_waktu_hari');

            if (checkbox && amountInput && limitSelect) {
                const updateInputs = () => {
                    const isDisabled = !checkbox.checked;
                    amountInput.disabled = isDisabled;
                    limitSelect.disabled = isDisabled;
                    amountInput.style.backgroundColor = isDisabled ? '#f0f0f0' : 'white';
                    limitSelect.style.backgroundColor = isDisabled ? '#f0f0f0' : 'white';
                    if (isDisabled) amountInput.value = '0';
                };

                updateInputs();
                checkbox.onchange = updateInputs;
            }
        }

        /**
         * Logika untuk mengaktifkan/menonaktifkan input kustom pada Step 6.
         */
        function setupRoomSizeSelection(data) {
            const sizeButtons = document.querySelectorAll('#room_size_selection .gender-button');
            const customInputsDiv = document.getElementById('custom_size_inputs');
            const customWidthInput = document.getElementById('custom_width');
            const customLengthInput = document.getElementById('custom_length');

            // Hapus event listeners lama
            sizeButtons.forEach(btn => {
                btn.onclick = null;
            });

            // Fungsi untuk memperbarui tampilan input kustom
            const updateCustomInputsVisibility = (sizeType) => {
                if (sizeType === 'Kustom') {
                    customInputsDiv.style.display = 'flex';
                } else {
                    customInputsDiv.style.display = 'none';
                }
            };

            // Handle klik tombol ukuran
            sizeButtons.forEach(btn => {
                btn.onclick = function () {
                    sizeButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const selectedSize = this.textContent.trim();
                    updateCustomInputsVisibility(selectedSize);

                    // Set nilai input kustom jika ukuran standar dipilih
                    if (selectedSize.includes('x')) {
                        const [w, l] = selectedSize.split(' x ');
                        customWidthInput.value = w.trim();
                        customLengthInput.value = l.trim();
                    } else if (selectedSize === 'Kustom') {
                        // Biarkan nilai input kustom jika 'Kustom' dipilih
                    }
                };
            });

            // Inisialisasi tampilan berdasarkan data yang ada
            let activeType = '3 x 4'; // Default
            if (data && data.size && data.size.type) {
                activeType = data.size.type;
            }

            // Set active button
            sizeButtons.forEach(btn => {
                if (btn.textContent.trim() === activeType.trim()) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Set custom input values
            if (data && data.size) {
                customWidthInput.value = data.size.custom_w || '';
                customLengthInput.value = data.size.custom_l || '';
            } else {
                customWidthInput.value = '3';
                customLengthInput.value = '4';
            }

            updateCustomInputsVisibility(activeType);
        }

        /**
         * Mengubah ukuran gambar menggunakan Canvas sebelum disimpan.
         */
        function resizeImage(base64Str, maxWidth = 1024, maxHeight = 1024, quality = 0.8) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = function () {
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    resolve(canvas.toDataURL('image/jpeg', quality));
                };
                img.src = base64Str;
            });
        }

        /**
         * SIMULASI UNGGAH GAMBAR BARU (DENGAN RESIZING) - DIPERBAIKI
         */
        async function handleImageUpload(inputElement, type) {
            console.log('Upload dimulai, type:', type);
            console.log('isEditMode:', isEditMode);
            console.log('currentKosId:', currentKosId);

            if (!currentKosId) {
                console.log('ERROR: currentKosId null');
                showModal({
                    title: "Peringatan",
                    message: "Silakan simpan data Kos (Langkah 1) terlebih dahulu.",
                    type: "warning",
                    onConfirm: () => {
                        inputElement.value = '';
                    }
                });
                return;
            }

            if (inputElement.files && inputElement.files[0]) {
                const file = inputElement.files[0];
                console.log('File yang dipilih:', file.name, 'Size:', file.size, 'Type:', file.type);

                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    showModal({
                        title: "Peringatan",
                        message: "Ukuran file terlalu besar. Maksimal 5MB.",
                        type: "warning",
                        onConfirm: () => {
                            inputElement.value = '';
                        }
                    });
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showModal({
                        title: "Peringatan",
                        message: "Format file tidak didukung. Gunakan JPG, PNG, atau WebP.",
                        type: "warning",
                        onConfirm: () => {
                            inputElement.value = '';
                        }
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = async function (e) {
                    try {
                        inputElement.disabled = true;

                        console.log('Memulai resize gambar...');
                        const resizedImageUrl = await resizeImage(e.target.result, 1024, 1024, 0.7);

                        let list = getKosList();
                        const kosIndex = list.findIndex(kos => {
                            if (isEditMode) {
                                return kos.database_id === currentKosId;
                            } else {
                                return kos.id === currentKosId;
                            }
                        });
                        console.log('Found kosIndex for upload:', kosIndex);

                        if (kosIndex > -1) {
                            const data = list[kosIndex];

                            data.images = data.images || {};
                            data.images[type] = data.images[type] || [];

                            let maxPhotos;
                            if (type === 'bangunan') {
                                maxPhotos = 1;
                            } else if (type === 'kamar') {
                                maxPhotos = 10;
                            } else {
                                maxPhotos = 5;
                            }

                            if (data.images[type].length >= maxPhotos) {
                                showModal({
                                    title: "Peringatan",
                                    message: `Maksimal ${maxPhotos} foto untuk bagian ini.`,
                                    type: "warning",
                                    onConfirm: () => {
                                        inputElement.value = '';
                                    }
                                });
                                return;
                            }

                            if (type === 'bangunan') {
                                data.images[type] = [resizedImageUrl];
                            } else {
                                data.images[type].push(resizedImageUrl);
                            }

                            saveKosList(list);
                            console.log('Gambar berhasil disimpan!');
                            loadContent('data_kos_step4', 4);

                        } else {
                            showModal({
                                title: "Kesalahan",
                                message: "Data kos tidak ditemukan. Tidak dapat menyimpan gambar.",
                                type: "error",
                                onConfirm: () => {
                                    inputElement.value = '';
                                }
                            });
                        }
                    } catch (error) {
                        console.error("Gagal memproses gambar:", error);
                        showModal({
                            title: "Kesalahan",
                            message: "Gagal memproses gambar: " + error.message,
                            type: "error",
                            onConfirm: () => {
                                inputElement.value = '';
                                inputElement.disabled = false;
                            }
                        });
                    } finally {
                        inputElement.disabled = false;
                    }
                };

                reader.onerror = function () {
                    showModal({
                        title: "Kesalahan",
                        message: "Gagal membaca file. Coba file lain.",
                        type: "error",
                        onConfirm: () => {
                            inputElement.value = '';
                        }
                    });
                };

                reader.readAsDataURL(file);
            } else {
                console.log('Tidak ada file yang dipilih');
            }
        }

        /**
         * Menghapus gambar dari LocalStorage.
         */
        function removeImage(type, index) {
            if (!currentKosId) return;
            console.log('removeImage called:', { type, index, isEditMode, currentKosId });

            showConfirm({
                title: "Konfirmasi Hapus",
                message: "Anda yakin ingin menghapus foto ini?",
                confirmText: "Hapus",
                cancelText: "Batal",
                onConfirm: function () {
                    let list = getKosList();
                    const kosIndex = list.findIndex(kos => {
                        if (isEditMode) {
                            return kos.database_id === currentKosId;
                        } else {
                            return kos.id === currentKosId;
                        }
                    });
                    console.log('Found kosIndex:', kosIndex);
                    console.log('List length:', list.length);

                    if (kosIndex > -1) {
                        const data = list[kosIndex];
                        data.images = data.images || {};
                        data.images[type] = data.images[type] || [];

                        if (data.images[type] && index >= 0 && index < data.images[type].length) {
                            data.images[type].splice(index, 1);
                            saveKosList(list);

                            setTimeout(() => {
                                loadContent('data_kos_step4', 4);
                            }, 100);
                        } else {
                            console.error('Invalid index');
                            showModal({
                                title: "Kesalahan",
                                message: "Foto tidak ditemukan.",
                                type: "error"
                            });
                        }
                    } else {
                        console.error('Kos not found in localStorage');
                        showModal({
                            title: "Kesalahan",
                            message: "Data kos tidak ditemukan.",
                            type: "error"
                        });
                    }
                }
            });
        }


        function transformKosFromDB(dbKos) {
            return {
                id: dbKos.id,
                nama_kos: dbKos.nama_kos,
                tipe_kos: dbKos.tipe_kos,
                deskripsi: dbKos.deskripsi,
                total_rooms: dbKos.total_kamar,
                available_rooms: dbKos.kamar_tersedia,
                completed: true,
                synced: true,
                address: {
                    alamat: dbKos.alamat_kos?.alamat || '',
                    provinsi: dbKos.alamat_kos?.provinsi || '',
                    kabupaten: dbKos.alamat_kos?.kabupaten || '',
                    kecamatan: dbKos.alamat_kos?.kecamatan || '',
                    catatan: dbKos.alamat_kos?.catatan_alamat || '',
                    lat: dbKos.alamat_kos?.lat || null,
                    lon: dbKos.alamat_kos?.lon || null
                },
                size: { type: '3 x 4' },
                room_details: dbKos.kamar?.map(k => ({
                    nomor: k.nama_kamar,
                    lantai: k.lantai,
                    terisi: k.status === 'terisi'
                })) || [],
                images: {
                    bangunan: dbKos.foto_kos
                        ?.filter(f => f.tipe === 'bangunan')
                        .map(f => {
                            if (!f.path_foto) return '';

                            // Jika sudah ada prefix
                            if (f.path_foto.startsWith('data:image')) {
                                return f.path_foto;
                            }

                            // Jika base64 JPEG (mulai dengan /9j/)
                            if (f.path_foto.startsWith('/9j/')) {
                                return 'data:image/jpeg;base64,' + f.path_foto;
                            }

                            // Jika base64 PNG (mulai dengan iVBOR)
                            if (f.path_foto.startsWith('iVBOR')) {
                                return 'data:image/png;base64,' + f.path_foto;
                            }

                            return f.path_foto;
                        }) || [],

                    kamar: dbKos.foto_kos
                        ?.filter(f => f.tipe === 'kamar')
                        .map(f => {
                            if (!f.path_foto) return '';
                            if (f.path_foto.startsWith('data:image')) return f.path_foto;
                            if (f.path_foto.startsWith('/9j/')) return 'data:image/jpeg;base64,' + f.path_foto;
                            if (f.path_foto.startsWith('iVBOR')) return 'data:image/png;base64,' + f.path_foto;
                            return f.path_foto;
                        }) || []
                },
                fasilitas: {
                    fasilitasUmum: dbKos.fasilitas?.filter(f => f.kategori === 'fasilitasUmum').map(f => f.nama_fasilitas) || [],
                    fasilitasKamar: dbKos.fasilitas?.filter(f => f.kategori === 'fasilitasKamar').map(f => f.nama_fasilitas) || [],
                    fasilitasKMandi: dbKos.fasilitas?.filter(f => f.kategori === 'fasilitasKMandi').map(f => f.nama_fasilitas) || [],
                    parkir: dbKos.fasilitas?.filter(f => f.kategori === 'parkir').map(f => f.nama_fasilitas) || []
                },
                rules: dbKos.peraturan?.map(p => p.nama_peraturan) || [],
                price: (() => {
                    const price = {
                        monthly: 0,
                        daily: 0,
                        weekly: 0,
                        three_monthly: 0,
                        six_monthly: 0,
                        yearly: 0,
                        set_fine: false,
                        fine_amount: 0,
                        fine_limit: 0
                    };

                    const hargaSewa = dbKos.kamar?.[0]?.harga_sewa;
                    if (hargaSewa) {
                        const dendaData = hargaSewa.find(h => h.denda_per_hari > 0);
                        if (dendaData) {
                            price.set_fine = true;
                            price.fine_amount = dendaData.denda_per_hari;
                            price.fine_limit = dendaData.batas_hari_denda;
                        }
                        hargaSewa.forEach(h => {
                            switch (h.periode) {
                                case 'harian': price.daily = h.harga; break;
                                case 'mingguan': price.weekly = h.harga; break;
                                case 'bulanan': price.monthly = h.harga; break;
                                case '3_bulanan': price.three_monthly = h.harga; break;
                                case '6_bulanan': price.six_monthly = h.harga; break;
                                case 'tahunan': price.yearly = h.harga; break;
                            }
                        });
                    }
                    return price;
                })()
            };
        }

        function transformKosFromLocal(localKos) {
            return {
                id: localKos.id,
                nama_kos: localKos.nama_kos || '',
                tipe_kos: localKos.tipe_kos || 'campur',
                deskripsi: localKos.deskripsi || '',
                total_rooms: localKos.total_rooms || localKos.total_kamar || 0,
                available_rooms: localKos.available_rooms || localKos.kamar_tersedia || 0,
                completed: localKos.completed || false, // ← BISA FALSE UNTUK DRAFT
                synced: localKos.synced || false,       // ← BISA FALSE UNTUK DRAFT
                address: localKos.address || {
                    alamat: '',
                    provinsi: '',
                    kabupaten: '',
                    kecamatan: '',
                    catatan: '',
                    lat: null,
                    lon: null
                },
                size: localKos.size || { type: '3 x 4' },
                room_details: localKos.room_details || [],
                images: localKos.images || {
                    bangunan: [],
                    kamar: []
                },
                fasilitas: localKos.fasilitas || {
                    fasilitasUmum: [],
                    fasilitasKamar: [],
                    fasilitasKMandi: [],
                    parkir: []
                },
                rules: localKos.rules || [],
                price: localKos.price ? {
                    monthly: localKos.price.monthly || 0,
                    daily: localKos.price.daily || 0,
                    weekly: localKos.price.weekly || 0,
                    three_monthly: localKos.price.three_monthly || 0,
                    six_monthly: localKos.price.six_monthly || 0,
                    yearly: localKos.price.yearly || 0,
                    set_fine: localKos.price.set_fine || false,
                    fine_amount: localKos.price.fine_amount || 0,
                    fine_limit: localKos.price.fine_limit || 0
                } : {
                    monthly: 0,
                    daily: 0,
                    weekly: 0,
                    three_monthly: 0,
                    six_monthly: 0,
                    yearly: 0,
                    set_fine: false,
                    fine_amount: 0,
                    fine_limit: 0
                }
            };
        }

        // ==========================================================
        // 3. KUMPULAN TEMPLATE HTML 
        // ==========================================================
        const templates = {
            'home': async () => {
                const monthly = window.dashboardData?.monthlyRevenue || 0;
                const total = window.dashboardData?.totalRevenue || 0;
                const userName = window.dashboardData?.userName || 'User';
                
                return `
                    <section class="dashboard-page">
                        <div class="greeting-section">
                            <div class="greeting-box">
                                <h2>Hallo</h2>
                                <p>{{ auth()->user()->name }}</p>
                            </div>
                            <div class="data-box monthly-revenue">
                                <p>Pendapatan Bulanan <span class="month">{{ now()->translatedFormat('F Y') }}</span></p>
                                <h3>Rp. {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                            </div>
                            <div class="data-box total-revenue">
                                <p>Pendapatan Total</p>
                                <h3>Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </section>
                `;
            },

            // TEMPLATE UNTUK DASHBOARD KOS SAYA - DIPERBAIKI
            'kos_saya_dashboard': async () => {
                try {
                    const response = await fetch('/api/get-kos');
                    const dbKosList = await response.json();
                    const kosList = dbKosList.map(transformKosFromDB);
                    let kosCardsHtml = '';

                    const addButton = `
                    <div style="text-align: right; margin-bottom: 20px;">
                        <a href="#" class="btn btn-primary" onclick="startAddNewKos();">
                            <i class="fas fa-plus-circle"></i> Tambahkan Kos Baru
                        </a>
                    </div>
                `;

                    if (kosList.length > 0) {
                        kosCardsHtml = kosList.map(kos => {
                            // const frontPhotoUrl = (kos.images && kos.images.bangunan && kos.images.bangunan.length > 0)
                            //     ? kos.images.bangunan[0]
                            //     : IMAGE_PLACEHOLDER;
                            const frontPhotoUrl = kos.images.bangunan[0] || IMAGE_PLACEHOLDER;

                            const getPriceDisplay = (priceData) => {
                                if (!priceData) return 'Harga Belum Diatur';

                                let display = [];
                                if (priceData.monthly > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.monthly)}/bln`);
                                }
                                if (priceData.three_monthly > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.three_monthly)}/3bln`);
                                }
                                if (priceData.six_monthly > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.six_monthly)}/6bln`);
                                }
                                if (priceData.yearly > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.yearly)}/thn`);
                                }
                                if (priceData.daily > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.daily)}/hari`);
                                }
                                if (priceData.weekly > 0) {
                                    display.push(`Rp ${formatRupiah(priceData.weekly)}/minggu`);
                                }

                                if (display.length === 1) {
                                    return display[0];
                                }

                                if (display.length > 1) {
                                    return `${display[0]} (+${display.length - 1} pilihan lain)`;
                                }

                                return 'Harga Belum Diatur';
                            };

                            const priceDisplay = getPriceDisplay(kos.price);
                            const syncStatus = kos.synced;

                            return `
                            <div class="dashboard-card kos-detail-card">
                                
                                <div class="kos-detail-info">
                                    <h3>Detail Kos: ${kos.nama_kos}</h3>
                                    <div class="kos-detail-item"><strong>Status:</strong> ${syncStatus ? '<span style="color: var(--primary-color);">Telah Disubmit</span>' : '<span style="color: #FFB300;">Dalam Proses Pengisian</span>'}</div>
                                    <div class="kos-detail-item"><strong>Tipe:</strong> ${kos.tipe_kos}</div>
                                    <div class="kos-detail-item"><strong>Ukuran:</strong> ${kos.size ? kos.size.type : '-'} (${kos.size && kos.size.type === 'Kustom' ? `${kos.size.custom_w}x${kos.size.custom_l}` : ''})</div>
                                    
                                    <div class="kos-detail-item price-info">
                                        <strong>Harga Kamar:</strong> 
                                        <span>${priceDisplay}</span>
                                    </div>
                                    
                                    <div class="kos-actions">
                                        <a href="#" class="btn btn-secondary" onclick="viewKosDetail(${kos.id})">Lihat Detail</a>
                                        <a href="#" class="btn btn-primary" onclick="startEditKos(${kos.id})">Edit Data Kos</a>
                                        <button class="btn btn-danger" onclick="deleteKosFromDatabase(${kos.id}, '${kos.nama_kos}')">Hapus Kos</button>
                                    </div>
                                </div>
                                
                                <div class="kos-photo-display">
                                    <img src="${frontPhotoUrl}" alt="Foto Depan Kos">
                                </div>
                                
                            </div>
                        `;
                        }).join('');
                    } else {
                        kosCardsHtml = `
                        <div class="dashboard-card empty-state" style="margin-bottom: 20px;">
                            <h3>Anda Belum Memiliki Kos Terdaftar</h3>
                            <p>Mulai dengan mengklik tombol 'Tambahkan Kos Baru' di atas.</p>
                        </div>
                    `;
                    }

                    return `
                    <section>
                        <h2><i class="fas fa-house-user"></i> Daftar Kos Saya (${kosList.length} Kos)</h2>
                        ${addButton}
                        ${kosCardsHtml}
                    </section>
                `;
                } catch (error) {
                    console.error("Gagal memuat data kos:", error);
                    return `
                        <section class="dashboard-page">
                            <h2>Gagal Memuat Data Kos</h2>
                            <p>Terjadi kesalahan saat mengambil data kos. Silakan coba lagi nanti.</p>
                        </section>
                    `;
                }
            },

            // TEMPLATE UNTUK TAMPILAN DETAIL LENGKAP KOS
            'kos_detail_view': (kosData) => {
                if (!kosData) {
                    return `<section class="dashboard-page"><h2>Data Kos Tidak Ditemukan</h2><div class="form-actions"><a href="#" class="btn btn-primary" onclick="loadKosDashboard();">Kembali ke Daftar Kos</a></div></section>`;
                }

                const renderGallery = (imagesArray, title) => {
                    const validImages = Array.isArray(imagesArray) ? imagesArray : [];
                    if (validImages.length === 0) return `<h4>${title}</h4><p>Belum ada foto yang diunggah.</p><hr style="margin: 10px 0;">`;

                    const galleryHtml = validImages.map(url => `
                        <div style="width: 100px; height: 75px; border: 1px solid var(--border-color); border-radius: 4px; overflow: hidden; margin-right: 10px; margin-bottom: 10px;">
                            <img src="${url}" alt="Foto Kos" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    `).join('');

                    return `
                        <h4>${title} (${validImages.length} Foto)</h4>
                        <div style="display: flex; flex-wrap: wrap; margin-top: 10px; margin-bottom: 20px;">
                            ${galleryHtml}
                        </div>
                        <hr style="margin: 10px 0;">
                    `;
                };

                const renderSimulationData = (title, data) => `
                    <div style="margin-top: 10px;">
                        <strong>${title}:</strong> ${data}
                    </div>
                `;

                const renderRoomDetails = (details) => {
                    const validDetails = Array.isArray(details) ? details : [];
                    if (validDetails.length === 0) return '<p>Belum ada detail kamar individual yang disimpan.</p>';

                    const listHtml = validDetails.map(room => `
                        <li style="margin-bottom: 5px; padding: 5px 0; border-bottom: 1px dashed #eee;">
                            <strong style="display: inline-block; width: 140px;">${room.nomor}</strong> 
                            Lantai ${room.lantai || '1'} - 
                            <span style="font-weight: 600; color: ${room.terisi ? '#d9534f' : '#5cb85c'};">
                                ${room.terisi ? 'TERISI' : 'KOSONG'}
                            </span>
                        </li>
                    `).join('');

                    return `
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">4. Detail Kamar Individual (${validDetails.length} Kamar)</h3>
                            <ul style="list-style: none; padding: 0;">
                                ${listHtml}
                            </ul>
                        </div>
                    `;
                };

                const getAllPriceDisplay = (priceData) => {
                    if (!priceData) return '<p>Harga belum diatur</p>';

                    let html = '';
                    if (priceData.monthly > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per Bulan:</strong> Rp ${formatRupiah(priceData.monthly)}</div>`;
                    }
                    if (priceData.daily > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per Hari:</strong> Rp ${formatRupiah(priceData.daily)}</div>`;
                    }
                    if (priceData.weekly > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per Minggu:</strong> Rp ${formatRupiah(priceData.weekly)}</div>`;
                    }
                    if (priceData.three_monthly > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per 3 Bulan:</strong> Rp ${formatRupiah(priceData.three_monthly)}</div>`;
                    }
                    if (priceData.six_monthly > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per 6 Bulan:</strong> Rp ${formatRupiah(priceData.six_monthly)}</div>`;
                    }
                    if (priceData.yearly > 0) {
                        html += `<div class="kos-detail-item"><strong>Harga Per Tahun:</strong> Rp ${formatRupiah(priceData.yearly)}</div>`;
                    }

                    return html;
                };

                const fineDisplay = kosData.price && kosData.price.set_fine
                    ? `Ya, denda ${formatRupiah(kosData.price.fine_amount)} setelah ${kosData.price.fine_limit} hari.`
                    : 'Tidak ditetapkan.';

                const allPricesDisplay = getAllPriceDisplay(kosData.price);

                return `
                    <section class="dashboard-page">
                        <h2 style="margin-bottom: 30px;"><i class="fas fa-eye"></i> Detail Lengkap Kos: ${kosData.nama_kos}</h2>
                        
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">1. Data Dasar Kos</h3>
                            <div class="kos-detail-item"><strong>Nama Kos:</strong> ${kosData.nama_kos}</div>
                            <div class="kos-detail-item"><strong>Tipe:</strong> ${kosData.tipe_kos}</div>
                            <div class="kos-detail-item"><strong>Status:</strong> ${kosData.completed ? 'Telah Disubmit' : 'Dalam Proses Pengisian'}</div>
                            <div class="kos-detail-item"><strong>Deskripsi:</strong> ${kosData.deskripsi}</div>
                        </div>
                        
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">2. Alamat dan Lokasi</h3>
                            <div class="kos-detail-item"><strong>Alamat Lengkap:</strong> ${kosData.address.alamat || '-'}</div>
                            <div class="kos-detail-item"><strong>Catatan Alamat:</strong> ${kosData.address.catatan || '-'}</div>
                            <div class="kos-detail-item"><strong>Wilayah:</strong> ${kosData.address.kecamatan || '-'}, ${kosData.address.kabupaten || '-'}, ${kosData.address.provinsi || '-'}</div>
                        </div>
                        
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">3. Ukuran dan Ketersediaan</h3>
                            <div class="kos-detail-item"><strong>Ukuran Kamar:</strong> ${kosData.size.type} 
                                ${kosData.size.type === 'Kustom' ? `(${kosData.size.custom_w}m x ${kosData.size.custom_l}m)` : ''}
                            </div>
                            <div class="kos-detail-item"><strong>Total Kamar:</strong> ${kosData.total_rooms || 0}</div>
                            <div class="kos-detail-item"><strong>Kamar Tersedia:</strong> ${kosData.available_rooms || 0}</div>
                        </div>
                        
                        ${renderRoomDetails(kosData.room_details)}

                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">5. Galeri Foto</h3>
                            ${renderGallery(kosData.images.bangunan, 'Foto Bangunan Depan')}
                            ${renderGallery(kosData.images.kamar, 'Foto Kamar')}
                        </div>
                        
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">6. Fasilitas & Peraturan (Simulasi)</h3>
                            ${renderSimulationData('Peraturan', kosData.rules)}
                            <hr style="margin: 10px 0;">
                            ${renderSimulationData('Fasilitas Umum', kosData.fasilitas.fasilitasUmum)}
                            ${renderSimulationData('Fasilitas Kamar', kosData.fasilitas.fasilitasKamar)}
                            ${renderSimulationData('Fasilitas K. Mandi', kosData.fasilitas.fasilitasKMandi)}
                            ${renderSimulationData('Parkir', kosData.fasilitas.parkir)}
                        </div>

                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">7. Harga Sewa</h3>
                            ${allPricesDisplay}
                            <div class="kos-detail-item"><strong>Aturan Denda:</strong> ${fineDisplay}</div>
                        </div>

                        <div class="form-actions" style="justify-content: flex-start;">
                            <a href="#" class="btn btn-secondary" onclick="loadKosDashboard();">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kos
                            </a>
                            <a href="#" class="btn btn-primary" onclick="startEditKos(${kosData.id});">
                                <i class="fas fa-edit"></i> Edit Data Kos
                            </a>
                        </div>
                    </section>
                `;
            },

            'data_kos_step1': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Lengkapi Data Kos</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form id="kos_form_step1" onsubmit="return false;">
                        <div class="form-group">
                            <label for="nama_kos">Nama Kos</label>
                            <input type="text" id="nama_kos" placeholder="Masukkan nama kos" required>
                        </div>
                        <div class="form-group">
                            <label>Untuk putra/ putri?</label>
                            <div class="gender-options" id="tipe_kos_selection">
                                <button type="button" class="gender-button" data-tipe="putra">Putra</button>
                                <button type="button" class="gender-button" data-tipe="putri">Putri</button>
                                <button type="button" class="gender-button" data-tipe="campur">Campur</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_kos">Deskripsi kos</label>
                            <textarea id="deskripsi_kos" placeholder="Tuliskan deskripsi singkat tentang kos (maks 200 karakter)"></textarea>
                            <small class="text-right">0/200</small>
                        </div>
                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadKosDashboard();">Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="handleStep1Save();">Lanjutkan</button>
                        </div>
                    </form>
                </section>
            `,
            'data_kos_step2': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Lengkapi Data Kos</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form class="rules-form" onsubmit="return false;">
                        <h3>Peraturan Koszzz</h3>
                        <div class="rules-grid">
                            <div class="rules-column">
                                <label class="checkbox-container">Akses 24+1 Jam<input type="checkbox" checked><span class="checkmark"></span></label>
                                <label class="checkbox-container">Boleh bawa hewan<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Boleh pasutri<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Dilarang merokok dikamar<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Harga termasuk listrik<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Khusus Mahasiswa<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Khusus Karyawan<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Kriteria Umum<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container"><span style="color: red;">⚠️ DANGER!</span> Gay dilarang KOSZZZ<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Maks. 1 orang/kamar<input type="checkbox" checked><span class="checkmark"></span></label>
                                <label class="checkbox-container">Pasutri wajib membawa surat nikah<input type="checkbox"><span class="checkmark"></span></label>
                            </div>
                            <div class="rules-column">
                                <label class="checkbox-container">Tambahan biaya untuk alat elektronik<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Tamu boleh menginap<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Tamu dilarang menginap<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Tamu menginap dikenakan biaya<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Tidak untuk pasutri<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Wajib sertakan KTP saat pengajuan sewa<input type="checkbox"><span class="checkmark"></span></label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step1', 1);">Kembali</a>
                            <a href="#" class="btn btn-primary" onclick="handleStep2Save(); return false;">Lanjutkan</a>
                        </div>
                    </form>
                </section>
            `,
            'data_kos_step3': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Alamat Koszz Anda</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <section class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6 items-start">
                        <!-- FORM KIRI (mobile full width) -->
                        <form onsubmit="return false;" class="lg:order-1 order-2">
                        <div class="space-y-4">
                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" id="alamat" placeholder="Nama jalan, nomor, dan detail lainnya" required />
                            </div>

                            <!-- 3 Kolom Admin Area -->

                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <input type="text" id="provinsi" placeholder="Masukkan Provinsi" required />
                            </div>
                            <div class="form-group">
                                <label for="kabupaten">Kabupaten/Kota</label>
                                <input type="text" id="kabupaten" placeholder="Masukkan Kabupaten/Kota" required />
                            </div>
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <input type="text" id="kecamatan" placeholder="Masukkan Kecamatan" required />
                            </div>

                            <!-- Catatan -->
                            <div class="form-group">
                                <label for="catatan">Catatan Alamat</label>
                                <input type="text" id="catatan" placeholder="Catatan untuk memudahkan pencarian" />
                            </div>

                            <input type="hidden" id="lat" name="lat">
                            <input type="hidden" id="lon" name="lon">
                            
                            <!-- Display Koordinat -->
                            <div class="coordinate-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>Koordinat:</strong>
                                <span id="coordinateDisplay">Belum dipilih</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="copyCoordinates()" title="Salin koordinat">
                                <small class="d-block text-muted">Geser pin di peta untuk presisi lokasi</small>
                            </div>
                        </div>

                        <div class="form-actions mt-6">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step2', 2);">Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="handleStep3Save();">Lanjutkan</button>
                        </div>
                        </form>

                        <!-- MAP KANAN (desktop) / ATAS (mobile) -->
                        <aside class="lg:order-2 order-1">
                            <div class="map-container-card">
                                <h4><i class="fas fa-map"></i> Pin Lokasi di Peta</h4>
                                <div id="mapContainer" style="height: 400px; border-radius: 8px; border: 1px solid #ddd; background: #f8f9fa;">
                                    <div class="map-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading map...</span>
                                        </div>
                                        <p>Memuat peta...</p>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">Klik peta atau geser pin untuk menentukan lokasi tepat</small>
                            </div>
                        </aside>
                    </section>
                </section>
            `,
            'data_kos_step4': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Foto Bangunan Kos Anda</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form onsubmit="return false;">
                        <h3>Foto Bangunan Depan (Hanya 1 Foto)</h3>
                        <div class="form-group">
                            <input type="file" id="upload-input-bangunan" accept="image/*" style="display: none;">
                            <div id="galeri_bangunan" class="photo-gallery-container">
                            </div>
                        </div>

                        <h3>Foto Kamar (Maks. 10 Foto)</h3>
                         <div class="form-group">
                            <input type="file" id="upload-input-kamar" accept="image/*" style="display: none;">
                            <div id="galeri_kamar" class="photo-gallery-container">
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step3', 3);">Kembali</a>
                            <a href="#" class="btn btn-primary" onclick="loadContent('data_kos_step5', 5);">Lanjutkan</a>
                        </div>
                    </form>
                </section>
            `,
            'data_kos_step5': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Fasilitas yang ada di kos</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form onsubmit="return false;">
                        <div style="display: grid; gap: 40px; grid-template-columns: repeat(4, 1fr);">
                            <div data-category="Fasilitas Umum">
                                <h3>fasilitasUmum</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container" data-category="fasilitasUmum">Balcon<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">CCTV<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Dapur<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Dispenser<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Jemuran<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Kulkas<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Mesin Cuci<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Penjaga Kos<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">TV<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasUmum">Wifi<input type="checkbox"><span class="checkmark"></span></label>
                                </div>
                            </div>
                            
                            <div data-category="fasilitasKamar">
                                <h3>Fasilitas Kamar</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container" data-category="fasilitasKamar">AC<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Dapur Pribadi<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Jendela<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Kasur<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Kipas Angin<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Kursi<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Lemari<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Meja<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKamar">Ventilasi<input type="checkbox"><span class="checkmark"></span></label>
                                </div>
                            </div>

                            <div data-category="fasilitasKMandi">
                                <h3>Fasilitas Kamar Mandi</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Air Panas<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Bak Mandi<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Ember Mandi<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">K. Mandi Dalam<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">K. Mandi Luar<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Kloset Duduk<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Kloset Jongkok<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Shower<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="fasilitasKMandi">Wastafel<input type="checkbox"><span class="checkmark"></span></label>
                                </div>
                            </div>
                            
                            <div data-category="parkir">
                                <h3>Parkir</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container" data-category="parkir">Parkir Mobil<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="parkir">Parkir Motor<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="parkir">Parkir Motor & Sepeda<input type="checkbox"><span class="checkmark"></span></label>
                                    <label class="checkbox-container" data-category="parkir">Parkir Sepeda<input type="checkbox"><span class="checkmark"></span></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step4', 4);">Kembali</a>
                            <a href="#" class="btn btn-primary" onclick="handleStep5Save();">Lanjutkan</a>
                        </div>
                    </form>
                </section>
            `,
            'data_kos_step6': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Ketersediaan Kamar Kos</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form onsubmit="return false;">
                        <h3>Ukuran Kamar</h3>
                        <div class="gender-options" id="room_size_selection">
                            <button type="button" class="gender-button">3 x 3</button>
                            <button type="button" class="gender-button">3 x 4</button>
                            <button type="button" class="gender-button">Kustom</button>
                        </div>
                        
                        <div id="custom_size_inputs" style="display: flex; gap: 10px; margin-top: 15px;">
                            <input type="number" id="custom_width" placeholder="Lebar M" style="width: 100px;" min="1" step="0.1">
                            <span style="align-self: flex-end; margin-bottom: 10px;">X</span>
                            <input type="number" id="custom_length" placeholder="Panjang M" style="width: 100px;" min="1" step="0.1">
                        </div>

                        <h3 style="margin-top: 30px;">Data Ketersediaan Kamar</h3>
                        <div class="form-group">
                            <label for="jumlah_total_kamar">Jumlah total kamar</label>
                            <input type="number" id="jumlah_total_kamar" placeholder="Jumlah kamar" style="width: 30%;" min="1">
                        </div>
                        <div class="form-group">
                            <label for="jumlah_kamar_tersedia">Jumlah kamar tersedia</label>
                            <input type="number" id="jumlah_kamar_tersedia" placeholder="Jumlah kamar yang kosong" style="width: 30%;" min="0">
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="goToDetailKamar();" style="margin-top: 10px;">Atur detail setiap kamar</button>

                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step5', 5);">Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="handleStep6Save();">Lanjutkan</button>
                        </div>
                    </form>
                </section>
            `,
            'data_kos_step6_detail': async (currentStep) => {
                try {
                    console.log('=== DEBUG data_kos_step6_detail START ===');
                    console.log('currentKosId:', currentKosId);
                    console.log('isEditMode:', isEditMode);

                    let kosData;
                    if (isEditMode && currentKosId) {
                        console.log('🔍 EDIT MODE: Fetching from getKosDataFromDB');
                        kosData = await getKosDataFromDB(currentKosId);
                    } else if (currentKosId) {
                        console.log('🔍 CREATE MODE: Looking in localStorage');
                        const list = getKosList();
                        kosData = list.find(k => k.id === currentKosId);

                        if (!kosData) {
                            console.log('🔍 Not found in localStorage, trying getKosDataFromDB');
                            kosData = await getKosDataFromDB(currentKosId);
                        }
                    }

                    console.log('kosData found:', kosData);

                    if (!kosData) {
                        console.log('❌ No kosData found');
                        console.log('=== DEBUG END ===');
                        return `<section class="dashboard-page">
                     <h2 style="color: var(--primary-color);">Peringatan</h2>
                     <p>Data kos tidak ditemukan. Silakan mulai dari Langkah 1.</p>
                     <div class="form-actions"><a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step6', 6);">Kembali</a></div>
                 </section>`;
                    }
                    const totalRooms = kosData.total_rooms || kosData.total_kamar || 0;
                    const roomDetails = kosData.room_details || [];
                    console.log('totalRooms:', totalRooms);
                    console.log('=== DEBUG END ===');
                    if (totalRooms === 0) {
                        return `<section class="dashboard-page">
                     <h2 style="color: var(--primary-color);">Peringatan</h2>
                     <p>Anda harus mengisi jumlah total kamar (misalnya: 5) pada langkah sebelumnya sebelum mengatur detail kamar.</p>
                     <div class="form-actions"><a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step6', 6);">Kembali</a></div>
                 </section>`;
                    }

                    let roomFormsHtml = '';
                    for (let i = 1; i <= totalRooms; i++) {
                        const existingRoom = roomDetails[i - 1] || {
                            nomor: `Kamar ${i}`,
                            lantai: '1',
                            terisi: false
                        };

                        const isChecked = existingRoom.terisi ? 'checked' : '';

                        roomFormsHtml += `
                        <form onsubmit="return false;" style="border: 1px solid var(--border-color); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                            <p style="font-weight: bold; margin-bottom: 15px;">Kamar #${i}</p>
                            <div class="address-form-grid" style="grid-template-columns: 1fr 1fr;">
                                <div class="form-group">
                                    <label for="nomor_kamar_${i}">Nomor Kamar / Nama Kamar</label>
                                    <input type="text" id="nomor_kamar_${i}" value="${existingRoom.nomor}" placeholder="Nomor kamar" style="width: 100%;">
                                    <small style="color: var(--text-light);">Dari ${totalRooms} Kamar</small>
                                </div>
                                <div class="form-group">
                                    <label for="lantai_${i}">Lantai</label>
                                    <input type="number" id="lantai_${i}" value="${existingRoom.lantai}" placeholder="Lantai" style="width: 100%;" min="1">
                                </div>
                            </div>
                            <label class="checkbox-container" style="margin-top: 10px;">
                                Kamar sudah terisi
                                <input type="checkbox" id="terisi_kamar_${i}" ${isChecked}>
                                <span class="checkmark"></span>
                            </label>
                        </form>
                    `;
                    }

                    return `
                    <section class="dashboard-page">
                        <a href="#" onclick="loadContent('data_kos_step6', 6);" style="text-decoration: none; color: var(--primary-color); font-weight: 600;">
                            <i class="fas fa-arrow-left"></i> Kembali ke data ketersediaan kamar
                        </a>
                        <div class="progress-bar" style="margin-top: 20px;">
                            <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                            <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                        </div>
    
                        <h3>Data Detail Kamar (${totalRooms} Kamar)</h3>
                        <p style="margin-bottom: 20px; color: var(--text-light);">Isi detail untuk setiap kamar kos Anda.</p>
                        
                        ${roomFormsHtml}
                        
                        <div class="form-actions" style="justify-content: flex-start;">
                            <button type="button" class="btn btn-primary" onclick="handleStep6DetailSave();" style="margin-top: 10px;">
                                <i class="fas fa-save"></i> Simpan Detail Kamar
                            </button>
                        </div>
                    </section>
                `;
                } catch (error) {
                    console.error('Error in data_kos_step6_detail:', error);
                    return `<section class="dashboard-page">
                     <h2 style="color: red;">Error</h2>
                     <p>Terjadi kesalahan: ${error.message}</p>
                     <div class="form-actions"><a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step6', 6);">Kembali</a></div>
                 </section>`;
                }
            },
            'data_kos_step7': (currentStep) => `
                <section class="dashboard-page">
                    <h2>Harga Sewa Kamar</h2>
                    <div class="progress-bar">
                        <div class="progress-bar-segment ${currentStep >= 1 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 2 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 3 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 4 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 5 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 6 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 7 ? 'active' : ''}"></div>
                        <div class="progress-bar-segment ${currentStep >= 8 ? 'active' : ''}"></div>
                    </div>

                    <form onsubmit="return false;">
                        <h3>Pilih Periode Sewa yang Tersedia</h3>
                        <p style="color: var(--text-light); margin-bottom: 20px;">Centang periode yang ingin Anda tawarkan dan isi harganya.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <!-- Harga Per Bulan -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per Bulan</span>
                                    <input type="checkbox" id="harga_bulan_check" checked onchange="toggleMonthlyPriceInput();">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_bulan" placeholder="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <!-- Harga Per Hari -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per Hari</span>
                                    <input type="checkbox" id="harga_hari_check" onchange="togglePriceInputVisibility('harga_hari_check', 'harga_hari_input');">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_hari_input" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <!-- Harga Per Minggu -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per Minggu</span>
                                    <input type="checkbox" id="harga_minggu_check" onchange="togglePriceInputVisibility('harga_minggu_check', 'harga_minggu_input');">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_minggu_input" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <!-- Harga Per 3 Bulan -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per 3 Bulan</span>
                                    <input type="checkbox" id="harga_3bulan_check" onchange="togglePriceInputVisibility('harga_3bulan_check', 'harga_3bulan_input');">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_3bulan_input" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <!-- Harga Per 6 Bulan -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per 6 Bulan</span>
                                    <input type="checkbox" id="harga_6bulan_check" onchange="togglePriceInputVisibility('harga_6bulan_check', 'harga_6bulan_input');">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_6bulan_input" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <!-- Harga Per Tahun -->
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Harga Per Tahun</span>
                                    <input type="checkbox" id="harga_tahun_check" onchange="togglePriceInputVisibility('harga_tahun_check', 'harga_tahun_input');">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="harga_tahun_input" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                        </div>

                        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                            <h3>Pengaturan Denda</h3>
                            <div class="price-form-row">
                                <label class="checkbox-container" style="display: flex; align-items: center; flex-grow: 1; margin: 0; padding-left: 0;">
                                    <span style="font-weight: 600; min-width: 150px;">Tetapkan denda keterlambatan</span>
                                    <input type="checkbox" id="denda_check" onchange="toggleFineInputVisibility();">
                                    <span class="checkmark" style="position: static; margin-left: auto;"></span>
                                </label>
                                <input type="text" id="denda_jumlah" value="0" style="width: 200px; padding: 8px 12px;">
                            </div>
                            
                            <div class="form-group">
                                <label for="batas_waktu_hari">Batas Waktu (hari)</label>
                                <select id="batas_waktu_hari" style="padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; width: 200px;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="5">5</option>
                                    <option value="7">7</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step6', 6);">Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="handleStep7Save();">Lanjutkan</button>
                        </div>
                    </form>
                </section>
            `,
            // 'data_kos_step8': (currentStep) =>
            //     `
            //     <section class="dashboard-page" style="text-align: center; padding: 100px 30px;">
            //         <h2 style="font-size: 30px; margin-bottom: 30px;">${isEditMode ? 'Data Kos Berhasil Diperbarui' : 'Kos Baru Berhasil Ditambahkan'}</h2>
            //         <div class="form-actions" style="justify-content: center;">
            //             <button class="btn btn-primary" onclick="finalizeKosSubmission();">${isEditMode ? 'Lihat Perubahan' : 'Selesai & Lanjutkan'}</button>
            //         </div>
            //     </section>
            // `,
            'data_kos_step8': (currentStep) => {
                // Cek apakah ini edit atau create
                const kosData = getKosList().find(k =>
                    k.database_id === currentKosId || k.id === currentKosId
                );
                const isEdit = kosData && kosData.database_id;

                const title = isEdit ? 'Data Kos Berhasil Diperbarui' : 'Kos Baru Berhasil Ditambahkan';
                const buttonText = isEdit ? 'Lihat Perubahan' : 'Selesai & Lanjutkan';

                return `
                    <section class="dashboard-page" style="text-align: center; padding: 100px 30px;">
                        <h2 style="font-size: 30px; margin-bottom: 30px;">${title}</h2>
                        <div class="form-actions" style="justify-content: center;">
                            <button class="btn btn-primary" onclick="finalizeKosSubmission();">${buttonText}</button>
                        </div>
                    </section>
                `;
            },

            'management': `
                <section class="dashboard-page">
                    <h2>Halaman Manajemen</h2>
                    <p>Pilih salah satu menu dari dropdown Manajemen untuk mengelola:</p>
                    <ul style="margin-top: 20px; padding-left: 20px;">
                        <li><strong>Booking</strong> - Kelola pemesanan kamar</li>
                        <li><strong>Kelola Tagihan</strong> - Kelola pembayaran dan tagihan</li>
                        <li><strong>Penyewa</strong> - Data dan informasi penyewa</li>
                    </ul>
                </section>
            `,

            'booking': `
                <section class="dashboard-page" id="booking-section">
                    <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--text-color);">Pengajuan Booking</h2>

                    <!-- Tabs untuk status booking -->
                    <div class="tabs" style="display: flex; gap: 5px; margin: 0 0 30px 0; background-color: var(--active-bg); padding: 4px; border-radius: 8px; width: fit-content;">
                        <button class="tab-button active" onclick="switchBookingTab('butuh-konfirmasi')" data-status="menunggu_konfirmasi" style="padding: 8px 20px; border: none; background-color: var(--card-background); cursor: pointer; font-weight: 600; color: var(--text-color); border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 14px;">
                            Butuh Konfirmasi
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('tunggu-pembayaran')" data-status="menunggu_pembayaran" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Tunggu Pembayaran
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('terbayar')" data-status="diterima" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Terbayar
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('ditolak')" data-status="ditolak" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Ditolak
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('riwayat')" data-status="telah_keluar" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Riwayat
                        </button>
                    </div>

                    <div id="booking-container">
                        <div style="text-align: center; padding: 50px; color: var(--text-light);">
                            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #6a0dad; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                            <p>Memuat data booking...</p>
                            <style>
                                @keyframes spin {
                                    0% { transform: rotate(0deg); }
                                    100% { transform: rotate(360deg); }
                                }
                            </style>
                        </div>
                    </div>

                    <div id="butuh-konfirmasi" class="tab-content" style="display: block;"></div>
                    <div id="tunggu-pembayaran" class="tab-content" style="display: none;"></div>
                    <div id="terbayar" class="tab-content" style="display: none;"></div>
                    <div id="ditolak" class="tab-content" style="display: none;"></div>
                    <div id="riwayat" class="tab-content" style="display: none;"></div>
                </section>
            `,

            'tagihan': `
                <section class="dashboard-page">
                    <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--text-color);">
                        <i class="fas fa-file-invoice-dollar"></i> Kelola Tagihan
                    </h2>

                    <!-- Container untuk card tagihan -->
                    <div style="display: flex; gap: 20px; min-height: 400px;">
                        <!-- Kolom card tagihan -->
                        <div style="flex: 1;">
                            <!-- Card 1 - Kamar 22 -->
                            <div style="border: 2px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 20px; background-color: var(--card-background); position: relative; width: 100%; max-width: 600px;">
                                <!-- Bagian kanan: Status Kontrak dan Tombol Selengkapnya -->
                                <div style="position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                                    <!-- Status Kontrak -->
                                    <div style="background-color: #e6ffe6; padding: 8px 15px; border-radius: 6px;">
                                        <span style="color: #28a745; font-size: 14px; font-weight: 600;">
                                            Kontrak Aktif
                                        </span>
                                    </div>
                                    
                                    <!-- Tombol Selengkapnya -->
                                    <a href="#" onclick="lihatDetailTagihan('kamar-22'); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                        Selengkapnya
                                    </a>
                                </div>
                                
                                <!-- Bagian kiri: Informasi Penyewa -->
                                <div style="display: flex; align-items: flex-start; gap: 15px; margin-right: 160px;">
                                    <!-- Ikon profil -->
                                    <i class="fas fa-user-circle" style="font-size: 50px; color: #6a0dad;"></i>
                                    
                                    <div style="flex-grow: 1;">
                                        <!-- Nama penyewa -->
                                        <p style="font-weight: 700; margin: 0 0 5px 0; color: var(--text-color); font-size: 18px;">
                                            Ahmad Sahroni
                                        </p>
                                        
                                        <!-- Nama kos -->
                                        <p style="color: var(--text-light); margin: 0 0 15px 0; font-size: 15px;">
                                            Kos Maguwo City
                                        </p>
                                        
                                        <!-- Detail kamar -->
                                        <div>
                                            <span style="font-weight: 700; font-size: 15px; color: var(--text-color);">Kamar 22 (2 Bulan)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 - Kamar 24 -->
                            <div style="border: 2px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 20px; background-color: var(--card-background); position: relative; width: 100%; max-width: 600px;">
                                <!-- Bagian kanan: Status Kontrak dan Tombol Selengkapnya -->
                                <div style="position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                                    <!-- Status Kontrak -->
                                    <div style="background-color: #f8d7da; padding: 8px 15px; border-radius: 6px;">
                                        <span style="color: #dc3545; font-size: 14px; font-weight: 600;">
                                            Kontrak Non-Aktif
                                        </span>
                                    </div>
                                    
                                    <!-- Tombol Selengkapnya -->
                                    <a href="#" onclick="lihatDetailTagihan('kamar-24'); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                        Selengkapnya
                                    </a>
                                </div>
                                
                                <!-- Bagian kiri: Informasi Penyewa -->
                                <div style="display: flex; align-items: flex-start; gap: 15px; margin-right: 160px;">
                                    <!-- Ikon profil -->
                                    <i class="fas fa-user-circle" style="font-size: 50px; color: #dc3545;"></i>
                                    
                                    <div style="flex-grow: 1;">
                                        <!-- Nama penyewa -->
                                        <p style="font-weight: 700; margin: 0 0 5px 0; color: var(--text-color); font-size: 18px;">
                                            Ahmad Sahroni
                                        </p>
                                        
                                        <!-- Nama kos -->
                                        <p style="color: var(--text-light); margin: 0 0 15px 0; font-size: 15px;">
                                            Kos Maguwo City
                                        </p>
                                        
                                        <!-- Detail kamar -->
                                        <div>
                                            <span style="font-weight: 700; font-size: 15px; color: var(--text-color);">Kamar 24 (1 Bulan)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 - Kamar 26 -->
                            <div style="border: 2px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 20px; background-color: var(--card-background); position: relative; width: 100%; max-width: 600px;">
                                <!-- Bagian kanan: Status Kontrak dan Tombol Selengkapnya -->
                                <div style="position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                                    <!-- Status Kontrak -->
                                    <div style="background-color: #e6ffe6; padding: 8px 15px; border-radius: 6px;">
                                        <span style="color: #28a745; font-size: 14px; font-weight: 600;">
                                            Kontrak Aktif
                                        </span>
                                    </div>
                                    
                                    <!-- Tombol Selengkapnya -->
                                    <a href="#" onclick="lihatDetailTagihan('kamar-26'); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                        Selengkapnya
                                    </a>
                                </div>
                                
                                <!-- Bagian kiri: Informasi Penyewa -->
                                <div style="display: flex; align-items: flex-start; gap: 15px; margin-right: 160px;">
                                    <!-- Ikon profil -->
                                    <i class="fas fa-user-circle" style="font-size: 50px; color: #6a0dad;"></i>
                                    
                                    <div style="flex-grow: 1;">
                                        <!-- Nama penyewa -->
                                        <p style="font-weight: 700; margin: 0 0 5px 0; color: var(--text-color); font-size: 18px;">
                                            Ahmad Sahroni
                                        </p>
                                        
                                        <!-- Nama kos -->
                                        <p style="color: var(--text-light); margin: 0 0 15px 0; font-size: 15px;">
                                            Kos Maguwo City
                                        </p>
                                        
                                        <!-- Detail kamar -->
                                        <div>
                                            <span style="font-weight: 700; font-size: 15px; color: var(--text-color);">Kamar 26 (1 Bulan)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom kosong di kanan -->
                        <div style="flex: 1;">
                            <!-- Area kosong -->
                        </div>
                    </div>
                </section>
            `,

            'kelola_tagihan_detail': (dataTagihan) => {
                if (!dataTagihan) {
                    const savedData = localStorage.getItem('detailTagihan');
                    dataTagihan = savedData ? JSON.parse(savedData) : {
                        nama: 'Ahmad Sahroni',
                        kos: 'Kos Maguwo City',
                        kamar: '22',
                        durasi: '2 Bulan',
                        totalTagihan: 1200000,
                        deposit: 500000,
                        denda: 20000,
                        batasDenda: 10,
                        status: 'aktif'
                    };
                }

                const statusColor = dataTagihan.status === 'aktif' ? '#28a745' : '#dc3545';
                const statusBackground = dataTagihan.status === 'aktif' ? '#e6ffe6' : '#f8d7da';
                const statusText = dataTagihan.status === 'aktif' ? 'Kontrak Aktif' : 'Kontrak Non-Aktif';
                const iconColor = dataTagihan.status === 'aktif' ? '#6a0dad' : '#dc3545';

                return `
                <section class="dashboard-page">
                    <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--text-color);">
                        <i class="fas fa-file-invoice-dollar"></i> Kelola Tagihan
                    </h2>
                    
                    <div style="margin-bottom: 30px;">
                        <a href="#" onclick="loadContent('tagihan');" style="color: var(--primary-color); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Tagihan
                        </a>
                    </div>

                    <div style="border: 2px solid var(--border-color); border-radius: 12px; padding: 25px; background-color: var(--card-background);">
                        <!-- Header dengan status kontrak -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                            <div>
                                <h3 style="font-size: 22px; color: var(--text-color); margin: 0 0 5px 0; font-weight: 700;">Atur Kontrak Tagihan</h3>
                                <p style="color: var(--text-light); margin: 0; font-size: 14px;">Untuk ${dataTagihan.nama} - ${dataTagihan.kos}</p>
                            </div>
                            <div style="background-color: ${statusBackground}; padding: 8px 16px; border-radius: 6px;">
                                <span style="color: ${statusColor}; font-size: 14px; font-weight: 600;">${statusText}</span>
                            </div>
                        </div>

                        <!-- Informasi Penyewa -->
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <i class="fas fa-user-circle" style="font-size: 50px; color: ${iconColor};"></i>
                                <div>
                                    <h4 style="font-size: 18px; color: var(--text-color); margin: 0 0 5px 0; font-weight: 700;">${dataTagihan.nama}</h4>
                                    <p style="color: var(--text-light); margin: 0; font-size: 15px;">${dataTagihan.kos}</p>
                                </div>
                            </div>
                            <div>
                                <p style="font-weight: 600; color: var(--text-color); margin: 0; font-size: 16px;">Kamar ${dataTagihan.kamar} (${dataTagihan.durasi})</p>
                            </div>
                        </div>

                        <!-- Rincian Tagihan -->
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                            <h4 style="font-size: 18px; color: var(--text-color); margin: 0 0 20px 0; font-weight: 700;">Rincian Tagihan</h4>
                            
                            <!-- Total Tagihan Penyewa -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 5px 0; font-size: 16px;">Total Tagihan Penyewa</p>
                                    <p style="color: var(--text-light); margin: 0; font-size: 14px;">Untuk ${dataTagihan.durasi}</p>
                                </div>
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin: 0; font-size: 18px;">Rp. ${formatRupiah(dataTagihan.totalTagihan)}</p>
                                </div>
                            </div>

                            <!-- Biaya deposit -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 5px 0; font-size: 16px;">Biaya deposit</p>
                                </div>
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin: 0; font-size: 18px;">Rp. ${formatRupiah(dataTagihan.deposit)}</p>
                                </div>
                            </div>

                            <!-- Biaya denda terlambat pembayaran -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 5px 0; font-size: 16px;">Biaya denda terlambat pembayaran</p>
                                </div>
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin: 0; font-size: 18px;">Rp. ${formatRupiah(dataTagihan.denda)}</p>
                                </div>
                            </div>

                            <!-- Denda berlaku setelah -->
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 5px 0; font-size: 16px;">Denda berlaku setelah</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="background-color: #f0f0f0; padding: 8px 15px; border-radius: 6px;">
                                        <span style="font-weight: 700; color: var(--text-color); font-size: 18px;">${dataTagihan.batasDenda}</span>
                                    </div>
                                    <span style="font-weight: 600; color: var(--text-color); font-size: 16px;">Hari</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Simpan Data -->
                        <div style="text-align: center; margin-top: 30px;">
                            <button onclick="editDataTagihan()" style="padding: 12px 40px; background-color: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </section>
                `;
            },

            'penyewa': `
                <section class="dashboard-page" style="margin-top: 20px;">
                    <h2 style="font-size: 24px; margin-bottom: 20px; color: var(--text-color);">
                        <i class="fas fa-users"></i> Penyewa
                    </h2>
                    
                    <!-- Tabs untuk filter penyewa -->
                    <div class="tabs" style="display: flex; gap: 5px; margin: 0 0 30px 0; background-color: var(--active-bg); padding: 4px; border-radius: 8px; width: fit-content;">
                        <button class="tab-button active" onclick="switchPenyewaTab('semua')" style="padding: 8px 20px; border: none; background-color: var(--card-background); cursor: pointer; font-weight: 600; color: var(--text-color); border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 14px;">
                            Semua
                        </button>
                        <button class="tab-button" onclick="switchPenyewaTab('kontrak-aktif')" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Kontrak Aktif
                        </button>
                        <button class="tab-button" onclick="switchPenyewaTab('kontrak-nonaktif')" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Kontrak Non-Aktif
                        </button>
                    </div>

                    <!-- Container untuk card penyewa dan tombol tambah -->
                    <div style="display: flex; gap: 20px; min-height: 400px;">
                        <!-- Kolom card penyewa -->
                        <div style="flex: 1;">
                            <!-- Konten Tab: Semua Penyewa -->
                            <div id="semua-penyewa" class="tab-content" style="display: block;">
                                <!-- Card Penyewa 1 - KONTRUK AKTIF -->
                                <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); width: 100%; max-width: 600px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <!-- Informasi Penyewa -->
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                        <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                            AS
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kamar 22 (2 Bulan)</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Status dan Tombol -->
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="background-color: #e6ffe6; padding: 6px 12px; border-radius: 4px;">
                                            <span style="color: #28a745; font-size: 13px; font-weight: 600;">
                                                Kontrak Aktif
                                            </span>
                                        </div>
                                        <a href="#" onclick="lihatDetailPenyewa(1); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>

                                <!-- Card Penyewa 2 - KONTRUK NON-AKTIF -->
                                <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); width: 100%; max-width: 600px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <!-- Informasi Penyewa -->
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                        <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                            AS
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kamar 22 (2 Bulan)</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Status dan Tombol -->
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="background-color: #f8d7da; padding: 6px 12px; border-radius: 4px;">
                                            <span style="color: #dc3545; font-size: 13px; font-weight: 600;">
                                                Kontrak Non-Aktif
                                            </span>
                                        </div>
                                        <a href="#" onclick="lihatDetailPenyewa(2); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Konten Tab: Kontrak Aktif -->
                            <div id="kontrak-aktif-penyewa" class="tab-content" style="display: none;">
                                <!-- Card Penyewa dengan Kontrak Aktif -->
                                <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); width: 100%; max-width: 600px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <!-- Informasi Penyewa -->
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                        <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                            AS
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kamar 22 (2 Bulan)</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Status dan Tombol -->
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="background-color: #e6ffe6; padding: 6px 12px; border-radius: 4px;">
                                            <span style="color: #28a745; font-size: 13px; font-weight: 600;">
                                                Kontrak Aktif
                                            </span>
                                        </div>
                                        <a href="#" onclick="lihatDetailPenyewa(1); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Konten Tab: Kontrak Non-Aktif -->
                            <div id="kontrak-nonaktif-penyewa" class="tab-content" style="display: none;">
                                <!-- Card Penyewa dengan Kontrak Non-Aktif -->
                                <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); width: 100%; max-width: 600px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <!-- Informasi Penyewa -->
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                        <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                            AS
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kamar 22 (2 Bulan)</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Status dan Tombol -->
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="background-color: #f8d7da; padding: 6px 12px; border-radius: 4px;">
                                            <span style="color: #dc3545; font-size: 13px; font-weight: 600;">
                                                Kontrak Non-Aktif
                                            </span>
                                        </div>
                                        <a href="#" onclick="lihatDetailPenyewa(2); return false;" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer;">
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom kosong di kanan -->
                        <div style="flex: 1;">
                            <!-- Area kosong -->
                        </div>
                    </div>

                    <!-- Tombol Tambah Penyewa -->
                    <div style="margin-top: 30px; text-align: left;">
                        <button onclick="tambahPenyewa()" style="padding: 12px 30px; background-color: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-plus"></i> Tambah Penyewa
                        </button>
                    </div>
                </section>
            `,

            'detail_penyewa': () => {
                // Ambil data dari localStorage atau gunakan default
                const savedData = localStorage.getItem('currentPenyewaData');
                const penyewaData = savedData ? JSON.parse(savedData) : {
                    id: 1,
                    nama: 'Ahmad Sahroni',
                    sewaSejak: '3 Juli 2025',
                    noWhatsapp: '0812 2222 XXXX',
                    kamar: '22',
                    durasi: '2',
                    hargaSewa: 600000,
                    kontrakAktif: true,
                    selesaiKontrak: '3 September 2025',
                    totalHarga: 1200000,
                    periodeSewa: 'Bulanan'
                };

                return `
                <section class="dashboard-page" style="margin-top: 20px;">
                    <!-- Header dengan tombol kembali -->
                    <div style="margin-bottom: 30px;">
                        <a href="#" onclick="loadContent('penyewa');" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Penyewa
                        </a>
                        <h2 style="font-size: 24px; margin-bottom: 20px; color: var(--text-color);">
                            <i class="fas fa-user"></i> Detail Penyewa
                        </h2>
                    </div>

                    <!-- Container utama dengan grid -->
                    <div style="display: flex; gap: 30px; align-items: flex-start;">
                        <!-- Kolom kiri - Profile Client -->
                        <div style="flex: 1; background-color: white; border-radius: 12px; padding: 25px; border: 1px solid var(--border-color); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            
                            <!-- Foto Profil dan Nama Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <!-- Foto Profil -->
                                <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; background-color: var(--primary-color); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="color: white; font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 20px; font-weight: 700; color: var(--text-color); margin: 0 0 5px 0;">
                                        ${penyewaData.nama}
                                    </h3>
                                    <p style="color: var(--text-light); font-size: 14px; margin: 0;">
                                        Sewa Sejak ${penyewaData.sewaSejak}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Nomor WhatsApp dengan logo WhatsApp -->
                            <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 10px;">
                                <i class="fab fa-whatsapp" style="color: #25D366; font-size: 20px;"></i>
                                <p style="font-size: 16px; font-weight: 600; color: var(--text-color); margin: 0;">
                                    ${penyewaData.noWhatsapp}
                                </p>
                            </div>
                            
                            <!-- Info Kamar dengan tombol Ubah -->
                            <div style="margin-bottom: 30px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <div>
                                        <p style="font-size: 16px; font-weight: 700; color: var(--text-color); margin: 0;">
                                            Kamar ${penyewaData.kamar} (${penyewaData.durasi} Bulan)
                                        </p>
                                    </div>
                                    <a href="#" onclick="ubahKamar(${penyewaData.id})" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                        Ubah nomor kamar <i class="fas fa-chevron-right" style="font-size: 12px;"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Tombol Kontrak Sewa dan Hentikan Kontrak -->
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <!-- Kontrak Sewa -->
                                <div class="kontrak-btn" onclick="lihatKontrakSewa(${penyewaData.id})">
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0; font-size: 14px;">
                                        Kontrak Sewa
                                    </p>
                                </div>
                                
                                <!-- Hentikan Kontrak -->
                                <div class="kontrak-btn danger" onclick="hentikanKontrak(${penyewaData.id})">
                                    <p style="font-weight: 600; color: #dc3545; margin: 0; font-size: 14px;">
                                        Hentikan Kontrak
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom kanan - Hitungan Sewa -->
                        <div style="flex: 1; background-color: white; border-radius: 12px; padding: 25px; border: 1px solid var(--border-color); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <h3 style="font-size: 16px; font-weight: 700; color: var(--text-color); margin: 0 0 25px 0; padding-bottom: 10px; border-bottom: 1px solid var(--border-color);">
                                Hitungan sewa
                            </h3>
                            
                            <!-- Grid untuk detail sewa -->
                            <div style="display: flex; flex-direction: column; gap: 20px;">
                                <!-- Pilihan Periode - DROPDOWN -->
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 10px 0; font-size: 14px;">
                                        Periode Sewa
                                    </p>
                                    <div class="periode-dropdown">
                                        <button class="periode-dropdown-btn" onclick="togglePeriodeDropdown()">
                                            <span id="periode-sewa-text">${penyewaData.periodeSewa}</span>
                                            <i class="fas fa-chevron-down" id="periode-dropdown-icon"></i>
                                        </button>
                                        <div class="periode-dropdown-content" id="periode-dropdown-content">
                                            <div class="periode-option ${penyewaData.periodeSewa === 'Harian' ? 'selected' : ''}" onclick="selectPeriode('Harian')">Harian</div>
                                            <div class="periode-option ${penyewaData.periodeSewa === 'Mingguan' ? 'selected' : ''}" onclick="selectPeriode('Mingguan')">Mingguan</div>
                                            <div class="periode-option ${penyewaData.periodeSewa === 'Bulanan' ? 'selected' : ''}" onclick="selectPeriode('Bulanan')">Bulanan</div>
                                            <div class="periode-option ${penyewaData.periodeSewa === '3 Bulan' ? 'selected' : ''}" onclick="selectPeriode('3 Bulan')">3 Bulan</div>
                                            <div class="periode-option ${penyewaData.periodeSewa === '6 Bulan' ? 'selected' : ''}" onclick="selectPeriode('6 Bulan')">6 Bulan</div>
                                            <div class="periode-option ${penyewaData.periodeSewa === 'Tahunan' ? 'selected' : ''}" onclick="selectPeriode('Tahunan')">Tahunan</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Lama sewa -->
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 10px 0; font-size: 14px;">
                                        Lama sewa (${penyewaData.periodeSewa === 'Harian' ? 'hari' : penyewaData.periodeSewa === 'Mingguan' ? 'minggu' : 'bulan'})
                                    </p>
                                    <div style="background-color: #f8f9fa; padding: 10px 15px; border-radius: 8px; display: inline-block; min-width: 50px; text-align: center; border: 1px solid #e0e0e0;">
                                        <span style="font-size: 16px; font-weight: 700; color: var(--text-color);">
                                            ${penyewaData.durasi}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Selesai kontrak -->
                                <div>
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 10px 0; font-size: 14px;">
                                        Selesai kontrak:
                                    </p>
                                    <p style="font-size: 16px; font-weight: 700; color: var(--text-color); margin: 0;">
                                        ${penyewaData.selesaiKontrak}
                                    </p>
                                </div>
                                
                                <!-- Harga Sewa dengan tombol Ubah -->
                                <div style="padding-top: 15px; margin-top: 10px; border-top: 1px solid #eee;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                        <p style="font-weight: 600; color: var(--text-color); margin: 0; font-size: 14px;">
                                            Harga sewa
                                        </p>
                                        <a href="#" onclick="ubahHarga(${penyewaData.id})" style="color: var(--primary-color); text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer;">
                                            Ubah
                                        </a>
                                    </div>
                                    <p style="font-size: 18px; font-weight: 700; color: var(--primary-color); margin: 0;">
                                        Rp. ${formatRupiah(penyewaData.hargaSewa)} / ${penyewaData.periodeSewa === 'Harian' ? 'hari' : penyewaData.periodeSewa === 'Mingguan' ? 'minggu' : penyewaData.periodeSewa.toLowerCase()}
                                    </p>
                                </div>
                                
                                <!-- Garis pemisah dan Total Harga -->
                                <div style="padding-top: 20px; margin-top: 15px; border-top: 2px solid var(--primary-color);">
                                    <p style="font-weight: 600; color: var(--text-color); margin: 0 0 10px 0; font-size: 14px;">
                                        Total Harga
                                    </p>
                                    <p style="font-size: 22px; font-weight: 700; color: var(--primary-color); margin: 0;">
                                        Rp. ${formatRupiah(penyewaData.totalHarga)}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                `;
            },

            'account': () => {
                // Data statis sesuai gambar
                const userData = {
                    nama: '{{ auth()->user()->name }}',
                    noHandphone: '{{ auth()->user()->no_hp }}',
                    email: '{{ auth()->user()->email }}'
                };

                return `
                    <section class="dashboard-page" style="padding: 30px;">
                        <!-- Header (sesuai gambar - Koszzz di atas) -->
                        <div style="margin-bottom: 30px;">
                            <div style="font-size: 32px; font-weight: bold; color: var(--primary-color); margin-bottom: 5px;">
                                Koszzz
                            </div>
                            <div style="height: 1px; background-color: var(--border-color); margin: 20px 0;"></div>
                        </div>
                        
                        <!-- Container utama dengan border -->
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 0; overflow: hidden;">
                            <!-- Judul "Informasi Pribadi" -->
                            <div style="background-color: #f8f9fa; padding: 20px 30px; border-bottom: 1px solid var(--border-color);">
                                <h2 style="font-size: 18px; color: var(--text-color); margin: 0; font-weight: 600;">
                                    Informasi Pribadi
                                </h2>
                            </div>
                            
                            <!-- Konten informasi -->
                            <div style="padding: 30px;">
                                <!-- Baris 1: Nama Lengkap -->
                                <div style="margin-bottom: 25px;">
                                    <div style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Nama Lengkap
                                    </div>
                                    <div style="font-size: 16px; color: var(--text-light);">
                                        ${userData.nama}
                                    </div>
                                </div>
                                
                                <!-- Baris 2: Nomor Handphone -->
                                <div style="margin-bottom: 25px;">
                                    <div style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Nomor Handphone
                                    </div>
                                    <div style="font-size: 16px; color: var(--text-light);">
                                        ${userData.noHandphone}
                                    </div>
                                </div>
                                
                                <!-- Baris 4: Email -->
                                <div>
                                    <div style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Email
                                    </div>
                                    <div style="font-size: 16px; color: var(--text-light);">
                                        ${userData.email}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                `;


                // Tampilan normal (view mode) - SAMA PERSIS DENGAN GAMBAR
                return `
                <section class="dashboard-page">
                    <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--text-color);">
                        <i class="fas fa-user"></i> Akun
                    </h2>

                    <div style="border: 2px solid var(--border-color); border-radius: 12px; padding: 30px; background-color: var(--card-background);">
                        <!-- Header dengan judul "Informasi Pribadi" -->
                        <h3 style="font-size: 20px; color: var(--primary-color); margin-bottom: 30px; font-weight: 700;">
                            Informasi Pribadi
                        </h3>
                        
                        <!-- Informasi dalam 2 kolom (seperti gambar) -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                            <!-- Kolom Kiri -->
                            <div style="display: flex; flex-direction: column; gap: 20px;">
                                <!-- Nama Lengkap -->
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Nama Lengkap
                                    </p>
                                    <p style="font-size: 16px; color: var(--text-light); margin: 0;">
                                        ${userData.nama}
                                    </p>
                                </div>
                                
                                <!-- Password -->
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Password
                                    </p>
                                    <p style="font-size: 16px; color: var(--text-light); margin: 0;">
                                        ${userData.password}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div style="display: flex; flex-direction: column; gap: 20px;">
                                <!-- Nomor Handphone -->
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Nomor Handphone
                                    </p>
                                    <p style="font-size: 16px; color: var(--text-light); margin: 0;">
                                        ${userData.noHandphone}
                                    </p>
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <p style="font-weight: 700; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Email
                                    </p>
                                    <p style="font-size: 16px; color: var(--text-light); margin: 0;">
                                        ${userData.email}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Edit di pojok kanan bawah -->
                        <div style="margin-top: 40px; text-align: right;">
                            <button onclick="loadContent('account', true)" 
                                    style="padding: 10px 25px; background-color: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </section>
            `;
            }
        };

        // ==================== 🗺️ MAP FUNCTIONS ====================
        let map, marker;
        let selectedLat = null;
        let selectedLon = null;

        // 1. Fungsi untuk tunggu element
        function waitForElement(selector, timeout = 5000) {
            return new Promise((resolve, reject) => {
                const startTime = Date.now();

                function check() {
                    const element = document.querySelector(selector);
                    if (element) {
                        resolve(element);
                    } else if (Date.now() - startTime > timeout) {
                        reject(new Error(`Element ${selector} not found`));
                    } else {
                        setTimeout(check, 100);
                    }
                }

                check();
            });
        }

        // 2. Load Leaflet (tanpa cek mapContainer dulu)
        function loadLeafletMap() {
            console.log('🔍 loadLeafletMap called');

            return new Promise((resolve, reject) => {
                // Jika Leaflet sudah dimuat
                if (window.L) {
                    console.log('✅ Leaflet already loaded');
                    resolve();
                    return;
                }

                // Load CSS jika belum
                if (!document.querySelector('link[href*="leaflet"]')) {
                    const leafletCSS = document.createElement('link');
                    leafletCSS.rel = 'stylesheet';
                    leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    leafletCSS.onload = () => console.log('✅ Leaflet CSS loaded');
                    document.head.appendChild(leafletCSS);
                }

                // Load JS
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';

                script.onload = () => {
                    console.log('✅ Leaflet JS loaded');
                    resolve();
                };

                script.onerror = (error) => {
                    console.error('❌ Failed to load Leaflet:', error);
                    reject(error);
                };

                document.head.appendChild(script);
            });
        }

        // 3. Initialize map (SETELAH element ada)
        async function initMapForStep3() {
            console.log('🚀 initMapForStep3 called');

            try {
                // TUNGGU mapContainer muncul
                console.log('⏳ Waiting for mapContainer...');
                const mapContainer = await waitForElement('#mapContainer');
                console.log('✅ mapContainer found:', mapContainer);

                // Load Leaflet jika belum
                if (!window.L) {
                    console.log('📦 Loading Leaflet...');
                    await loadLeafletMap();
                }

                // Default location
                let defaultLat = -7.7956;
                let defaultLon = 110.3695;

                // Coba ambil dari existing data
                if (currentKosId) {
                    const kosData = await getKosDataFromDB(currentKosId);
                    if (kosData && kosData.address && kosData.address.lat && kosData.address.lon) {
                        defaultLat = parseFloat(kosData.address.lat);
                        defaultLon = parseFloat(kosData.address.lon);
                        selectedLat = defaultLat;
                        selectedLon = defaultLon;
                        console.log(`📍 Using existing coordinates: ${defaultLat}, ${defaultLon}`);
                    }
                }

                console.log(`📍 Setting map view to: ${defaultLat}, ${defaultLon}`);

                // Inisialisasi peta
                map = L.map('mapContainer').setView([defaultLat, defaultLon], 13);
                console.log('✅ Map created');

                // Tambahkan tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Tambahkan marker
                marker = L.marker([defaultLat, defaultLon], {
                    draggable: true,
                    title: 'Drag me to adjust location'
                }).addTo(map);

                // Set initial coordinates
                updateCoordinateDisplay(defaultLat, defaultLon);

                // Event: marker digeser
                marker.on('dragend', function (e) {
                    const { lat, lng } = e.target.getLatLng();
                    console.log(`📍 Marker dragged to: ${lat}, ${lng}`);

                    try {
                        updateCoordinateFields(lat, lng);
                        reverseGeocode(lat, lng);
                    } catch (error) {
                        console.error('❌ Error handling marker drag:', error);
                    }
                });

                // Event: klik peta
                map.on('click', function (e) {
                    const { lat, lng } = e.latlng;
                    console.log(`📍 Map clicked at: ${lat}, ${lng}`);

                    try {
                        if (marker) {
                            marker.setLatLng([lat, lng]);
                        } else {
                            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                        }

                        updateCoordinateFields(lat, lng);
                        reverseGeocode(lat, lng);

                    } catch (error) {
                        console.error('❌ Error handling map click:', error);
                    }
                });

                // Tambahkan kontrol
                L.control.zoom({ position: 'topright' }).addTo(map);

                console.log('✅ Map fully initialized!');

            } catch (error) {
                console.error('❌ Error in initMapForStep3:', error);
                showMapError();
            }
        }

        // 4. Fungsi untuk dipanggil dari template/loadContent
        async function setupStep3Map() {
            console.log('🗺️ Setting up map for step 3');

            // Step 1: Tunggu form fields muncul dulu
            try {
                await waitForElement('#alamat', 3000);
                console.log('✅ Form fields ready');

                // Step 2: Load Leaflet di background
                loadLeafletMap().then(() => {
                    console.log('✅ Leaflet ready in background');
                }).catch(err => {
                    console.warn('⚠️ Leaflet load warning:', err);
                });

                // Step 3: Init map setelah delay
                setTimeout(() => {
                    initMapForStep3();
                }, 800);

            } catch (error) {
                console.error('❌ Setup failed:', error);
            }
        }

        // 5. Fungsi lainnya tetap sama...
        // searchAddressOnMap, reverseGeocode, dll...

        // Search address on map
        function searchAddressOnMap() {
            const address = document.getElementById('alamat').value;
            if (!address) {
                alert('Masukkan alamat terlebih dahulu!');
                return;
            }

            const searchBtn = event.target;
            const originalText = searchBtn.innerHTML;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            searchBtn.disabled = true;

            console.log(`🔍 Searching for: ${address}`);

            // Encode URL dengan benar
            const encodedAddress = encodeURIComponent(address + ', Indonesia');
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&limit=1&countrycodes=id`;

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'User-Agent': 'KosMapsApp/1.0'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Search failed: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        const { lat, lon, display_name } = data[0];
                        console.log(`📍 Found: ${display_name} at ${lat}, ${lon}`);

                        // Update map
                        map.setView([lat, lon], 16);
                        marker.setLatLng([lat, lon]);
                        updateCoordinateFields(lat, lon);

                        // Update address field
                        document.getElementById('alamat').value = display_name;

                        // Extract location details
                        extractLocationDetails(display_name);

                        // Show popup
                        marker.bindPopup(`<b>Lokasi ditemukan</b><br>${display_name}`).openPopup();

                    } else {
                        alert('Alamat tidak ditemukan! Coba alamat yang lebih spesifik.');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);

                    // Fallback: Tampilkan pesan user-friendly
                    if (error.message.includes('Failed to fetch')) {
                        alert('Tidak dapat terhubung ke layanan peta. Periksa koneksi internet Anda.');
                    } else if (error.message.includes('429')) {
                        alert('Terlalu banyak permintaan. Tunggu beberapa saat sebelum mencoba lagi.');
                    } else {
                        alert('Gagal mencari alamat. Silakan coba alamat yang berbeda.');
                    }
                })
                .finally(() => {
                    searchBtn.innerHTML = originalText;
                    searchBtn.disabled = false;
                });
        }

        // Reverse geocode
        function reverseGeocode(lat, lon) {
            // Opsional: Bisa skip reverse geocode jika tidak critical
            console.log('🌍 Attempting reverse geocode...');

            // Buat URL dengan format yang benar
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`;

            // Tambahkan timeout dan headers
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Accept-Language': 'id-ID,id;q=0.9,en;q=0.8',
                    // Nominatim requires User-Agent
                    'User-Agent': 'KosMapsApp/1.0 (your-email@example.com)'
                },
                mode: 'cors',
                signal: controller.signal
            })
                .then(response => {
                    clearTimeout(timeoutId);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.display_name) {
                        console.log('📍 Reverse geocode success:', data.display_name);

                        // Update alamat field
                        const alamatField = document.getElementById('alamat');
                        if (alamatField) {
                            alamatField.value = data.display_name;
                        }

                        // Update detail alamat jika ada
                        if (data.address) {
                            const addr = data.address;
                            updateFormField('provinsi', addr.province || addr.state || '');
                            updateFormField('kabupaten', addr.city || addr.town || addr.county || '');
                            updateFormField('kecamatan', addr.suburb || addr.village || '');
                        }
                    }
                })
                .catch(error => {
                    clearTimeout(timeoutId);

                    // Jangan tampilkan error ke user, log saja
                    console.warn('⚠️ Reverse geocode failed (non-critical):', error.message);

                    // Bisa tambahkan fallback ke manual input
                    if (error.name === 'AbortError') {
                        console.log('⏱️ Reverse geocode timeout, user can input manually');
                    }
                });
        }
        // Helper untuk update form field dengan safety
        function updateFormField(fieldId, value) {
            const field = document.getElementById(fieldId);
            if (field && value) {
                field.value = value;
            }
        }

        // Update coordinate display
        // function updateCoordinateDisplay(lat, lon) {
        //     const display = document.getElementById('coordinateDisplay');
        //     if (display) {
        //         display.textContent = `Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}`;
        //     }
        // }

        // Update coordinate fields
        function updateCoordinateFields(lat, lon) {
            try {
                selectedLat = lat;
                selectedLon = lon;

                console.log(`📍 Updating coordinates: ${lat}, ${lon}`);

                // Update hidden fields - dengan safety check
                const latField = document.getElementById('lat');
                const lonField = document.getElementById('lon');

                if (latField && lonField) {
                    latField.value = lat.toFixed(7);
                    lonField.value = lon.toFixed(7);
                    console.log('✅ Hidden fields updated');
                } else {
                    console.warn('⚠️ lat/lon fields not found in DOM');
                    // Simpan sementara ke localStorage
                    if (currentKosId) {
                        let list = getKosList();
                        const kosIndex = list.findIndex(kos =>
                            kos.database_id === currentKosId || kos.id === currentKosId);
                        if (kosIndex > -1) {
                            if (!list[kosIndex].address) list[kosIndex].address = {};
                            list[kosIndex].address.lat = lat;
                            list[kosIndex].address.lon = lon;
                            saveKosList(list);
                            console.log('✅ Coordinates saved to localStorage instead');
                        }
                    }
                }

                // Update display
                updateCoordinateDisplay(lat, lon);

            } catch (error) {
                console.error('❌ Error in updateCoordinateFields:', error);
            }
        }

        function updateCoordinateDisplay(lat, lon) {
            try {
                const display = document.getElementById('coordinateDisplay');
                if (display) {
                    display.textContent = `Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}`;
                    display.style.color = '#28a745'; // Hijau untuk feedback visual
                    console.log('✅ Coordinate display updated');
                } else {
                    console.warn('⚠️ coordinateDisplay element not found');
                }
            } catch (error) {
                console.error('❌ Error updating coordinate display:', error);
            }
        }

        function copyCoordinates() {
            const lat = document.getElementById('lat')?.value;
            const lon = document.getElementById('lon')?.value;

            if (!lat || !lon) {
                alert('Belum ada koordinat untuk disalin');
                return;
            }

            const coords = `${lat}, ${lon}`;

            navigator.clipboard.writeText(coords)
                .then(() => {
                    // Feedback visual
                    const btn = event.target.closest('button');
                    if (btn) {
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-success');

                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    }

                    console.log('✅ Coordinates copied:', coords);
                })
                .catch(err => {
                    console.error('Failed to copy:', err);
                    alert('Gagal menyalin koordinat');
                });
        }

        // Extract location details
        function extractLocationDetails(fullAddress) {
            const parts = fullAddress.split(',').map(p => p.trim());

            if (parts.length >= 4) {
                const provinsi = parts[parts.length - 2] || '';
                const kabupaten = parts[parts.length - 3] || '';
                const kecamatan = parts[parts.length - 4] || '';

                document.getElementById('provinsi').value = provinsi;
                document.getElementById('kabupaten').value = kabupaten;
                document.getElementById('kecamatan').value = kecamatan;
            }
        }

        // Show map error
        function showMapError() {
            const mapContainer = document.getElementById('mapContainer');
            if (mapContainer) {
                mapContainer.innerHTML = `
            <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; text-align: center;">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                <h5 style="margin-bottom: 10px;">Peta tidak dapat dimuat</h5>
                <p style="margin-bottom: 15px; color: #666;">Coba refresh halaman atau periksa koneksi internet.</p>
                <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                    <i class="fas fa-redo"></i> Refresh Halaman
                </button>
            </div>
        `;
            }
        }

        // Debug function
        function debugMap() {
            console.log('=== DEBUG MAP ===');
            console.log('1. Window.L exists?', !!window.L);
            console.log('2. mapContainer exists?', !!document.getElementById('mapContainer'));
            console.log('3. mapContainer dimensions:',
                document.getElementById('mapContainer')?.offsetWidth + 'x' +
                document.getElementById('mapContainer')?.offsetHeight);
            console.log('4. Leaflet CSS loaded?',
                document.querySelector('link[href*="leaflet"]') ? 'Yes' : 'No');
            console.log('5. currentKosId:', currentKosId);
            console.log('6. Map object:', map);
        }
        // Debug function untuk cek element
        function debugCoordinateFields() {
            console.log('=== DEBUG COORDINATE FIELDS ===');
            console.log('1. lat element:', document.getElementById('lat'));
            console.log('2. lon element:', document.getElementById('lon'));
            console.log('3. coordinateDisplay:', document.getElementById('coordinateDisplay'));
            console.log('4. selectedLat:', selectedLat);
            console.log('5. selectedLon:', selectedLon);
            console.log('6. currentKosId:', currentKosId);
        }

        // Panggil di console: debugCoordinateFields()

        // ==========================================================
        // 4. FUNGSI JAVASCRIPT UTAMA UNTUK MENGGANTI KONTEN
        // ==========================================================

        /**
         * Mengatur mode "Lihat Detail Kos" dan memuat template detail.
         */
        // function viewKosDetail(id) {
        //     currentKosId = id;
        //     const kosData = getKosData(id);
        //     loadContent('kos_detail_view', kosData);
        // }
        async function viewKosDetail(kosId) {
            try {
                // FETCH DARI DATABASE
                const response = await fetch(`/api/get-kos/${kosId}`);
                const dbKos = await response.json();

                // TRANSFORM KE FORMAT TEMPLATE
                const kosData = transformKosFromDB(dbKos);

                // LOAD TEMPLATE
                loadContent('kos_detail_view', kosData);

            } catch (error) {
                console.error('Error loading kos detail:', error);
                alert('Gagal memuat detail kos');
            }
        }

        /**
         * Mengatur mode "Tambah Kos Baru" dan memuat Step 1.
         */
        function startAddNewKos() {
            currentKosId = null;
            loadContent('data_kos_step1', 1);
        }

        /**
         * Mengatur mode "Edit Kos" dan memuat Step 1 untuk kos spesifik.
         */
        function startEditKos(id) {
            currentKosId = id;
            loadContent('data_kos_step1', 1);
        }

        /**
         * Memuat konten ke area utama dan memperbarui status navigasi.
         */
        async function loadContent(pageKey, stepOrData = 1) {
            console.log('loadContent dipanggil:', pageKey, stepOrData);

            const contentArea = document.getElementById('content-area');
            const navItems = document.querySelectorAll('#nav-menu .nav-item');
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            let htmlContent;

            const isDataObject = typeof stepOrData === 'object' && stepOrData !== null;
            const stepNumber = isDataObject ? 1 : stepOrData;
            const dataObject = isDataObject ? stepOrData : null;

            if (pageKey.startsWith('data_kos_step')) {
                if (currentKosId) {
                    const kosList = getKosList();
                    const existingKos = kosList.find(k =>
                        k.database_id === currentKosId || k.id === currentKosId
                    );

                    if (existingKos?.database_id) {
                        isEditMode = true;
                        console.log('🔄 EDIT MODE untuk database_id:', existingKos.database_id);

                    } else {
                        isEditMode = false;
                        console.log('🆕 CREATE MODE (draft) untuk id:', currentKosId);
                    }
                } else {
                    isEditMode = false;
                    console.log('🆕 CREATE MODE BARU');
                }
            } else {
                isEditMode = false;
            }

            // ========== ✅ STEP 2: SYNC DATA JIKA EDIT MODE ==========
            if (pageKey.startsWith('data_kos_step') && isEditMode && currentKosId) {
                console.log('🔄 EDIT MODE DETECTED: Syncing kos to localStorage...');

                try {
                    // Cek dulu apakah data sudah ada di localStorage
                    const kosList = getKosList();
                    const alreadySynced = kosList.some(k => k.database_id === currentKosId);

                    if (!alreadySynced) {
                        console.log('📡 Data not in localStorage, syncing from database...');
                        await syncSingleKosToLocalStorage(currentKosId);
                        console.log('✅ Sync completed for EDIT MODE');
                    } else {
                        console.log('📝 Data already in localStorage, skipping sync');
                    }
                } catch (error) {
                    console.error('❌ Sync failed, continuing anyway:', error);
                    // Jangan block user jika sync gagal
                }
            }

            if (typeof templates[pageKey] === 'function') {
                if (pageKey === 'detail_penyewa' || pageKey === 'kelola_tagihan_detail') {
                    htmlContent = templates[pageKey](dataObject);
                } else if (pageKey === 'kos_saya_dashboard' || pageKey === 'home') {
                    htmlContent = await templates[pageKey]();
                } else if (pageKey === 'data_kos_step6_detail') {
                    const dataToUse = getKosDataFromDB(currentKosId);
                    htmlContent = await templates[pageKey](dataToUse);
                } else if (pageKey === 'kos_detail_view') {
                    htmlContent = await templates[pageKey](dataObject);
                } else {
                    htmlContent = templates[pageKey](stepNumber);
                }
            } else {
                htmlContent = templates[pageKey];
            }

            contentArea.innerHTML = htmlContent;

            // Inisialisasi form sesuai halaman
            if (pageKey === 'data_kos_step1') {
                setTimeout(async () => {
                    console.log('🔄 initStep1Form, currentKosId:', currentKosId);
                    console.log('Mode:', isEditMode ? 'EDIT' : 'CREATE');

                    // EDIT MODE
                    if (currentKosId) {
                        let kosData;
                        // const kosData = await getKosDataFromDB(currentKosId);
                        // console.log('KOS DATA FOUND:', kosData);

                        if (isEditMode) {
                            console.log('📝 EDIT MODE: Loading from localStorage...');
                            const list = getKosList();
                            kosData = list.find(k => k.database_id === currentKosId);

                            if (!kosData) {
                                console.warn('⚠️ Kos not found in localStorage after sync, trying fallback...');
                                kosData = await getKosDataFromDB(currentKosId);
                            }

                        } else {
                            console.log('📝 CREATE MODE: Loading draft from localStorage...');
                            const list = getKosList();
                            kosData = list.find(k => k.id === currentKosId);
                        }

                        if (kosData) {
                            document.getElementById('nama_kos').value = kosData.nama_kos || '';
                            document.getElementById('deskripsi_kos').value = kosData.deskripsi || '';

                            // TIPE KOS
                            const tipe = kosData.tipe_kos || 'putra';
                            document.querySelectorAll('#tipe_kos_selection .gender-button').forEach(btn => {
                                btn.classList.toggle('active', btn.dataset.tipe === tipe);
                            });
                        }
                    }

                    // TIPE BUTTON EVENT
                    document.querySelectorAll('#tipe_kos_selection .gender-button').forEach(btn => {
                        btn.onclick = function () {
                            document.querySelectorAll('#tipe_kos_selection .gender-button').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                        };
                    });
                }, 200);

            } else if (pageKey === 'data_kos_step2') {
                setTimeout(async () => {
                    console.log('🔄 initStep2Form, currentKosId:', currentKosId);
                    console.log('Mode:', isEditMode ? 'EDIT' : 'CREATE');

                    // LOAD RULES DARI LOCALSTORAGE (EDIT MODE)
                    if (currentKosId) {
                        let kosData;

                        if (isEditMode) {
                            const list = getKosList();
                            kosData = list.find(k => k.database_id === currentKosId);
                        } else {
                            const list = getKosList();
                            kosData = list.find(k => k.id === currentKosId);
                        }

                        console.log('Step2 KOS DATA:', kosData);

                        if (kosData && kosData.rules) {
                            // CHECKBOXES TERCHECK
                            document.querySelectorAll('.rules-form input[type="checkbox"]').forEach(checkbox => {
                                const ruleText = checkbox.parentElement.textContent.trim();
                                checkbox.checked = kosData.rules.includes(ruleText);
                            });
                        }
                    }

                    // EVENT LISTENER CHECKBOX
                    document.querySelectorAll('.rules-form input[type="checkbox"]').forEach(checkbox => {
                        checkbox.onchange = function () {
                            console.log('Rule changed:', this.parentElement.textContent.trim(), this.checked);
                        };
                    });
                }, 200);

            } else if (pageKey === 'data_kos_step3') {
                console.log('🔄 Loading Step 3 (Alamat)')
                console.log('Mode:', isEditMode ? 'EDIT' : 'CREATE');

                setTimeout(async () => {
                    console.log('📋 Template rendered, loading data...');

                    // Load data ke form
                    if (currentKosId) {
                        let kosData;

                        if (isEditMode) {
                            const list = getKosList();
                            kosData = list.find(k => k.database_id === currentKosId);
                        } else {
                            const list = getKosList();
                            kosData = list.find(k => k.id === currentKosId);
                        }

                        if (kosData && kosData.address) {
                            ['alamat', 'provinsi', 'kabupaten', 'kecamatan', 'catatan', 'lat', 'lon'].forEach(field => {
                                const el = document.getElementById(field);
                                if (el && kosData.address[field]) {
                                    el.value = kosData.address[field];
                                }
                            });

                            const display = document.getElementById('coordinateDisplay');
                            if (display && kosData.address.lat && kosData.address.lon) {
                                display.textContent =
                                    `Lat: ${parseFloat(kosData.address.lat).toFixed(6)}, Lon: ${parseFloat(kosData.address.lon).toFixed(6)}`;
                            }
                        }
                    }

                    setTimeout(() => {
                        if (typeof setupStep3Map === 'function') {
                            setupStep3Map();
                        } else if (typeof initMapForStep3 === 'function') {
                            // Fallback
                            initMapForStep3();
                        }
                    }, 500);

                }, 100);

            } else if (pageKey === 'data_kos_step4') {
                setTimeout(async () => {
                    if (currentKosId === null) {
                        showModal({
                            title: "Peringatan",
                            message: "Anda harus menyimpan data Kos (Langkah 1) terlebih dahulu.",
                            type: "warning",
                            onConfirm: () => {
                                loadContent('data_kos_step1', 1);
                            }
                        });
                        return;
                    }
                    let kosData;
                    if (isEditMode) {
                        const list = getKosList();
                        kosData = list.find(k => k.database_id === currentKosId);
                    } else {
                        const list = getKosList();
                        kosData = list.find(k => k.id === currentKosId);
                    }
                    console.log('Step 4 - kosData:', kosData);
                    console.log('Step 4 - images:', kosData?.images);
                    console.log('Step 4 - bangunan images:', kosData?.images?.bangunan);

                    initializeStep4Form(kosData);
                }, 50);

            } else if (pageKey === 'data_kos_step5') {
                setTimeout(async () => {
                    if (currentKosId === null) {
                        showModal({
                            title: "Peringatan",
                            message: "Silakan simpan data Kos terlebih dahulu.",
                            type: "warning",
                            onConfirm: () => loadContent('data_kos_step1', 1)
                        });
                        return;
                    }
                    let kosData;
                    if (isEditMode) {
                        const list = getKosList();
                        kosData = list.find(k => k.database_id === currentKosId);
                    } else {
                        const list = getKosList();
                        kosData = list.find(k => k.id === currentKosId);
                    }

                    initStep5Form(kosData);
                }, 50);

            } else if (pageKey === 'data_kos_step6') {
                setTimeout(async () => {
                    let kosData;
                    if (isEditMode) {
                        const list = getKosList();
                        kosData = list.find(k => k.database_id === currentKosId);
                    } else {
                        const list = getKosList();
                        kosData = list.find(k => k.id === currentKosId);
                    }

                    initializeStep6Form(kosData);
                }, 50);

            } else if (pageKey === 'data_kos_step7') {
                setTimeout(async () => {
                    if (currentKosId === null) {
                        showModal({
                            title: "Peringatan",
                            message: "Anda harus menyimpan data Kos (Langkah 1) terlebih dahulu.",
                            type: "warning",
                            onConfirm: () => {
                                loadContent('data_kos_step1', 1);
                            }
                        });
                        return;
                    }
                    let kosData;
                    if (isEditMode) {
                        const list = getKosList();
                        kosData = list.find(k => k.database_id === currentKosId);
                    } else {
                        const list = getKosList();
                        kosData = list.find(k => k.id === currentKosId);
                    }

                    initializeStep7Form(kosData);

                }, 50);
            }

            // Memperbarui kelas 'active' pada sidebar
            navItems.forEach(item => {
                item.classList.remove('active');

                let dataPage = item.getAttribute('data-page');

                if (dataPage === 'data_kos_step1' && (pageKey.startsWith('data_kos_step') || pageKey === 'kos_saya_dashboard' || pageKey === 'kos_detail_view')) {
                    item.classList.add('active');
                } else if (pageKey === 'home' && dataPage === 'home') {
                    item.classList.add('active');
                }
                else if ((pageKey === 'management' || pageKey === 'booking' || pageKey === 'tagihan' || pageKey === 'penyewa' || pageKey === 'detail_penyewa') && dataPage === 'management') {
                    item.classList.add('active');
                }
                else if (pageKey === 'account' && dataPage === 'account') {
                    item.classList.add('active');
                }
            });

            dropdownItems.forEach(item => {
                item.classList.remove('active');
                let dataPage = item.getAttribute('data-page');
                if (pageKey === dataPage) {
                    item.classList.add('active');
                }
            });

            if (pageKey === 'booking') {
                // Render template booking
                contentArea.innerHTML = templates['booking'];

                // Setelah render, initialize booking
                setTimeout(() => {
                    if (typeof loadBookings === 'function') {
                        loadBookings('menunggu_konfirmasi');
                    }
                }, 100);
            }
        }

        /**
         * Fungsi khusus yang dipanggil saat tombol "Kos Saya" diklik
         */
        async function loadKosDashboard() {
            currentKosId = null;
            await loadContent('kos_saya_dashboard');
        }

        /**
         * Fungsi untuk toggle dropdown manajemen
         */
        function toggleManagementDropdown() {
            const dropdown = document.getElementById('management-dropdown');
            const dropdownIcon = document.querySelector('#management-toggle .dropdown-icon');

            isManagementDropdownOpen = !isManagementDropdownOpen;

            if (isManagementDropdownOpen) {
                dropdown.classList.add('show');
                dropdownIcon.classList.add('rotated');
            } else {
                dropdown.classList.remove('show');
                dropdownIcon.classList.remove('rotated');
            }
        }

        /**
         * Fungsi untuk toggle dropdown periode
         */
        function togglePeriodeDropdown() {
            const dropdownContent = document.getElementById('periode-dropdown-content');
            const dropdownIcon = document.getElementById('periode-dropdown-icon');

            if (dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
                dropdownIcon.classList.remove('fa-chevron-up');
                dropdownIcon.classList.add('fa-chevron-down');
            } else {
                dropdownContent.classList.add('show');
                dropdownIcon.classList.remove('fa-chevron-down');
                dropdownIcon.classList.add('fa-chevron-up');
            }
        }

        /**
         * Fungsi untuk memilih periode dari dropdown
         */
        function selectPeriode(periode) {
            // Update tampilan dropdown
            document.getElementById('periode-sewa-text').textContent = periode;

            // Update semua opsi dropdown
            const options = document.querySelectorAll('.periode-option');
            options.forEach(option => {
                option.classList.remove('selected');
                if (option.textContent === periode) {
                    option.classList.add('selected');
                }
            });

            // Tutup dropdown
            const dropdownContent = document.getElementById('periode-dropdown-content');
            const dropdownIcon = document.getElementById('periode-dropdown-icon');
            dropdownContent.classList.remove('show');
            dropdownIcon.classList.remove('fa-chevron-up');
            dropdownIcon.classList.add('fa-chevron-down');

            // Update data di localStorage
            const savedData = localStorage.getItem('currentPenyewaData');
            if (savedData) {
                const penyewaData = JSON.parse(savedData);
                penyewaData.periodeSewa = periode;
                localStorage.setItem('currentPenyewaData', JSON.stringify(penyewaData));

                // Refresh tampilan untuk update label
                loadContent('detail_penyewa');
            }

            showModal({
                title: "Periode Sewa Diubah",
                message: `Periode sewa telah diubah menjadi ${periode}`,
                type: "info"
            });
        }

        // function acceptBooking(bookingId) {
        //     if (confirm('Setujui booking ini?')) {
        //         fetch(`/booking/${bookingId}/accept`, {
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //                 'Content-Type': 'application/json'
        //             }
        //         })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.success) {
        //                     alert('Booking diterima!');
        //                     location.reload();
        //                 }
        //             });
        //     }
        // }

        // ==========================================================
        // 5. FUNGSI UNTUK BOOKING MANAGEMENT (UPDATED)
        // ==========================================================

        /**
         * Load bookings dari API
         */
        // async function loadBookings(statusFilter = 'menunggu_konfirmasi') {
        //     try {
        //         showLoading('booking-container', 'Memuat data booking...');

        //         const response = await fetch('/api/admin/bookings');
        //         const data = await response.json();

        //         if (data.success) {
        //             renderBookings(data.bookings, statusFilter);
        //         } else {
        //             showError('booking-container', 'Gagal memuat data booking');
        //         }
        //     } catch (error) {
        //         console.error('Error loading bookings:', error);
        //         showError('booking-container', 'Gagal memuat data booking');
        //     }
        // }
        async function loadBookings(statusFilter = 'menunggu_konfirmasi') {
            try {
                showLoading('booking-container', 'Memuat data booking...');
                // Tentukan endpoint berdasarkan user role
                const isAdmin = true; // Ganti dengan logic check role

                const endpoint = isAdmin
                    ? `/api/admin/bookings?status=${status}`
                    : `/api/my-bookings?status=${status}`;

                const response = await fetch(endpoint);
                const data = await response.json();

                console.log('Bookings data:', data);

                if (!data.success || data.bookings.length === 0) {
                    document.getElementById('booking-container').innerHTML =
                        '<p class="empty-state">Tidak ada booking</p>';
                    return;
                }

                // Render bookings
                renderBookings(data.bookings, statusFilter);

                // document.getElementById('booking-container').innerHTML = html;

            } catch (error) {
                console.error('Error loading bookings:', error);
                document.getElementById('booking-container').innerHTML =
                    '<p class="error">Gagal memuat data booking</p>';
            }
        }

        function convertPeriodeSewa(periode) {
            const periodeLabels = {
                'bulanan': 'Per bulan',
                'harian': 'Per hari',
                'mingguan': 'Per minggu',
                '3_bulanan': 'Per 3 bulan',
                '6_bulanan': 'Per 6 bulan',
                'tahunan': 'Per tahun',
                '1_bulan': 'Per bulan',
                '1_hari': 'Per hari',
                '1_minggu': 'Per minggu',
                '12_bulanan': 'Per tahun',
                '1_tahun': 'Per tahun'
            };

            // Langsung ambil dari mapping
            return periodeLabels[periode] || periode;
        }

        /**
         * Render bookings ke UI
         */
        function renderBookings(bookings, statusFilter) {
            const container = document.getElementById('booking-container');

            if (!container) return;

            // Filter berdasarkan status
            let filteredBookings = bookings;
            if (statusFilter !== 'semua') {
                if (statusFilter === 'ditolak') {
                    filteredBookings = bookings.filter(b =>
                        b.status === 'ditolak' || b.status === 'dibatalkan'
                    );
                } else {
                    filteredBookings = bookings.filter(b => b.status === statusFilter);
                }
            }

            if (filteredBookings.length === 0) {
                container.innerHTML = `
            <div class="empty-state" style="text-align: center; padding: 50px; color: var(--text-light);">
                <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                <p>Tidak ada data booking dengan status ini</p>
            </div>
        `;
                return;
            }

            let html = '';

            filteredBookings.forEach(booking => {
                // Generate initials untuk avatar
                const initials = booking.nama_penyewa
                    .split(' ')
                    .map(n => n[0])
                    .join('')
                    .toUpperCase()
                    .substring(0, 2);

                // Status badge color
                let badgeColor = '#FF9800';
                let badgeText = 'Butuh Konfirmasi';

                if (booking.status === 'diterima') {
                    badgeColor = '#28a745';
                    badgeText = 'Diterima';
                } else if (booking.status === 'menunggu_pembayaran') {
                    badgeColor = '#2196F3';
                    badgeText = 'Tunggu Pembayaran';
                } else if (booking.status === 'ditolak') {
                    badgeColor = '#dc3545';
                    badgeText = 'Ditolak';
                } else if (booking.status === 'dibatalkan') {
                    badgeColor = '#6c757d';
                    badgeText = 'Dibatalkan';
                } else if (booking.status === 'selesai') {
                    badgeColor = '#17a2b8';
                    badgeText = 'Selesai';
                } else if (booking.status === 'telah_keluar') {
                    badgeColor = '#1735b8ff';
                    badgeText = 'Telah Keluar';
                }

                // Format tanggal
                const mulaiSewa = new Date(booking.tanggal_checkin).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                // Format harga
                const formattedHarga = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(booking.total_harga);

                html += `
        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <!-- Status Badge -->
            <div style="margin-bottom: 20px;">
                <span style="background-color: ${badgeColor}; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    ${badgeText}
                </span>
            </div>
            
            <!-- Informasi Penyewa -->
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
                        ${booking.nama_kamar_display} - Lantai ${booking.lantai_display}
                    </p>
                </div>
            </div>
            
            <!-- Detail Sewa -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 25px;">
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">${mulaiSewa}</p>
                </div>
                <div>
                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">
                        ${booking.durasi_sewa} ${convertPeriodeSewa(booking.periode_sewa)}
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
            
            <!-- Tombol Aksi -->
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                ${booking.status === 'menunggu_konfirmasi' ? `
                <button onclick="acceptBooking(${booking.id})" 
                        style="padding: 10px 30px; border: none; background-color: #28a745; border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px;">
                    <i class="fas fa-check" style="margin-right: 5px;"></i> Terima
                </button>
                <button onclick="rejectBooking(${booking.id})" 
                        style="padding: 10px 30px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px;">
                    <i class="fas fa-times" style="margin-right: 5px;"></i> Tolak
                </button>
                ` : ''}
                
                ${booking.status === 'diterima' ? `
                <button onclick="markAsPaid(${booking.id})" 
                        style="padding: 10px 30px; border: none; background-color: #2196F3; border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px;">
                    Tandai Sudah Bayar
                </button>
                ` : ''}

                ${booking.status === 'selesai' ? `
                <button onclick="checkoutBooking(${booking.id})" 
                        style="padding: 10px 30px; border: none; background-color: #17a2b8; border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i> Penyewa Keluar
                </button>
                ` : ''}

                ${booking.status === 'ditolak' || booking.status === 'dibatalkan' ? `
                <button onclick="deleteBookingHistory(${booking.id})" 
                        style="padding: 10px 20px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px; margin-right: 10px;">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i> Hapus
                </button>
                ` : ''}

                ${booking.status === 'telah_keluar' ? `
                <button onclick="deleteBookingHistory(${booking.id})" 
                        style="padding: 10px 20px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px;">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i> Hapus
                </button>
                ` : ''}
                
                <button onclick="viewBookingDetail(${booking.id})" 
                        style="padding: 10px 30px; border: 2px solid #6a0dad; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #6a0dad; font-size: 14px;">
                    Detail
                </button>
            </div>
        </div>
        `;
            });

            container.innerHTML = html;
        }

        /**
         * Ganti tab booking
         */
        function switchBookingTab(tabId) {
            // Update UI tabs
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => {
                tab.style.display = 'none';
            });

            const selectedTab = document.getElementById(tabId);
            if (selectedTab) {
                selectedTab.style.display = 'block';
            }

            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                const buttonTab = button.getAttribute('onclick');
                if (buttonTab && buttonTab.includes(tabId)) {
                    button.style.backgroundColor = 'var(--card-background)';
                    button.style.color = 'var(--text-color)';
                    button.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                } else {
                    button.style.backgroundColor = '';
                    button.style.color = 'var(--text-light)';
                    button.style.boxShadow = 'none';
                }
            });

            // Map tab ID ke status filter
            const statusMap = {
                'butuh-konfirmasi': 'menunggu_konfirmasi',
                'tunggu-pembayaran': 'menunggu_pembayaran',
                'terbayar': 'selesai',
                'riwayat': 'telah_keluar',
                'ditolak': 'ditolak',
                'semua': 'semua'
            };

            // Load data untuk tab yang dipilih
            loadBookings(statusMap[tabId]);
        }

        /**
         * Terima booking (new version - dengan API)
         */
        async function acceptBooking(bookingId) {
            showConfirm({
                title: "Konfirmasi",
                message: `Apakah Anda yakin ingin menerima booking ini?`,
                confirmText: "Terima",
                cancelText: "Batal",
                onConfirm: async function () {
                    try {
                        const response = await fetch(`/api/admin/booking/${bookingId}/accept`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showModal({
                                title: "Sukses",
                                message: "Booking berhasil diterima!",
                                type: "success",
                            });
                            setTimeout(() => {
                                loadBookings('menunggu_pembayaran');
                                location.reload();
                            }, 3000);
                        } else {
                            showModal({
                                title: "Error",
                                message: data.message || "Gagal menerima booking",
                                type: "error"
                            });
                        }
                    } catch (error) {
                        console.error('Error accepting booking:', error);
                        showModal({
                            title: "Error",
                            message: "Terjadi kesalahan saat menerima booking",
                            type: "error"
                        });
                    }
                }
            });
        }

        /**
         * Tolak booking (new version - dengan API)
         */
        async function rejectBooking(bookingId) {
            showConfirm({
                title: "Konfirmasi",
                message: `Apakah Anda yakin ingin menolak booking ini?`,
                confirmText: "Tolak",
                cancelText: "Batal",
                onConfirm: async function () {
                    try {
                        const response = await fetch(`/api/admin/booking/${bookingId}/reject`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showModal({
                                title: "Sukses",
                                message: "Booking berhasil ditolak!",
                                type: "success",
                                onClose: function () {
                                    loadBookings('ditolak');
                                }
                            });
                        } else {
                            showModal({
                                title: "Error",
                                message: data.message || "Gagal menolak booking",
                                type: "error"
                            });
                        }
                    } catch (error) {
                        console.error('Error rejecting booking:', error);
                        showModal({
                            title: "Error",
                            message: "Terjadi kesalahan saat menolak booking",
                            type: "error"
                        });
                    }
                }
            });
        }

        // Function untuk penyewa keluar
        async function checkoutBooking(bookingId) {
            showConfirm({
                title: "Konfirmasi Penyewa Keluar",
                message: "Apakah penyewa sudah benar-benar keluar dari kamar? Kamar akan dikembalikan menjadi 'tersedia' untuk booking baru.",
                confirmText: "Ya, Penyewa Keluar",
                cancelText: "Batal",
                onConfirm: async function () {
                    try {
                        const response = await fetch(`/api/admin/booking/${bookingId}/checkout`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            showModal({
                                title: "Sukses",
                                message: "Penyewa berhasil dikeluarkan. Kamar sekarang tersedia.",
                                type: "success",
                                onConfirm: function () {
                                    loadBookings('selesai'); // Refresh tab terbayar
                                }
                            });
                        } else {
                            throw new Error(data.message || "Gagal mengeluarkan penyewa");
                        }
                    } catch (error) {
                        showModal({
                            title: "Error",
                            message: error.message,
                            type: "error"
                        });
                    }
                }
            });
        }

        /**
 * Hapus riwayat booking (soft delete)
 */
        async function deleteBookingHistory(bookingId) {
            showConfirm({
                title: "Hapus Riwayat",
                message: "Apakah Anda yakin ingin menghapus riwayat booking ini?",
                confirmText: "Ya, Hapus",
                cancelText: "Batal",
                onConfirm: async function () {
                    try {
                        const response = await fetch(`/api/admin/booking/${bookingId}/delete-history`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showModal({
                                title: "Berhasil",
                                message: "Riwayat booking berhasil dihapus",
                                type: "success",
                                onConfirm: function () {
                                    const activeTab = document.querySelector('.tab-button.active');
                                    if (activeTab) {
                                        const tabId = activeTab.getAttribute('onclick');
                                        const match = tabId.match(/switchBookingTab\('([^']+)'\)/);
                                        if (match && match[1]) {
                                            const tabName = match[1];
                                            const statusMap = {
                                                'butuh-konfirmasi': 'menunggu_konfirmasi',
                                                'tunggu-pembayaran': 'menunggu_pembayaran',
                                                'terbayar': 'selesai',
                                                'riwayat': 'telah_keluar',
                                                'ditolak': 'ditolak'
                                            };
                                            loadBookings(statusMap[tabName] || tabName);
                                        }
                                    } else {
                                        location.reload();
                                    }
                                }
                            });
                        } else {
                            throw new Error(data.message || "Gagal menghapus riwayat");
                        }
                    } catch (error) {
                        showModal({
                            title: "Error",
                            message: error.message,
                            type: "error"
                        });
                    }
                }
            });
        }

        /**
         * Lihat detail booking
         */
        async function viewBookingDetail(bookingId) {
            try {
                const response = await fetch(`/api/admin/booking/${bookingId}`);
                const data = await response.json();

                if (data.success) {
                    const booking = data.booking;

                    // BUAT HTML MODAL SENDIRI KAYAK editDataTagihan
                    const modalHtml = `
                <div class="modal-containers modal-custom">
                    <div class="modal-header">
                        <h3>Detail Booking: ${booking.kode_checkin}</h3>
                        <span id="closeCustomModal" style="cursor: pointer; font-size: 24px;">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: left;">
                            <p style="margin-bottom:4px"><strong>Nama Penyewa:</strong> ${booking.nama_penyewa}</p>
                            <p style="margin-bottom:4px"><strong>Jenis Kelamin:</strong> ${booking.jenis_kelamin}</p>
                            <p style="margin-bottom:4px"><strong>Email:</strong> ${booking.email}</p>
                            <p style="margin-bottom:10px"><strong>Telepon:</strong> ${booking.no_hp || '-'}</p>
                            <hr>
                            <p style="margin-top:10px margin-bottom:4px"><strong>Kos:</strong> ${booking.nama_kos}</p>
                            <p style="margin-bottom:4px"><strong>Kamar:</strong> ${booking.nama_kamar} (Lantai ${booking.lantai})</p>
                            <p style="margin-bottom:10px"><strong>Alamat:</strong> ${booking.alamat || '-'}</p>
                            <hr>
                            <p style="margin-top:10px margin-bottom:4px"><strong>Tanggal Check-in:</strong> ${new Date(booking.tanggal_checkin).toLocaleDateString('id-ID')}</p>
                            <p style="margin-bottom:4px"><strong>Durasi:</strong> ${booking.durasi_sewa} ${convertPeriodeSewa(booking.periode_sewa)}</p>
                            <p style="margin-bottom:4px"><strong>Total Harga:</strong> ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(booking.total_harga)}</p>
                            <p style="margin-bottom:4px"><strong>Status:</strong> ${booking.status}</p>
                            <p><strong>Catatan Penyewa:</strong> ${booking.catatan_penyewa || 'Tidak ada catatan'}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="modalCloseBtn" class="btn btn-primary">Tutup</button>
                    </div>
                </div>
            `;

                    const modalOverlay = document.getElementById('modalOverlay');
                    const modalContainer = document.getElementById('modalContainer');

                    modalContainer.innerHTML = modalHtml;
                    modalOverlay.style.display = 'flex';

                    // EVENT LISTENERS
                    document.getElementById('closeCustomModal').onclick = closeCustomModal;
                    document.getElementById('modalCloseBtn').onclick = closeCustomModal;

                    // OVERLAY CLOSE
                    modalOverlay.onclick = (e) => { if (e.target === modalOverlay) closeCustomModal(); };

                    function closeCustomModal() {
                        modalOverlay.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error viewing booking detail:', error);

                // Untuk error, masih bisa pake showModal biasa
                showModal({
                    title: "Error",
                    message: "Gagal memuat detail booking",
                    type: "error"
                });
            }
        }

        /**
         * Helper functions
         */
        function showLoading(containerId, message = 'Memuat...') {
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = `
            <div style="text-align: center; padding: 50px; color: var(--text-light);">
                <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #6a0dad; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                <p>${message}</p>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            </div>
        `;
            }
        }

        function showError(containerId, message = 'Terjadi kesalahan') {
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = `
            <div style="text-align: center; padding: 50px; color: var(--error-color);">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                <p>${message}</p>
            </div>
        `;
            }
        }

        // ==========================================================
        // 6. FUNGSI UNTUK DETAIL PENYEWA
        // ==========================================================

        /**
         * Fungsi untuk melihat detail penyewa
         */
        function lihatDetailPenyewa(penyewaId) {
            console.log('lihatDetailPenyewa dipanggil dengan ID:', penyewaId);

            let penyewaData;

            if (penyewaId === 1) {
                penyewaData = {
                    id: 1,
                    nama: 'Ahmad Sahroni',
                    sewaSejak: '3 Juli 2025',
                    noWhatsapp: '0812 2222 XXXX',
                    kamar: '22',
                    durasi: '2',
                    hargaSewa: 600000,
                    kontrakAktif: true,
                    selesaiKontrak: '3 September 2025',
                    totalHarga: 1200000,
                    periodeSewa: 'Bulanan'
                };
            } else if (penyewaId === 2) {
                penyewaData = {
                    id: 2,
                    nama: 'Ahmad Sahroni',
                    sewaSejak: '3 Juli 2025',
                    noWhatsapp: '0812 2222 XXXX',
                    kamar: '22',
                    durasi: '2',
                    hargaSewa: 600000,
                    kontrakAktif: false,
                    selesaiKontrak: '3 September 2025',
                    totalHarga: 1200000,
                    periodeSewa: 'Bulanan'
                };
            }

            localStorage.setItem('currentPenyewaData', JSON.stringify(penyewaData));

            loadContent('detail_penyewa');
        }

        /**
         * Fungsi untuk melihat kontrak sewa
         */
        function lihatKontrakSewa(penyewaId) {
            showModal({
                title: "Kontrak Sewa",
                message: `Menampilkan kontrak sewa untuk penyewa #${penyewaId}`,
                type: "info"
            });
        }

        /**
         * Fungsi untuk menghentikan kontrak
         */
        function hentikanKontrak(penyewaId) {
            showConfirm({
                title: "Konfirmasi",
                message: `Apakah Anda yakin ingin menghentikan kontrak penyewa #${penyewaId}?`,
                confirmText: "Hentikan",
                cancelText: "Batal",
                onConfirm: function () {
                    showModal({
                        title: "Sukses",
                        message: `Kontrak penyewa #${penyewaId} telah dihentikan.`,
                        type: "success",
                        onConfirm: () => {
                            loadContent('penyewa');
                        }
                    });
                }
            });
        }

        /**
         * Fungsi untuk mengubah harga sewa
         */
        function ubahHarga(penyewaId) {
            showModal({
                title: "Ubah Harga Sewa",
                message: `Form untuk mengubah harga sewa penyewa #${penyewaId} akan ditampilkan di sini.`,
                type: "info"
            });
        }

        /**
         * Fungsi untuk mengubah kamar
         */
        function ubahKamar(penyewaId) {
            showModal({
                title: "Ubah Nomor Kamar",
                message: `Form untuk mengubah nomor kamar penyewa #${penyewaId} akan ditampilkan di sini.`,
                type: "info"
            });
        }

        /**
         * Fungsi untuk mengganti tab di halaman penyewa
         */
        function switchPenyewaTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => {
                tab.style.display = 'none';
            });

            let selectedTab;
            if (tabId === 'semua') {
                selectedTab = document.getElementById('semua-penyewa');
            } else if (tabId === 'kontrak-aktif') {
                selectedTab = document.getElementById('kontrak-aktif-penyewa');
            } else if (tabId === 'kontrak-nonaktif') {
                selectedTab = document.getElementById('kontrak-nonaktif-penyewa');
            }

            if (selectedTab) {
                selectedTab.style.display = 'block';
            }

            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                const buttonTab = button.getAttribute('onclick');
                if (buttonTab && buttonTab.includes(tabId)) {
                    button.style.backgroundColor = 'var(--card-background)';
                    button.style.color = 'var(--text-color)';
                    button.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                } else {
                    button.style.backgroundColor = '';
                    button.style.color = 'var(--text-light)';
                    button.style.boxShadow = 'none';
                }
            });
        }

        /**
         * Fungsi untuk menambah penyewa baru
         */
        function tambahPenyewa() {
            showModal({
                title: "Tambah Penyewa",
                message: "Fitur tambah penyewa akan dibuka di sini.",
                type: "info"
            });
        }

        /**
         * Fungsi untuk melihat detail tagihan
         */
        function lihatDetailTagihan(tagihanId) {
            let dataTagihan;

            if (tagihanId === 'kamar-22') {
                dataTagihan = {
                    id: tagihanId,
                    nama: 'Ahmad Sahroni',
                    kos: 'Kos Maguwo City',
                    kamar: '22',
                    durasi: '2 Bulan',
                    totalTagihan: 1200000,
                    deposit: 500000,
                    denda: 20000,
                    batasDenda: 10,
                    status: 'aktif'
                };
            } else if (tagihanId === 'kamar-24') {
                dataTagihan = {
                    id: tagihanId,
                    nama: 'Ahmad Sahroni',
                    kos: 'Kos Maguwo City',
                    kamar: '24',
                    durasi: '1 Bulan',
                    totalTagihan: 600000,
                    deposit: 300000,
                    denda: 10000,
                    batasDenda: 7,
                    status: 'non-aktif'
                };
            } else if (tagihanId === 'kamar-26') {
                dataTagihan = {
                    id: tagihanId,
                    nama: 'Ahmad Sahroni',
                    kos: 'Kos Maguwo City',
                    kamar: '26',
                    durasi: '1 Bulan',
                    totalTagihan: 600000,
                    deposit: 300000,
                    denda: 10000,
                    batasDenda: 10,
                    status: 'aktif'
                };
            }

            localStorage.setItem('detailTagihan', JSON.stringify(dataTagihan));

            loadContent('kelola_tagihan_detail', dataTagihan);
        }

        /**
         * Fungsi untuk menyimpan data tagihan
         */
        function simpanDataTagihan(dataTagihan = null) {
            if (!dataTagihan) {
                const savedData = localStorage.getItem('detailTagihan');
                dataTagihan = savedData ? JSON.parse(savedData) : {};
            }

            console.log('Menyimpan data tagihan:', dataTagihan);

            showModal({
                title: "Sukses",
                message: `Data tagihan untuk ${dataTagihan.nama} (Kamar ${dataTagihan.kamar}) berhasil disimpan!`,
                type: "success",
                onConfirm: () => {
                    loadContent('tagihan');
                }
            });
        }

        function editDataTagihan(dataTagihan = null) {
            if (!dataTagihan) {
                const savedData = localStorage.getItem('detailTagihan');
                dataTagihan = savedData ? JSON.parse(savedData) : {};
            }

            // ✅ CUSTOM MODAL DENGAN INPUT FIELDS
            const modalHtml = `
                <div class="modal-containers modal-custom">
                    <div class="modal-header">
                        <h3>Edit Tagihan ${dataTagihan.nama || ''}</h3>
                        <span id="closeCustomModal" style="cursor: pointer; font-size: 24px;">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-groups">
                            <label>Total Tagihan (Rp):</label>
                            <input type="number" id="modalInputTagihan" class="input" value="${dataTagihan.totalTagihan}" autofocus>
                        </div>
                        <div class="form-groups">
                            <label>Durasi sewa :</label>
                            <input type="text" id="modalInputDurasi" placeholder="Bulan" class="input" value="${dataTagihan.durasi}">
                        </div>
                        <div class="form-groups">
                            <label>Biaya deposit (Rp):</label>
                            <input type="number" id="modalInputDeposit" class="input" value="${dataTagihan.deposit}">
                        </div>
                        <div class="form-groups">
                            <label>Biaya denda keterlambatan (Rp):</label>
                            <input type="number" id="modalInputDenda" class="input" value="${dataTagihan.denda}">
                        </div>
                        <div class="form-groups">
                            <label>Denda berlaku setelah:</label>
                            <input type="number" id="modalInputBatasDenda" class="input" value="${dataTagihan.batasDenda}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="modalCancelBtn" class="btn btn-secondary">Batal</button>
                        <button id="modalSaveBtn" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            `;

            const modalOverlay = document.getElementById('modalOverlay');
            const modalContainer = document.getElementById('modalContainer');

            modalContainer.innerHTML = modalHtml;
            modalOverlay.style.display = 'flex';

            // EVENT LISTENERS
            document.getElementById('closeCustomModal').onclick = closeCustomModal;
            document.getElementById('modalCancelBtn').onclick = closeCustomModal;
            document.getElementById('modalSaveBtn').onclick = saveTagihan;

            // OVERLAY CLOSE
            modalOverlay.onclick = (e) => { if (e.target === modalOverlay) closeCustomModal(); };

            function saveTagihan() {
                const newData = {
                    totalTagihan: parseInt(document.getElementById('modalInputTagihan').value),
                    durasi: document.getElementById('modalInputDurasi').value,
                    deposit: parseInt(document.getElementById('modalInputDeposit').value),
                    denda: parseInt(document.getElementById('modalInputDenda').value),
                    batasDenda: parseInt(document.getElementById('modalInputBatasDenda').value),
                    updatedAt: new Date().toISOString()
                };
                let daftarKos = getKosList();

                localStorage.setItem('detailTagihan', JSON.stringify(newData));
                console.log('✅ Data tagihan disimpan:', newData);

                closeCustomModal();
                loadContent('tagihan');
            }

            function closeCustomModal() {
                modalOverlay.style.display = 'none';
            }
        }

        /**
 * Sync data dari Database ke LocalStorage saat app dibuka
 */
        async function syncFromDatabase() {
            try {
                console.log('🔄 [1] Starting sync...');

                const response = await fetch('/api/get-kos');
                const dbKosList = await response.json();

                console.log('🔄 [2] Raw API data first item:', dbKosList[0]);

                if (!Array.isArray(dbKosList)) {
                    console.error('❌ Not array:', dbKosList);
                    return 0;
                }

                // 🎯 TRANSFORM DATA
                const transformedKosList = dbKosList.map((dbKos, index) => {
                    console.log(`🔄 Processing: ${dbKos.nama_kos}`);

                    // 🎯 DEKLARASI SEMUA VARIABLE DI SINI (DI AWAL)
                    let address = {};
                    let fasilitasFormatted = { fasilitasUmum: [], fasilitasKamar: [], fasilitasKMandi: [], parkir: [] };
                    let rulesFormatted = [];
                    let imagesFormatted = { bangunan: [], kamar: [] };
                    let roomDetailsFormatted = [];
                    let priceFormatted = {
                        monthly: 0, daily: 0, weekly: 0,
                        three_monthly: 0, six_monthly: 0, yearly: 0,
                        set_fine: false, fine_amount: 0, fine_limit: 0
                    };

                    // ========== PROCESS DATA ==========

                    // 1. Address
                    if (dbKos.alamat_kos && typeof dbKos.alamat_kos === 'object') {
                        address = {
                            alamat: dbKos.alamat_kos.alamat || '',
                            provinsi: dbKos.alamat_kos.provinsi || '',
                            kabupaten: dbKos.alamat_kos.kabupaten || '',
                            kecamatan: dbKos.alamat_kos.kecamatan || '',
                            catatan: dbKos.alamat_kos.catatan_alamat || '',
                            lat: dbKos.alamat_kos.lat || null,  // TAMBAH INI
                            lon: dbKos.alamat_kos.lon || null   // TAMBAH INI
                        };
                        console.log('   ✅ Got address with coordinates');
                    }

                    // 2. Fasilitas
                    if (Array.isArray(dbKos.fasilitas)) {
                        dbKos.fasilitas.forEach(f => {
                            if (!f || !f.kategori || !f.nama_fasilitas) return;

                            // Data API: kategori = "fasilitasUmum"
                            if (fasilitasFormatted.hasOwnProperty(f.kategori)) {
                                fasilitasFormatted[f.kategori].push(f.nama_fasilitas);
                            } else {
                                // Fallback
                                fasilitasFormatted.fasilitasUmum.push(f.nama_fasilitas);
                            }
                        });
                        console.log(`   ✅ Got ${dbKos.fasilitas.length} fasilitas`);
                    }

                    // 3. Rules
                    if (Array.isArray(dbKos.peraturan)) {
                        rulesFormatted = dbKos.peraturan
                            .filter(p => p && p.nama_peraturan)
                            .map(p => p.nama_peraturan);
                        console.log(`   ✅ Got ${rulesFormatted.length} rules`);
                    }

                    // 4. Images
                    if (Array.isArray(dbKos.foto_kos)) {
                        dbKos.foto_kos.forEach(foto => {
                            if (!foto) return;

                            // Gunakan full_base64_url jika ada (sudah ada data: prefix)
                            let imageUrl = foto.full_base64_url;

                            // Jika tidak ada, ambil path_foto dan TAMBAH PREFIX
                            if (!imageUrl && foto.path_foto) {
                                // 🎯 FIX: Tambah data: prefix untuk base64
                                if (foto.path_foto.startsWith('/9j/') ||
                                    foto.path_foto.startsWith('/9j/4AAQ') ||
                                    foto.path_foto.startsWith('iVBOR') ||
                                    foto.path_foto.match(/^[A-Za-z0-9+/=]/)) {

                                    // Deteksi tipe gambar dari base64 prefix
                                    if (foto.path_foto.startsWith('/9j/')) {
                                        imageUrl = 'data:image/jpeg;base64,' + foto.path_foto;
                                    } else if (foto.path_foto.startsWith('iVBOR')) {
                                        imageUrl = 'data:image/png;base64,' + foto.path_foto;
                                    } else {
                                        // Default ke JPEG
                                        imageUrl = 'data:image/jpeg;base64,' + foto.path_foto;
                                    }
                                } else {
                                    // Jika bukan base64, mungkin URL langsung
                                    imageUrl = foto.path_foto;
                                }
                            }

                            // Validasi panjang base64 (minimal 100 chars)
                            if (imageUrl && imageUrl.includes('base64,') && imageUrl.length < 150) {
                                console.warn('⚠️ Base64 terlalu pendek, mungkin truncated');
                                imageUrl = IMAGE_PLACEHOLDER;
                            }

                            // Tambah ke array sesuai tipe
                            if (imageUrl) {
                                if (foto.tipe === 'bangunan') {
                                    imagesFormatted.bangunan.push(imageUrl);
                                } else if (foto.tipe === 'kamar') {
                                    imagesFormatted.kamar.push(imageUrl);
                                }
                            }
                        });

                        console.log(`✅ Processed ${dbKos.foto_kos.length} photos -> ${imagesFormatted.bangunan.length} bangunan, ${imagesFormatted.kamar.length} kamar`);
                    }

                    // 5. Kamar & Harga
                    if (Array.isArray(dbKos.kamar) && dbKos.kamar.length > 0) {
                        console.log(`   ✅ Got ${dbKos.kamar.length} kamar`);

                        // Room details
                        roomDetailsFormatted = dbKos.kamar.map(k => ({
                            nomor: k.nama_kamar || 'Kamar',
                            lantai: k.lantai || 1,
                            terisi: k.status === 'terisi'
                        }));

                        // Price from first kamar
                        const firstKamar = dbKos.kamar[0];
                        if (firstKamar && Array.isArray(firstKamar.harga_sewa)) {
                            firstKamar.harga_sewa.forEach(h => {
                                if (h && h.periode && h.harga) {
                                    const hargaNumber = parseFloat(h.harga) || 0;
                                    switch (h.periode) {
                                        case 'harian': priceFormatted.daily = hargaNumber; break;
                                        case 'mingguan': priceFormatted.weekly = hargaNumber; break;
                                        case 'bulanan':
                                            priceFormatted.monthly = hargaNumber;
                                            priceFormatted.set_fine = parseFloat(h.denda_per_hari) > 0;
                                            priceFormatted.fine_amount = parseFloat(h.denda_per_hari) || 0;
                                            priceFormatted.fine_limit = parseInt(h.batas_hari_denda) || 0;
                                            break;
                                        case '3_bulanan': priceFormatted.three_monthly = hargaNumber; break;
                                        case '6_bulanan': priceFormatted.six_monthly = hargaNumber; break;
                                        case 'tahunan': priceFormatted.yearly = hargaNumber; break;
                                    }
                                }
                            });
                            console.log('   ✅ Got harga sewa');
                        }
                    }

                    // ========== RETURN OBJECT ==========
                    return {
                        id: Date.now() + index,
                        database_id: dbKos.id,
                        nama_kos: dbKos.nama_kos || 'Kos Tanpa Nama',
                        tipe_kos: dbKos.tipe_kos || 'campur',
                        deskripsi: dbKos.deskripsi || '',
                        total_rooms: dbKos.total_kamar || 0,
                        available_rooms: dbKos.kamar_tersedia || 0,
                        completed: true,
                        synced: true,

                        // 🎯 VARIABLE YANG SUDAH DIOLAH
                        address: address,
                        size: { type: '3 x 4' },
                        room_details: roomDetailsFormatted,
                        images: imagesFormatted,
                        fasilitas: fasilitasFormatted,
                        rules: rulesFormatted,
                        price: priceFormatted
                    };
                });

                console.log('🔄 [3] Saving to localStorage...');
                saveKosList(transformedKosList);

                console.log('✅ [4] Sync complete. Sample:', transformedKosList[0]);
                return transformedKosList.length;

            } catch (error) {
                console.error('❌ Sync failed:', error);
                return 0;
            }
        }

        /**
 * Sync data satu kos dari Database ke LocalStorage (untuk EDIT MODE)
 */
        async function syncSingleKosToLocalStorage(kosId) {
            try {
                console.log('🔄 Syncing single kos to localStorage, kosId:', kosId);

                // 1. Fetch data kos dari API
                const response = await fetch(`/api/get-kos/${kosId}`);

                if (!response.ok) {
                    throw new Error(`API error: ${response.status}`);
                }

                const dbKos = await response.json();
                console.log('📥 Data from API:', dbKos);

                // 2. Helper function untuk process images (sama seperti di syncFromDatabase)
                function processImages(fotoKosArray, tipe) {
                    if (!Array.isArray(fotoKosArray)) return [];

                    return fotoKosArray
                        .filter(foto => foto && foto.tipe === tipe)
                        .map(foto => {
                            let imageUrl = foto.full_base64_url;

                            if (!imageUrl && foto.path_foto) {
                                // Tambah prefix data:image jika belum ada
                                if (foto.path_foto.startsWith('/9j/')) {
                                    imageUrl = 'data:image/jpeg;base64,' + foto.path_foto;
                                } else if (foto.path_foto.startsWith('iVBOR')) {
                                    imageUrl = 'data:image/png;base64,' + foto.path_foto;
                                } else if (foto.path_foto.startsWith('data:image')) {
                                    imageUrl = foto.path_foto;
                                } else {
                                    // Default ke JPEG
                                    imageUrl = 'data:image/jpeg;base64,' + foto.path_foto;
                                }
                            }

                            return imageUrl || '';
                        })
                        .filter(url => url.length > 0);
                }

                // 3. Helper function untuk process fasilitas
                function processFasilitas(fasilitasArray) {
                    const result = {
                        fasilitasUmum: [],
                        fasilitasKamar: [],
                        fasilitasKMandi: [],
                        parkir: []
                    };

                    if (!Array.isArray(fasilitasArray)) return result;

                    fasilitasArray.forEach(f => {
                        if (f && f.kategori && f.nama_fasilitas) {
                            if (result.hasOwnProperty(f.kategori)) {
                                result[f.kategori].push(f.nama_fasilitas);
                            }
                        }
                    });

                    return result;
                }

                // 4. Helper function untuk process harga
                function processPrice(kamarArray) {
                    const price = {
                        monthly: 0, daily: 0, weekly: 0,
                        three_monthly: 0, six_monthly: 0, yearly: 0,
                        set_fine: false, fine_amount: 0, fine_limit: 0
                    };

                    if (Array.isArray(kamarArray) && kamarArray.length > 0) {
                        const firstKamar = kamarArray[0];
                        if (firstKamar && Array.isArray(firstKamar.harga_sewa)) {
                            firstKamar.harga_sewa.forEach(h => {
                                if (h && h.periode && h.harga) {
                                    const hargaNumber = parseFloat(h.harga) || 0;
                                    switch (h.periode) {
                                        case 'harian': price.daily = hargaNumber; break;
                                        case 'mingguan': price.weekly = hargaNumber; break;
                                        case 'bulanan':
                                            price.monthly = hargaNumber;
                                            price.set_fine = parseFloat(h.denda_per_hari) > 0;
                                            price.fine_amount = parseFloat(h.denda_per_hari) || 0;
                                            price.fine_limit = parseInt(h.batas_hari_denda) || 0;
                                            break;
                                        case '3_bulanan': price.three_monthly = hargaNumber; break;
                                        case '6_bulanan': price.six_monthly = hargaNumber; break;
                                        case 'tahunan': price.yearly = hargaNumber; break;
                                    }
                                }
                            });
                        }
                    }

                    return price;
                }

                // 5. Transform data
                const transformedKos = {
                    id: Date.now(), // New localStorage ID
                    database_id: dbKos.id, // Database ID
                    nama_kos: dbKos.nama_kos || 'Kos Tanpa Nama',
                    tipe_kos: dbKos.tipe_kos || 'campur',
                    deskripsi: dbKos.deskripsi || '',
                    total_rooms: dbKos.total_kamar || 0,
                    available_rooms: dbKos.kamar_tersedia || 0,
                    completed: true,
                    synced: true,
                    address: {
                        alamat: dbKos.alamat_kos?.alamat || '',
                        provinsi: dbKos.alamat_kos?.provinsi || '',
                        kabupaten: dbKos.alamat_kos?.kabupaten || '',
                        kecamatan: dbKos.alamat_kos?.kecamatan || '',
                        catatan: dbKos.alamat_kos?.catatan_alamat || '',
                        lat: dbKos.alamat_kos?.lat || null,
                        lon: dbKos.alamat_kos?.lon || null
                    },
                    size: { type: '3 x 4' },
                    room_details: Array.isArray(dbKos.kamar)
                        ? dbKos.kamar.map(k => ({
                            nomor: k.nama_kamar || 'Kamar',
                            lantai: k.lantai || 1,
                            terisi: k.status === 'terisi'
                        }))
                        : [],
                    images: {
                        bangunan: processImages(dbKos.foto_kos, 'bangunan'),
                        kamar: processImages(dbKos.foto_kos, 'kamar')
                    },
                    fasilitas: processFasilitas(dbKos.fasilitas),
                    rules: Array.isArray(dbKos.peraturan)
                        ? dbKos.peraturan
                            .filter(p => p && p.nama_peraturan)
                            .map(p => p.nama_peraturan)
                        : [],
                    price: processPrice(dbKos.kamar)
                };

                console.log('🔄 Transformed data:', transformedKos);

                // 6. Save ke localStorage
                const list = getKosList();
                const existingIndex = list.findIndex(k => k.database_id === kosId);

                if (existingIndex > -1) {
                    // Update existing
                    list[existingIndex] = transformedKos;
                    console.log('📝 Updated existing kos in localStorage');
                } else {
                    // Add new
                    list.push(transformedKos);
                    console.log('📝 Added new kos to localStorage');
                }

                saveKosList(list);
                console.log('✅ Successfully synced kos to localStorage');

                return transformedKos;

            } catch (error) {
                console.error('❌ Error syncing single kos:', error);
                throw error;
            }
        }



        // ==========================================================
        // 7. INISIALISASI
        // ==========================================================

        setTimeout(() => {
            const kosList = getKosList();
            console.log(`📊 LocalStorage saat load: ${kosList.length} kos`);

            if (kosList.length === 0) {
                console.log('🔄 LocalStorage kosong, sync dari database...');
                syncFromDatabase().then(count => {
                    if (count > 0) {
                        console.log(`✅ ${count} kos di-load dari database`);

                        // Jika sedang di halaman kos dashboard, refresh
                        if (window.location.hash.includes('kos') ||
                            document.querySelector('[data-page="kos_saya_dashboard"]')) {
                            setTimeout(() => loadKosDashboard(), 500);
                        }
                    }
                }).catch(error => {
                    console.error('❌ Sync gagal:', error);
                });
            }
        }, 1000);
        // Memuat konten HOME saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', () => {
            loadContent('home');

            // Event listener untuk menu sidebar (termasuk dropdown)
            document.getElementById('nav-menu').addEventListener('click', async (event) => {
                const target = event.target.closest('.nav-item');
                const dropdownTarget = event.target.closest('.dropdown-item');

                if (dropdownTarget && dropdownTarget.hasAttribute('data-page')) {
                    event.preventDefault();
                    const page = dropdownTarget.getAttribute('data-page');
                    loadContent(page);

                    // JANGAN PANGGIL toggleManagementDropdown() DI SINI SAMA SEKALI
                    // Biarkan dropdown tetap terbuka
                }
                else if (target && target.hasAttribute('data-page')) {
                    event.preventDefault();
                    const page = target.getAttribute('data-page');

                    if (page === 'management') {
                        toggleManagementDropdown();
                    } else if (page === 'data_kos_step1') {
                        await loadKosDashboard();
                    } else {
                        loadContent(page);
                    }
                }
            });

            // Tutup dropdown jika klik di luar area
            document.addEventListener('click', (event) => {
                const dropdown = document.getElementById('management-dropdown');
                const toggleButton = document.getElementById('management-toggle');

                if (isManagementDropdownOpen &&
                    !dropdown.contains(event.target) &&
                    !toggleButton.contains(event.target)) {
                    toggleManagementDropdown();
                }
            });

            function closeDropdown() {
                const menu = document.getElementById('user-dropdown-menu');
                const button = document.getElementById('dropdown-toggle-btn');

                if (menu) {
                    menu.classList.add('hidden');
                    console.log('Dropdown closed');
                }
                if (button) {
                    button.setAttribute('aria-expanded', 'false');
                }
            }

            const accountBtn = document.getElementById('account-btn');
            if (accountBtn) {
                accountBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    closeDropdown();
                    setTimeout(() => loadContent('account'), 100);
                });
            }

        });

        // Di fungsi lihatDetailPenyewa, tambahkan properti foto:
        function lihatDetailPenyewa(penyewaId) {
            console.log('lihatDetailPenyewa dipanggil dengan ID:', penyewaId);

            let penyewaData;

            if (penyewaId === 1) {
                penyewaData = {
                    id: 1,
                    nama: 'Ahmad Sahroni',
                    sewaSejak: '3 Juli 2025',
                    noWhatsapp: '0812 2222 XXXX',
                    kamar: '22',
                    durasi: '2',
                    hargaSewa: 600000,
                    kontrakAktif: true,
                    selesaiKontrak: '3 September 2025',
                    totalHarga: 1200000,
                    periodeSewa: 'Bulanan',
                    foto: 'https://randomuser.me/api/portraits/men/1.jpg' // URL foto profil
                };
            } else if (penyewaId === 2) {
                penyewaData = {
                    id: 2,
                    nama: 'Ahmad Sahroni',
                    sewaSejak: '3 Juli 2025',
                    noWhatsapp: '0812 2222 XXXX',
                    kamar: '22',
                    durasi: '2',
                    hargaSewa: 600000,
                    kontrakAktif: false,
                    selesaiKontrak: '3 September 2025',
                    totalHarga: 1200000,
                    periodeSewa: 'Bulanan',
                    foto: 'https://randomuser.me/api/portraits/men/2.jpg' // URL foto profil
                };
            }

            localStorage.setItem('currentPenyewaData', JSON.stringify(penyewaData));

            loadContent('detail_penyewa');
        }

        /**
         * Fungsi untuk membuka halaman detail kamar setelah menyimpan data sementara
         */
        function goToDetailKamar() {
            console.log('=== DEBUG goToDetailKamar START ===');
            console.log('currentKosId:', currentKosId);
            console.log('isEditMode:', isEditMode);

            // Simpan data sementara dari form step 6
            const activeSizeButton = document.querySelector('#room_size_selection .gender-button.active');
            const totalRooms = document.getElementById('jumlah_total_kamar').value;
            const availableRooms = document.getElementById('jumlah_kamar_tersedia').value;
            const customWidth = document.getElementById('custom_width').value;
            const customLength = document.getElementById('custom_length').value;

            console.log('totalRooms from form:', totalRooms);
            console.log('activeSizeButton:', activeSizeButton);

            if (!totalRooms || parseInt(totalRooms) <= 0) {
                console.log('❌ totalRooms invalid');
                showModal({
                    title: "Peringatan",
                    message: "Anda harus mengisi jumlah total kamar (misalnya: 5) sebelum mengatur detail kamar.",
                    type: "warning"
                });
                return;
            }

            // Simpan data sementara ke kos yang sedang diedit
            let list = getKosList();
            console.log('Current kos list:', list);

            const kosIndex = list.findIndex(kos =>
                kos.database_id === currentKosId || kos.id === currentKosId);
            console.log('kosIndex found:', kosIndex);

            if (kosIndex > -1) {
                const sizeType = activeSizeButton ? activeSizeButton.textContent.trim() : '3 x 4';
                const sizeData = {
                    type: sizeType,
                    custom_w: customWidth,
                    custom_l: customLength
                };

                list[kosIndex] = {
                    ...list[kosIndex],
                    size: sizeData,
                    total_rooms: parseInt(totalRooms),
                    available_rooms: parseInt(availableRooms) || 0,
                };
                saveKosList(list);
                console.log('✅ Saved to localStorage, calling loadContent...');
                console.log('=== DEBUG END ===');

                // Buka halaman detail kamar
                loadContent('data_kos_step6_detail', 6);
            } else {
                console.log('❌ Kos not found in localStorage');
                console.log('=== DEBUG END ===');
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan. Silakan mulai dari Langkah 1.",
                    type: "error"
                });
            }
        }

        document.addEventListener('click', (e) => {
            const target = e.target.closest('.waiting-card');
            if (!target) return;

            // Booking Menunggu
            if (target.id === 'btn-booking-menunggu') {
                e.preventDefault();
                loadContent('booking');
                setTimeout(() => {
                    switchBookingTab('butuh-konfirmasi');
                }, 50);
            }

            // Tagihan Penyewa
            if (target.id === 'btn-tagihan-penyewa') {
                e.preventDefault();
                loadContent('tagihan');
            }
        });

        document.addEventListener('click', (e) => {
            const target = e.target.closest('.management-card');
            if (!target) return;

            // Booking Menunggu
            if (target.id === 'btn-penyewa') {
                e.preventDefault();
                loadContent('penyewa');
                setTimeout(() => {
                    switchPenyewaTab('semua');
                }, 50);
            }
        });

    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>

</html>