<?php
/*
Template Name: Import Export Guideline
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUK Import/Export Documentation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@400;500;600;700&family=Outfit:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --container-bg: rgba(30, 41, 59, 0.7);
            --accent-color: #38bdf8;
            --accent-glow: rgba(56, 189, 248, 0.3);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.1);
            --code-bg: #1e293b;
            --table-header: rgba(56, 189, 248, 0.15);
            --sidebar-width: 280px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Sidebar Navigation */
        aside {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-right: 1px solid var(--border-color);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .logo-container {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-color), #818cf8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        /* Tab Navigation in Sidebar */
        .tab-nav {
            list-style: none;
            margin-bottom: 2rem;
        }

        .tab-nav-item {
            margin-bottom: 0.25rem;
        }

        .tab-nav-link {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.95rem;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid transparent;
        }

        .tab-nav-link:hover {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent-color);
        }

        .tab-nav-link.active {
            background: rgba(56, 189, 248, 0.15);
            color: var(--accent-color);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .tab-nav-link .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .tab-nav-link .nav-label {
            flex: 1;
        }

        .tab-nav-link .status-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .tab-nav-link .status-badge.complete {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        /* Section Navigation (within tab) */
        .section-nav {
            list-style: none;
            border-top: 1px solid var(--border-color);
            padding-top: 1rem;
        }

        .section-nav-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
        }

        .section-nav-item {
            margin-bottom: 0.25rem;
        }

        .section-nav-link {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.85rem;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 6px;
            display: block;
            transition: var(--transition);
            cursor: pointer;
        }

        .section-nav-link:hover,
        .section-nav-link.active {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }

        /* Main Content */
        main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 3rem 8% 4rem 5%;
            max-width: 1400px;
        }

        header {
            margin-bottom: 3rem;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease-out forwards;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        section {
            margin-bottom: 4rem;
        }

        h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        h2::before {
            content: '';
            display: block;
            width: 4px;
            height: 1.25rem;
            background: var(--accent-color);
            border-radius: 4px;
        }

        h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            margin: 2rem 0 1rem;
            color: #fff;
        }

        p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        code {
            background: var(--code-bg);
            color: var(--accent-color);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
        }

        /* Callout / Note */
        .callout {
            background: rgba(56, 189, 248, 0.05);
            border-left: 4px solid var(--accent-color);
            padding: 1.25rem 1.5rem;
            border-radius: 0 12px 12px 0;
            margin: 1.5rem 0;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .callout.warning {
            background: rgba(245, 158, 11, 0.05);
            border-left-color: var(--warning-color);
        }

        .callout.success {
            background: rgba(16, 185, 129, 0.05);
            border-left-color: var(--success-color);
        }

        .callout-icon {
            color: var(--accent-color);
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .callout.warning .callout-icon {
            color: var(--warning-color);
        }

        .callout.success .callout-icon {
            color: var(--success-color);
        }

        .callout code {
            background: rgba(56, 189, 248, 0.15);
        }

        .callout-warning {
            background: rgba(245, 158, 11, 0.05);
            border-left-color: var(--warning-color);
        }

        .callout-warning .callout-icon {
            color: var(--warning-color);
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-blue {
            background: rgba(56, 189, 248, 0.2);
            color: var(--accent-color);
        }

        .badge-green {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        /* Matching Strategy Cards */
        .matching-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .matching-card {
            background: var(--container-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .matching-card-header {
            background: rgba(56, 189, 248, 0.1);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .matching-card-body {
            padding: 1.25rem;
        }

        .matching-card-body p {
            margin-bottom: 0.75rem;
        }

        .matching-card-body ul,
        .matching-card-body ol {
            padding-left: 1.25rem;
            color: var(--text-secondary);
        }

        .matching-card-body li {
            margin-bottom: 0.5rem;
        }

        .matching-card-body code {
            background: var(--code-bg);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
        }

        /* Tables */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin: 1.5rem 0;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--container-bg);
            backdrop-filter: blur(10px);
        }

        th {
            background: var(--table-header);
            text-align: left;
            padding: 1rem 1.25rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--accent-color);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
            color: #fff;
        }

        strong {
            color: #fff;
            font-weight: 600;
        }

        /* Hierarchy Chart */
        .hierarchy-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #1e293b;
            overflow-x: auto;
            position: relative;
        }

        .hierarchy-box .inner-box {
            border: 1px solid #1e293b;
            background: #fff;
            padding: 0;
            max-width: 900px;
            margin: 0 auto;
        }

        .hierarchy-box .box-header {
            border-bottom: 1px solid #1e293b;
            padding: 0.6rem;
            text-align: center;
            font-weight: 500;
        }

        .hierarchy-box .box-content {
            padding: 1.5rem;
        }

        .tree-line {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .tree-indent {
            display: flex;
            flex-shrink: 0;
        }

        .indent-unit {
            width: 2rem;
            height: 2rem;
            border-left: 1px solid #cbd5e1;
            position: relative;
        }

        .indent-unit.last-child::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 1.25rem;
            border-top: 1px solid #cbd5e1;
        }

        .folder-icon {
            color: #f59e0b;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .highlight-green {
            color: #10b981;
            font-weight: 600;
            margin-left: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .file-list {
            list-style: none;
            padding-left: 3rem;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .file-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.15rem;
        }

        .file-list li::before {
            content: '';
            color: #94a3b8;
        }

        .hierarchy-box::after {
            content: 'STRUCTURE';
            position: absolute;
            top: 0.75rem;
            right: 1rem;
            font-size: 0.65rem;
            color: #94a3b8;
            letter-spacing: 2px;
        }

        /* File Tree */
        .file-tree {
            list-style: none;
            font-family: 'Fira Code', monospace;
            background: var(--code-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            font-size: 0.85rem;
        }

        .tree-item {
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .folder {
            color: #fbbf24;
        }

        .image {
            color: #10b981;
        }

        .file {
            color: #94a3b8;
        }

        .arrow {
            color: #38bdf8;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .comment {
            color: #64748b;
            margin-left: auto;
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
        }

        /* Checklist */
        .checklist {
            background: var(--container-bg);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .checklist-item:last-child {
            border-bottom: none;
        }

        .checkbox {
            width: 22px;
            height: 22px;
            border: 2px solid var(--accent-color);
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checkbox:hover {
            box-shadow: 0 0 10px var(--accent-glow);
        }

        .checkbox.checked {
            background: var(--accent-color);
        }

        .checkbox.checked::after {
            content: '';
            color: var(--bg-color);
            font-weight: bold;
            font-size: 12px;
        }

        .item-text {
            cursor: pointer;
            flex: 1;
            font-size: 0.9rem;
        }

        /* CSV Example Box */
        .csv-box {
            background: var(--code-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin: 1.5rem 0;
        }

        .csv-header {
            background: rgba(56, 189, 248, 0.1);
            padding: 0.75rem 1.25rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            color: var(--accent-color);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .csv-content {
            padding: 1.25rem;
            font-family: 'Fira Code', monospace;
            font-size: 0.8rem;
            overflow-x: auto;
            white-space: pre;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .csv-content .header-row {
            color: var(--accent-color);
            font-weight: 500;
        }

        /* Coming Soon Badge */
        .coming-soon {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            text-align: center;
            background: var(--container-bg);
            border-radius: 16px;
            border: 1px dashed var(--border-color);
        }

        .coming-soon-icon {
            width: 80px;
            height: 80px;
            background: rgba(56, 189, 248, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .coming-soon h3 {
            margin: 0 0 0.5rem;
            font-size: 1.5rem;
        }

        .coming-soon p {
            color: var(--text-secondary);
            max-width: 400px;
        }

        /* Footer */
        .doc-footer {
            margin-top: 4rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            aside {
                width: 70px;
                padding: 1.5rem 0.75rem;
                align-items: center;
            }

            .logo-text,
            .nav-label,
            .status-badge,
            .section-nav {
                display: none;
            }

            .tab-nav-link {
                justify-content: center;
                padding: 0.75rem;
            }

            main {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            aside {
                display: none;
            }

            main {
                margin-left: 0;
                padding: 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            /* Mobile Tab Bar */
            .mobile-tabs {
                display: flex;
                overflow-x: auto;
                gap: 0.5rem;
                padding-bottom: 1rem;
                margin-bottom: 1.5rem;
                border-bottom: 1px solid var(--border-color);
            }

            .mobile-tab {
                flex-shrink: 0;
                padding: 0.5rem 1rem;
                background: var(--container-bg);
                border: 1px solid var(--border-color);
                border-radius: 20px;
                color: var(--text-secondary);
                font-size: 0.85rem;
                cursor: pointer;
                transition: var(--transition);
            }

            .mobile-tab.active {
                background: rgba(56, 189, 248, 0.15);
                border-color: var(--accent-color);
                color: var(--accent-color);
            }
        }

        @media (min-width: 769px) {
            .mobile-tabs {
                display: none;
            }
        }
    </style>
</head>

<body>
    <aside>
        <div class="logo-container">
            <div class="logo-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" />
                </svg>
            </div>
            <span class="logo-text">PUK Docs</span>
        </div>

        <ul class="tab-nav">
            <li class="tab-nav-item">
                <a class="tab-nav-link active" data-tab="accessories">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83" />
                    </svg>
                    <span class="nav-label">Accessories</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
            <li class="tab-nav-item">
                <a class="tab-nav-link" data-tab="features">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    <span class="nav-label">Features</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
            <li class="tab-nav-item">
                <a class="tab-nav-link" data-tab="finish-color">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="13.5" cy="6.5" r="0.5" fill="currentColor" />
                        <circle cx="17.5" cy="10.5" r="0.5" fill="currentColor" />
                        <circle cx="8.5" cy="7.5" r="0.5" fill="currentColor" />
                        <circle cx="6.5" cy="12.5" r="0.5" fill="currentColor" />
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.555C21.965 6.012 17.461 2 12 2z" />
                    </svg>
                    <span class="nav-label">Finish Color</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
            <li class="tab-nav-item">
                <a class="tab-nav-link" data-tab="family">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                    </svg>
                    <span class="nav-label">Family</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
            <li class="tab-nav-item">
                <a class="tab-nav-link" data-tab="products">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                    <span class="nav-label">Products</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
            <li class="tab-nav-item">
                <a class="tab-nav-link" data-tab="images">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <span class="nav-label">Image Assign</span>
                    <span class="status-badge complete">Ready</span>
                </a>
            </li>
        </ul>

        <ul class="section-nav" id="sectionNav">
            <!-- Dynamic section nav will be populated by JS -->
        </ul>
    </aside>

    <main>
        <header>
            <h1>Import/Export Documentation</h1>
            <p class="subtitle">Comprehensive guide for managing data imports and exports in the PUK system.</p>
        </header>

        <!-- Mobile Tab Bar -->
        <div class="mobile-tabs">
            <div class="mobile-tab active" data-tab="accessories">Accessories</div>
            <div class="mobile-tab" data-tab="features">Features</div>
            <div class="mobile-tab" data-tab="finish-color">Finish Color</div>
            <div class="mobile-tab" data-tab="family">Family</div>
            <div class="mobile-tab" data-tab="products">Products</div>
            <div class="mobile-tab" data-tab="images">Images</div>
        </div>

        <!-- Tab: Accessories -->
        <div class="tab-content active" id="tab-accessories">
            <section id="acc-overview">
                <h2>Accessories Import/Export</h2>
                <p>Accessories are stored as a WordPress taxonomy (<code>accessories</code>) with custom ACF fields. The import/export system allows bulk management of accessory data via CSV files.</p>

                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Important:</strong> The <code>Code</code> field is the <strong>primary unique identifier</strong>. Existing accessories are matched by code during import.</p>
                    </div>
                </div>
            </section>

            <section id="acc-columns">
                <h2>CSV Column Reference</h2>
                <p>The following columns are supported in the import/export CSV file:</p>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Required</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Code</code></td>
                                <td><code>tax_acc__code</code></td>
                                <td><strong>Yes</strong></td>
                                <td>Unique identifier for the accessory (e.g., AC044, HC-001)</td>
                            </tr>
                            <tr>
                                <td><code>Name</code></td>
                                <td>Term Name</td>
                                <td><strong>Yes*</strong></td>
                                <td>Display name of the accessory. *Required for new accessories</td>
                            </tr>
                            <tr>
                                <td><code>Included</code></td>
                                <td><code>tax_acc_ft__type</code></td>
                                <td>No</td>
                                <td>Type indicator (e.g., 0 = Optional, 1 = Included)</td>
                            </tr>
                            <tr>
                                <td><code>Integrated label</code></td>
                                <td><code>tax_acc_integ__label</code></td>
                                <td>No</td>
                                <td>Integration label suffix (e.g., .HC, .DIM)</td>
                            </tr>
                            <tr>
                                <td><code>Description</code></td>
                                <td>Term Description</td>
                                <td>No</td>
                                <td>Detailed description of the accessory</td>
                            </tr>
                            <tr>
                                <td><code>Image</code></td>
                                <td><code>tax_acc_ft__img</code></td>
                                <td>No</td>
                                <td>Image URL or attachment ID</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="acc-example">
                <h2>CSV Format Example</h2>
                <p>Below is an example of a properly formatted accessories CSV file:</p>

                <div class="csv-box">
                    <div class="csv-header">
                        <span>accessories-import.csv</span>
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">UTF-8 with BOM</span>
                    </div>
                    <div class="csv-content"><span class="header-row">Code,Name,Included,Integrated label,Description,Image</span>
AC044,Surface Mount Kit,0,,Surface mounting bracket for wall installation,
AC075,Emergency Battery Pack,1,.EM,3-hour emergency battery backup system,
HC-001,Heat Controller,0,.HC,Integrated heat management controller,
DIM-002,DALI Dimmer Module,1,.DIM,DALI-2 compatible dimming interface,
AC100,Junction Box Cover,0,,Protective cover for junction box,https://example.com/images/ac100.jpg</div>
                </div>

                <h3>Visual Table Preview</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Included</th>
                                <th>Integrated label</th>
                                <th>Description</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>AC044</code></td>
                                <td>Surface Mount Kit</td>
                                <td>0</td>
                                <td></td>
                                <td>Surface mounting bracket for wall installation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>AC075</code></td>
                                <td>Emergency Battery Pack</td>
                                <td>1</td>
                                <td>.EM</td>
                                <td>3-hour emergency battery backup system</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>HC-001</code></td>
                                <td>Heat Controller</td>
                                <td>0</td>
                                <td>.HC</td>
                                <td>Integrated heat management controller</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>DIM-002</code></td>
                                <td>DALI Dimmer Module</td>
                                <td>1</td>
                                <td>.DIM</td>
                                <td>DALI-2 compatible dimming interface</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>AC100</code></td>
                                <td>Junction Box Cover</td>
                                <td>0</td>
                                <td></td>
                                <td>Protective cover for junction box</td>
                                <td><span style="color: var(--accent-color); font-size: 0.8rem;">https://...</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="acc-import-logic">
                <h2>Import Logic</h2>
                <p>Understanding how the import process handles your data:</p>

                <h3>Matching Strategy</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Match By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1st</strong></td>
                                <td>Code (ACF field)</td>
                                <td>Updates existing accessory with matching code</td>
                            </tr>
                            <tr>
                                <td><strong>2nd</strong></td>
                                <td>Name (fallback)</td>
                                <td>If no code match, tries to find by exact name</td>
                            </tr>
                            <tr>
                                <td><strong>3rd</strong></td>
                                <td>Create New</td>
                                <td>If no match found, creates new accessory</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Image Handling</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Image Value</th>
                                <th>Behavior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>https://example.com/image.jpg</code></td>
                                <td>Downloads image and creates WordPress attachment</td>
                            </tr>
                            <tr>
                                <td><code>12345</code> (numeric)</td>
                                <td>Uses existing attachment ID directly</td>
                            </tr>
                            <tr>
                                <td><em>(empty)</em></td>
                                <td>Leaves existing image unchanged</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout warning">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Name Conflicts:</strong> If a name already exists with a different code, the system will create a unique term by appending the code (e.g., "Surface Mount Kit (AC044)").</p>
                    </div>
                </div>
            </section>

            <section id="acc-export">
                <h2>Export Process</h2>
                <p>When exporting accessories, the system generates a CSV file with all taxonomy terms and their ACF field values.</p>

                <h3>Export Features</h3>
                <ul class="file-tree">
                    <li class="tree-item"><span class="folder">📋</span> UTF-8 encoding with BOM (Excel compatible)</li>
                    <li class="tree-item"><span class="folder">📋</span> All accessories included (including empty/hidden)</li>
                    <li class="tree-item"><span class="folder">📋</span> Images exported as full URLs</li>
                    <li class="tree-item"><span class="folder">📋</span> Sorted alphabetically by name</li>
                    <li class="tree-item"><span class="folder">📋</span> Filename: <code>accessories-taxonomy-export-YYYY-MM-DD.csv</code></li>
                </ul>
            </section>

            <section id="acc-checklist">
                <h2>Pre-Import Checklist</h2>
                <p style="margin-bottom: 1.5rem;">Verify these items before importing your accessories CSV:</p>

                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">CSV file is UTF-8 encoded</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">First row contains column headers</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Every row has a unique Code value</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">New accessories have both Code and Name filled</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Image URLs are publicly accessible (if using URLs)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">No special characters in Code field (use alphanumeric, dashes, underscores)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Integrated label values start with a dot (e.g., .HC, .EM)</div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tab: Features -->
        <div class="tab-content" id="tab-features">
            <section id="feat-overview">
                <h2>Features Import/Export</h2>
                <p>Features are stored as a WordPress taxonomy (<code>features</code>) with custom ACF fields. Features represent product attributes like IP ratings, certifications, and capabilities that can be assigned to products.</p>

                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Simple Structure:</strong> Features use only 4 columns. The <code>Code</code> field is optional but recommended as a unique identifier for updates.</p>
                    </div>
                </div>
            </section>

            <section id="feat-columns">
                <h2>CSV Column Reference</h2>
                <p>The following columns are supported in the import/export CSV file:</p>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Required</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Code</code></td>
                                <td><code>tax_featured__code</code></td>
                                <td>No*</td>
                                <td>Unique identifier for the feature. *Recommended for reliable updates</td>
                            </tr>
                            <tr>
                                <td><code>Name</code></td>
                                <td>Term Name</td>
                                <td><strong>Yes</strong></td>
                                <td>Display name of the feature (e.g., "IP65 Rated", "DALI Compatible")</td>
                            </tr>
                            <tr>
                                <td><code>Type</code></td>
                                <td><code>tax_featured__type</code></td>
                                <td>No</td>
                                <td>Feature category/type classification</td>
                            </tr>
                            <tr>
                                <td><code>Icon</code></td>
                                <td><code>tax_featured__icon</code></td>
                                <td>No</td>
                                <td>Icon image URL or attachment ID</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="feat-example">
                <h2>CSV Format Example</h2>
                <p>Below is an example of a properly formatted features CSV file:</p>

                <div class="csv-box">
                    <div class="csv-header">
                        <span>features-import.csv</span>
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">UTF-8 with BOM</span>
                    </div>
                    <div class="csv-content"><span class="header-row">Code,Name,Type,Icon</span>
IP65,IP65,IP,https://example.com/icons/ip65.svg
IP66,IP66,IP,
IP67,IP67,IP,
DALI,DALI,Dimming,https://example.com/icons/dali.svg</div>
                </div>

                <h3>Visual Table Preview</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Icon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>IP65</code></td>
                                <td>IP65</td>
                                <td>IP</td>
                                <td><span style="color: var(--accent-color); font-size: 0.8rem;">https://...</span></td>
                            </tr>
                            <tr>
                                <td><code>IP66</code></td>
                                <td>IP66</td>
                                <td>IP</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>IP67</code></td>
                                <td>IP67</td>
                                <td>IP</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>DALI</code></td>
                                <td>DALI</td>
                                <td>Dimming</td>
                                <td><span style="color: var(--accent-color); font-size: 0.8rem;">https://...</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="feat-import-logic">
                <h2>Import Logic</h2>
                <p>Understanding how the import process handles your data:</p>

                <h3>Matching Strategy</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Match By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1st</strong></td>
                                <td>Code (ACF field)</td>
                                <td>Updates existing feature with matching code</td>
                            </tr>
                            <tr>
                                <td><strong>2nd</strong></td>
                                <td>Name (exact match)</td>
                                <td>If no code match, tries to find by exact name</td>
                            </tr>
                            <tr>
                                <td><strong>3rd</strong></td>
                                <td>Create New</td>
                                <td>If no match found, creates new feature</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Icon Handling</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Icon Value</th>
                                <th>Behavior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>https://example.com/icon.svg</code></td>
                                <td>Downloads icon and creates WordPress attachment</td>
                            </tr>
                            <tr>
                                <td><code>12345</code> (numeric)</td>
                                <td>Uses existing attachment ID directly</td>
                            </tr>
                            <tr>
                                <td><em>(empty)</em></td>
                                <td>Leaves existing icon unchanged</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout success">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Tip:</strong> SVG icons are recommended for features as they scale perfectly and maintain quality at any size.</p>
                    </div>
                </div>
            </section>

            <section id="feat-types">
                <h2>Feature Types (Predefined)</h2>
                <p>The <code>Type</code> column accepts only these predefined values:</p>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Type Value</th>
                                <th>Description</th>
                                <th>Example Features</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Dimming</code></td>
                                <td>Dimming and control capabilities</td>
                                <td>DALI, Dimmable, 1-10V, Casambi</td>
                            </tr>
                            <tr>
                                <td><code>IP</code></td>
                                <td>Ingress Protection ratings</td>
                                <td>IP20, IP44, IP65, IP66, IP67, IP68</td>
                            </tr>
                            <tr>
                                <td><code>IK</code></td>
                                <td>Impact resistance ratings</td>
                                <td>IK06, IK07, IK08, IK10</td>
                            </tr>
                            <tr>
                                <td><code>Classe</code></td>
                                <td>Electrical insulation class</td>
                                <td>Class I, Class II, Class III</td>
                            </tr>
                            <tr>
                                <td><code>Tools</code></td>
                                <td>Installation and maintenance tools</td>
                                <td>Tool-free access, Quick connect</td>
                            </tr>
                            <tr>
                                <td><code>RGB</code></td>
                                <td>Color changing capabilities</td>
                                <td>RGB, RGBW, Tunable White, CCT</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout warning">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Important:</strong> Type values are case-sensitive. Use exactly as shown above (e.g., <code>Dimming</code> not <code>dimming</code>).</p>
                    </div>
                </div>
            </section>

            <section id="feat-export">
                <h2>Export Process</h2>
                <p>When exporting features, the system generates a CSV file with all taxonomy terms and their ACF field values.</p>

                <h3>Export Features</h3>
                <ul class="file-tree">
                    <li class="tree-item"><span class="folder">📋</span> UTF-8 encoding with BOM (Excel compatible)</li>
                    <li class="tree-item"><span class="folder">📋</span> All features included (including those not assigned to products)</li>
                    <li class="tree-item"><span class="folder">📋</span> Icons exported as full URLs</li>
                    <li class="tree-item"><span class="folder">📋</span> Sorted alphabetically by name</li>
                    <li class="tree-item"><span class="folder">📋</span> Filename: <code>features-taxonomy-export-YYYY-MM-DD.csv</code></li>
                </ul>
            </section>

            <section id="feat-checklist">
                <h2>Pre-Import Checklist</h2>
                <p style="margin-bottom: 1.5rem;">Verify these items before importing your features CSV:</p>

                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">CSV file is UTF-8 encoded</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">First row contains column headers (Code, Name, Type, Icon)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Every row has a Name value (required)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Code values are unique across all features</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Icon URLs are publicly accessible (if using URLs)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Column count matches in all rows (4 columns)</div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tab: Finish Color -->
        <div class="tab-content" id="tab-finish-color">
            <section id="color-overview">
                <h2>Finish Color Import/Export</h2>
                <p>Finish Colors are stored as a WordPress taxonomy (<code>finish-color</code>) with custom ACF fields. These represent the available color/finish options for products (e.g., White, Black, Anodized Silver).</p>

                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Simple Structure:</strong> Finish Colors use only 4 columns. The <code>Color Code</code> field is optional but recommended as a unique identifier for updates.</p>
                    </div>
                </div>
            </section>

            <section id="color-columns">
                <h2>CSV Column Reference</h2>
                <p>The following columns are supported in the import/export CSV file:</p>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Required</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Color Code</code></td>
                                <td><code>tax_finish_color__code</code></td>
                                <td>No*</td>
                                <td>Unique identifier for the color. *Recommended for reliable updates</td>
                            </tr>
                            <tr>
                                <td><code>Color</code></td>
                                <td>Term Name</td>
                                <td><strong>Yes</strong></td>
                                <td>Display name of the finish color (e.g., "White", "Matt Black")</td>
                            </tr>
                            <tr>
                                <td><code>Description</code></td>
                                <td>Term Description</td>
                                <td>No</td>
                                <td>Additional details about the color/finish</td>
                            </tr>
                            <tr>
                                <td><code>Color Image</code></td>
                                <td><code>tax_finish_color__img</code></td>
                                <td>No</td>
                                <td>Color swatch image URL or attachment ID</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="color-example">
                <h2>CSV Format Example</h2>
                <p>Below is an example of a properly formatted finish color CSV file:</p>

                <div class="csv-box">
                    <div class="csv-header">
                        <span>finish-color-import.csv</span>
                        <span style="font-size: 0.75rem; color: var(--text-secondary);">UTF-8 with BOM</span>
                    </div>
                    <div class="csv-content"><span class="header-row">Color Code,Color,Description,Color Image</span>
FNC001,RAL 9006,Silver metallic finish,https://example.com/colors/ral9006.jpg
FNC002,RAL 7016,Anthracite grey finish,
FNC003,RAL 9005 BLACK,Deep black finish,
FNC004,STAINLESS STEEL,Brushed stainless steel,</div>
                </div>

                <h3>Visual Table Preview</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Color Code</th>
                                <th>Color</th>
                                <th>Description</th>
                                <th>Color Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>FNC001</code></td>
                                <td>RAL 9006</td>
                                <td>Silver metallic finish</td>
                                <td><span style="color: var(--accent-color); font-size: 0.8rem;">https://...</span></td>
                            </tr>
                            <tr>
                                <td><code>FNC002</code></td>
                                <td>RAL 7016</td>
                                <td>Anthracite grey finish</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>FNC003</code></td>
                                <td>RAL 9005 BLACK</td>
                                <td>Deep black finish</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><code>FNC004</code></td>
                                <td>STAINLESS STEEL</td>
                                <td>Brushed stainless steel</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="color-import-logic">
                <h2>Import Logic</h2>
                <p>Understanding how the import process handles your data:</p>

                <h3>Matching Strategy</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Match By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1st</strong></td>
                                <td>Color Code (ACF field)</td>
                                <td>Updates existing color with matching code</td>
                            </tr>
                            <tr>
                                <td><strong>2nd</strong></td>
                                <td>Color Name (exact match)</td>
                                <td>If no code match, tries to find by exact name</td>
                            </tr>
                            <tr>
                                <td><strong>3rd</strong></td>
                                <td>Create New</td>
                                <td>If no match found, creates new finish color</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Color Image Handling</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Image Value</th>
                                <th>Behavior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>https://example.com/swatch.jpg</code></td>
                                <td>Downloads image and creates WordPress attachment</td>
                            </tr>
                            <tr>
                                <td><code>12345</code> (numeric)</td>
                                <td>Uses existing attachment ID directly</td>
                            </tr>
                            <tr>
                                <td><em>(empty)</em></td>
                                <td>Leaves existing image unchanged</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout success">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Tip:</strong> Use square color swatch images (e.g., 100x100px) for consistent display across the site.</p>
                    </div>
                </div>
            </section>

            <section id="color-export">
                <h2>Export Process</h2>
                <p>When exporting finish colors, the system generates a CSV file with all taxonomy terms and their ACF field values.</p>

                <h3>Export Features</h3>
                <ul class="file-tree">
                    <li class="tree-item"><span class="folder">📋</span> UTF-8 encoding with BOM (Excel compatible)</li>
                    <li class="tree-item"><span class="folder">📋</span> All finish colors included (including those not assigned to products)</li>
                    <li class="tree-item"><span class="folder">📋</span> Color images exported as full URLs</li>
                    <li class="tree-item"><span class="folder">📋</span> Sorted alphabetically by color name</li>
                    <li class="tree-item"><span class="folder">📋</span> Filename: <code>finish-color-taxonomy-export-YYYY-MM-DD.csv</code></li>
                </ul>
            </section>

            <section id="color-checklist">
                <h2>Pre-Import Checklist</h2>
                <p style="margin-bottom: 1.5rem;">Verify these items before importing your finish color CSV:</p>

                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">CSV file is UTF-8 encoded</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">First row contains column headers (Color Code, Color, Description, Color Image)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Every row has a Color value (required)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Color Code values are unique across all colors</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Color Image URLs are publicly accessible (if using URLs)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Column count matches in all rows (4 columns)</div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tab: Family -->
        <div class="tab-content" id="tab-family">
            <section id="family-overview">
                <h2>Product Family Import/Export</h2>
                <p>This section handles the hierarchical <code>product-family</code> taxonomy with <strong>4 levels of hierarchy</strong> and multiple ACF fields for images, metadata, and feature relationships.</p>

                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Key Point:</strong> Product Family uses a 4-level hierarchy with UIDs for reliable matching during import/export operations.</p>
                    </div>
                </div>
            </section>

            <section id="family-hierarchy">
                <h2>Hierarchy Structure</h2>
                <p>The Product Family taxonomy follows a strict 4-level hierarchy:</p>

                <div class="hierarchy-box">
                    <div class="inner-box">
                        <div class="box-header">Product Family Levels</div>
                        <div class="box-content">
                            <!-- Level 0 -->
                            <div class="tree-line">
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span><strong>Level 0:</strong> Main Category (e.g., Floodlights, Indoor)</span>
                            </div>

                            <!-- Level 1 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span><strong>Level 1:</strong> Family (e.g., Qubo, Linear)</span>
                            </div>

                            <!-- Level 2 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit" style="border-left: none;"></div>
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span><strong>Level 2:</strong> Sub Family (e.g., Qubo Mini, Linear Pro)</span>
                            </div>

                            <!-- Level 3 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit" style="border-left: none;"></div>
                                    <div class="indent-unit" style="border-left: none;"></div>
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span><strong>Level 3:</strong> Sub Sub Family (e.g., Qubo Mini 50W, Linear Pro RGB)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="family-columns">
                <h2>CSV Column Reference</h2>
                <p>The Product Family CSV uses <strong>18 primary columns</strong>:</p>

                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Applies To</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>Level 1</td>
                                <td>Unique identifier for Family level (auto-generated if empty)</td>
                            </tr>
                            <tr>
                                <td><code>Sub Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>Level 2</td>
                                <td>Unique identifier for Sub Family level</td>
                            </tr>
                            <tr>
                                <td><code>Sub Sub Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>Level 3</td>
                                <td>Unique identifier for Sub Sub Family level</td>
                            </tr>
                            <tr>
                                <td><code>Family Code</code></td>
                                <td><code>family_code</code></td>
                                <td>Level 1</td>
                                <td>Short code for Family (e.g., QBO, LNR)</td>
                            </tr>
                            <tr>
                                <td><code>Sub Family Code</code></td>
                                <td><code>family_code</code></td>
                                <td>Level 2</td>
                                <td>Short code for Sub Family</td>
                            </tr>
                            <tr>
                                <td><code>Main Category</code></td>
                                <td>Term Name</td>
                                <td>Level 0</td>
                                <td><strong>Required.</strong> Top-level category name</td>
                            </tr>
                            <tr>
                                <td><code>Family</code></td>
                                <td>Term Name</td>
                                <td>Level 1</td>
                                <td>Family name (child of Main Category)</td>
                            </tr>
                            <tr>
                                <td><code>Sub Family</code></td>
                                <td>Term Name</td>
                                <td>Level 2</td>
                                <td>Sub Family name (child of Family)</td>
                            </tr>
                            <tr>
                                <td><code>Sub Sub Family</code></td>
                                <td>Term Name</td>
                                <td>Level 3</td>
                                <td>Sub Sub Family name (child of Sub Family)</td>
                            </tr>
                            <tr>
                                <td><code>Description</code></td>
                                <td>Term Description</td>
                                <td>Level 1+</td>
                                <td>Term description (only saved for Level 1 and above)</td>
                            </tr>
                            <tr>
                                <td><code>Featured Subfamily</code></td>
                                <td><code>pf_fet_img</code></td>
                                <td>Level 1+</td>
                                <td>Featured image URL or attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Technical Drawing</code></td>
                                <td><code>pf_subfam_tech_drawing</code></td>
                                <td>Level 1+</td>
                                <td>Technical drawing image URL or attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Gallery 1-4</code></td>
                                <td><code>prod_gallery_1-4</code></td>
                                <td>Level 1+</td>
                                <td>Gallery images (4 separate columns)</td>
                            </tr>
                            <tr>
                                <td><code>Designer</code></td>
                                <td><code>pf_designed_by</code></td>
                                <td>Level 1+</td>
                                <td>Designer name</td>
                            </tr>
                            <tr>
                                <td><code>Family Features</code></td>
                                <td><code>tax_sub_family_features</code></td>
                                <td>Level 1+</td>
                                <td>Comma-separated feature slugs from 'features' taxonomy</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="family-example">
                <h2>CSV Example Format</h2>
                <p>Example showing the hierarchical structure with all 4 levels:</p>

                <div class="table-scroll">
                    <table class="csv-example">
                        <thead>
                            <tr>
                                <th>Family UID</th>
                                <th>Sub Family UID</th>
                                <th>Sub Sub Family UID</th>
                                <th>Family Code</th>
                                <th>Sub Family Code</th>
                                <th>Main Category</th>
                                <th>Family</th>
                                <th>Sub Family</th>
                                <th>Sub Sub Family</th>
                                <th>Description</th>
                                <th>Featured Subfamily</th>
                                <th>Technical Drawing</th>
                                <th>Gallery 1</th>
                                <th>Gallery 2</th>
                                <th>Gallery 3</th>
                                <th>Gallery 4</th>
                                <th>Designer</th>
                                <th>Family Features</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Floodlights</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>PF-001</td>
                                <td></td>
                                <td></td>
                                <td>QBO</td>
                                <td></td>
                                <td>Floodlights</td>
                                <td>Qubo</td>
                                <td></td>
                                <td></td>
                                <td>Premium floodlight series</td>
                                <td>https://example.com/qubo.jpg</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Studio XYZ</td>
                                <td>ip65,dali</td>
                            </tr>
                            <tr>
                                <td>PF-001</td>
                                <td>PF-001-A</td>
                                <td></td>
                                <td>QBO</td>
                                <td>QBO-M</td>
                                <td>Floodlights</td>
                                <td>Qubo</td>
                                <td>Qubo Mini</td>
                                <td></td>
                                <td>Compact version for tight spaces</td>
                                <td>https://example.com/qubo-mini.jpg</td>
                                <td>https://example.com/qubo-mini-tech.svg</td>
                                <td>https://example.com/g1.jpg</td>
                                <td>https://example.com/g2.jpg</td>
                                <td></td>
                                <td></td>
                                <td>Studio XYZ</td>
                                <td>ip65,ip66,dali</td>
                            </tr>
                            <tr>
                                <td>PF-001</td>
                                <td>PF-001-A</td>
                                <td>PF-001-A-1</td>
                                <td>QBO</td>
                                <td>QBO-M</td>
                                <td>Floodlights</td>
                                <td>Qubo</td>
                                <td>Qubo Mini</td>
                                <td>Qubo Mini 50w</td>
                                <td>50 watt variant</td>
                                <td>https://example.com/qubo-mini-50.jpg</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ip65</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="family-matching">
                <h2>Import Matching Strategy</h2>
                <p>The import uses different matching strategies based on hierarchy level:</p>

                <div class="matching-grid">
                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-blue">Level 0</span>
                            Main Category
                        </div>
                        <div class="matching-card-body">
                            <p><strong>Match by:</strong> Name Only</p>
                            <ul>
                                <li>Always searches by exact term name</li>
                                <li>Creates new term if not found</li>
                                <li>Auto-generates UID for new terms</li>
                            </ul>
                        </div>
                    </div>

                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-green">Level 1-3</span>
                            Family / Sub Family / Sub Sub Family
                        </div>
                        <div class="matching-card-body">
                            <p><strong>Match by:</strong> Hybrid (UID → Name+Parent)</p>
                            <ol>
                                <li><strong>UID Match:</strong> If UID provided, search by <code>tax_family__uid</code></li>
                                <li><strong>Fallback:</strong> If no UID, search by Name + Parent combination</li>
                                <li><strong>Create:</strong> If not found, create new term with auto-generated UID</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <h3>Term Name Capitalization</h3>
                <p>All term names are automatically converted to <strong>Title Case</strong> during import:</p>
                <ul>
                    <li><code>MINI</code> → <code>Mini</code></li>
                    <li><code>qubo pro</code> → <code>Qubo Pro</code></li>
                    <li><code>FLOODLIGHTS</code> → <code>Floodlights</code></li>
                </ul>
            </section>

            <section id="family-images">
                <h2>Image Handling</h2>
                <p>Images can be provided in two formats:</p>

                <div class="matching-grid">
                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-blue">URL</span>
                            Image URL
                        </div>
                        <div class="matching-card-body">
                            <p>Provide a full URL to download:</p>
                            <code>https://example.com/image.jpg</code>
                            <p style="margin-top: 0.5rem;"><small>System will download and create attachment</small></p>
                        </div>
                    </div>

                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-green">ID</span>
                            Attachment ID
                        </div>
                        <div class="matching-card-body">
                            <p>Provide existing attachment ID:</p>
                            <code>1234</code>
                            <p style="margin-top: 0.5rem;"><small>Uses existing media library attachment</small></p>
                        </div>
                    </div>
                </div>

                <h3>Image Fields by Level</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Level 0</th>
                            <th>Level 1</th>
                            <th>Level 2</th>
                            <th>Level 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Featured Subfamily</td>
                            <td>-</td>
                            <td>Yes</td>
                            <td>Yes</td>
                            <td>Yes</td>
                        </tr>
                        <tr>
                            <td>Technical Drawing</td>
                            <td>-</td>
                            <td>Yes</td>
                            <td>Yes</td>
                            <td>Yes</td>
                        </tr>
                        <tr>
                            <td>Gallery 1-4</td>
                            <td>-</td>
                            <td>Yes</td>
                            <td>Yes</td>
                            <td>Yes</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section id="family-features">
                <h2>Family Features Field</h2>
                <p>The <code>Family Features</code> column links to the <code>features</code> taxonomy using comma-separated slugs:</p>

                <div class="callout callout-warning">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Important:</strong> Feature terms must already exist in the <code>features</code> taxonomy. The import will skip any slugs that don't match existing terms.</p>
                    </div>
                </div>

                <h3>Format Example</h3>
                <p>Use comma-separated feature slugs (not names):</p>
                <code>ip65,ip66,dali,ik10</code>
            </section>

            <section id="family-checklist">
                <h2>Pre-Import Checklist</h2>
                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>CSV saved as UTF-8 with BOM</strong>
                            <p>Required for proper Excel compatibility and special characters</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Main Category column is filled</strong>
                            <p>Every row must have a Main Category value</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Hierarchy is consistent</strong>
                            <p>Sub Family rows must include their parent Family name</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>UIDs preserved from export</strong>
                            <p>Keep existing UIDs to update rather than create duplicates</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Feature slugs exist</strong>
                            <p>Verify feature slugs in Family Features column exist in the features taxonomy</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Image URLs accessible</strong>
                            <p>External image URLs must be publicly accessible for download</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tab: Products -->
        <div class="tab-content" id="tab-products">
            <section id="prod-overview">
                <h2>Products Import/Export</h2>
                <p>This section handles the <code>product</code> custom post type with extensive ACF fields, taxonomy assignments, and related product relationships.</p>

                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Key Point:</strong> Products use <code>SKU</code> as the primary unique identifier. If SKU exists, the product is updated; otherwise, a new product is created.</p>
                    </div>
                </div>
            </section>

            <section id="prod-columns">
                <h2>CSV Column Reference</h2>
                <p>The Products CSV has <strong>core columns</strong> plus <strong>ACF field columns</strong>:</p>

                <h3>Core Columns (Required)</h3>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>SKU</code></td>
                                <td><code>prod__sku</code></td>
                                <td><strong>Primary identifier.</strong> Used to match existing products for updates</td>
                            </tr>
                            <tr>
                                <td><code>Product Title</code></td>
                                <td>Post Title</td>
                                <td><strong>Required for new products.</strong> Optional when updating existing by SKU</td>
                            </tr>
                            <tr>
                                <td><code>Main Category</code></td>
                                <td>Taxonomy Level 0</td>
                                <td>Top-level product-family category</td>
                            </tr>
                            <tr>
                                <td><code>Family</code></td>
                                <td>Taxonomy Level 1</td>
                                <td>Product Family name</td>
                            </tr>
                            <tr>
                                <td><code>Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>UID for Family level (preferred for matching)</td>
                            </tr>
                            <tr>
                                <td><code>Sub Family</code></td>
                                <td>Taxonomy Level 2</td>
                                <td>Sub Family name</td>
                            </tr>
                            <tr>
                                <td><code>Sub Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>UID for Sub Family level</td>
                            </tr>
                            <tr>
                                <td><code>Sub Sub Family</code></td>
                                <td>Taxonomy Level 3</td>
                                <td>Sub Sub Family name</td>
                            </tr>
                            <tr>
                                <td><code>Sub Sub Family UID</code></td>
                                <td><code>tax_family__uid</code></td>
                                <td>UID for Sub Sub Family level</td>
                            </tr>
                            <tr>
                                <td><code>Related Family</code></td>
                                <td><code>prod_related_fam__terms</code></td>
                                <td>Comma-separated Family UIDs for related families</td>
                            </tr>
                            <tr>
                                <td><code>Status</code></td>
                                <td>Post Status</td>
                                <td>publish, draft, pending, private</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Product Specification Fields</h3>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Wattage</code></td>
                                <td><code>pro_wattage</code></td>
                                <td>text</td>
                                <td>Product wattage</td>
                            </tr>
                            <tr>
                                <td><code>CCT</code></td>
                                <td><code>pro_cct</code></td>
                                <td>text</td>
                                <td>Color temperature</td>
                            </tr>
                            <tr>
                                <td><code>Beam Angle</code></td>
                                <td><code>pro_beam_angle</code></td>
                                <td>text</td>
                                <td>Beam angle in degrees</td>
                            </tr>
                            <tr>
                                <td><code>Lumens</code></td>
                                <td><code>pro_lumens</code></td>
                                <td>text</td>
                                <td>Light output</td>
                            </tr>
                            <tr>
                                <td><code>Finish Color</code></td>
                                <td><code>pro_finish_color</code></td>
                                <td>taxonomy</td>
                                <td>Term name from <code>finish-color</code> taxonomy</td>
                            </tr>
                            <tr>
                                <td><code>Dimming</code></td>
                                <td><code>pro_dimming</code></td>
                                <td>taxonomy</td>
                                <td>Term name from <code>features</code> taxonomy</td>
                            </tr>
                            <tr>
                                <td><code>IP Rating</code></td>
                                <td><code>pro_iprating</code></td>
                                <td>text</td>
                                <td>IP protection rating</td>
                            </tr>
                            <tr>
                                <td><code>IK Rating</code></td>
                                <td><code>pro_ikrating</code></td>
                                <td>text</td>
                                <td>IK impact rating</td>
                            </tr>
                            <tr>
                                <td><code>Material</code></td>
                                <td><code>pro_material</code></td>
                                <td>text</td>
                                <td>Product material</td>
                            </tr>
                            <tr>
                                <td><code>Included Accessories</code></td>
                                <td><code>prod_acc_in__terms</code></td>
                                <td>taxonomy</td>
                                <td>Comma-separated terms from <code>accessories</code> (name or code)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Gallery & Media Fields</h3>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Format</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Measurement Image</code></td>
                                <td><code>pro_mesr_img</code></td>
                                <td>URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Gallery 5-20</code></td>
                                <td><code>prod_gallery_5</code> to <code>prod_gallery_20</code></td>
                                <td>URL or Attachment ID (16 fields)</td>
                            </tr>
                            <tr>
                                <td><code>LTD Files</code></td>
                                <td><code>pro_dwnld_ltd_files</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Instructions</code></td>
                                <td><code>pro_dwnld_instructions</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Revit</code></td>
                                <td><code>pro_dwnld_revit</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>3D BIM</code></td>
                                <td><code>pro_dwnld_3dbim</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Photometric</code></td>
                                <td><code>pro_dwnld_photometric</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                            <tr>
                                <td><code>Video</code></td>
                                <td><code>pro_dwnld_provideo</code></td>
                                <td>File URL or Attachment ID</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Relationship Fields</h3>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>ACF Field</th>
                                <th>Format</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Also Available</code></td>
                                <td><code>pd_alavlbl_select_product</code></td>
                                <td>JSON array of product titles: <code>["Product A", "Product B"]</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="prod-example">
                <h2>CSV Example Format</h2>
                <p>Simplified example showing core columns:</p>

                <div class="table-scroll">
                    <table class="csv-example">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product Title</th>
                                <th>Main Category</th>
                                <th>Family</th>
                                <th>Family UID</th>
                                <th>Sub Family</th>
                                <th>Sub Family UID</th>
                                <th>Wattage</th>
                                <th>CCT</th>
                                <th>Finish Color</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SKU-001</td>
                                <td>Qubo Mini 50W 3000K</td>
                                <td>Floodlights</td>
                                <td>Qubo</td>
                                <td>PF-001</td>
                                <td>Qubo Mini</td>
                                <td>PF-001-A</td>
                                <td>50W</td>
                                <td>3000K</td>
                                <td>RAL 7016</td>
                                <td>publish</td>
                            </tr>
                            <tr>
                                <td>SKU-002</td>
                                <td>Qubo Mini 50W 4000K</td>
                                <td>Floodlights</td>
                                <td>Qubo</td>
                                <td>PF-001</td>
                                <td>Qubo Mini</td>
                                <td>PF-001-A</td>
                                <td>50W</td>
                                <td>4000K</td>
                                <td>RAL 9006</td>
                                <td>publish</td>
                            </tr>
                            <tr>
                                <td>SKU-003</td>
                                <td>Linear Pro RGB 100W</td>
                                <td>Indoor</td>
                                <td>Linear</td>
                                <td>PF-002</td>
                                <td>Linear Pro</td>
                                <td>PF-002-A</td>
                                <td>100W</td>
                                <td>RGB</td>
                                <td>RAL 9005 BLACK</td>
                                <td>draft</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="prod-matching">
                <h2>Import Matching Strategy</h2>

                <h3>Product Matching</h3>
                <div class="matching-grid">
                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-blue">Step 1</span>
                            SKU Lookup
                        </div>
                        <div class="matching-card-body">
                            <p>Search for existing product by <code>prod__sku</code> field</p>
                            <ul>
                                <li><strong>Found:</strong> Update existing product</li>
                                <li><strong>Not Found:</strong> Create new product</li>
                            </ul>
                        </div>
                    </div>

                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-green">Step 2</span>
                            Title Requirement
                        </div>
                        <div class="matching-card-body">
                            <p>Product Title validation:</p>
                            <ul>
                                <li><strong>New Product:</strong> Title is required</li>
                                <li><strong>Existing Product:</strong> Uses existing title if not provided</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h3>Family Assignment</h3>
                <p>The system uses <strong>deepest UID first</strong> strategy:</p>

                <ol>
                    <li><strong>Check Sub Sub Family UID</strong> - If provided and found, assign directly</li>
                    <li><strong>Check Sub Family UID</strong> - If Sub Sub Family UID not found</li>
                    <li><strong>Check Family UID</strong> - If Sub Family UID not found</li>
                    <li><strong>Fallback to Names</strong> - If no UIDs found, match by name level-by-level</li>
                </ol>

                <div class="callout callout-warning">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Important:</strong> Products are always assigned to the <strong>deepest</strong> (most specific) family level in their hierarchy.</p>
                    </div>
                </div>
            </section>

            <section id="prod-taxonomy">
                <h2>Taxonomy Field Handling</h2>
                <p>Taxonomy fields support multiple input formats:</p>

                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Taxonomy</th>
                                <th>Input Format</th>
                                <th>Auto-Create</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Finish Color</code></td>
                                <td><code>finish-color</code></td>
                                <td>Term name (e.g., "RAL 7016")</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td><code>Dimming</code></td>
                                <td><code>features</code></td>
                                <td>Term name (e.g., "DALI")</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td><code>Included Accessories</code></td>
                                <td><code>accessories</code></td>
                                <td>Name, slug, or <code>tax_acc__code</code></td>
                                <td>No</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Multiple Values</h3>
                <p>Use comma-separated values for multiple terms:</p>
                <code>DALI, 0-10V, Bluetooth</code>
            </section>

            <section id="prod-media">
                <h2>Media & File Handling</h2>

                <h3>Image Fields (Gallery 5-20)</h3>
                <div class="matching-grid">
                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-blue">URL</span>
                            Image URL
                        </div>
                        <div class="matching-card-body">
                            <p>Provide a full URL:</p>
                            <code>https://example.com/product.jpg</code>
                            <p style="margin-top: 0.5rem;"><small>Downloads and creates attachment</small></p>
                        </div>
                    </div>

                    <div class="matching-card">
                        <div class="matching-card-header">
                            <span class="badge badge-green">ID</span>
                            Attachment ID
                        </div>
                        <div class="matching-card-body">
                            <p>Provide existing attachment ID:</p>
                            <code>1234</code>
                            <p style="margin-top: 0.5rem;"><small>Uses existing media library item</small></p>
                        </div>
                    </div>
                </div>

                <h3>File Fields (Downloads)</h3>
                <p>Download files support both URLs and attachment IDs:</p>
                <ul>
                    <li>LTD Files, Instructions, Revit, 3D BIM, Photometric, Video</li>
                    <li>URLs are downloaded and stored in media library</li>
                    <li>Existing files are reused by filename match</li>
                </ul>
            </section>

            <section id="prod-relationship">
                <h2>Relationship Fields</h2>
                <p>The <code>Also Available</code> field links to other products using titles:</p>

                <h3>Format</h3>
                <p>JSON array of product titles:</p>
                <code>["Qubo Mini 50W 4000K", "Linear Pro RGB 100W"]</code>

                <div class="callout callout-warning">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <p><strong>Important:</strong> Referenced products must exist before import. Titles must match exactly.</p>
                    </div>
                </div>

                <h3>Related Family</h3>
                <p>Comma-separated Family UIDs:</p>
                <code>PF-001,PF-002,PF-003</code>
            </section>

            <section id="prod-checklist">
                <h2>Pre-Import Checklist</h2>
                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>CSV saved as UTF-8 with BOM</strong>
                            <p>Required for proper Excel compatibility and special characters</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>SKU column has unique values</strong>
                            <p>Each product must have a unique SKU for proper matching</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Product Title filled for new products</strong>
                            <p>Title is required when creating new products (optional for updates)</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Family hierarchy exists</strong>
                            <p>Import Family taxonomy before importing products</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Accessories taxonomy terms exist</strong>
                            <p>Accessories are NOT auto-created - import them first</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Related products exist for relationship fields</strong>
                            <p>Products referenced in "Also Available" must exist first</p>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="checklist-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="checklist-content">
                            <strong>Image URLs are publicly accessible</strong>
                            <p>External image URLs must be downloadable</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="prod-order">
                <h2>Recommended Import Order</h2>
                <p>For a clean import, follow this order:</p>

                <ol>
                    <li><strong>Accessories</strong> - Import taxonomy terms first (not auto-created)</li>
                    <li><strong>Features</strong> - Import dimming/feature terms</li>
                    <li><strong>Finish Color</strong> - Import color terms</li>
                    <li><strong>Family</strong> - Import full hierarchy with UIDs</li>
                    <li><strong>Products</strong> - Import products with all references</li>
                </ol>
            </section>
        </div>

        <!-- Tab: Image Assign -->
        <div class="tab-content" id="tab-images">
            <section id="img-upload">
                <h2>Upload Location</h2>
                <div class="callout">
                    <div class="callout-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </div>
                    <div>
                        <p>All images must be uploaded to the following directory on the server:</p>
                        <p style="margin-top: 0.75rem;"><code>wp-content/uploads/puk-import/</code></p>
                    </div>
                </div>
            </section>

            <section id="img-family">
                <h2>1. Product Family Images</h2>
                <p>Product families utilize a <strong>hierarchical folder structure</strong> that precisely mirrors the
                    taxonomy hierarchy.</p>

                <h3>Hierarchy Overview</h3>
                <div class="hierarchy-box">
                    <div class="inner-box">
                        <div class="box-header">puk-import/</div>
                        <div class="box-content">
                            <!-- Level 0 -->
                            <div class="tree-line">
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span>Main Category (e.g., Floodlights)</span>
                            </div>

                            <!-- Level 1 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span>Family Name (e.g., Qubo)</span>
                            </div>

                            <!-- Level 2 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit" style="border-left: none;"></div>
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span>Sub-Family Name (e.g., Micro)</span>
                                <span class="highlight-green">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="20" y1="12" x2="4" y2="12" />
                                        <polyline points="12 19 20 12 12 5" />
                                    </svg>
                                    IMAGES HERE
                                </span>
                            </div>

                            <!-- Files -->
                            <ul class="file-list" style="padding-left: 7rem;">
                                <li>main.jpg, hover.jpg, tech.webp</li>
                                <li>gallery-1.jpg to gallery-4.jpg</li>
                            </ul>

                            <!-- Level 3 -->
                            <div class="tree-line">
                                <div class="tree-indent">
                                    <div class="indent-unit" style="border-left: none;"></div>
                                    <div class="indent-unit"></div>
                                    <div class="indent-unit last-child"></div>
                                </div>
                                <span class="folder-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span>Sub-Sub-Family UID (e.g., 103)</span>
                                <span class="highlight-green">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="20" y1="12" x2="4" y2="12" />
                                        <polyline points="12 19 20 12 12 5" />
                                    </svg>
                                    IMAGES HERE
                                </span>
                            </div>
                            <ul class="file-list" style="padding-left: 9.5rem;">
                                <li>gallery-5.jpg to gallery-15.jpg</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h3>Hierarchy Levels</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70px;">Level</th>
                                <th>Folder Name</th>
                                <th>Example</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>0</strong></td>
                                <td>Main Category</td>
                                <td><code>Floodlights/</code></td>
                                <td>Top-level product category</td>
                            </tr>
                            <tr>
                                <td><strong>1</strong></td>
                                <td>Family</td>
                                <td><code>Qubo/</code></td>
                                <td>Product family name</td>
                            </tr>
                            <tr>
                                <td><strong>2</strong></td>
                                <td>Sub-Family</td>
                                <td><code>Micro/</code></td>
                                <td>Sub-family name</td>
                            </tr>
                            <tr>
                                <td><strong>3</strong></td>
                                <td>UID</td>
                                <td><code>103/</code></td>
                                <td>Sub-sub-family unique identifier</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Real Example</h3>
                <ul class="file-tree">
                    <li class="tree-item"><span class="folder">📁</span> puk-import/</li>
                    <li class="tree-item" style="padding-left: 1.25rem;"><span class="folder">📁</span> Floodlights/</li>
                    <li class="tree-item" style="padding-left: 2.5rem;"><span class="folder">📁</span> Qubo/</li>
                    <li class="tree-item" style="padding-left: 3.75rem;"><span class="folder">📁</span> Micro/</li>
                    <li class="tree-item" style="padding-left: 5rem;"><span class="image">🖼</span> main.webp <span
                            class="arrow">→</span> <span class="comment">Assigned to Sub-Family "Micro"</span></li>
                    <li class="tree-item" style="padding-left: 5rem;"><span class="image">🖼</span> hover.webp <span
                            class="arrow">→</span> <span class="comment">Assigned to Sub-Family "Micro"</span></li>
                    <li class="tree-item" style="padding-left: 5rem;"><span class="image">🖼</span> tech.webp <span
                            class="arrow">→</span> <span class="comment">Assigned to Sub-Family "Micro"</span></li>
                    <li class="tree-item" style="padding-left: 5rem;"><span class="image">🖼</span> gallery-1.jpg <span
                            class="arrow">→</span> <span class="comment">Assigned to Sub-Family "Micro"</span></li>
                    <li class="tree-item" style="padding-left: 5rem;"><span class="folder">📁</span> 103/</li>
                    <li class="tree-item" style="padding-left: 6.25rem;"><span class="image">🖼</span> gallery-5.jpg <span
                            class="arrow">→</span> <span class="comment">Assigned to ALL products (UID: 103)</span></li>
                    <li class="tree-item" style="padding-left: 6.25rem;"><span class="image">🖼</span> gallery-6.jpg <span
                            class="arrow">→</span> <span class="comment">Assigned to ALL products (UID: 103)</span></li>
                </ul>

                <h3>Image Types Reference</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Purpose</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>main.jpg</code></td>
                                <td><strong>Main Image</strong></td>
                                <td>Primary featured image</td>
                            </tr>
                            <tr>
                                <td><code>hover.jpg</code></td>
                                <td><strong>Hover Image</strong></td>
                                <td>Displayed on mouse hover</td>
                            </tr>
                            <tr>
                                <td><code>tech.jpg</code></td>
                                <td><strong>Technical Drawing</strong></td>
                                <td>Specifications & dimensions</td>
                            </tr>
                            <tr>
                                <td><code>gallery.jpg</code></td>
                                <td><strong>Gallery</strong></td>
                                <td>Appends to gallery</td>
                            </tr>
                            <tr>
                                <td><code>gallery-1.jpg</code></td>
                                <td><strong>Gallery 1-4</strong></td>
                                <td>Gallery slots 1-4</td>
                            </tr>
                            <tr>
                                <td><code>gallery-5.jpg</code></td>
                                <td><strong>Gallery 5-15</strong></td>
                                <td>Extended gallery (UID folder)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="img-accessories">
                <h2>2. Accessories Images</h2>
                <p>Accessories utilize a <strong>flat folder structure</strong> with filename matching.</p>

                <ul class="file-tree" style="margin: 1.5rem 0;">
                    <li class="tree-item"><span class="folder">📁</span> puk-import/</li>
                    <li class="tree-item" style="padding-left: 1.25rem;"><span class="folder">📁</span> Accessories/</li>
                    <li class="tree-item" style="padding-left: 2.5rem;"><span class="image">🖼</span> AC044.jpg <span
                            class="arrow">→</span> <span class="comment">Assigned to Accessory code "AC044"</span></li>
                    <li class="tree-item" style="padding-left: 2.5rem;"><span class="image">🖼</span> AC075.png <span
                            class="arrow">→</span> <span class="comment">Assigned to Accessory code "AC075"</span></li>
                </ul>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Rule</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Filename = Code</strong></td>
                                <td>Filename (without extension) must match accessory code exactly</td>
                            </tr>
                            <tr>
                                <td><strong>One Image</strong></td>
                                <td>Only one image per accessory (featured image)</td>
                            </tr>
                            <tr>
                                <td><strong>Example</strong></td>
                                <td>Accessory code <code>AC044</code> &rarr; filename <code>AC044.jpg</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="img-formats">
                <h2>Supported Image Formats</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Format</th>
                                <th>Extension</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>JPEG</strong></td>
                                <td><code>.jpg</code> <code>.jpeg</code></td>
                                <td>Good for photos</td>
                            </tr>
                            <tr>
                                <td><strong>PNG</strong></td>
                                <td><code>.png</code></td>
                                <td>Good for transparency</td>
                            </tr>
                            <tr>
                                <td><strong>WebP</strong></td>
                                <td><code>.webp</code></td>
                                <td><strong>Recommended</strong> - Best quality/size ratio</td>
                            </tr>
                            <tr>
                                <td><strong>GIF</strong></td>
                                <td><code>.gif</code></td>
                                <td>For animated images</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="img-checklist">
                <h2>Pre-Upload Checklist</h2>
                <p style="margin-bottom: 1.5rem;">Before uploading, please verify the following items.</p>

                <div class="checklist">
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Images are
                            in supported format (jpg, png, webp, gif)</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Main
                            Category folder names match system values</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Family and
                            Sub-Family folder names match taxonomy terms</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">
                            Sub-Sub-Family UID folders match the UID values</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Accessory
                            filenames match accessory codes exactly</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">No spaces
                            in any filenames</div>
                    </div>
                    <div class="checklist-item">
                        <div class="checkbox" onclick="this.classList.toggle('checked')"></div>
                        <div class="item-text" onclick="this.previousElementSibling.classList.toggle('checked')">Image
                            filenames use correct suffixes (main, hover, etc.)</div>
                    </div>
                </div>
            </section>
        </div>

        <div class="doc-footer">
            <p>Document Version: 2.0</p>
            <p>&copy; 2026 PUK Lighting. All rights reserved.</p>
        </div>
    </main>

    <script>
        // Tab Navigation
        const tabLinks = document.querySelectorAll('.tab-nav-link, .mobile-tab');
        const tabContents = document.querySelectorAll('.tab-content');
        const sectionNav = document.getElementById('sectionNav');

        // Section definitions for each tab
        const tabSections = {
            'accessories': [
                { id: 'acc-overview', label: 'Overview' },
                { id: 'acc-columns', label: 'CSV Columns' },
                { id: 'acc-example', label: 'CSV Example' },
                { id: 'acc-import-logic', label: 'Import Logic' },
                { id: 'acc-export', label: 'Export Process' },
                { id: 'acc-checklist', label: 'Pre-Import Checklist' }
            ],
            'features': [
                { id: 'feat-overview', label: 'Overview' },
                { id: 'feat-columns', label: 'CSV Columns' },
                { id: 'feat-example', label: 'CSV Example' },
                { id: 'feat-import-logic', label: 'Import Logic' },
                { id: 'feat-types', label: 'Common Types' },
                { id: 'feat-export', label: 'Export Process' },
                { id: 'feat-checklist', label: 'Pre-Import Checklist' }
            ],
            'finish-color': [
                { id: 'color-overview', label: 'Overview' },
                { id: 'color-columns', label: 'CSV Columns' },
                { id: 'color-example', label: 'CSV Example' },
                { id: 'color-import-logic', label: 'Import Logic' },
                { id: 'color-export', label: 'Export Process' },
                { id: 'color-checklist', label: 'Pre-Import Checklist' }
            ],
            'family': [
                { id: 'family-overview', label: 'Overview' },
                { id: 'family-hierarchy', label: 'Hierarchy Structure' },
                { id: 'family-columns', label: 'CSV Columns' },
                { id: 'family-example', label: 'CSV Example' },
                { id: 'family-matching', label: 'Matching Strategy' },
                { id: 'family-images', label: 'Image Handling' },
                { id: 'family-features', label: 'Family Features' },
                { id: 'family-checklist', label: 'Pre-Import Checklist' }
            ],
            'products': [
                { id: 'prod-overview', label: 'Overview' },
                { id: 'prod-columns', label: 'CSV Columns' },
                { id: 'prod-example', label: 'CSV Example' },
                { id: 'prod-matching', label: 'Matching Strategy' },
                { id: 'prod-taxonomy', label: 'Taxonomy Fields' },
                { id: 'prod-media', label: 'Media Handling' },
                { id: 'prod-relationship', label: 'Relationship Fields' },
                { id: 'prod-checklist', label: 'Pre-Import Checklist' },
                { id: 'prod-order', label: 'Import Order' }
            ],
            'images': [
                { id: 'img-upload', label: 'Upload Location' },
                { id: 'img-family', label: 'Product Family Images' },
                { id: 'img-accessories', label: 'Accessories Images' },
                { id: 'img-formats', label: 'Supported Formats' },
                { id: 'img-checklist', label: 'Pre-Upload Checklist' }
            ]
        };

        function updateSectionNav(tabId) {
            const sections = tabSections[tabId] || [];
            if (sections.length === 0) {
                sectionNav.style.display = 'none';
                return;
            }

            sectionNav.style.display = 'block';
            sectionNav.innerHTML = `
                <li class="section-nav-title">On this page</li>
                ${sections.map(s => `
                    <li class="section-nav-item">
                        <a class="section-nav-link" href="#${s.id}">${s.label}</a>
                    </li>
                `).join('')}
            `;

            // Add click handlers for smooth scroll
            sectionNav.querySelectorAll('.section-nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        }

        function switchTab(tabId) {
            // Update tab links
            tabLinks.forEach(link => {
                link.classList.toggle('active', link.dataset.tab === tabId);
            });

            // Update tab contents
            tabContents.forEach(content => {
                content.classList.toggle('active', content.id === `tab-${tabId}`);
            });

            // Update section navigation
            updateSectionNav(tabId);

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Add click handlers to tab links
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                switchTab(this.dataset.tab);
            });
        });

        // Initialize section nav for default tab
        updateSectionNav('accessories');

        // Section highlight on scroll (for tabs with sections)
        window.addEventListener('scroll', () => {
            const activeTab = document.querySelector('.tab-content.active');
            if (activeTab) {
                const sections = activeTab.querySelectorAll('section[id]');
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (pageYOffset >= sectionTop - 150) {
                        current = section.getAttribute('id');
                    }
                });

                sectionNav.querySelectorAll('.section-nav-link').forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
                });
            }
        });
    </script>
</body>

</html>
