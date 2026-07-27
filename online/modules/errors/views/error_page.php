<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($title); ?></title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --sub: #6b7280;
            --accent: #e11d48;
            --primary: #0c5fdb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: radial-gradient(circle at top, #e8f0ff 0%, var(--bg) 52%);
            color: var(--text);
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 520px;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.12);
            padding: 28px 24px;
            text-align: center;
        }
        .code {
            display: inline-block;
            font-weight: 700;
            color: var(--accent);
            background: #ffe4e6;
            border-radius: 999px;
            padding: 7px 14px;
            margin-bottom: 12px;
            font-size: 13px;
            letter-spacing: .4px;
        }
        h1 {
            margin: 0 0 10px;
            font-size: 24px;
            line-height: 1.3;
        }
        p {
            margin: 0;
            color: var(--sub);
            line-height: 1.6;
            font-size: 15px;
        }
        .actions {
            margin-top: 22px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            border: 0;
            border-radius: 10px;
            text-decoration: none;
            padding: 11px 16px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-light {
            background: #eef2ff;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="code">ERROR <?php echo (int) $status; ?></span>
        <h1><?php echo html_escape($heading); ?></h1>
        <p><?php echo html_escape($message); ?></p>
        <div class="actions">
            <a class="btn btn-primary" href="<?php echo html_escape($action_url); ?>"><?php echo html_escape($action_text); ?></a>
            <button class="btn btn-light" type="button" onclick="window.location.reload();">Muat Ulang</button>
        </div>
    </main>
</body>
</html>
