<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama MBG</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #edf3fc, #dce7f5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            padding: 35px 28px;
            border-radius: 22px;
            box-shadow: 0 15px 40px rgba(30, 58, 95, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 75px;
            height: 75px;
            background: #eaf1fc;
            border-radius: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 38px;
            margin: 0 auto 18px;
        }

        h1 {
            color: #1e3a5f;
            font-size: 29px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            color: #7b8798;
            font-size: 14px;
            line-height: 1.6;
        }

        .label {
            color: #53647b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 14px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 13px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px;
            background: #f7f9fd;
            border: 1px solid #e5ebf5;
            border-radius: 14px;
            text-decoration: none;
            color: #243b59;
            transition: all 0.25s ease;
        }

        .menu a:hover {
            background: #1e3a5f;
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(30, 58, 95, 0.18);
            border-color: #1e3a5f;
        }

        .icon {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e7eefb;
            border-radius: 12px;
            font-size: 23px;
            transition: 0.25s;
        }

        .menu a:hover .icon {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu-text {
            flex: 1;
        }

        .menu-text h3 {
            font-size: 15px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .menu-text p {
            font-size: 12px;
            color: #8793a5;
            line-height: 1.4;
        }

        .menu a:hover .menu-text p {
            color: #d8e5f7;
        }

        .arrow {
            font-size: 22px;
            color: #9aa8bb;
            transition: 0.25s;
        }

        .menu a:hover .arrow {
            color: #ffffff;
            transform: translateX(4px);
        }

        footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf0f5;
            color: #9aa4b2;
            font-size: 12px;
        }

        footer span {
            color: #1e3a5f;
            font-weight: 700;
        }

        @media (max-width: 480px) {
            .container {
                padding: 28px 20px;
                border-radius: 18px;
            }

            h1 {
                font-size: 25px;
            }

            .menu a {
                padding: 13px;
                gap: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <div class="logo">🍱</div>
            <h1>Aplikasi MBG</h1>
            <p>Sistem Pendataan<br>Makan Bergizi Gratis</p>
        </div>

        <div class="label">MENU UTAMA</div>

        <div class="menu">

            <a href="tampilSiswa.php">
                <div class="icon">👨‍🎓</div>
                <div class="menu-text">
                    <h3>Data Siswa</h3>
                    <p>Kelola data siswa</p>
                </div>
                <div class="arrow">›</div>
            </a>

            <a href="tampilMBG.php">
                <div class="icon">📋</div>
                <div class="menu-text">
                    <h3>Data Penerimaan MBG</h3>
                    <p>Kelola data penerimaan makanan</p>
                </div>
                <div class="arrow">›</div>
            </a>

            <a href="tampilMenuMBG.php">
                <div class="icon">🍛</div>
                <div class="menu-text">
                    <h3>Data Menu MBG</h3>
                    <p>Kelola daftar menu makanan</p>
                </div>
                <div class="arrow">›</div>
            </a>

        </div>

        <footer>
            &copy; 2026 <span>Aplikasi Pendataan MBG</span>
            <br> Sistem Informasi Pendataan
        </footer>

    </div>

</body>
</html>