<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/db-check", function (Request $request) {
    try {
        // Fetch all tables
        $tablesRaw = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;");
        $tables = array_column($tablesRaw, 'name');

        // Selected table (default to first table or 'users')
        $selectedTable = $request->query('table', in_array('users', $tables) ? 'users' : ($tables[0] ?? null));

        $columns = [];
        $rows = [];
        $totalRows = 0;

        if ($selectedTable && in_array($selectedTable, $tables)) {
            // Get columns info
            $columns = DB::select("PRAGMA table_info(\"{$selectedTable}\")");

            // Get total count
            $countResult = DB::select("SELECT COUNT(*) as count FROM \"{$selectedTable}\"");
            $totalRows = $countResult[0]->count ?? 0;

            // Get first 100 rows
            $rows = DB::select("SELECT * FROM \"{$selectedTable}\" LIMIT 100");
        }

        // Get count for all tables
        $tableCounts = [];
        foreach ($tables as $t) {
            $cnt = DB::select("SELECT COUNT(*) as count FROM \"{$t}\"");
            $tableCounts[$t] = $cnt[0]->count ?? 0;
        }

        // Render clean HTML
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Viewer - ' . htmlspecialchars($selectedTable ?? 'SQLite') . '</title>
    <style>
        :root {
            --bg: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #1e293b;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --badge-bg: #334155;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        aside {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-header p {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            word-break: break-all;
        }
        .table-list {
            list-style: none;
            overflow-y: auto;
            flex: 1;
            padding: 8px;
        }
        .table-item a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s;
        }
        .table-item a:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text-main);
        }
        .table-item.active a {
            background: var(--accent);
            color: #0f172a;
            font-weight: 600;
        }
        .table-item.active .badge {
            background: #0f172a;
            color: var(--accent);
        }
        .badge {
            background: var(--badge-bg);
            color: var(--text-muted);
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 9999px;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
        }
        header h1 {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .badge-count {
            background: #0369a1;
            color: #e0f2fe;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 9999px;
            font-weight: 600;
        }
        .content {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }
        th, td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
        }
        th {
            background: rgba(0,0,0,0.2);
            font-weight: 600;
            color: var(--text-muted);
            position: sticky;
            top: 0;
        }
        tr:hover td {
            background: rgba(255,255,255,0.02);
        }
        .pk {
            color: #fbbf24;
            font-weight: 700;
        }
        .empty-state {
            padding: 40px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <aside>
        <div class="sidebar-header">
            <h2>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                    <path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path>
                </svg>
                SQLite Database
            </h2>
            <p>' . htmlspecialchars(config("database.connections.sqlite.database")) . '</p>
        </div>
        <ul class="table-list">';

        foreach ($tables as $t) {
            $active = ($t === $selectedTable) ? ' active' : '';
            $cnt = $tableCounts[$t] ?? 0;
            $html .= '<li class="table-item' . $active . '">
                <a href="?table=' . urlencode($t) . '">
                    <span>' . htmlspecialchars($t) . '</span>
                    <span class="badge">' . $cnt . '</span>
                </a>
            </li>';
        }

        $html .= '</ul>
    </aside>
    <main>
        <header>
            <h1>
                Table: <code>' . htmlspecialchars($selectedTable ?? 'None') . '</code>
                <span class="badge-count">' . $totalRows . ' rows</span>
            </h1>
        </header>
        <div class="content">';

        if (!$selectedTable) {
            $html .= '<div class="empty-state">No tables found in this database.</div>';
        } else {
            // Schema card
            $html .= '<div class="card">
                <div class="card-header">Schema Columns</div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Column Name</th>
                                <th>Data Type</th>
                                <th>Nullable</th>
                                <th>Default Value</th>
                                <th>Primary Key</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($columns as $col) {
                $html .= '<tr>
                    <td>' . htmlspecialchars((string)$col->cid) . '</td>
                    <td><strong>' . htmlspecialchars($col->name) . '</strong></td>
                    <td><code>' . htmlspecialchars($col->type ?: 'ANY') . '</code></td>
                    <td>' . ($col->notnull ? '<span style="color:#ef4444;">No</span>' : '<span style="color:#22c55e;">Yes</span>') . '</td>
                    <td>' . htmlspecialchars($col->dflt_value ?? 'NULL') . '</td>
                    <td>' . ($col->pk ? '<span class="pk">&#10003; PK</span>' : '&mdash;') . '</td>
                </tr>';
            }
            $html .= '</tbody></table></div></div>';

            // Data rows card
            $html .= '<div class="card">
                <div class="card-header">Data Records (Showing up to 100 rows)</div>
                <div style="overflow-x: auto;">';

            if (empty($rows)) {
                $html .= '<div class="empty-state">No rows in this table yet.</div>';
            } else {
                $html .= '<table><thead><tr>';
                foreach ($columns as $col) {
                    $html .= '<th>' . htmlspecialchars($col->name) . '</th>';
                }
                $html .= '</tr></thead><tbody>';

                foreach ($rows as $row) {
                    $html .= '<tr>';
                    foreach ($columns as $col) {
                        $colName = $col->name;
                        $val = $row->$colName ?? null;
                        if (is_null($val)) {
                            $html .= '<td style="color:var(--text-muted); font-style:italic;">NULL</td>';
                        } else {
                            $html .= '<td>' . htmlspecialchars((string)$val) . '</td>';
                        }
                    }
                    $html .= '</tr>';
                }
                $html .= '</tbody></table>';
            }
            $html .= '</div></div>';
        }

        $html .= '</div></main></body></html>';

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');

    } catch (\Exception $e) {
        return response('<div style="font-family:sans-serif; padding:30px; color:#ef4444;"><h2>Database Error:</h2><p>' . htmlspecialchars($e->getMessage()) . '</p></div>', 500)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
});
