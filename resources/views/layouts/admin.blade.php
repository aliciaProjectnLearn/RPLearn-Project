<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - RPLearn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <style>
        :root {
            --dark: #222831;
            --dark-soft: #393E46;
            --orange: #F6973F;
            --light: #EEEEEE;
        }

        body {
            background: var(--dark-soft);
            color: var(--light);
        }

        .admin-sidebar {
            width: 240px;
            background: var(--dark);
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .admin-sidebar h4 {
            color: var(--orange);
            font-weight: 700;
        }

        .admin-sidebar a {
            display: block;
            color: var(--light);
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 8px;
            transition: 0.2s;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background: var(--orange);
            color: #fff;
        }

        .admin-content {
            margin-left: 240px;
            padding: 30px;
        }

        .stat-card {
            background: var(--dark);
            border-radius: 16px;
            padding: 20px;
            color: var(--light);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--orange);
        }

        .admin-table {
            background: var(--dark);
            border-radius: 16px;
            overflow: hidden;
        }

        .admin-table table {
            color: var(--light);
        }

        .admin-table thead {
            background: var(--dark-soft);
        }
    </style>
</head>
<body>

<div class="admin-sidebar">
    <h4>RPLearn</h4>
    <hr style="border-color: rgba(255,255,255,0.1);">

    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.modules.index') }}">Modul</a>
    <a href="{{ route('admin.users.index') }}">Users</a>
    <a href="{{ route('admin.dictionaries.index') }}">Kamus</a>
    <a href="{{ route('admin.faq.index') }}">FAQ</a>
</div>

<div class="admin-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
