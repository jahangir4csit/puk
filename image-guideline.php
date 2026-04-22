<?php
/*
Template Name: Image Guideline
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUK Image Import Guidelines</title>
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
        }

        .logo-container {
            margin-bottom: 3rem;
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

        nav ul {
            list-style: none;
        }

        nav li {
            margin-bottom: 0.5rem;
        }

        nav a {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        nav a:hover,
        nav a.active {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent-color);
        }

        /* Main Content */
        main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 4rem 10% 4rem 5%;
            max-width: 1400px;
        }

        header {
            margin-bottom: 4rem;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
        }

        section {
            margin-bottom: 6rem;
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        h2::before {
            content: '';
            display: block;
            width: 4px;
            height: 1.5rem;
            background: var(--accent-color);
            border-radius: 4px;
        }

        h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            margin: 2.5rem 0 1.5rem;
            color: #fff;
        }

        /* Callout / Note */
        .callout {
            background: rgba(56, 189, 248, 0.05);
            border-left: 4px solid var(--accent-color);
            padding: 1.5rem;
            border-radius: 0 12px 12px 0;
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .callout-icon {
            color: var(--accent-color);
            flex-shrink: 0;
            margin-top: 0.2rem;
        }

        .callout code {
            background: rgba(56, 189, 248, 0.15);
            color: var(--accent-color);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
            font-size: 1rem;
        }

        /* Hierarchy Chart */
        .hierarchy-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 2.5rem;
            border: 1px solid #e2e8f0;
            font-family: 'Fira Code', monospace;
            font-size: 0.95rem;
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
            padding: 0.75rem;
            text-align: center;
            font-weight: 500;
        }

        .hierarchy-box .box-content {
            padding: 2rem;
        }

        .tree-line {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .tree-indent {
            display: flex;
            flex-shrink: 0;
        }

        .indent-unit {
            width: 2.5rem;
            height: 2.5rem;
            border-left: 1px solid #cbd5e1;
            position: relative;
        }

        .indent-unit.last-child::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 1.5rem;
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
            margin-left: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .file-list {
            list-style: none;
            padding-left: 3.5rem;
            color: #475569;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .file-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .file-list li::before {
            content: '•';
            color: #94a3b8;
        }

        .hierarchy-box::after {
            content: 'ASCII MAP';
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 0.7rem;
            color: var(--text-secondary);
            letter-spacing: 2px;
        }

        /* Tables */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin: 2rem 0;
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
            padding: 1.25rem 1.5rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--accent-color);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
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

        /* Folder Structure List */
        .file-tree {
            list-style: none;
            font-family: 'Fira Code', monospace;
            background: var(--code-bg);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .tree-item {
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .folder {
            color: #fbbf24;
        }

        .image {
            color: #10b981;
        }

        .arrow {
            color: #38bdf8;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .comment {
            color: #64748b;
            margin-left: auto;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
        }

        /* Checklist */
        .checklist {
            background: var(--container-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .checklist-item:last-child {
            border-bottom: none;
        }

        .checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid var(--accent-color);
            border-radius: 6px;
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
            content: '✓';
            color: var(--bg-color);
            font-weight: bold;
            font-size: 14px;
        }

        .item-text {
            cursor: pointer;
            flex: 1;
        }

        /* Footer */
        .doc-footer {
            margin-top: 8rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            aside {
                width: 80px;
                padding: 2rem 1rem;
                align-items: center;
            }

            .logo-text,
            .nav-label {
                display: none;
            }

            main {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            aside {
                display: none;
            }

            main {
                margin-left: 0;
                padding: 2rem;
            }

            h1 {
                font-size: 2.5rem;
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
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                </svg>
            </div>
            <span class="logo-text">PUK</span>
        </div>
        <nav>
            <ul>
                <li><a href="#upload" class="active"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg> <span class="nav-label">Upload Location</span></a></li>
                <li><a href="#family"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        </svg> <span class="nav-label">Product Families</span></a></li>
                <li><a href="#accessories"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <line x1="9" y1="3" x2="9" y2="21" />
                        </svg> <span class="nav-label">Accessories</span></a></li>
                <li><a href="#formats"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg> <span class="nav-label">Supported Formats</span></a></li>
                <li><a href="#checklist"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg> <span class="nav-label">Checklist</span></a></li>
            </ul>
        </nav>
    </aside>

    <main>
        <header>
            <h1>Image Import Guidelines</h1>
            <p class="subtitle">A comprehensive manual for organizing and uploading assets to the PUK ecosystem.</p>
        </header>

        <section id="upload">
            <h2>Upload Location</h2>
            <div class="callout">
                <div class="callout-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                </div>
                <div>
                    <p>All images must be uploaded to the following directory on the server:</p>
                    <p style="margin-top: 1rem;"><code>wp-content/uploads/puk-import/</code></p>
                </div>
            </div>
        </section>

        <section id="family">
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
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
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
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
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
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                </svg>
                            </span>
                            <span>Sub-Family Name (e.g., Micro)</span>
                            <span class="highlight-green">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="20" y1="12" x2="4" y2="12" />
                                    <polyline points="12 19 20 12 12 5" />
                                </svg>
                                IMAGES HERE
                            </span>
                        </div>

                        <!-- Files -->
                        <ul class="file-list" style="padding-left: 8.5rem;">
                            <li>main.jpg</li>
                            <li>hover.jpg</li>
                            <li>tech.webp</li>
                            <li>gallery.jpg</li>
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
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                </svg>
                            </span>
                            <span>Sub-Sub-Family UID (e.g., 103)</span>
                            <span class="highlight-green">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="20" y1="12" x2="4" y2="12" />
                                    <polyline points="12 19 20 12 12 5" />
                                </svg>
                                IMAGES HERE
                            </span>
                        </div>
                        <ul class="file-list" style="padding-left: 11.5rem;">
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
                            <th style="width: 80px;">Level</th>
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
                <li class="tree-item" style="padding-left: 1.5rem;"><span class="folder">📁</span> Floodlights/</li>
                <li class="tree-item" style="padding-left: 3rem;"><span class="folder">📁</span> Qubo/</li>
                <li class="tree-item" style="padding-left: 4.5rem;"><span class="folder">📁</span> Micro/</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> main.webp <span
                        class="arrow">→</span> Assigned to Sub-Family "Micro"</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> hover.webp <span
                        class="arrow">→</span> Assigned to Sub-Family "Micro"</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> tech.webp <span
                        class="arrow">→</span> Assigned to Sub-Family "Micro"</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> gallery-1.jpg <span
                        class="arrow">→</span> Assigned to Sub-Family "Micro"</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> gallery-2.jpg <span
                        class="arrow">→</span> Assigned to Sub-Family "Micro"</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="folder">📁</span> 103/</li>
                <li class="tree-item" style="padding-left: 7.5rem;"><span class="image">🖼</span> gallery-5.jpg <span
                        class="arrow">→</span> Assigned to ALL products (UID: 103)</li>
                <li class="tree-item" style="padding-left: 7.5rem;"><span class="image">🖼</span> gallery-6.jpg <span
                        class="arrow">→</span> Assigned to ALL products (UID: 103)</li>
                <li class="tree-item" style="padding-left: 7.5rem;"><span class="image">🖼</span> gallery-15.jpg <span
                        class="arrow">→</span> Assigned to ALL products (UID: 103)</li>

                <!-- Maxi -->
                <li class="tree-item" style="padding-left: 3rem; margin-top: 1rem;"><span class="folder">📁</span> Maxi/
                </li>
                <li class="tree-item" style="padding-left: 4.5rem;"><span class="image">🖼</span> main.webp <span
                        class="arrow">→</span> Assigned to Sub-Family "Maxi"</li>
                <li class="tree-item" style="padding-left: 4.5rem;"><span class="image">🖼</span> tech.webp <span
                        class="arrow">→</span> Assigned to Sub-Family "Maxi"</li>
                <li class="tree-item" style="padding-left: 4.5rem;"><span class="folder">📁</span> 104/</li>
                <li class="tree-item" style="padding-left: 6rem;"><span class="image">🖼</span> gallery-5.jpg <span
                        class="arrow">→</span> ALL products (UID: 104)</li>
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
                            <td><strong>Gallery 1</strong></td>
                            <td>Gallery slot 1</td>
                        </tr>
                        <tr>
                            <td><code>gallery-2.jpg</code></td>
                            <td><strong>Gallery 2</strong></td>
                            <td>Gallery slot 2</td>
                        </tr>
                        <tr>
                            <td><code>gallery-3.jpg</code></td>
                            <td><strong>Gallery 3</strong></td>
                            <td>Gallery slot 3</td>
                        </tr>
                        <tr>
                            <td><code>gallery-4.jpg</code></td>
                            <td><strong>Gallery 4</strong></td>
                            <td>Gallery slot 4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="accessories">
            <h2>2. Accessories Images</h2>
            <p>Accessories utilize a <strong>flat folder structure</strong> with filename matching.</p>

            <ul class="file-tree" style="margin: 2rem 0;">
                <li class="tree-item"><span class="folder">📁</span> puk-import/</li>
                <li class="tree-item" style="padding-left: 1.5rem;"><span class="folder">📁</span> Accessories/</li>
                <li class="tree-item" style="padding-left: 3rem;"><span class="image">🖼</span> AC044.jpg <span
                        class="arrow">──►</span> Assigned to Accessory code "AC044"</li>
                <li class="tree-item" style="padding-left: 3rem;"><span class="image">🖼</span> AC075.png <span
                        class="arrow">──►</span> Assigned to Accessory code "AC075"</li>
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

        <section id="formats">
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

        <section id="checklist">
            <h2>Pre-Upload Checklist</h2>
            <p style="margin-bottom: 2rem;">Before uploading, please verify the following items to ensure a smooth
                import process.</p>

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

        <div class="doc-footer">
            <p>Document Version: 1.0</p>
            <p>&copy; 2026 PUK Lighting. All rights reserved.</p>
        </div>
    </main>

    <script>
        // Active Nav Highlighter
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('nav a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth Scroll
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>