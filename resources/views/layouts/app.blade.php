<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Planner') — Financial Check Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #ffffff;
            --bg-body: #f1f5f9;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-emerald: #10b981;
            --accent-rose: #f43f5e;
            --accent-amber: #f59e0b;
            --accent-purple: #8b5cf6;
            --accent-cyan: #06b6d4;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-light: #e2e8f0;
            --gradient-blue: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-green: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --gradient-red: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            --gradient-orange: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-sm: 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,.08), 0 8px 10px -6px rgba(0,0,0,.04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-body);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .fp-navbar {
            background: var(--bg-primary);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: .75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .fp-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #fff;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .fp-navbar .navbar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--gradient-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .fp-navbar .nav-link {
            color: rgba(255,255,255,.7);
            font-weight: 500;
            font-size: .875rem;
            padding: .5rem 1rem;
            border-radius: var(--radius-sm);
            transition: all .2s ease;
        }
        .fp-navbar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.08);
        }
        .fp-navbar .nav-link.btn-nav-primary {
            background: var(--accent-blue);
            color: #fff;
        }
        .fp-navbar .nav-link.btn-nav-primary:hover {
            background: #2563eb;
        }

        /* ===== CARDS ===== */
        .fp-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow .3s ease, transform .2s ease;
        }
        .fp-card:hover {
            box-shadow: var(--shadow-md);
        }
        .fp-card-header {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: .95rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            border-bottom: 1px solid var(--border-light);
        }
        .fp-card-header .header-icon {
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            color: #fff;
            flex-shrink: 0;
        }
        .fp-card-body { padding: 1.5rem; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all .3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-secondary);
            margin-bottom: .4rem;
        }
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .stat-card .stat-sub {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .4rem;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 120px; height: 120px;
            border-radius: 50%;
            opacity: .04;
            transform: translate(30%, -30%);
        }
        .stat-card.stat-income::after { background: var(--accent-emerald); }
        .stat-card.stat-expense::after { background: var(--accent-rose); }
        .stat-card.stat-net::after { background: var(--accent-blue); }

        /* ===== BADGES & STATUS ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 50px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .2px;
        }
        .status-badge.badge-sehat { background: #ecfdf5; color: #059669; }
        .status-badge.badge-waspada { background: #fffbeb; color: #d97706; }
        .status-badge.badge-bahaya { background: #fef2f2; color: #dc2626; }
        .status-badge .pulse-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        .badge-sehat .pulse-dot { background: #10b981; }
        .badge-waspada .pulse-dot { background: #f59e0b; }
        .badge-bahaya .pulse-dot { background: #ef4444; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .4; }
        }

        /* ===== RATIO BAR ===== */
        .ratio-bar-wrap {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .ratio-bar {
            flex: 1;
            height: 8px;
            border-radius: 99px;
            background: #f1f5f9;
            overflow: hidden;
            position: relative;
        }
        .ratio-bar .ratio-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .8s cubic-bezier(.4,0,.2,1);
            position: relative;
        }
        .ratio-bar .ratio-fill.fill-ok { background: linear-gradient(90deg, #10b981, #34d399); }
        .ratio-bar .ratio-fill.fill-warn { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .ratio-bar .ratio-fill.fill-bad { background: linear-gradient(90deg, #ef4444, #f87171); }
        .ratio-pct {
            font-family: 'JetBrains Mono', monospace;
            font-size: .8rem;
            font-weight: 600;
            min-width: 50px;
            text-align: right;
        }

        /* ===== TABLES ===== */
        .fp-table { font-size: .875rem; }
        .fp-table th {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-light);
            padding: .75rem 1rem;
        }
        .fp-table td { padding: .7rem 1rem; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        .fp-table tbody tr:hover { background: #f8fafc; }
        .fp-table .mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: .82rem;
            text-align: right;
        }

        /* ===== CATEGORY SECTIONS (form) ===== */
        .cat-section {
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            margin-bottom: .75rem;
            overflow: hidden;
            transition: box-shadow .2s ease;
        }
        .cat-section:hover { box-shadow: var(--shadow-sm); }
        .cat-section-header {
            padding: .85rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
        }
        .cat-section-header .cat-label {
            font-weight: 700;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .cat-section-header .cat-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .cat-section-body { padding: .75rem 1rem; }
        .cat-section-body .item-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .5rem;
        }
        .cat-section-body .item-row .form-control {
            border-radius: var(--radius-sm);
            border: 1px solid #e2e8f0;
            font-size: .85rem;
            padding: .5rem .75rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .cat-section-body .item-row .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }
        .cat-section-body .input-amount {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
        }
        .btn-add-item {
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .7rem;
            border-radius: 50px;
            transition: all .2s;
        }
        .btn-remove-item {
            width: 32px; height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid #fecaca;
            background: #fff;
            color: #ef4444;
            font-size: .8rem;
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }
        .btn-remove-item:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }

        /* ===== GROUP HEADERS ===== */
        .group-header {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 1.5rem 0 .75rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid var(--border-light);
        }
        .group-header .group-icon {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: #fff;
        }
        .group-header h6 {
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin: 0;
        }

        /* ===== DETAIL TABLE ===== */
        .detail-section {
            margin-bottom: .25rem;
        }
        .detail-section-header {
            background: #f8fafc;
            padding: .65rem 1.25rem;
            font-weight: 700;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .55rem 1.25rem .55rem 2rem;
            border-bottom: 1px solid #f8fafc;
            font-size: .85rem;
            transition: background .15s;
        }
        .detail-item:hover { background: #fafbfc; }
        .detail-item .item-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--text-muted);
            margin-right: .6rem;
            flex-shrink: 0;
        }
        .detail-item .item-name { display: flex; align-items: center; color: #475569; }
        .detail-item .item-val {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            font-size: .82rem;
            color: var(--text-primary);
        }
        .detail-subtotal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .7rem 1.25rem;
            background: #f8fafc;
            font-weight: 700;
            font-size: .85rem;
            border-bottom: 2px solid var(--border-light);
        }
        .detail-subtotal .sub-val {
            font-family: 'JetBrains Mono', monospace;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .detail-grand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            font-weight: 800;
            font-size: .95rem;
        }
        .detail-grand .grand-val {
            font-family: 'JetBrains Mono', monospace;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .pct-chip {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .2rem .6rem;
            border-radius: 50px;
            font-size: .7rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }
        .pct-chip.chip-ok { background: #ecfdf5; color: #059669; }
        .pct-chip.chip-bad { background: #fef2f2; color: #dc2626; }

        /* ===== NOTES SECTION ===== */
        .note-item {
            display: flex;
            gap: .75rem;
            padding: .75rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: .5rem;
            font-size: .87rem;
            line-height: 1.5;
            background: #f8fafc;
            border-left: 3px solid var(--border-light);
        }
        .note-item.note-warn { border-left-color: var(--accent-amber); background: #fffbeb; }
        .note-item.note-danger { border-left-color: var(--accent-rose); background: #fef2f2; }
        .note-item.note-ok { border-left-color: var(--accent-emerald); background: #ecfdf5; }
        .note-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        .saran-item {
            padding: .6rem 0;
            font-size: .87rem;
            color: #334155;
            line-height: 1.6;
        }
        .saran-item::marker { color: var(--accent-blue); font-weight: 700; }

        /* ===== ALERTS ===== */
        .fp-alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .5rem;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ===== BUTTONS ===== */
        .btn-fp {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: .875rem;
            border-radius: var(--radius-sm);
            padding: .6rem 1.25rem;
            transition: all .2s ease;
            border: none;
        }
        .btn-fp-primary {
            background: var(--accent-blue);
            color: #fff;
            box-shadow: 0 1px 3px rgba(59,130,246,.3);
        }
        .btn-fp-primary:hover {
            background: #2563eb;
            box-shadow: 0 4px 12px rgba(59,130,246,.35);
            transform: translateY(-1px);
            color: #fff;
        }
        .btn-fp-lg {
            padding: .85rem 2.5rem;
            font-size: 1rem;
            border-radius: var(--radius-md);
        }
        .btn-fp-outline {
            background: transparent;
            border: 1px solid var(--border-light);
            color: var(--text-secondary);
        }
        .btn-fp-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--text-primary);
        }

        /* ===== FORM ===== */
        .form-label-fp {
            font-weight: 600;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .3px;
            color: var(--text-secondary);
            margin-bottom: .4rem;
        }
        .form-control-fp {
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: .65rem 1rem;
            font-size: .9rem;
            transition: all .2s;
        }
        .form-control-fp:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-state .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1.5rem;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--text-muted);
        }

        /* ===== REPORT CARDS (index) ===== */
        .report-card {
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: all .3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .report-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: #cbd5e1;
        }
        .report-card .rc-avatar {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .report-card .rc-name { font-weight: 700; font-size: 1rem; color: var(--text-primary); }
        .report-card .rc-period { font-size: .82rem; color: var(--text-muted); margin-top: .15rem; }
        .report-card .rc-meta {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            font-size: .78rem;
            color: var(--text-muted);
        }
        .report-card .rc-actions {
            display: flex;
            gap: .4rem;
            margin-top: .75rem;
        }

        /* ===== ANALYSIS TABLE ===== */
        .analysis-row {
            display: grid;
            grid-template-columns: 1.5fr 1.2fr .8fr .8fr 2fr .5fr;
            align-items: center;
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: .85rem;
            transition: background .15s;
        }
        .analysis-row:hover { background: #fafbfc; }
        .analysis-row.analysis-header {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-secondary);
            background: #f8fafc;
            border-bottom: 2px solid var(--border-light);
        }
        .analysis-row.row-bold {
            font-weight: 700;
            background: #f8fafc;
            border-bottom: 2px solid var(--border-light);
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ===== PRINT ===== */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; font-size: 12px; }
            .fp-card, .stat-card, .report-card { box-shadow: none; border: 1px solid #ddd; }
            .stat-card:hover, .report-card:hover { transform: none; box-shadow: none; }
        }

        /* ===== TABS ===== */
        .fp-tabs {
            display: flex;
            gap: 4px;
            background: #f1f5f9;
            border-radius: var(--radius-md);
            padding: 4px;
            margin-bottom: 1.5rem;
        }
        .fp-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .75rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: .875rem;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all .25s ease;
            text-decoration: none;
        }
        .fp-tab:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,.6);
        }
        .fp-tab.active {
            color: #fff;
            background: var(--bg-primary);
            box-shadow: var(--shadow-md);
        }
        .fp-tab .tab-num {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            font-weight: 700;
            background: rgba(0,0,0,.08);
            color: inherit;
        }
        .fp-tab.active .tab-num {
            background: rgba(255,255,255,.2);
        }

        /* ===== NERACA TWO-COLUMN ===== */
        .neraca-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 991px) {
            .neraca-grid { grid-template-columns: 1fr; }
        }
        .neraca-col-header {
            padding: .85rem 1.25rem;
            font-weight: 800;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
            color: #fff;
        }
        .neraca-col-header.header-aset {
            background: linear-gradient(135deg, #059669, #10b981);
        }
        .neraca-col-header.header-kewajiban {
            background: linear-gradient(135deg, #dc2626, #f43f5e);
        }
        .neraca-section-label {
            padding: .6rem 1rem;
            font-weight: 700;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--text-secondary);
            background: #f8fafc;
            border-bottom: 1px solid var(--border-light);
            border-top: 1px solid var(--border-light);
        }
        .neraca-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .5rem 1rem .5rem 1.5rem;
            border-bottom: 1px solid #f8fafc;
            font-size: .85rem;
        }
        .neraca-item:hover { background: #fafbfc; }
        .neraca-subtotal {
            display: flex;
            justify-content: space-between;
            padding: .65rem 1rem;
            font-weight: 700;
            font-size: .82rem;
            background: #f1f5f9;
            border-bottom: 1px solid var(--border-light);
        }
        .neraca-grand {
            display: flex;
            justify-content: space-between;
            padding: .85rem 1rem;
            font-weight: 800;
            font-size: .9rem;
            border-top: 2px solid var(--border-light);
        }

        /* ===== FCU RATIO CARDS ===== */
        .fcu-card {
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all .3s ease;
        }
        .fcu-card:hover {
            box-shadow: var(--shadow-md);
        }
        .fcu-card .fcu-num {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--bg-primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .9rem;
            margin-right: .75rem;
            flex-shrink: 0;
        }
        .fcu-card .fcu-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
        }
        .fcu-card .fcu-desc {
            font-size: .82rem;
            color: var(--text-secondary);
            margin-top: .35rem;
            line-height: 1.5;
        }
        .fcu-detail-row {
            display: flex;
            justify-content: space-between;
            padding: .4rem 0;
            font-size: .85rem;
            border-bottom: 1px dashed #f1f5f9;
        }
        .fcu-detail-row:last-child { border-bottom: none; }
        .fcu-detail-row .fcu-val {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: .82rem;
        }

        /* ===== ANIMATIONS ===== */
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== STEP PROGRESS BAR ===== */
        .form-stepper { padding: 0 1rem; }
        .stepper-track {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
        }
        .stepper-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem;
            position: relative;
            z-index: 1;
        }
        .step-circle {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: #e2e8f0;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all .4s cubic-bezier(.4,0,.2,1);
            border: 3px solid transparent;
        }
        .stepper-step.active .step-circle {
            background: var(--accent-blue);
            color: #fff;
            border-color: rgba(59,130,246,.25);
            box-shadow: 0 0 0 4px rgba(59,130,246,.12);
        }
        .stepper-step.completed .step-circle {
            background: var(--accent-emerald);
            color: #fff;
            border-color: rgba(16,185,129,.25);
        }
        .step-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            transition: color .3s;
        }
        .stepper-step.active .step-label,
        .stepper-step.completed .step-label { color: var(--text-primary); }
        .stepper-line {
            flex: 1;
            max-width: 120px;
            height: 3px;
            background: #e2e8f0;
            border-radius: 99px;
            margin: 0 .5rem;
            margin-bottom: 1.2rem;
            overflow: hidden;
        }
        .stepper-line-fill {
            width: 0%; height: 100%;
            background: var(--accent-blue);
            border-radius: 99px;
            transition: width .5s cubic-bezier(.4,0,.2,1);
        }
        .stepper-line.line-filled .stepper-line-fill { width: 100%; }

        /* ===== FORM HINT ===== */
        .form-hint {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: var(--radius-sm);
            padding: .65rem 1rem;
            font-size: .82rem;
        }
        .form-hint kbd {
            background: var(--bg-primary);
            color: #fff;
            padding: .15rem .4rem;
            border-radius: 4px;
            font-size: .72rem;
            font-family: 'JetBrains Mono', monospace;
        }

        /* ===== INPUT GROUP FP ===== */
        .input-group-fp .input-group-text {
            background: #f8fafc;
            border: 1px solid var(--border-light);
            border-right: 0;
            color: var(--text-muted);
            font-size: .85rem;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        }
        .input-group-fp .form-control-fp {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* ===== TAB BADGES ===== */
        .tab-badge {
            font-size: .65rem;
            font-weight: 700;
            background: rgba(0,0,0,.06);
            color: var(--text-muted);
            padding: .15rem .5rem;
            border-radius: 50px;
            margin-left: .25rem;
            transition: all .3s;
        }
        .fp-tab.active .tab-badge {
            background: rgba(255,255,255,.15);
            color: rgba(255,255,255,.8);
        }
        .tab-badge.badge-has-data {
            background: rgba(16,185,129,.15);
            color: #059669;
        }
        .fp-tab.active .tab-badge.badge-has-data {
            background: rgba(16,185,129,.3);
            color: #a7f3d0;
        }

        /* ===== COL HEADER TOTAL ===== */
        .col-header-total {
            font-family: 'JetBrains Mono', monospace;
            font-size: .82rem;
            font-weight: 700;
            opacity: .85;
        }

        /* ===== COLLAPSIBLE CATEGORY SECTIONS ===== */
        .cat-collapsible .cat-section-body {
            max-height: 0;
            overflow: hidden;
            padding: 0 1rem;
            transition: max-height .35s cubic-bezier(.4,0,.2,1), padding .35s ease;
        }
        .cat-collapsible.cat-open .cat-section-body {
            max-height: 2000px;
            padding: .75rem 1rem;
        }
        .cat-collapsible .cat-section-footer {
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
        }
        .cat-collapsible.cat-open .cat-section-footer {
            max-height: 60px;
        }
        .cat-section-footer {
            padding: .5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px dashed #e2e8f0;
            background: #fafbfc;
        }
        .cat-footer-total {
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* ===== CAT HEADER ENHANCEMENTS ===== */
        .cat-section-header {
            cursor: pointer;
            user-select: none;
            transition: background .2s;
        }
        .cat-section-header:hover {
            background: #f8fafc;
        }
        .cat-header-right {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-shrink: 0;
        }
        .cat-chevron {
            font-size: .75rem;
            color: var(--text-muted);
            transition: transform .35s cubic-bezier(.4,0,.2,1);
        }
        .cat-collapsible.cat-open .cat-chevron {
            transform: rotate(180deg);
        }
        .cat-subtotal {
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
            transition: color .3s;
        }
        .cat-subtotal.has-value {
            color: var(--accent-blue);
            font-weight: 700;
        }

        /* ===== CAT ICON WRAP ===== */
        .cat-icon-wrap {
            width: 26px; height: 26px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            flex-shrink: 0;
        }

        /* ===== CAT COUNT BADGE ===== */
        .cat-count-badge {
            font-size: .65rem;
            font-weight: 600;
            background: #f1f5f9;
            color: var(--text-muted);
            padding: .1rem .45rem;
            border-radius: 50px;
            margin-left: .2rem;
        }

        /* ===== IDEAL BADGE ===== */
        .ideal-badge {
            font-size: .6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #dbeafe, #ede9fe);
            color: #4f46e5;
            padding: .15rem .5rem;
            border-radius: 50px;
            letter-spacing: .3px;
        }

        /* ===== INPUT GROUP AMOUNT ("Rp" prefix) ===== */
        .input-group-amount {
            max-width: 200px;
            flex-shrink: 0;
        }
        .input-group-amount .rp-prefix {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-right: 0;
            color: var(--text-muted);
            font-size: .75rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            padding: .4rem .5rem;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        }
        .input-group-amount .input-amount {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important;
        }
        .input-amount.input-flash {
            animation: flashGreen .4s ease;
        }
        @keyframes flashGreen {
            0% { background: #ecfdf5; }
            100% { background: #fff; }
        }

        /* ===== QUICK FILL BUTTONS ===== */
        .quick-fill-wrap {
            display: flex;
            gap: 2px;
            flex-shrink: 0;
        }
        .btn-quick-fill {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: var(--text-secondary);
            font-size: .6rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            padding: .2rem .35rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all .15s;
            line-height: 1;
            white-space: nowrap;
        }
        .btn-quick-fill:hover {
            background: var(--accent-blue);
            color: #fff;
            border-color: var(--accent-blue);
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .quick-fill-wrap { display: none; }
            .input-group-amount { max-width: 150px; }
        }

        /* ===== ADD ITEM BUTTON (new style) ===== */
        .btn-add-item-new {
            font-size: .75rem;
            font-weight: 600;
            color: var(--cat-color, var(--accent-blue));
            background: transparent;
            border: 1px dashed var(--cat-color, var(--accent-blue));
            border-radius: 50px;
            padding: .3rem .75rem;
            transition: all .2s;
            cursor: pointer;
        }
        .btn-add-item-new:hover {
            background: color-mix(in srgb, var(--cat-color, var(--accent-blue)) 8%, transparent);
            border-style: solid;
        }

        /* ===== ITEM ROW ANIMATIONS ===== */
        .item-row-enter {
            animation: slideInRow .3s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideInRow {
            from { opacity: 0; transform: translateY(-8px); max-height: 0; }
            to { opacity: 1; transform: translateY(0); max-height: 60px; }
        }
        .item-row-exit {
            animation: slideOutRow .25s cubic-bezier(.4,0,.2,1) forwards;
        }
        @keyframes slideOutRow {
            from { opacity: 1; transform: translateX(0); max-height: 60px; }
            to { opacity: 0; transform: translateX(20px); max-height: 0; margin-bottom: 0; }
        }

        /* ===== FLOATING SUMMARY BAR ===== */
        .floating-summary {
            position: fixed;
            bottom: -100px;
            left: 0; right: 0;
            z-index: 999;
            background: var(--bg-primary);
            border-top: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 -8px 30px rgba(0,0,0,.15);
            padding: .65rem 0;
            transition: bottom .4s cubic-bezier(.4,0,.2,1);
        }
        .floating-summary.summary-visible {
            bottom: 0;
        }
        .summary-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .summary-items {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .summary-item {
            display: flex;
            flex-direction: column;
        }
        .summary-label {
            font-size: .6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: rgba(255,255,255,.5);
        }
        .summary-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
        }
        .summary-value.text-success { color: #34d399 !important; }
        .summary-value.text-danger { color: #f87171 !important; }
        .summary-divider {
            color: rgba(255,255,255,.25);
            font-size: 1.2rem;
            font-weight: 300;
            margin: 0 .25rem;
        }
        .summary-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .filled-count {
            font-size: .72rem;
            color: rgba(255,255,255,.45);
            font-weight: 500;
            white-space: nowrap;
        }
        .btn-fp-md {
            padding: .55rem 1.5rem;
            font-size: .85rem;
            border-radius: var(--radius-sm);
        }

        @media (max-width: 768px) {
            .summary-items { flex-wrap: wrap; gap: .4rem; }
            .summary-item { flex-direction: row; gap: .35rem; align-items: baseline; }
            .summary-divider { display: none; }
            .summary-value { font-size: .75rem; }
            .floating-summary { padding: .5rem 0; }
            .filled-count { display: none; }
        }

        /* ===== OVERRIDE ITEM ROW FOR NEW STRUCTURE ===== */
        .cat-section-body .item-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .5rem;
            padding: .25rem 0;
            border-radius: var(--radius-sm);
            transition: background .15s;
        }
        .cat-section-body .item-row:hover {
            background: #f8fafc;
        }
        .cat-section-body .item-row .form-control {
            border-radius: var(--radius-sm);
            border: 1px solid #e2e8f0;
            font-size: .85rem;
            padding: .5rem .75rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .cat-section-body .item-row .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="fp-navbar no-print">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a class="navbar-brand text-decoration-none" href="{{ route('cashflow.index') }}">
                    <span class="brand-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    Financial Planner
                </a>
                <div class="d-flex align-items-center gap-1">
                    <a class="nav-link" href="{{ route('cashflow.index') }}">
                        <i class="bi bi-grid-1x2 me-1"></i> Dashboard
                    </a>
                    <a class="nav-link btn-nav-primary" href="{{ route('cashflow.create') }}">
                        <i class="bi bi-plus-lg me-1"></i> Buat Baru
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="container py-4">
        @if(session('success'))
            <div class="fp-alert alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="fp-alert alert alert-danger alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="fade-in">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="text-center py-4 no-print" style="font-size:.78rem; color: var(--text-muted);">
        <div class="container">
            <span>Financial Planner &copy; {{ date('Y') }}</span>
            <span class="mx-1">&middot;</span>
            <span>Professional Financial Checkup Tool</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
