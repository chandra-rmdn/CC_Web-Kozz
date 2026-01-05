<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszzz Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 50%;
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
                    <a href="#" class="dropdown-item" data-page="tagihan">
                        <i class="fas fa-file-invoice-dollar"></i> Kelola Tagihan
                    </a>
                    <a href="#" class="dropdown-item" data-page="penyewa">
                        <i class="fas fa-users"></i> Penyewa
                    </a>
                </div>
                <a href="#" class="nav-item" data-page="account">
                    <i class="fas fa-user"></i> Akun
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <span class="chat">Chat</span>
                <div class="user-profile">
                    <i class="fas fa-user-circle profile-icon"></i>
                    {{ auth()->user()->name }}
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

    <script>
        let currentKosData = {
            nama_kos: '',
            tipe_kos: 'putra',
            deskripsi: '',
        };

        let currentKos = {
            local_id: null,
            db_id: null
        };

        // let currentKosData = {};


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

            // Set konten modal
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalConfirm.textContent = confirmText;
            modalCancel.textContent = cancelText;

            // Set tipe modal (untuk styling)
            modalContainer.className = 'modal-container';
            if (type) {
                modalContainer.classList.add(`modal-${type}`);
            }

            // Tampilkan/sembunyikan tombol batal
            modalCancel.style.display = showCancel ? 'inline-block' : 'none';

            // Tampilkan modal
            modalOverlay.style.display = 'flex';

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
                modalConfirm.removeEventListener('click', confirmHandler);
                modalCancel.removeEventListener('click', cancelHandler);
                modalClose.removeEventListener('click', closeHandler);
                modalOverlay.removeEventListener('click', overlayClickHandler);
            };

            // Event listener untuk klik di luar modal
            const overlayClickHandler = (e) => {
                if (e.target === modalOverlay) {
                    closeHandler();
                }
            };

            // Tambahkan event listener baru
            modalConfirm.addEventListener('click', confirmHandler);
            if (showCancel) {
                modalCancel.addEventListener('click', cancelHandler);
            }
            modalClose.addEventListener('click', closeHandler);
            modalOverlay.addEventListener('click', overlayClickHandler);

            // Fokus ke tombol OK untuk aksesibilitas
            setTimeout(() => modalConfirm.focus(), 100);
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

        /**
         * Mengambil daftar kos dari LocalStorage.
         */
        // function getKosList() {/
        //     const data = localStorage.getItem(KOS_LIST_KEY);
        //     try {
        //         return data ? JSON.parse(data) : [];
        //     } catch (e) {
        //         console.error("Local Storage data corrupted, returning empty list.", e);
        //         return [];
        //     }
        // }
        async function getKosList() {
            try {
                // Coba ambil dari database dulu
                const response = await fetch('/api/get-all-kos');
                if (response.ok) {
                    const data = await response.json();

                    // Simpan ke localStorage sebagai cache/backup
                    localStorage.setItem('daftarKos_cache', JSON.stringify(data));

                    // Log untuk debug
                    console.log('📊 Data diambil dari database:', data.length + ' kos');

                    return data;
                }
            } catch (error) {
                console.log('⚠️ Gagal ambil dari database, pakai localStorage:', error);
            }

            // Fallback ke localStorage jika database error
            const data = localStorage.getItem('daftarKos');
            return data ? JSON.parse(data) : [];
        }

        /**
         * Menyimpan daftar kos ke LocalStorage.
         */
        function saveKosList(list) {
            localStorage.setItem(KOS_LIST_KEY, JSON.stringify(list));
        }

        async function syncLocalWithDatabase() {
            console.log('🔄 Syncing localStorage with database...');

            try {
                const response = await fetch('/admin/kos-list', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success && result.data) {
                    const dbKosList = result.data;
                    let localList = getKosList();

                    console.log('Database has', dbKosList.length, 'kos');
                    console.log('Local has', localList.length, 'kos');

                    dbKosList.forEach(dbKos => {
                        const existingIndex = localList.findIndex(localKos =>
                            localKos.id == dbKos.id ||
                            localKos.database_id == dbKos.id ||
                            (localKos.nama && localKos.nama === dbKos.nama_kos)
                        );

                        const transformedData = {
                            id: dbKos.id,  // ✅ ID DATABASE
                            database_id: dbKos.id,  // ✅ REFERENCE
                            nama: dbKos.nama_kos,
                            tipe: dbKos.tipe_kos,
                            deskripsi: dbKos.deskripsi,

                            // Alamat
                            alamat: dbKos.alamat_kos?.alamat,
                            provinsi: dbKos.alamat_kos?.provinsi,
                            kabupaten: dbKos.alamat_kos?.kabupaten,
                            kecamatan: dbKos.alamat_kos?.kecamatan,
                            catatan_alamat: dbKos.alamat_kos?.catatan_alamat,

                            // Fasilitas & Peraturan
                            fasilitas: dbKos.fasilitas?.map(f => f.id) || [],
                            peraturan: dbKos.peraturan?.map(p => p.id) || [],

                            // Kamar data - PENTING!
                            total_kamar: dbKos.total_kamar || 0,
                            kamar_tersedia: dbKos.kamar_tersedia || 0,
                            total_rooms: dbKos.total_kamar || 0,
                            available_rooms: dbKos.kamar_tersedia || 0,

                            // Data untuk kompatibilitas
                            nama_kos: dbKos.nama_kos,
                            tipe_kos: dbKos.tipe_kos,
                            completed: true,
                            kamars: dbKos.kamars || [],
                            room_details: dbKos.kamars?.map(k => ({
                                nomor: k.nama_kamar,
                                lantai: k.lantai || 1,
                                ukuran: k.ukuran_kamar || '3 x 3',
                                terisi: k.status === 'terisi'
                            })) || []
                        };

                        if (existingIndex === -1) {
                            localList.push(transformedData);
                            console.log('➕ Added new kos from DB:', dbKos.nama_kos, 'ID:', dbKos.id);
                        } else {
                            // Update existing, tapi jangan overwrite data yang sudah ada di local
                            localList[existingIndex] = {
                                ...localList[existingIndex],
                                ...transformedData,
                                id: dbKos.id,  // Pastikan ID database
                                database_id: dbKos.id
                            };
                            console.log('🔄 Updated existing kos:', dbKos.nama_kos, 'ID:', dbKos.id);
                        }
                    });

                    // Hapus entry lokal yang tidak ada di database
                    const validIds = dbKosList.map(k => k.id);
                    localList = localList.filter(kos =>
                        validIds.includes(kos.id) ||
                        validIds.includes(kos.database_id) ||
                        !kos.database_id // Keep local-only entries for now
                    );

                    saveKosList(localList);
                    console.log('✅ localStorage synced with database. Total:', localList.length);

                    // Log hasil sync
                    console.log('Final localStorage:', localList.map(k => ({
                        id: k.id,
                        database_id: k.database_id,
                        nama: k.nama,
                        total_kamar: k.total_kamar
                    })));

                } else {
                    console.error('Failed to get data from database');
                }
            } catch (error) {
                console.error('Sync error:', error);
            }
        }

        // Panggil saat load app
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(syncLocalWithDatabase, 1000);
        });

        /**
         * Mengambil data satu kos berdasarkan ID.
         */
        function getKosData(kosId) {
            console.log('getKosData called for ID:', kosId);

            // 1. Jika kosId null/undefined, coba pakai currentKosId
            if (!kosId && window.currentKosId) {
                kosId = window.currentKosId;
                console.log('Using window.currentKosId:', kosId);
            }

            // 2. Cari di localStorage
            const list = getKosList();
            const kosFromLocal = list.find(kos => kos.id == kosId);

            if (kosFromLocal) {
                console.log('Found in localStorage:', kosFromLocal);
                return kosFromLocal;
            }

            // 3. Jika edit mode, gunakan currentKosData
            if (currentKosData && currentKos.db_id == kosId) {
                console.log('Using currentKosData for edit mode');
                return {
                    id: kosId,
                    ...currentKosData
                };
            }

            // 4. Jika masih CREATE mode dengan currentKosId
            if (window.currentKosId && window.currentKosId == kosId) {
                console.log('Using currentKosData for create mode');
                return {
                    id: kosId,
                    ...currentKosData
                };
            }

            console.log('No data found for ID:', kosId);
            return {};
        }

        /**
         * Menghapus data satu kos berdasarkan ID.
         */
        function deleteKos(id) {
            showConfirm({
                title: "Konfirmasi Hapus",
                message: "Apakah Anda yakin ingin menghapus kos ini? Aksi ini tidak dapat dibatalkan.",
                confirmText: "Hapus",
                cancelText: "Batal",
                onConfirm: function () {
                    let list = getKosList();
                    list = list.filter(kos => kos.id !== id);
                    saveKosList(list);
                    loadKosDashboard();

                    showModal({
                        title: "Sukses",
                        message: "Kos berhasil dihapus!",
                        type: "success"
                    });
                }
            });
        }

        /**
         * Helper untuk format angka menjadi mata uang Rupiah.
         */
        function formatRupiah(number) {
            try {
                if (number === 0 || number === undefined || number === null) return '0';

                const num = typeof number === 'string' ? parseInt(number.replace(/[^0-9]/g, '')) || 0 : number;

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

            // Validasi form
            let errorMessages = [];

            if (!namaKos) errorMessages.push("• Nama Kos belum diisi");
            if (!deskripsiKos) errorMessages.push("• Deskripsi Kos belum diisi");
            if (!tipeKosElement) errorMessages.push("• Tipe Kos belum dipilih");

            if (errorMessages.length > 0) {
                showModal({
                    title: "Form Belum Lengkap",
                    message: "Mohon lengkapi data berikut:\n\n" + errorMessages.join("\n"),
                    type: "warning"
                });
                return;
            }

            let list = getKosList();
            const isEditing = currentKosId !== null;

            console.log('Step 1 data:', { nama_kos: namaKos, tipe_kos: tipeKosElement.textContent.trim(), deskripsi: deskripsiKos });
            currentKosData.nama_kos = namaKos;
            currentKosData.tipe_kos = tipeKosElement.textContent.trim().toLowerCase();
            currentKosData.deskripsi = deskripsiKos;
            console.log('currentKosData updated:', currentKosData);

            if (isEditing) {
                // Update kos yang sudah ada
                const kosIndex = list.findIndex(kos => kos.id === currentKosId);
                if (kosIndex > -1) {
                    list[kosIndex] = {
                        ...list[kosIndex],
                        nama: namaKos,
                        tipe: tipeKosElement.textContent.trim(),
                        deskripsi: deskripsiKos,
                    };

                    saveKosList(list);

                    showModal({
                        title: "Sukses",
                        message: "Data dasar kos berhasil diperbarui!",
                        type: "success",
                        onConfirm: () => {
                            loadContent('data_kos_step2', 2);
                        }
                    });
                } else {
                    showModal({
                        title: "Kesalahan",
                        message: "Data kos tidak ditemukan.",
                        type: "error"
                    });
                }
            } else {
                // Tambah kos baru
                const newKos = {
                    id: Date.now(), // ID unik sederhana
                    nama: namaKos,
                    tipe: tipeKosElement.textContent.trim(),
                    deskripsi: deskripsiKos,
                    completed: false,
                    images: { bangunan: [], kamar: [] },
                    rules: {},
                    address: {},
                    facilities: {},
                    size: { type: '3 x 4', custom_w: '3', custom_l: '4' },
                    total_rooms: 0,
                    available_rooms: 0,
                    room_details: [],
                    price: {
                        monthly: 0,
                        daily: 0,
                        weekly: 0,
                        three_monthly: 0,
                        six_monthly: 0,
                        yearly: 0,
                        set_fine: false,
                        fine_amount: 0,
                        fine_limit: 1,
                    },
                };
                list.push(newKos);
                currentKosId = newKos.id;
                saveKosList(list);

                showModal({
                    title: "Sukses",
                    message: "Data dasar kos berhasil disimpan!",
                    type: "success",
                    onConfirm: () => {
                        loadContent('data_kos_step2', 2);
                    }
                });
            }
        }

        function handleStep2Save() {
            console.log('handleStep2Save dipanggil');

            // Ambil SEMUA checkbox di step 2 yang dicentang
            const checkboxes = document.querySelectorAll('.rules-form input[type="checkbox"]:checked');
            const rules = Array.from(checkboxes).map(cb => {
                const label = cb.closest('.checkbox-container');
                return label ? label.textContent.trim().replace('✓', '').trim() : '';
            }).filter(rule => rule.length > 0);

            console.log('Step 2 rules:', rules);

            // Simpan ke currentKosData
            currentKosData.rules = rules;
            console.log('currentKosData updated:', currentKosData);

            // Lanjut ke step 3
            loadContent('data_kos_step3', 3);
        }



        /**
         * Mengambil data dari form step 3 (Alamat) dan menyimpannya.
         */
        function handleStep3Save() {
            const alamat = document.getElementById('alamat').value.trim();
            const catatan = document.getElementById('catatan').value.trim();
            const provinsi = document.getElementById('provinsi').value.trim();
            const kabupaten = document.getElementById('kabupaten').value.trim();
            const kecamatan = document.getElementById('kecamatan').value.trim();
            const addressData = {
                alamat: alamat,
                catatan: catatan,
                provinsi: provinsi,
                kabupaten: kabupaten,
                kecamatan: kecamatan
            };

            // Validasi form
            let errorMessages = [];

            if (!alamat) errorMessages.push("• Alamat belum diisi");
            if (!provinsi) errorMessages.push("• Provinsi belum diisi");
            if (!kabupaten) errorMessages.push("• Kabupaten/Kota belum diisi");
            if (!kecamatan) errorMessages.push("• Kecamatan belum diisi");

            if (errorMessages.length > 0) {
                showModal({
                    title: "Form Belum Lengkap",
                    message: "Mohon lengkapi data berikut:\n\n" + errorMessages.join("\n"),
                    type: "warning"
                });
                return;
            }

            console.log('Step 3 data:', { alamat, provinsi, kabupaten, kecamatan, catatan });
            currentKosData.alamat = alamat;
            currentKosData.catatan_alamat = catatan;
            currentKosData.provinsi = provinsi;
            currentKosData.kabupaten = kabupaten;
            currentKosData.kecamatan = kecamatan;
            currentKosData.address = addressData;
            console.log('currentKosData updated:', currentKosData);

            let list = getKosList();
            const kosIndex = list.findIndex(kos => kos.id === currentKosId);

            if (kosIndex > -1) {
                list[kosIndex] = {
                    ...list[kosIndex],
                    address: {
                        alamat: alamat,
                        catatan: catatan,
                        provinsi: provinsi,
                        kabupaten: kabupaten,
                        kecamatan: kecamatan,
                        address: addressData
                    }
                };
                saveKosList(list);

                showModal({
                    title: "Sukses",
                    message: "Data alamat kos berhasil disimpan!",
                    type: "success",
                    onConfirm: () => {
                        loadContent('data_kos_step4', 4);
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


        function handleStep5Save() {
            console.log('Step 5: Simpan fasilitas');

            const checkboxes = document.querySelectorAll('.dashboard-page input[type="checkbox"]:checked');
            // ATAU
            // const checkboxes = document.querySelectorAll('label.checkbox-container input[type="checkbox"]:checked');

            // Ambil SEMUA checkbox yang dicentang di step 5
            const fasilitas = Array.from(checkboxes).map(cb => cb.value);
            currentKosData.fasilitas = fasilitas;


            console.log('Fasilitas disimpan:', fasilitas);
            console.log('FULL currentKosData:', currentKosData);

            // Lanjut step 6
            loadContent('data_kos_step6', 6);
        }



        /**
         * Mengambil data dari form step 6 (Ukuran dan Ketersediaan) dan menyimpannya.
         */
        function handleStep6Save() {
            console.log('🔧 handleStep6Save dipanggil');

            try {
                const activeSizeButton = document.querySelector('#room_size_selection .gender-button.active');
                const totalRoomsInput = document.getElementById('jumlah_total_kamar');
                const availableRoomsInput = document.getElementById('jumlah_kamar_tersedia');
                const customWidthInput = document.getElementById('custom_width');
                const customLengthInput = document.getElementById('custom_length');

                // Validasi elemen form
                if (!totalRoomsInput || !availableRoomsInput) {
                    throw new Error('Form element tidak ditemukan');
                }

                const totalRooms = totalRoomsInput.value;
                const availableRooms = availableRoomsInput.value;
                const customWidth = customWidthInput?.value || '';
                const customLength = customLengthInput?.value || '';

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

                const total = parseInt(totalRooms);
                const available = parseInt(availableRooms);
                const sizeData = {
                    type: sizeType,
                    custom_w: customWidth,
                    custom_l: customLength
                };

                console.log('📊 Data kamar:', { total, available, sizeType });

                // Validasi currentKosId
                if (!currentKosId) {
                    throw new Error('currentKosId tidak ditemukan');
                }

                let list = getKosList();
                const kosIndex = list.findIndex(kos => kos.id === currentKosId);

                if (kosIndex > -1) {
                    // Update localStorage
                    list[kosIndex] = {
                        ...list[kosIndex],
                        size: sizeData,
                        total_rooms: total,
                        available_rooms: available,
                        total_kamar: total,
                        kamar_tersedia: available
                    };

                    saveKosList(list);

                    // Update currentKosData
                    currentKosData.size = sizeData;
                    currentKosData.total_rooms = total;
                    currentKosData.available_rooms = available;
                    currentKosData.total_kamar = total;
                    currentKosData.kamar_tersedia = available;

                    console.log('✅ Data kamar tersimpan:', {
                        total_kamar: total,
                        kamar_tersedia: available,
                        localStorageUpdated: true
                    });

                    showModal({
                        title: "Sukses",
                        message: `Data kamar berhasil disimpan!\nTotal: ${total} kamar\nTersedia: ${available} kamar`,
                        type: "success",
                        onConfirm: () => {
                            loadContent('data_kos_step7', 7);
                        }
                    });
                } else {
                    throw new Error(`Kos dengan ID ${currentKosId} tidak ditemukan di localStorage`);
                }

            } catch (error) {
                console.error('❌ Error di handleStep6Save:', error);
                showModal({
                    title: "Kesalahan",
                    message: error.message || "Terjadi kesalahan saat menyimpan data kamar",
                    type: "error"
                });
            }
        }

        /**
         * Mengambil data dari form step 6 Detail (Per kamar) dan menyimpannya.
         */
        function handleStep6DetailSave() {
            let list = getKosList();
            const kosIndex = list.findIndex(kos => kos.id === currentKosId);

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

                currentKosData.room_details = newRoomDetails;
                currentKosData.available_rooms = availableCount;
                console.log('📊 currentKosData detail FULL:', currentKosData);

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
            const kosIndex = list.findIndex(kos => kos.id === currentKosId);

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

                currentKosData.price = {
                    monthly: finalMonthlyPrice,
                    daily: dailyPrice,
                    weekly: weeklyPrice,
                    three_monthly: threeMonthlyPrice,
                    six_monthly: sixMonthlyPrice,
                    yearly: yearlyPrice,
                    set_fine: fineChecked,
                    fine_amount: fineAmount,
                    fine_limit: parseInt(fineLimit)
                };
                console.log('currentKosData price updated:', currentKosData.price);

                showModal({
                    title: "Sukses",
                    message: "Data harga sewa berhasil disimpan!",
                    type: "success",
                    onConfirm: () => {
                        loadContent('data_kos_step8', 8);
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



        /**
 * Menandai proses pengisian kos selesai (di step 8).
 */
        async function finalizeKosSubmission() {
            console.log('=== FINALIZE KOS SUBMISSION ===');

            // 1. AMBIL DATA LENGKAP DARI LOCALSTORAGE
            const list = getKosList();
            let kosId = window.currentKosId || currentKos?.db_id;
            console.log('Looking for kos with ID:', kosId);

            const kosData = list.find(kos =>
                kos.id == kosId ||
                kos.database_id == kosId ||
                kos.db_id == kosId
            );

            if (!kosData) {
                showModal({
                    title: "Kesalahan",
                    message: "Data kos tidak ditemukan di penyimpanan lokal.",
                    type: "error"
                });
                return;
            }

            console.log('Data lengkap dari localStorage:', kosData);

            // 2. TRANSFORM DATA KE FORMAT YANG DITERIMA BACKEND
            const payload = {
                // Data dasar kos
                nama_kos: kosData.nama || kosData.nama_kos || '',
                tipe_kos: (kosData.tipe || kosData.tipe_kos || 'putra').toLowerCase(),
                deskripsi: kosData.deskripsi || '',
                total_kamar: kosData.total_kamar || kosData.total_rooms || 0,
                kamar_tersedia: kosData.kamar_tersedia || kosData.available_rooms || 0,

                // Data alamat
                alamat_kos: {
                    alamat: kosData.alamat || (kosData.address && kosData.address.alamat) || '',
                    provinsi: kosData.provinsi || (kosData.address && kosData.address.provinsi) || '',
                    kabupaten: kosData.kabupaten || (kosData.address && kosData.address.kabupaten) || '',
                    kecamatan: kosData.kecamatan || (kosData.address && kosData.address.kecamatan) || '',
                    catatan_alamat: kosData.catatan_alamat || (kosData.address && kosData.address.catatan) || ''
                },

                // Data fasilitas dan peraturan (ID array)
                fasilitas: Array.isArray(kosData.fasilitas) ? kosData.fasilitas : [],
                peraturan: Array.isArray(kosData.peraturan) ? kosData.peraturan : [],

                // Data harga
                harga_sewa: {
                    harian: kosData.price?.daily || 0,
                    mingguan: kosData.price?.weekly || 0,
                    bulanan: kosData.price?.monthly || 0,
                    tiga_bulanan: kosData.price?.three_monthly || 0,
                    enam_bulanan: kosData.price?.six_monthly || 0,
                    tahunan: kosData.price?.yearly || 0,
                    denda_per_hari: kosData.price?.fine_amount || 0,
                    batas_hari_denda: kosData.price?.fine_limit || 7
                },

                // Data kamar (array)
                kamars: Array.isArray(kosData.room_details) ? kosData.room_details.map((room, index) => ({
                    nama_kamar: room.nomor || `Kamar ${index + 1}`,
                    lantai: parseInt(room.lantai) || 1,
                    ukuran_kamar: kosData.size?.type || '3 x 4',
                    status: room.terisi ? 'terisi' : 'tersedia'
                })) : [],

                // Data gambar/foto
                foto_kos: []
            };

            // 3. TAMBAHKAN DATA GAMBAR JIKA ADA
            if (kosData.images) {
                const fotoBangunan = Array.isArray(kosData.images.bangunan)
                    ? kosData.images.bangunan.map(url => ({
                        tipe: 'bangunan',
                        url: url
                    }))
                    : [];

                const fotoKamar = Array.isArray(kosData.images.kamar)
                    ? kosData.images.kamar.map(url => ({
                        tipe: 'kamar',
                        url: url
                    }))
                    : [];

                payload.foto_kos = [...fotoBangunan, ...fotoKamar];
            }

            console.log('Payload yang akan dikirim:', payload);

            // 4. TENTUKAN URL DAN METHOD
            const isEditMode = currentKos?.db_id;
            const url = isEditMode
                ? `/admin/kos/${currentKos.db_id}`
                : '/admin/kos';
            const method = isEditMode ? 'PUT' : 'POST';

            console.log(`Mode: ${isEditMode ? 'EDIT' : 'CREATE'}, URL: ${url}, Method: ${method}`);

            // 5. KIRIM KE SERVER
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                console.log('Response dari server:', result);

                if (!response.ok) {
                    throw new Error(result.message || `HTTP error! status: ${response.status}`);
                }

                if (result.success) {
                    // 6. UPDATE LOCALSTORAGE DENGAN ID DATABASE (jika create)
                    if (!isEditMode && result.id) {
                        const updatedList = getKosList();
                        const index = updatedList.findIndex(k => k.id == kosId);
                        if (index !== -1) {
                            updatedList[index] = {
                                ...updatedList[index],
                                id: result.id,
                                database_id: result.id,
                                db_id: result.id,
                                completed: true
                            };
                            saveKosList(updatedList);
                        }
                    }

                    // 7. TAMPILKAN SUKSES
                    showModal({
                        title: "Sukses!",
                        message: isEditMode
                            ? `Kos "${payload.nama_kos}" berhasil diperbarui!`
                            : `Kos "${payload.nama_kos}" berhasil ditambahkan!`,
                        type: "success",
                        onConfirm: () => {
                            // Reset state
                            window.currentKosId = null;
                            currentKos = { db_id: null, local_id: null };
                            window.currentKosData = {};

                            // Kembali ke dashboard
                            loadKosDashboard();
                        }
                    });

                } else {
                    throw new Error(result.message || 'Gagal menyimpan data');
                }

            } catch (error) {
                console.error('Error mengirim data:', error);
                showModal({
                    title: "Gagal Menyimpan",
                    message: `Terjadi kesalahan: ${error.message}\n\nPastikan server berjalan dan koneksi internet stabil.`,
                    type: "error"
                });
            }
        }


        /**
 * Helper untuk simpan ke localStorage (opsional)
 */
        function saveKosToLocalList(dbId, namaKos) {
            const list = JSON.parse(localStorage.getItem('kosList') || '[]');
            list.push({
                id: dbId,
                nama_kos: namaKos,
                created_at: new Date().toISOString()
            });
            localStorage.setItem('kosList', JSON.stringify(list));
        }

        /**
         * Load form untuk edit
         */
        async function loadEditForm(kosId) {
            console.log('=== LOAD EDIT FORM ===', 'ID:', kosId);

            try {
                // 1. Ambil data dari API
                const response = await fetch(`/admin/kos/${kosId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                console.log('API Response:', result);

                if (result.success) {
                    // 2. BUAT TRANSFORMED DATA DARI API
                    const transformedData = {
                        id: result.data.id,  // ✅ ID DATABASE
                        database_id: result.data.id,  // ✅ SIMPAN REFERENCE
                        nama: result.data.nama_kos,  // Untuk dashboard
                        tipe: result.data.tipe_kos,  // Untuk dashboard
                        nama_kos: result.data.nama_kos,
                        tipe_kos: result.data.tipe_kos,
                        deskripsi: result.data.deskripsi,

                        // Alamat
                        alamat: result.data.alamat_kos?.alamat,
                        provinsi: result.data.alamat_kos?.provinsi,
                        kabupaten: result.data.alamat_kos?.kabupaten,
                        kecamatan: result.data.alamat_kos?.kecamatan,
                        catatan_alamat: result.data.alamat_kos?.catatan_alamat,

                        address: {
                            alamat: result.data.alamat_kos?.alamat,
                            provinsi: result.data.alamat_kos?.provinsi,
                            kabupaten: result.data.alamat_kos?.kabupaten,
                            kecamatan: result.data.alamat_kos?.kecamatan,
                            catatan: result.data.alamat_kos?.catatan_alamat
                        },

                        // Fasilitas & Peraturan
                        fasilitas: result.data.fasilitas?.map(f => f.id) || [],
                        peraturan: result.data.peraturan?.map(p => p.id) || [],

                        // Gambar
                        images: {
                            bangunan: result.data.foto_kos
                                ?.filter(f => f.tipe === 'bangunan')
                                ?.map(f => f.full_base64_url) || [],
                            kamar: result.data.foto_kos
                                ?.filter(f => f.tipe === 'kamar')
                                ?.map(f => f.full_base64_url) || []
                        },

                        // Kamar
                        room_details: result.data.kamars?.map(k => ({
                            nomor: k.nama_kamar,
                            lantai: k.lantai || 1,
                            ukuran: k.ukuran_kamar || '3 x 3',
                            terisi: k.status === 'terisi'
                        })) || [],

                        // Harga
                        price: {
                            daily: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === 'harian')?.harga || 0,
                            weekly: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === 'mingguan')?.harga || 0,
                            monthly: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === 'bulanan')?.harga || 0,
                            three_monthly: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === '3_bulanan')?.harga || 0,
                            six_monthly: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === '6_bulanan')?.harga || 0,
                            yearly: result.data.kamars?.[0]?.harga_sewa
                                ?.find(h => h.periode === 'tahunan')?.harga || 0,
                            fine_amount: result.data.kamars?.[0]?.harga_sewa?.[0]?.denda_per_hari || 0,
                            fine_limit: result.data.kamars?.[0]?.harga_sewa?.[0]?.batas_hari_denda || 0,
                            set_fine: !!result.data.kamars?.[0]?.harga_sewa?.[0]?.denda_per_hari
                        },

                        // Metadata - PENTING!
                        total_kamar: result.data.total_kamar || result.data.kamars?.length || 0,
                        kamar_tersedia: result.data.kamar_tersedia || result.data.kamars?.filter(k => k.status === 'tersedia')?.length || 0,
                        total_rooms: result.data.total_kamar || result.data.kamars?.length || 0,
                        available_rooms: result.data.kamar_tersedia || result.data.kamars?.filter(k => k.status === 'tersedia')?.length || 0,

                        size: {
                            type: result.data.kamars?.[0]?.ukuran_kamar || '3 x 3'
                        },

                        completed: true
                    };

                    console.log('🔄 Transformed data:', transformedData);

                    // 3. SIMPAN KE LOCALSTORAGE
                    let list = getKosList();
                    const existingIndex = list.findIndex(kos =>
                        kos.id == result.data.id ||
                        kos.database_id == result.data.id
                    );

                    if (existingIndex === -1) {
                        list.push(transformedData);
                    } else {
                        list[existingIndex] = transformedData;
                    }

                    saveKosList(list);
                    console.log('✅ Data saved to localStorage');

                    // 4. ✅ SIMPAN KE GLOBAL VARIABLE
                    window.currentKosId = result.data.id;  // ID DATABASE
                    localStorage.setItem('editing_kos_id', result.data.id);

                    currentKos = {
                        db_id: result.data.id,  // ✅ ID DATABASE
                        local_id: null
                    };

                    window.currentKosData = transformedData;

                    console.log('✅ Global state updated:', {
                        currentKosId: window.currentKosId,
                        currentKos: currentKos,
                        hasData: !!window.currentKosData
                    });

                    // 5. ✅ LOAD FORM DENGAN DATA
                    loadContent('data_kos_step1', 1, transformedData);

                    // 6. Tampilkan modal
                    setTimeout(() => {
                        showModal();
                        console.log('✅ Modal shown for editing kos ID:', result.data.id);
                    }, 100);

                } else {
                    alert('Gagal memuat data: ' + result.message);
                }
            } catch (error) {
                console.error('Error loading edit form:', error);
                alert('Terjadi kesalahan saat memuat data');
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

            // Reset event listeners
            genderButtons.forEach(btn => {
                btn.onclick = null;
            });

            // Isi data jika ada
            if (data) {
                namaInput.value = data.nama || '';
                deskripsiTextarea.value = data.deskripsi || '';

                const length = deskripsiTextarea.value.length;
                charCount.textContent = `${length}/200`;

                genderButtons.forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.textContent.trim() === (data.tipe || '').trim()) {
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
            console.log('initializeStep3Form called with:', data);
            if (!data || !data?.address) return;
            document.getElementById('alamat').value = data.address.alamat || '';
            document.getElementById('catatan').value = data.address.catatan || '';
            document.getElementById('provinsi').value = data.address.provinsi || '';
            document.getElementById('kabupaten').value = data.address.kabupaten || '';
            document.getElementById('kecamatan').value = data.address.kecamatan || '';

            if (data.fasilitas && Array.isArray(data.fasilitas)) {
                // Uncheck semua dulu
                document.querySelectorAll('input[name="fasilitas"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check yang sesuai
                data.fasilitas.forEach(fasilitasId => {
                    const checkbox = document.querySelector(`input[name="fasilitas"][value="${fasilitasId}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Isi peraturan
            if (data.peraturan && Array.isArray(data.peraturan)) {
                // Uncheck semua dulu
                document.querySelectorAll('input[name="rules"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check yang sesuai
                data.peraturan.forEach(peraturanId => {
                    const checkbox = document.querySelector(`input[name="rules"][value="${peraturanId}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
        }

        /**
         * Fungsi untuk inisialisasi form step 4 (Unggah Gambar) dengan data yang sudah ada.
         */
        function initializeStep4Form(data) {
            console.log('initializeStep4Form EDIT mode?', !!currentKos.db_id);
            console.log('Data images:', data?.images);
            // Inisialisasi images object
            if (data && !data.images) {
                data.images = { bangunan: [], kamar: [] };
            }
            if (!data || !data.images) return;

            const renderImages = (containerId, imagesArray, type) => {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                const validImages = Array.isArray(imagesArray) ? imagesArray : [];

                validImages.forEach((url, index) => {
                    const card = document.createElement('div');
                    card.className = 'photo-card';
                    card.innerHTML = `
                <img src="${url}" alt="Foto ${type === 'bangunan' ? 'Bangunan' : 'Kamar'}" class="photo-preview">
                <button class="remove-btn" onclick="removeImage('${type}', ${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
                    container.appendChild(card);
                });

                // Max photos logic
                let maxPhotos;
                if (type === 'bangunan') {
                    maxPhotos = 5;  // Bisa lebih dari 1 foto bangunan
                } else if (type === 'kamar') {
                    maxPhotos = 10;
                } else {
                    maxPhotos = 5;
                }

                // Tombol tambah foto
                if (validImages.length < maxPhotos) {
                    const addBtn = document.createElement('div');
                    addBtn.className = 'photo-card add-new-card';
                    addBtn.onclick = () => document.getElementById(`upload-input-${type}`).click();
                    addBtn.innerHTML = `
                <i class="fas fa-camera" style="font-size: 30px; color: var(--text-light); margin-bottom: 10px;"></i>
                <p style="color: var(--text-light);">+ Tambah Foto ${type === 'bangunan' ? 'Bangunan' : 'Kamar'}</p>
            `;
                    container.appendChild(addBtn);
                }
            };

            // ✅ RENDER DENGAN TYPE 'bangunan' DAN 'kamar'
            renderImages('galeri_bangunan', data.images.bangunan, 'bangunan');
            renderImages('galeri_kamar', data.images.kamar, 'kamar');

            // ✅ UPDATE INPUT IDs: 'depan' -> 'bangunan'
            document.getElementById('upload-input-bangunan').onchange = (e) =>
                handleImageUpload(e.target, 'bangunan');

            document.getElementById('upload-input-kamar').onchange = (e) =>
                handleImageUpload(e.target, 'kamar');
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

            // 1. Harga Bulanan - HAPUS DUPLIKASI
            const monthlyCheck = document.getElementById('harga_bulan_check');
            const monthlyInput = document.getElementById('harga_bulan');

            if (monthlyCheck && monthlyInput) {
                monthlyCheck.checked = priceData.monthly > 0;  // ← SATU KALIAN SAJA
                monthlyInput.value = formatRupiah(priceData.monthly);
                monthlyInput.addEventListener('input', function () {
                    this.value = formatRupiah(parseRupiahToNumber(this.value));
                });
                toggleMonthlyPriceInput();  // ← SATU KALIAN SAJA
            }

            // 2. Harga Lain - TAMBAH NULL CHECK
            const priceChecks = {
                'harga_hari_check': priceData.daily > 0,
                'harga_minggu_check': priceData.weekly > 0,
                'harga_3bulan_check': priceData.three_monthly > 0,
                'harga_6bulan_check': priceData.six_monthly > 0,
                'harga_tahun_check': priceData.yearly > 0
            };

            Object.entries(priceChecks).forEach(([checkId, checked]) => {
                const check = document.getElementById(checkId);
                if (check) check.checked = checked;
            });

            // 3. Input harga lain
            const prices = [
                { id: 'harga_hari_input', value: priceData.daily },
                { id: 'harga_minggu_input', value: priceData.weekly },
                { id: 'harga_3bulan_input', value: priceData.three_monthly },
                { id: 'harga_6bulan_input', value: priceData.six_monthly },
                { id: 'harga_tahun_input', value: priceData.yearly }
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

            // 4. Denda
            const dendaCheck = document.getElementById('denda_check');
            if (dendaCheck) dendaCheck.checked = priceData.set_fine || false;

            const dendaInput = document.getElementById('denda_jumlah');
            if (dendaInput) {
                dendaInput.value = formatRupiah(priceData.fine_amount || 0);
                dendaInput.addEventListener('input', function () {
                    this.value = formatRupiah(parseRupiahToNumber(this.value));
                });
            }

            const batasInput = document.getElementById('batas_waktu_hari');
            if (batasInput) batasInput.value = priceData.fine_limit || 7;

            // 5. Trigger semua toggle SEKALI SAJA
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
                    if (!checkbox.checked) input.value = '0';
                };

                updateInput();
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
                        const kosIndex = list.findIndex(kos => kos.id === currentKosId);

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

                            data.images[type].push(resizedImageUrl);

                            saveKosList(list);

                            currentKosData.images = currentKosData.images || {};
                            currentKosData.images[type] = data.images[type];
                            console.log('currentKosData images updated:', currentKosData.images);

                            console.log('Gambar berhasil disimpan!');

                            showModal({
                                title: "Sukses",
                                message: "Foto berhasil diunggah!",
                                type: "success",
                                onConfirm: () => {
                                    loadContent('data_kos_step4', 4);
                                }
                            });
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

            showConfirm({
                title: "Konfirmasi Hapus",
                message: "Anda yakin ingin menghapus foto ini?",
                confirmText: "Hapus",
                cancelText: "Batal",
                onConfirm: function () {
                    let list = getKosList();
                    const kosIndex = list.findIndex(kos => kos.id === currentKosId);

                    if (kosIndex > -1) {
                        const data = list[kosIndex];
                        data.images = data.images || {};
                        data.images[type] = data.images[type] || [];

                        if (data.images[type]) {
                            data.images[type].splice(index, 1);
                            saveKosList(list);

                            showModal({
                                title: "Sukses",
                                message: "Foto berhasil dihapus!",
                                type: "success",
                                onConfirm: () => {
                                    loadContent('data_kos_step4', 4);
                                }
                            });
                        }
                    }
                }
            });
        }

        // ==========================================================
        // 3. KUMPULAN TEMPLATE HTML 
        // ==========================================================
        const templates = {
            'home': `
                <section class="dashboard-page">
                    <div class="greeting-section">
                        <div class="greeting-box">
                            <h2>Hallo</h2>
                            <p>{{ auth()->user()->name }}</p>
                        </div>
                        <div class="data-box monthly-revenue">
                            <p>Pendapatan Bulanan <span class="month">{{ now()->translatedFormat('F Y') }}</span></p>
                            <h3>Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="data-box total-revenue">
                            <p>Pendapatan Total</p>
                            <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    <div class="waiting-section">
                        <h3>Ada Yang Menunggu</h3>
                        <div class="waiting-items">
                            <button class="waiting-card" id="btn-booking-menunggu" data-page="booking">
                                Booking Menunggu
                                <span class="dot"></span>
                            </button>
                            <button class="waiting-card" id="btn-tagihan-penyewa" data-page="tagihan">
                                Tagihan Penyewa
                                <span class="dot"></span>
                            </button>
                        </div>
                    </div>

                    <div class="management-section">
                        <h3>Kelola Kos</h3>
                        <div class="management-grid">
                            <button class="management-card">Atur Ketersediaan Kamar</button>
                            <button class="management-card">Atur Harga</button>
                            <button class="management-card" id="btn-penyewa">Penyewa</button>
                            <button class="management-card">Tambah Penyewa</button>
                        </div>
                    </div>
                </section>
            `,

            // TEMPLATE UNTUK DASHBOARD KOS SAYA - DIPERBAIKI
            'kos_saya_dashboard': async () => {
                const kosList = await getKosList();
                // let kosList = window.kosList || [];
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
                        const frontPhotoUrl = (kos.foto_kos && kos.foto_kos.length > 0) ?
                            kos.foto_kos[0].full_base64_url ||
                            'data:image/jpeg;base64,' + kos.foto_kos[0].path_foto :
                            IMAGE_PLACEHOLDER;

                        let priceDisplay = 'Harga belum diatur';
                        if (kos.kamar && kos.kamar.length > 0 && kos.kamar[0].harga) {
                            const harga = kos.kamar[0].harga;
                            const hargaBulanan = harga.find(h => h.periode === 'bulanan');
                            if (hargaBulanan) {
                                priceDisplay = `Rp ${formatRupiah(hargaBulanan.harga)}/bln`;
                            }
                        }

                        return `
                            <div class="dashboard-card kos-detail-card">
                                
                                <div class="kos-detail-info">
                                    <h3>Detail Kos: ${kos.nama}</h3>
                                    <div class="kos-detail-item"><strong>Status:</strong> ${kos.completed ? '<span style="color: var(--primary-color);">Telah Disubmit</span>' : '<span style="color: #FFB300;">Dalam Proses Pengisian</span>'}</div>
                                    <div class="kos-detail-item"><strong>Tipe:</strong> ${kos.tipe}</div>
                                    <div class="kos-detail-item"><strong>Ukuran:</strong> ${kos.size ? kos.size.type : '-'} (${kos.size && kos.size.type === 'Kustom' ? `${kos.size.custom_w}x${kos.size.custom_l}` : ''})</div>
                                    
                                    <div class="kos-detail-item price-info">
                                        <strong>Harga Kamar:</strong> 
                                        <span>${priceDisplay}</span>
                                    </div>
                                    
                                    <div class="kos-actions">
                                        <a href="#" class="btn btn-secondary" onclick="viewKosDetail(${kos.id})">Lihat Detail</a>
                                        <a href="#" class="btn btn-primary" onclick="startEditKos(${kos.id});">Edit Data Kos</a>
                                        <button class="btn btn-danger" onclick="deleteKos(${kos.id})">Hapus Kos</button>
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
                        <h2 style="margin-bottom: 30px;"><i class="fas fa-eye"></i> Detail Lengkap Kos: ${kosData.nama}</h2>
                        
                        <div class="detail-group">
                            <h3 style="color: var(--primary-color);">1. Data Dasar Kos</h3>
                            <div class="kos-detail-item"><strong>Nama Kos:</strong> ${kosData.nama}</div>
                            <div class="kos-detail-item"><strong>Tipe:</strong> ${kosData.tipe}</div>
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
                            ${renderSimulationData('Peraturan', 'Akses 24 Jam, Dilarang Merokok di Kamar, Maks. 1 orang/kamar.')}
                            <hr style="margin: 10px 0;">
                            ${renderSimulationData('Fasilitas Umum', 'CCTV, Wifi, Kulkas, Dapur.')}
                            ${renderSimulationData('Fasilitas Kamar', 'AC, Kasur, Lemari, Meja, Jendela.')}
                            ${renderSimulationData('Fasilitas KM', 'K. Mandi Dalam, Kloset Duduk, Shower.')}
                            ${renderSimulationData('Parkir', 'Parkir Motor.')}
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
                                <button type="button" class="gender-button active">Putra</button>
                                <button type="button" class="gender-button">Putri</button>
                                <button type="button" class="gender-button">Campur</button>
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
                                <label class="checkbox-container">Akses 24+1 Jam<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Boleh bawa hewan<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Boleh pasutri<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Dilarang merokok dikamar<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Harga termasuk listrik<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Khusus Mahasiswa<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Khusus Karyawan<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Kriteria Umum<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container"><span style="color: red;">⚠️ DANGER!</span> Gay dilarang KOSZZZ<input type="checkbox"><span class="checkmark"></span></label>
                                <label class="checkbox-container">Maks. 1 orang/kamar<input type="checkbox"><span class="checkmark"></span></label>
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
                            <button type="button" class="btn btn-primary" onclick="handleStep2Save();">Lanjutkan</button>
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

                    <form onsubmit="return false;">
                        <div class="address-form-grid">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" id="alamat" placeholder="Nama jalan, nomor, dan detail lainnya" required>
                            </div>
                            <div class="form-group">
                                <label for="catatan">Catatan Alamat</label>
                                <input type="text" id="catatan" placeholder="Catatan untuk memudahkan pencarian">
                            </div>
                        </div>
                        <div class="address-form-grid">
                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <input type="text" id="provinsi" placeholder="Masukkan Provinsi" required>
                            </div>
                            <div></div>
                        </div>
                        <div class="address-form-grid">
                            <div class="form-group">
                                <label for="kabupaten">Kabupaten/ Kota</label>
                                <input type="text" id="kabupaten" placeholder="Masukkan Kabupaten/Kota" required>
                            </div>
                            <div></div>
                        </div>
                        <div class="address-form-grid">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <input type="text" id="kecamatan" placeholder="Masukkan Kecamatan" required>
                            </div>
                            <div></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step2', 2);">Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="handleStep3Save();">Lanjutkan</button>
                        </div>
                    </form>
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
                            <div>
                                <h3>Fasilitas Umum</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container">Balcon<input type="checkbox" value="Balcon"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">CCTV<input type="checkbox" value="CCTV"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Dapur<input type="checkbox" value="Dapur"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Dispenser<input type="checkbox" value="Dispenser"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Jemuran<input type="checkbox" value="Jemuran"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">K. Mandi Luar<input type="checkbox" value="K. Mandi"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kulkas<input type="checkbox" value="Kulkas"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Mesin Cuci<input type="checkbox" value="Mesin Cuci"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Penjaga Kos<input type="checkbox" value="Penjaga Kos"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">TV<input type="checkbox" value="TV"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Taman<input type="checkbox" value="Taman"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Wifi<input type="checkbox" value="Wifi"><span class="checkmark"></span></label>
                                </div>
                            </div>
                            
                            <div>
                                <h3>Fasilitas Kamar</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container">AC<input type="checkbox" value="AC"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Dapur Pribadi<input type="checkbox" value="Dapur Pribadi"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Dispenser<input type="checkbox" value="Dispenser"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Jendela<input type="checkbox" value="Jendela"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kasur<input type="checkbox" value="Kasur"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kipas Angin<input type="checkbox" value="Kipas Angin"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kursi<input type="checkbox" value="Kursi"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Lemari<input type="checkbox" value="Lemari"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Meja<input type="checkbox" value="Meja"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Ventilasi<input type="checkbox" value="Ventilasi"><span class="checkmark"></span></label>
                                </div>
                            </div> 

                            <div>
                                <h3>Fasilitas Kamar Mandi</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container">Air Panas<input type="checkbox" value="Air Panas"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Bak Mandi<input type="checkbox" value="Bak Mandi"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Ember Mandi<input type="checkbox" value="Ember Mandi"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">K. Mandi Dalam<input type="checkbox" value="K. Mandi Dalam"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">K. Mandi Luar<input type="checkbox" value="K. Mandi Luar"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kloset Duduk<input type="checkbox" value="Kloset Duduk"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Kloset Jongkok<input type="checkbox" value="Kloset Jongkok"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Shower<input type="checkbox" value="Shower"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Wastafel<input type="checkbox" value="Watafel"><span class="checkmark"></span></label>
                                </div>
                            </div>
                            
                            <div>
                                <h3>Parkir</h3>
                                <div class="rules-column">
                                    <label class="checkbox-container">Parkir Mobil<input type="checkbox" value="Parkir Mobil"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Parkir Motor<input type="checkbox" value="Parkir Motor"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Parkir Motor & Sepeda<input type="checkbox"" value="Parkir Motor & Sepeda"><span class="checkmark"></span></label>
                                    <label class="checkbox-container">Parkir Sepeda<input type="checkbox" value="Parkir Sepeda"><span class="checkmark"></span></label>
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
            'data_kos_step6_detail': (currentStep) => {
                const kosData = getKosData(currentKosId);
                const totalRooms = kosData ? kosData.total_rooms : 0;
                const roomDetails = kosData ? (kosData.room_details || []) : [];

                if (!kosData || totalRooms === 0) {
                    return `<section class="dashboard-page">
                                 <h2 style="color: var(--primary-color);">Peringatan</h2>
                                 <p>Anda harus mengisi jumlah total kamar (misalnya: 5) pada langkah sebelumnya sebelum mengatur detail kamar.</p>
                                 <div class="form-actions"><a href="#" class="btn btn-secondary" onclick="loadContent('data_kos_step6', 6);">Kembali</a></div>
                             </section>`;
                }

                let roomFormsHtml = '';
                for (let i = 1; i <= totalRooms; i++) {
                    const existingRoom = roomDetails.find(r => r.id === i) || { nomor: `Kamar ${i}`, lantai: '1', terisi: false };

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
                                    <input type="checkbox" id="harga_bulan_check" onchange="toggleMonthlyPriceInput();">
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
            'data_kos_step8': (currentStep) => `
                <section class="dashboard-page" style="text-align: center; padding: 100px 30px;">
                    <h2 style="font-size: 30px; margin-bottom: 30px;">Kos Kamu Selesai Ditambahkan</h2>
                    <div class="form-actions" style="justify-content: center;">
                        <button class="btn btn-primary" onclick="finalizeKosSubmission();">Selesai & Lanjutkan</button>
                    </div>
                </section>
            `,

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
                <section class="dashboard-page">
                    <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--text-color);">Pengajuan Booking</h2>

                    <!-- Tabs untuk status booking -->
                    <div class="tabs" style="display: flex; gap: 5px; margin: 0 0 30px 0; background-color: var(--active-bg); padding: 4px; border-radius: 8px; width: fit-content;">
                        <button class="tab-button active" onclick="switchBookingTab('butuh-konfirmasi')" style="padding: 8px 20px; border: none; background-color: var(--card-background); cursor: pointer; font-weight: 600; color: var(--text-color); border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 14px;">
                            Butuh Konfirmasi
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('tunggu-pembayaran')" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Tunggu Pembayaran
                        </button>
                        <button class="tab-button" onclick="switchBookingTab('terbayar')" style="padding: 8px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-light); border-radius: 6px; font-size: 14px;">
                            Terbayar
                        </button>
                    </div>

                    <!-- Konten Tab: Butuh Konfirmasi -->
                    <div id="butuh-konfirmasi" class="tab-content" style="display: block;">
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Header dengan badge "Butuh Konfirmasi" - WARNA ORANGE -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <div>
                                    <span style="background-color: #FF9800; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                        Butuh Konfirmasi
                                    </span>
                                </div>
                            </div>
                    
                            <!-- Informasi Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    AS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Maguwo City</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 22</p>
                                </div>
                            </div>
                    
                            <!-- Detail Sewa -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 25px;">
                                <div>
                                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">3 Juli 2025</p>
                                </div>
                                <div>
                                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">2 Bulan</p>
                                </div>
                            </div>
                    
                            <!-- Tombol Aksi -->
                            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                <button class="btn-tolak" onclick="tolakBooking(1)" style="padding: 10px 30px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px; transition: all 0.2s;">
                                    <i class="fas fa-times" style="margin-right: 5px;"></i> Tolak
                                </button>
                                <button class="btn-terima" onclick="terimaBooking(1)" style="padding: 10px 30px; border: none; background-color: var(--primary-color); border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px; transition: all 0.2s;">
                                    <i class="fas fa-check" style="margin-right: 5px;"></i> Terima
                                </button>
                            </div>
                        </div>

                        <!-- Booking Card 2 -->
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <div>
                                    <span style="background-color: #FF9800; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                        Butuh Konfirmasi
                                    </span>
                                </div>
                            </div>
                    
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <div style="width: 50px; height: 50px; background-color: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    RS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Rina Sari</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Permata Hijau</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 15</p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 25px;">
                                <div>
                                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">10 Juli 2025</p>
                                </div>
                                <div>
                                    <p style="color: var(--text-light); font-size: 13px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 15px; color: var(--text-color);">6 Bulan</p>
                                </div>
                            </div>
                    
                            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                <button class="btn-tolak" onclick="tolakBooking(2)" style="padding: 10px 30px; border: 2px solid #dc3545; background-color: white; border-radius: 8px; cursor: pointer; font-weight: 700; color: #dc3545; font-size: 14px; transition: all 0.2s;">
                                    <i class="fas fa-times" style="margin-right: 5px;"></i> Tolak
                                </button>
                                <button class="btn-terima" onclick="terimaBooking(2)" style="padding: 10px 30px; border: none; background-color: var(--primary-color); border-radius: 8px; cursor: pointer; font-weight: 700; color: white; font-size: 14px; transition: all 0.2s;">
                                    <i class="fas fa-check" style="margin-right: 5px;"></i> Terima
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Konten Tab: Tunggu Pembayaran -->
                    <div id="tunggu-pembayaran" class="tab-content" style="display: none;">
                        <!-- Kartu Booking 1 -->
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Badge Tunggu Pembayaran - WARNA BIRU -->
                            <div style="margin-bottom: 20px;">
                                <span style="background-color: #2196F3; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    Tunggu Pembayaran
                                </span>
                            </div>
                    
                            <!-- Informasi Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                                <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    AS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Maguwo City</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 22</p>
                                </div>
                            </div>
                    
                            <!-- Detail Sewa -->
                            <div style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                                <!-- Baris Mulai Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">3 Juli 2025</p>
                                </div>
                                
                                <!-- Baris Lama Sewa -->
                                <div>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">2 Bulan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Booking 2 -->
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Badge Tunggu Pembayaran - WARNA BIRU -->
                            <div style="margin-bottom: 20px;">
                                <span style="background-color: #2196F3; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    Tunggu Pembayaran
                                </span>
                            </div>
                    
                            <!-- Informasi Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                                <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    AS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Maguwo City</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 22</p>
                                </div>
                            </div>
                    
                            <!-- Detail Sewa -->
                            <div style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                                <!-- Baris Mulai Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">3 Juli 2025</p>
                                </div>
                                
                                <!-- Baris Lama Sewa -->
                                <div>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">2 Bulan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konten Tab: Terbayar -->
                    <div id="terbayar" class="tab-content" style="display: none;">
                        <!-- Kartu Booking Terbayar 1 -->
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Badge Terbayar - WARNA HIJAU -->
                            <div style="margin-bottom: 20px;">
                                <span style="background-color: #28a745; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    Terbayar
                                </span>
                            </div>
                    
                            <!-- Informasi Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                                <div style="width: 50px; height: 50px; background-color: #6a0dad; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    AS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Ahmad Sahroni</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Maguwo City</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 22</p>
                                </div>
                            </div>
                    
                            <!-- Detail Sewa -->
                            <div style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                                <!-- Baris Mulai Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">3 Juli 2025</p>
                                </div>
                                
                                <!-- Baris Lama Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">2 Bulan</p>
                                </div>
                                
                                <!-- Baris Tanggal Pembayaran -->
                                <div>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Tanggal Pembayaran:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: #28a745;">28 Juni 2025</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Booking Terbayar 2 -->
                        <div class="booking-card" style="border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; background-color: var(--card-background); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Badge Terbayar - WARNA HIJAU -->
                            <div style="margin-bottom: 20px;">
                                <span style="background-color: #28a745; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    Terbayar
                                </span>
                            </div>
                    
                            <!-- Informasi Penyewa -->
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                                <div style="width: 50px; height: 50px; background-color: #dc3545; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    RS
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px; color: var(--text-color);">Rina Sari</p>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 2px;">Kos Permata Hijau</p>
                                    <p style="color: var(--text-light); font-size: 14px;">Kamar 15</p>
                                </div>
                            </div>
                    
                            <!-- Detail Sewa -->
                            <div style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                                <!-- Baris Mulai Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Mulai Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">10 Juli 2025</p>
                                </div>
                                
                                <!-- Baris Lama Sewa -->
                                <div style="margin-bottom: 15px;">
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Lama Sewa:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: var(--text-color);">6 Bulan</p>
                                </div>
                                
                                <!-- Baris Tanggal Pembayaran -->
                                <div>
                                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 5px; font-weight: 500;">Tanggal Pembayaran:</p>
                                    <p style="font-weight: 700; font-size: 16px; color: #28a745;">1 Juli 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <button onclick="simpanDataTagihan()" style="padding: 12px 40px; background-color: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
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
                    noHandphone: '0812 3333 XXXX',
                    password: '***********',
                    email: 'b_lilizzpertalite@gmail.com'
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
                                
                                <!-- Baris 3: Password -->
                                <div style="margin-bottom: 25px;">
                                    <div style="font-weight: 600; color: var(--text-color); margin-bottom: 8px; font-size: 14px;">
                                        Password
                                    </div>
                                    <div style="font-size: 16px; color: var(--text-light);">
                                        ${userData.password}
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

        // ==========================================================
        // 4. FUNGSI JAVASCRIPT UTAMA UNTUK MENGGANTI KONTEN
        // ==========================================================

        /**
         * Mengatur mode "Lihat Detail Kos" dan memuat template detail.
         */
        function viewKosDetail(id) {
            currentKosId = id;
            const kosData = getKosData(id);
            loadContent('kos_detail_view', kosData);
        }

        /**
         * Navigasi ke halaman detail kamar (Step 6 Detail)
         */
        function goToDetailKamar() {
            if (!currentKosId) {
                showModal({
                    title: "Peringatan",
                    message: "Silakan simpan data dasar kos terlebih dahulu.",
                    type: "warning"
                });
                return;
            }

            const kosData = getKosData(currentKosId);
            if (!kosData.total_rooms || kosData.total_rooms === 0) {
                showModal({
                    title: "Peringatan",
                    message: "Silakan isi jumlah total kamar terlebih dahulu.",
                    type: "warning"
                });
                return;
            }

            loadContent('data_kos_step6_detail', 6);
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
            console.log('🔥 startEditKos called with ID:', id);

            const data = getKosData(id);

            // Cari ID database
            let dbId = data.database_id || data.db_id;

            console.log('ID mapping:', {
                localId: id,
                dbId: dbId,
                hasDatabaseId: !!dbId,
                data: data
            });

            if (!dbId) {
                // Jika tidak ada ID database, sync dulu
                console.log('⚠️  No database ID found, syncing...');
                syncLocalWithDatabase();

                // Coba ambil lagi setelah sync
                setTimeout(() => {
                    const updatedData = getKosData(id);
                    dbId = updatedData.database_id || updatedData.db_id;

                    if (dbId) {
                        console.log('✅ Found DB ID after sync:', dbId);
                        startEditKosWithDbId(id, dbId, updatedData);
                    } else {
                        console.log('❌ Still no DB ID, using local ID');
                        startEditKosWithDbId(id, id, data);
                    }
                }, 500);
            } else {
                startEditKosWithDbId(id, dbId, data);
            }
        }

        // Helper function untuk handle edit dengan ID database
        function startEditKosWithDbId(localId, dbId, data) {
            console.log('🎯 Edit with DB ID:', dbId);

            window.currentKosId = dbId;

            currentKos = {
                db_id: dbId,      // ✅ ID DATABASE untuk finalizeKosSubmission()
                local_id: localId
            };

            localStorage.setItem('editing_kos_id', dbId);

            console.log('✅ Global state set:', {
                currentKosId: window.currentKosId,
                currentKos: currentKos
            });

            window.currentKosData = data;

            // Jika dbId adalah ID database (bukan timestamp panjang)
            if (dbId.toString().length < 10 && dbId > 0) {
                console.log('🔄 Fetching fresh data from database...');
                loadEditForm(dbId);  // Ambil data segar dari database
            } else {
                console.log('📂 Using local data');
                loadContent('data_kos_step1', 1, data);

                setTimeout(() => {
                    const modal = document.getElementById('addKosModal');
                    if (modal) modal.style.display = 'block';
                }, 200);
            }
        }

        /**
         * Memuat konten ke area utama dan memperbarui status navigasi.
         */
        function loadContent(pageKey, stepOrData = 1) {
            console.log('loadContent dipanggil:', pageKey, stepOrData);



            const contentArea = document.getElementById('content-area');
            const navItems = document.querySelectorAll('#nav-menu .nav-item');
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            let htmlContent;

            const isDataObject = typeof stepOrData === 'object' && stepOrData !== null;
            const stepNumber = isDataObject ? 1 : stepOrData;
            const dataObject = isDataObject ? stepOrData : null;

            if (typeof templates[pageKey] === 'function') {
                if (pageKey === 'detail_penyewa' || pageKey === 'kelola_tagihan_detail') {
                    htmlContent = templates[pageKey](dataObject);
                } else if (pageKey === 'kos_saya_dashboard' || pageKey === 'home') {
                    htmlContent = templates[pageKey]();
                } else if (pageKey === 'data_kos_step6_detail') {
                    const dataToUse = getKosData(currentKosId);
                    htmlContent = templates[pageKey](dataToUse);
                } else if (pageKey === 'kos_detail_view') {
                    htmlContent = templates[pageKey](dataObject);
                } else {
                    htmlContent = templates[pageKey](stepNumber);
                }
            } else {
                htmlContent = templates[pageKey];
            }

            contentArea.innerHTML = htmlContent;

            // Inisialisasi form sesuai halaman
            if (pageKey === 'data_kos_step1') {
                setTimeout(() => {
                    initializeStep1Form(getKosData(window.currentKosId));
                }, 50);
            } else if (pageKey === 'data_kos_step3') {
                setTimeout(() => {
                    initializeStep3Form(getKosData(currentKosId));
                }, 50);
            } else if (pageKey === 'data_kos_step4') {
                setTimeout(() => {
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
                    initializeStep4Form(getKosData(currentKosId));
                }, 50);
            } else if (pageKey === 'data_kos_step6') {
                setTimeout(() => {
                    initializeStep6Form(getKosData(currentKosId));
                }, 50);
            } else if (pageKey === 'data_kos_step7') {
                setTimeout(() => {
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
                    initializeStep7Form(getKosData(currentKosId));
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
        }

        /**
         * Fungsi khusus yang dipanggil saat tombol "Kos Saya" diklik
         */
        async function loadKosDashboard() {
            try {
                const listUrl = '/admin/kos-list';
                console.log('Fetching from:', listUrl);
                // const url = '/admin/kos/list';
                const response = await fetch(listUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const kosList = await response.json();
                console.log('Kos dari DB:', kosList);

                // ✅ PAKAI loadContent() seperti sistem kamu
                // currentKosList = kosList;  // simpan global
                window.kosList = kosList;  // GANTI window.kosFromDb
                window.currentKosList = kosList;
                loadContent('kos_saya_dashboard', 1);  // load view lama

            } catch (error) {
                console.error('Gagal load dashboard:', error);
                // fallback ke localStorage
                loadContent('kos_saya_dashboard', 1);
            }
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

        // ==========================================================
        // 5. FUNGSI UNTUK BOOKING MANAGEMENT
        // ==========================================================

        /**
         * Fungsi untuk mengganti tab di halaman booking
         */
        function switchBookingTab(tabId) {
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
        }

        /**
         * Fungsi untuk menerima booking
         */
        function terimaBooking(bookingId) {
            showConfirm({
                title: "Konfirmasi",
                message: `Apakah Anda yakin ingin menerima booking #${bookingId}?`,
                confirmText: "Terima",
                cancelText: "Batal",
                onConfirm: function () {
                    showModal({
                        title: "Sukses",
                        message: `Booking #${bookingId} telah diterima!`,
                        type: "success"
                    });
                }
            });
        }

        /**
         * Fungsi untuk menolak booking
         */
        function tolakBooking(bookingId) {
            showConfirm({
                title: "Konfirmasi",
                message: `Apakah Anda yakin ingin menolak booking #${bookingId}?`,
                confirmText: "Tolak",
                cancelText: "Batal",
                onConfirm: function () {
                    showModal({
                        title: "Sukses",
                        message: `Booking #${bookingId} telah ditolak!`,
                        type: "success"
                    });
                }
            });
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

        // ==========================================================
        // 7. INISIALISASI
        // ==========================================================

        // Memuat konten HOME saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', () => {
            loadContent('home');

            // Event listener untuk menu sidebar (termasuk dropdown)
            document.getElementById('nav-menu').addEventListener('click', (event) => {
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
                        loadKosDashboard();
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
            // Simpan data sementara dari form step 6
            const activeSizeButton = document.querySelector('#room_size_selection .gender-button.active');
            const totalRooms = document.getElementById('jumlah_total_kamar').value;
            const availableRooms = document.getElementById('jumlah_kamar_tersedia').value;
            const customWidth = document.getElementById('custom_width').value;
            const customLength = document.getElementById('custom_length').value;

            if (!totalRooms || parseInt(totalRooms) <= 0) {
                showModal({
                    title: "Peringatan",
                    message: "Anda harus mengisi jumlah total kamar (misalnya: 5) sebelum mengatur detail kamar.",
                    type: "warning"
                });
                return;
            }

            // Simpan data sementara ke kos yang sedang diedit
            let list = getKosList();
            const kosIndex = list.findIndex(kos => kos.id === currentKosId);

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

                // Buka halaman detail kamar
                loadContent('data_kos_step6_detail', 6);
            } else {
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
</body>

</html>