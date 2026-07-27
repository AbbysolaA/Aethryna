{{-- Shared admin styles for the volunteering screens (roster and positions).
     Included after volunteer._styles, which supplies the tokens. --}}
<style>
    .vl-admin-head { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 20px; }
    .vl-head-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }

    .vl-table-panel { padding: 0; overflow: hidden; }
    .vl-table-scroll { overflow-x: auto; }
    .vl-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; min-width: 860px; }
    .vl-table th { text-align: left; font-family: var(--font-mono); font-size: 0.7rem; letter-spacing: 1.4px; text-transform: uppercase; color: var(--ath-muted); padding: 18px 20px; border-bottom: 1px solid rgba(0,0,0,0.08); white-space: nowrap; }
    .vl-table td { padding: 16px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); vertical-align: top; color: var(--ath-text); }
    .vl-table tr:last-child td { border-bottom: none; }

    .vl-cell-sub { display: block; font-size: 0.82rem; color: var(--ath-muted); margin-top: 3px; }
    .vl-cell-flag { display: inline-block; margin-top: 5px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; color: #8a5a06; background: rgba(238,157,29,0.16); padding: 3px 9px; border-radius: 100px; }
    .vl-cell-dates { white-space: nowrap; }
    .vl-cell-num { font-variant-numeric: tabular-nums; font-weight: 700; color: var(--ath-deep); }
    .vl-cell-req { display: flex; flex-wrap: wrap; gap: 6px; }
    .vl-cell-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: flex-start; }

    .vl-tag { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; background: rgba(238,157,29,0.16); color: #8a5a06; }
    .vl-tag-quiet { background: rgba(0,0,0,0.06); color: var(--ath-muted); }

    .vl-onboard-form { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .vl-onboard-form label { display: inline-flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--ath-deep); cursor: pointer; }
    .vl-onboard-form input { accent-color: var(--ath-teal); }

    .vl-mini-btn { display: inline-block; padding: 6px 14px; border: 1.5px solid var(--ath-teal); background: transparent; color: var(--ath-teal); border-radius: 100px; font-family: inherit; font-weight: 700; font-size: 0.78rem; cursor: pointer; text-decoration: none; transition: background 0.2s, color 0.2s; }
    .vl-mini-btn:hover { background: var(--ath-teal); color: #fff; }
    .vl-mini-btn-danger { border-color: rgba(185,28,28,0.4); color: #b91c1c; }
    .vl-mini-btn-danger:hover { background: #b91c1c; border-color: #b91c1c; color: #fff; }

    .vl-pagination { margin-top: 26px; }

    /* Forms on admin screens */
    .vl-form-panel { max-width: 760px; }
    .vl-field-row-even { grid-template-columns: 1fr 1fr; }
    .vl-field select, .vl-field textarea {
        width: 100%; padding: 11px 14px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px;
        font-size: 0.95rem; font-family: inherit; color: var(--ath-text); background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; outline: none;
    }
    .vl-field select:focus, .vl-field textarea:focus { border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1); }
    .vl-field textarea { resize: vertical; min-height: 90px; }
    .vl-toggles { display: grid; gap: 12px; background: rgba(3,139,137,0.05); border-radius: 12px; padding: 18px 20px; margin: 6px 0 22px; }

    @media (max-width: 640px) { .vl-field-row-even { grid-template-columns: 1fr; } }
</style>
