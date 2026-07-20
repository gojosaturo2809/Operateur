<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> · Mini-blog</title>
    <style>
        :root{
            --bg:#F6F7FB; --surface:#FFFFFF; --ink:#16171F; --muted:#6B6F80;
            --border:#E6E8F0; --primary:#4F46E5; --primary-dark:#4338CA; --danger:#DC2626;
            --radius:14px; --shadow:0 1px 2px rgba(16,17,31,.04),0 8px 24px rgba(16,17,31,.06);
        }
        *{box-sizing:border-box}
        body{margin:0;background:var(--bg);color:var(--ink);line-height:1.55;
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;}
        a{color:var(--primary);text-decoration:none}
        a:hover{text-decoration:underline}

        .site-header{background:var(--surface);border-bottom:1px solid var(--border)}
        .nav{max-width:920px;margin:0 auto;padding:14px 20px;display:flex;align-items:center;gap:18px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:700;color:var(--ink);font-size:1.05rem}
        .brand:hover{text-decoration:none}
        .logo{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--primary),#7C73FF);
            display:grid;place-items:center;color:#fff;font-weight:800;font-size:.9rem}
        .nav-links{margin-left:auto;display:flex;align-items:center;gap:18px;font-size:.92rem}
        .nav-links a{color:var(--muted);font-weight:500}
        .nav-links a:hover{color:var(--ink);text-decoration:none}
        .nav-user{color:var(--ink);font-weight:600}

        .container{max-width:920px;margin:0 auto;padding:32px 20px 56px}
        .page-title{font-size:1.6rem;font-weight:700;letter-spacing:-.01em;margin:0 0 4px}
        .page-sub{color:var(--muted);margin:0 0 24px}

        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);
            box-shadow:var(--shadow);padding:22px}

        .article{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);
            box-shadow:var(--shadow);padding:18px 20px;margin-bottom:14px;
            border-left:3px solid transparent;transition:border-color .15s,transform .15s}
        .article:hover{border-left-color:var(--primary);transform:translateX(2px)}
        .article h3{margin:0 0 8px;font-family:Georgia,"Times New Roman",serif;font-size:1.25rem;font-weight:600}
        .article h3 a{color:var(--ink)}
        .meta{display:flex;align-items:center;gap:12px;color:var(--muted);font-size:.85rem}
        .pill{background:#EEF0FB;color:var(--primary);padding:2px 10px;border-radius:999px;font-weight:600;font-size:.78rem}

        .form-card{max-width:420px;margin:0 auto}
        .field{margin-bottom:14px}
        .field label{display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;color:var(--ink)}
        .field input,.field textarea{width:100%;padding:11px 13px;border:1px solid var(--border);
            border-radius:10px;font:inherit;color:var(--ink);background:#fff;transition:border-color .15s,box-shadow .15s}
        .field textarea{min-height:130px;resize:vertical}
        .field input:focus,.field textarea:focus{outline:none;border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(79,70,229,.15)}

        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 18px;
            border-radius:10px;border:1px solid transparent;font:inherit;font-weight:600;cursor:pointer;text-decoration:none}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:var(--primary-dark);text-decoration:none}
        .btn-block{width:100%}
        .link-danger{color:var(--danger);font-size:.82rem;font-weight:500}
        .link-danger:hover{color:#B42318}

        .table-wrap{overflow-x:auto}
        table.data{width:100%;border-collapse:collapse;background:var(--surface);
            border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
        table.data th,table.data td{padding:12px 14px;text-align:left;border-bottom:1px solid var(--border);font-size:.9rem}
        table.data th{background:#FAFBFE;color:var(--muted);font-weight:600;font-size:.74rem;text-transform:uppercase;letter-spacing:.04em}
        table.data tr:last-child td{border-bottom:none}
        .role-admin{color:var(--primary);font-weight:700}
        .role-membre{color:var(--muted)}

        .alert{padding:11px 15px;border-radius:10px;margin-bottom:20px;font-size:.9rem;font-weight:500}
        .alert-error{background:#FDECEC;color:#B42318;border:1px solid #F6C9C6}
        .alert-success{background:#E7F6EC;color:#15803D;border:1px solid #BBE7C9}

        .muted-note{color:var(--muted);font-size:.85rem;margin-top:16px;text-align:center}
        .foot{max-width:920px;margin:0 auto;padding:0 20px 40px;color:var(--muted);font-size:.8rem}

        @media (max-width:520px){.nav{flex-wrap:wrap;gap:12px}.nav-links{gap:14px;font-size:.85rem}}
        @media (prefers-reduced-motion:reduce){*{transition:none!important}}
    </style>
</head>
<body>
    <header class="site-header">
        <nav class="nav">
            <a href="/articles" class="brand"><span class="logo">B</span> Mini-blog</a>
            <div class="nav-links">
                <a href="/articles">Articles</a>
                <a href="/articles/create">Écrire</a>
                <a href="/admin">Admin</a>
                <?php if (session()->get('logged_in')): ?>
                    <span class="nav-user"><?= esc(session()->get('nom')) ?></span>
                    <a href="/logout">Déconnexion</a>
                <?php else: ?>
                    <a href="/login">Connexion</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <footer class="foot">
        Mini-blog — projet pédagogique ITUniversity · CodeIgniter 4 + SQLite
    </footer>
</body>
</html>
