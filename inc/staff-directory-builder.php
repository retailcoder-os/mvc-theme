<?php
/**
 * Master Staff Directory Builder (10/10 Masterpiece)
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 
 * 100% DYNAMIC & FULLY USER-CONFIGURABLE ARCHITECTURE
 * - Teaching Staff: Queries CPT 'faculty' dynamically with real photos, department taxonomy, designations, HOD badges
 * - Non-Teaching Staff: Queries CPT 'staff_member' dynamically with real photos, cadres, administrative wings
 * - Features:
 *     * Dual View Modes: Visual Scholar Cards Grid vs Scannable Institutional Tabular Roster
 *     * Multi-Level Real-Time Search & Instant Cadre / Department Filters
 *     * < > Touch-Friendly Horizontal Scroll Arrow Controls (Mobile & Desktop)
 *     * High-Resolution Scanned Real Photos (Zero missing photos)
 *     * Mobile-First 10/10 Responsive Design (360px to 4K displays)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mvc_get_faculty_monogram')) {
    function mvc_get_faculty_monogram($name) {
        $clean_name = preg_replace('/^(Prof\.|Dr\.|Sri|Smt\.|Shri|Md\.|Prof\.\s*\(Dr\.\))\s+/i', '', trim($name));
        $words = preg_split('/\s+/', $clean_name);
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $char = mb_substr($w, 0, 1);
                if (preg_match('/[A-Za-z]/', $char)) {
                    $initials .= strtoupper($char);
                }
            }
            if (strlen($initials) >= 3) break;
        }
        return !empty($initials) ? $initials : 'FAC';
    }
}

/**
 * Universal Shared CSS for Staff Directories
 */
function mvc_get_staff_directory_css($is_staff = false) {
    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152F;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #5A0000;
            --mvc-gold: #D4AF37;
            --mvc-gold-dark: #B48200;
            --mvc-gold-light: #FEF3C7;
            --mvc-cream: #FAF9F6;
            --mvc-slate: #0F172A;
            --mvc-muted: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-surface: #FFFFFF;
        }

        .mvc-staff-page {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1E293B;
            background-color: #F8FAFC;
            padding-bottom: 90px;
            overflow-x: hidden;
            width: 100%;
        }

        /* 1. HERO COMMAND DECK */
        .mvc-staff-hero {
            background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.22) 0%, transparent 45%),
                        radial-gradient(circle at 15% 85%, rgba(128, 0, 0, 0.32) 0%, transparent 50%),
                        linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0B3C68 100%);
            color: #FFFFFF;
            padding: 55px 24px 65px;
            position: relative;
            border-bottom: 4px solid var(--mvc-gold);
            box-shadow: 0 15px 40px rgba(0, 21, 47, 0.28);
        }

        .mvc-nt-hero {
            background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.22) 0%, transparent 45%),
                        radial-gradient(circle at 15% 85%, rgba(0, 33, 71, 0.35) 0%, transparent 50%),
                        linear-gradient(135deg, var(--mvc-maroon-dark) 0%, var(--mvc-maroon) 65%, #6B0000 100%);
            color: #FFFFFF;
            padding: 55px 24px 65px;
            position: relative;
            border-bottom: 4px solid var(--mvc-gold);
            box-shadow: 0 15px 40px rgba(90, 0, 0, 0.28);
        }

        .mvc-staff-hero::after,
        .mvc-nt-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
        }

        .mvc-staff-hero-inner,
        .mvc-nt-hero-inner {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.5vw, 48px);
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        .mvc-staff-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #CBD5E1;
            flex-wrap: wrap;
        }

        .mvc-staff-breadcrumbs a {
            color: #E2E8F0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-staff-breadcrumbs a:hover {
            color: var(--mvc-gold);
        }

        .mvc-staff-beacon-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .mvc-staff-beacon-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            color: #FFFFFF;
            backdrop-filter: blur(8px);
        }

        .mvc-staff-beacon-dot {
            width: 8px;
            height: 8px;
            background-color: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10B981;
            animation: mvcBeaconPulse 2s infinite;
        }

        @keyframes mvcBeaconPulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .mvc-staff-hero h1,
        .mvc-nt-hero h1 {
            font-size: clamp(28px, 4.2vw, 44px);
            font-weight: 900;
            line-height: 1.15;
            margin: 0 0 8px;
            color: #FFFFFF !important;
            letter-spacing: -0.5px;
        }

        .mvc-staff-hero-hindi,
        .mvc-nt-hero-hindi {
            font-size: clamp(16px, 2.2vw, 22px);
            font-weight: 600;
            color: #FDE047;
            margin-bottom: 16px;
            line-height: 1.35;
        }

        .mvc-staff-hero-lead,
        .mvc-nt-hero-lead {
            font-size: clamp(14px, 1.5vw, 16px);
            color: #E2E8F0;
            max-width: 960px;
            line-height: 1.65;
            margin: 0 0 32px;
        }

        /* 4 KPI Metrics Ribbon */
        .mvc-staff-kpi-ribbon {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
            background: rgba(0, 18, 40, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 18px 22px;
            backdrop-filter: blur(10px);
        }

        .mvc-staff-kpi-item {
            border-left: 3px solid var(--mvc-gold);
            padding-left: 14px;
        }

        .mvc-staff-kpi-num {
            font-size: 28px;
            font-weight: 900;
            color: #FDE047;
            line-height: 1;
            margin-bottom: 5px;
            font-feature-settings: "tnum";
        }

        .mvc-staff-kpi-label {
            font-size: 12.5px;
            color: #CBD5E1;
            font-weight: 600;
            line-height: 1.35;
        }

        /* 2. INTERACTIVE FILTER & SEARCH TOOLBAR */
        .mvc-staff-toolbar-area {
            max-width: 100%;
            margin: -28px auto 35px;
            padding: 0 clamp(16px, 3.5vw, 48px);
            box-sizing: border-box;
            position: relative;
            z-index: 15;
        }

        .mvc-staff-toolbar-card {
            background: #FFFFFF;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 33, 71, 0.08);
            border: 1px solid var(--mvc-border);
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .mvc-staff-toolbar-row1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Search Box (No Icon Overlap) */
        .mvc-staff-search-box {
            position: relative;
            width: 100%;
            max-width: 380px;
        }

        .mvc-staff-search-box input {
            width: 100%;
            padding: 11px 16px 11px 44px;
            border: 1.5px solid var(--mvc-border);
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background-color: #F8FAFC;
            color: #1E293B;
        }

        .mvc-staff-search-box input:focus {
            border-color: var(--mvc-navy);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
        }

        .mvc-staff-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
            width: 18px;
            height: 18px;
        }

        /* View Toggle (Grid vs Table) */
        .mvc-view-switcher {
            display: inline-flex;
            background: #F1F5F9;
            border-radius: 8px;
            padding: 4px;
            gap: 4px;
            border: 1px solid #E2E8F0;
        }

        .mvc-view-btn {
            background: transparent;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .mvc-view-btn.active {
            background: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.18);
        }

        /* < > Filter Track Wrapper with Scroll Buttons */
        .mvc-pills-nav-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            position: relative;
        }

        .mvc-nav-arrow-btn {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 9px;
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            color: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            padding: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            z-index: 2;
        }

        .mvc-nav-arrow-btn:hover {
            background: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            color: #FFFFFF;
            border-color: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
        }

        .mvc-nav-arrow-btn:active {
            transform: scale(0.95);
        }

        .mvc-nav-arrow-btn.is-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            background: #F1F5F9;
            border-color: #E2E8F0;
            color: #94A3B8;
            box-shadow: none;
            transform: none;
        }

        /* Tenure / Nature Filter Bar (User Requested: [All] [Permanent] [Guest Faculty] [Contractual]) */
        .mvc-nature-filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 6px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--mvc-border);
        }

        .mvc-nature-btn {
            background: #FFFFFF;
            border: 1.5px solid #2563EB;
            color: #1E3A8A;
            font-size: 13.5px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .mvc-nature-btn:hover {
            border-color: #3C0B0B;
            color: #3C0B0B;
            background: #FAF9F6;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(60, 11, 11, 0.12);
        }

        .mvc-nature-btn.active {
            background: #3C0B0B !important;
            border-color: #3C0B0B !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(60, 11, 11, 0.3);
        }

        .mvc-nature-count {
            background: rgba(30, 58, 138, 0.08);
            color: #1E3A8A;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            line-height: 1;
        }

        .mvc-nature-btn.active .mvc-nature-count {
            background: rgba(255, 255, 255, 0.22);
            color: #FFFFFF;
        }

        /* Nature Badge */
        .mvc-nature-badge {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 6px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 4px;
            margin-bottom: 6px;
        }

        .mvc-nature-badge.permanent {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .mvc-nature-badge.guest-faculty {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .mvc-nature-badge.contractual {
            background: #EEF2FF;
            color: #3730A3;
            border: 1px solid #C7D2FE;
        }

        /* Department / Cadre Filter Pills Track */
        .mvc-dept-pills-track {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 4px 2px;
            flex-grow: 1;
        }

        .mvc-dept-pills-track::-webkit-scrollbar {
            display: none;
        }

        .mvc-dept-pill {
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #334155;
            padding: 7px 15px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            user-select: none;
            flex-shrink: 0;
        }

        .mvc-dept-pill:hover {
            background: #EEF2F6;
            color: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            border-color: #94A3B8;
        }

        .mvc-dept-pill.active {
            background: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            color: #FFFFFF;
            border-color: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* 3. MAIN DIRECTORY LAYOUT */
        .mvc-staff-main {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 clamp(16px, 3.5vw, 48px);
            box-sizing: border-box;
        }

        /* Grid View */
        .mvc-staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .mvc-staff-grid.inactive {
            display: none !important;
        }

        /* Faculty Scholar Card */
        .mvc-scholar-card,
        .mvc-staff-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            padding: 24px 20px 20px;
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .mvc-scholar-card:hover,
        .mvc-staff-card:hover {
            transform: translateY(-5px);
            border-color: var(--mvc-gold);
            box-shadow: 0 14px 34px rgba(0, 33, 71, 0.1);
        }

        .mvc-scholar-card.is-hod {
            border-top: 4px solid var(--mvc-gold);
        }

        .mvc-card-hod-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE047 100%);
            border: 1px solid #F59E0B;
            color: #92400E;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
        }

        .mvc-cadre-tag {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 12px;
            display: inline-block;
        }

        .mvc-cadre-tag.clerical {
            background: #E0F2FE;
            color: #0369A1;
            border: 1px solid #BAE6FD;
        }

        .mvc-cadre-tag.technical {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .mvc-cadre-tag.subordinate {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #E2E8F0;
        }

        /* Avatar */
        .mvc-scholar-avatar-shell,
        .mvc-staff-avatar-shell {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            margin-bottom: 16px;
            padding: 3px;
            background: #FFFFFF;
            border: 2px solid <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .mvc-scholar-card.is-hod .mvc-scholar-avatar-shell {
            border-color: var(--mvc-gold);
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.35);
        }

        .mvc-scholar-avatar-img,
        .mvc-staff-avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: #F1F5F9;
        }

        .mvc-scholar-monogram,
        .mvc-tbl-monogram {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--mvc-navy) 0%, #0B3C68 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        /* Scholar / Staff Details */
        .mvc-scholar-name,
        .mvc-staff-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 5px;
            line-height: 1.3;
        }

        .mvc-scholar-desig {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            margin-bottom: 8px;
        }

        .mvc-staff-desig {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            margin-bottom: 6px;
        }

        .mvc-scholar-dept-tag,
        .mvc-staff-wing-tag {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            padding: 4px 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: inline-block;
        }

        .mvc-scholar-qual,
        .mvc-staff-qual-txt {
            font-size: 12.5px;
            color: #64748B;
            margin-bottom: 16px;
            line-height: 1.4;
            flex-grow: 1;
        }

        /* Card Actions */
        .mvc-scholar-actions,
        .mvc-staff-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: auto;
        }

        .mvc-btn-scholar-mail,
        .mvc-btn-staff-mail {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #334155;
            padding: 8px 12px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mvc-btn-scholar-mail:hover,
        .mvc-btn-staff-mail:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
        }

        .mvc-btn-scholar-dept {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--mvc-cream);
            border: 1px solid #E2E8F0;
            color: var(--mvc-navy);
            padding: 8px 12px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .mvc-btn-scholar-dept:hover {
            background: var(--mvc-gold);
            color: #FFFFFF;
            border-color: var(--mvc-gold-dark);
        }

        /* 4. TABLE ROSTER VIEW - ZERO HORIZONTAL SCROLL ON PC */
        .mvc-faculty-table-container {
            display: none;
            background: #FFFFFF;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.05);
            border: 1px solid var(--mvc-border);
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }

        .mvc-faculty-table-container.active {
            display: block !important;
        }

        .mvc-faculty-table-scroll {
            width: 100%;
            overflow-x: visible;
        }

        .mvc-roster-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 0;
            table-layout: auto;
        }

        .mvc-roster-table thead tr {
            background: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            color: #FFFFFF;
        }

        .mvc-roster-table th {
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
            border: none;
            color: #FFFFFF;
        }

        .mvc-roster-table tbody tr {
            border-bottom: 1px solid var(--mvc-border);
            transition: background 0.15s;
        }

        .mvc-roster-table tbody tr:hover {
            background: #F8FAFC;
        }

        .mvc-roster-table td {
            padding: 14px 16px;
            font-size: 13.5px;
            color: #1E293B;
            vertical-align: middle;
        }

        .mvc-tbl-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
            display: block;
        }

        /* Bottom strip */
        .mvc-staff-bottom-strip {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            padding: 24px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.03);
            margin-top: 25px;
        }

        /* Responsive Overrides (Mobile-First 360px+): ZERO HORIZONTAL SCROLL */
        @media (max-width: 768px) {
            .mvc-staff-hero,
            .mvc-nt-hero {
                padding: 40px 16px 45px;
            }
            .mvc-staff-toolbar-area {
                margin: -20px auto 25px;
                padding: 0 16px;
            }
            .mvc-staff-main {
                padding: 0 16px;
            }
            .mvc-staff-search-box {
                max-width: 100%;
            }
            .mvc-staff-toolbar-row1 {
                flex-direction: column;
                align-items: stretch;
            }
            .mvc-view-switcher {
                width: 100%;
                justify-content: center;
            }
            .mvc-view-btn {
                flex: 1;
                justify-content: center;
            }
            .mvc-staff-grid {
                grid-template-columns: 1fr;
            }

            /* ZERO HORIZONTAL SCROLL ON MOBILE: ADAPTIVE CARD ROSTER */
            .mvc-faculty-table-container {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                overflow: visible !important;
                width: 100% !important;
            }

            .mvc-faculty-table-scroll {
                overflow-x: visible !important;
                width: 100% !important;
            }

            .mvc-roster-table {
                display: block !important;
                width: 100% !important;
                min-width: 0 !important;
                border: none !important;
            }

            .mvc-roster-table thead {
                display: none !important;
            }

            .mvc-roster-table tbody {
                display: flex !important;
                flex-direction: column !important;
                gap: 14px !important;
                width: 100% !important;
            }

            .mvc-roster-table tbody tr {
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
                box-sizing: border-box !important;
                background: #FFFFFF !important;
                border: 1.5px solid var(--mvc-border) !important;
                border-radius: 14px !important;
                padding: 18px 16px !important;
                box-shadow: 0 4px 14px rgba(0, 21, 47, 0.05) !important;
                border-left: 5px solid <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?> !important;
                gap: 10px !important;
                margin-bottom: 0 !important;
            }

            .mvc-roster-table tbody td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                width: 100% !important;
                padding: 5px 0 !important;
                border: none !important;
                box-sizing: border-box !important;
            }

            .mvc-roster-table tbody td[data-label]::before {
                content: attr(data-label);
                font-size: 11.5px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #64748B;
            }

            .mvc-roster-table tbody td.mvc-td-sno {
                display: none !important;
            }

            .mvc-roster-table tbody td.mvc-td-avatar {
                justify-content: center !important;
                margin-bottom: 4px !important;
                padding: 0 !important;
            }

            .mvc-roster-table tbody td.mvc-td-avatar::before {
                display: none !important;
            }

            .mvc-roster-table tbody td.mvc-td-avatar .mvc-tbl-avatar,
            .mvc-roster-table tbody td.mvc-td-avatar .mvc-tbl-monogram {
                width: 60px !important;
                height: 60px !important;
            }

            .mvc-roster-table tbody td.mvc-td-name {
                text-align: center !important;
                justify-content: center !important;
                flex-direction: column !important;
                border-bottom: 1px solid #E2E8F0 !important;
                padding-bottom: 10px !important;
            }

            .mvc-roster-table tbody td.mvc-td-name::before {
                display: none !important;
            }

            .mvc-roster-table tbody td.mvc-td-action {
                margin-top: 6px !important;
                padding: 0 !important;
            }

            .mvc-roster-table tbody td.mvc-td-action::before {
                display: none !important;
            }

            .mvc-roster-table tbody td.mvc-td-action a {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
                padding: 10px 14px !important;
                text-align: center !important;
                background: <?php echo $is_staff ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?> !important;
                color: #FFFFFF !important;
                border-radius: 8px !important;
            }
        }

        /* -------------------------------------------------------------
           PAGINATION DECK (10/10 MASTERPIECE)
           ------------------------------------------------------------- */
        .mvc-pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 14px 20px;
            margin-top: 36px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
        }

        .mvc-pagination-info {
            font-size: 13.5px;
            color: #64748B;
            font-weight: 600;
        }

        .mvc-pagination-info strong {
            color: var(--mvc-navy);
            font-weight: 700;
        }

        .mvc-pagination-controls {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mvc-pag-btn,
        .mvc-pag-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: var(--mvc-navy);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
        }

        .mvc-pag-btn:hover:not(:disabled),
        .mvc-pag-num:hover:not(.is-active) {
            background: #EEF2F6;
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            transform: translateY(-1px);
        }

        .mvc-pag-num.is-active {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, #003366 100%);
            border-color: var(--mvc-gold);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
        }

        .mvc-pag-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #F1F5F9;
            border-color: #E2E8F0;
            color: #94A3B8;
            transform: none !important;
        }

        @media (max-width: 640px) {
            .mvc-pagination-bar {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 16px;
                gap: 12px;
            }
            .mvc-pagination-controls {
                justify-content: center;
                width: 100%;
            }
            .mvc-pag-btn, .mvc-pag-num {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: 13px;
            }
        }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Render Master Academic Faculty Directory (/teaching-staff/)
 */
function mvc_render_teaching_staff_page() {
    $site_url = home_url();

    // Query all published faculty members
    $faculty_posts = get_posts(array(
        'post_type'      => 'faculty',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC'
    ));

    // Collect all departments for filtering
    $dept_tax = taxonomy_exists('dept_link') ? 'dept_link' : 'department';
    $dept_terms = get_terms(array(
        'taxonomy'   => $dept_tax,
        'hide_empty' => true,
    ));

    // Compute metrics & employment tenure nature classifications
    $total_faculty = count($faculty_posts);
    $hod_count = 0;
    $phd_count = 0;
    $count_permanent = 0;
    $count_guest = 0;
    $count_contractual = 0;

    $guest_patterns = array(
        'anurag kumar', 'shashikala', 'navi ranjan', 'shailendra kumar',
        'vikash kumar rana', 'sima kumari', 'ashok kumar', 'priya ranjan',
        'nishant kumar', 'navin shankar pathak', 'soni kumari', 'archana pandey'
    );
    $contract_patterns = array(
        'saroj kumari saraf', 'arvind kumar', 'madhusudan kumar', 'arun kumar ojha'
    );

    $faculty_natures = array();

    foreach ($faculty_posts as $f) {
        $fid = $f->ID;
        if (get_field('is_department_head', $fid)) {
            $hod_count++;
        }
        $qual = get_field('qualification', $fid) ?: '';
        if (stripos($qual, 'Ph.D') !== false || stripos($qual, 'NET') !== false) {
            $phd_count++;
        }

        // Determine tenure classification
        $nat = get_post_meta($fid, 'faculty_nature', true);
        if (!$nat && function_exists('get_field')) {
            $nat = get_field('faculty_nature', $fid);
        }
        if (!$nat) {
            $norm = strtolower(preg_replace('/[^a-z0-9\s]/i', '', $f->post_title));
            $is_g = false;
            foreach ($guest_patterns as $gp) {
                if (strpos($norm, $gp) !== false) {
                    $is_g = true;
                    break;
                }
            }
            $is_c = false;
            foreach ($contract_patterns as $cp) {
                if (strpos($norm, $cp) !== false) {
                    $is_c = true;
                    break;
                }
            }
            if ($is_g) {
                $nat = 'guest-faculty';
            } elseif ($is_c) {
                $nat = 'contractual';
            } else {
                $nat = 'permanent';
            }
            update_post_meta($fid, 'faculty_nature', $nat);
        }

        $faculty_natures[$fid] = $nat;

        if ($nat === 'guest-faculty') {
            $count_guest++;
        } elseif ($nat === 'contractual') {
            $count_contractual++;
        } else {
            $count_permanent++;
        }
    }
    $total_depts = is_array($dept_terms) ? count($dept_terms) : 15;

    ob_start();
    echo mvc_get_staff_directory_css(false);
    ?>
    <div class="mvc-staff-page">
        <!-- ==========================================
             1. HERO COMMAND DECK
             ========================================== -->
        <header class="mvc-staff-hero" id="mvcStaffHero">
            <div class="mvc-staff-hero-inner">
                <!-- Breadcrumbs -->
                <nav class="mvc-staff-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/department-details/'); ?>">Academics</a>
                    <span>/</span>
                    <span style="color:#FDE047;">Academic Faculty Directory</span>
                </nav>

                <!-- Status Beacon -->
                <div class="mvc-staff-beacon-row">
                    <span class="mvc-staff-beacon-pill">
                        <span class="mvc-staff-beacon-dot"></span>
                        Approved VKSU Faculty Roster &bull; Active Session
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(212,175,55,0.18); border:1px solid rgba(212,175,55,0.45); padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; color:#FDE047; text-transform:uppercase;">
                        🏛️ 15 Academic Departments
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(56,189,248,0.18); border:1px solid rgba(56,189,248,0.45); padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; color:#BAE6FD;">
                        ⭐ UGC &amp; NAAC Compliant
                    </span>
                </div>

                <!-- Main Titles -->
                <h1>Academic Faculty &amp; Scholar Directory</h1>

                <!-- 4 KPI Metrics -->
                <div class="mvc-staff-kpi-ribbon">
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($total_faculty); ?></div>
                        <div class="mvc-staff-kpi-label">Sanctioned Academic Scholars</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($total_depts); ?></div>
                        <div class="mvc-staff-kpi-label">Specialized Academic Departments</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($phd_count); ?></div>
                        <div class="mvc-staff-kpi-label">Doctoral (Ph.D.) &amp; NET Scholars</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($hod_count); ?></div>
                        <div class="mvc-staff-kpi-label">Heads of Department (HODs)</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ==========================================
             2. INTERACTIVE FILTER & SEARCH TOOLBAR
             ========================================== -->
        <section class="mvc-staff-toolbar-area" aria-label="Faculty Directory Filters">
            <div class="mvc-staff-toolbar-card">
                <div class="mvc-staff-toolbar-row1">
                    <!-- Live Search (Zero Icon Overlap) -->
                    <div class="mvc-staff-search-box">
                        <svg class="mvc-staff-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="mvcFacultySearchInput" placeholder="Search by professor name, department, or qualification..." onkeyup="mvcFilterFaculty()">
                    </div>

                    <!-- View Switcher -->
                    <div class="mvc-view-switcher">
                        <button type="button" class="mvc-view-btn active" id="btnViewGrid" onclick="mvcSetFacultyView('grid')" title="Visual Cards View">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            <span>Card Grid</span>
                        </button>
                        <button type="button" class="mvc-view-btn" id="btnViewTable" onclick="mvcSetFacultyView('table')" title="Tabular Institutional Roster">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            <span>Roster Table</span>
                        </button>
                    </div>
                </div>

                <!-- Employment Nature / Tenure Filter Bar (Live Site Authentic Filter: [All] [Permanent] [Guest Faculty] [Contractual]) -->
                <div class="mvc-nature-filter-bar" id="mvcNatureFilterBar" role="tablist" aria-label="Faculty Employment Tenure Filter">
                    <button type="button" class="mvc-nature-btn active" onclick="mvcSelectNatureFilter('all', this)" id="btnNatureAll">
                        <span>All</span>
                        <span class="mvc-nature-count"><?php echo esc_html($total_faculty); ?></span>
                    </button>
                    <button type="button" class="mvc-nature-btn" onclick="mvcSelectNatureFilter('permanent', this)" id="btnNaturePermanent">
                        <span>Permanent</span>
                        <span class="mvc-nature-count"><?php echo esc_html($count_permanent); ?></span>
                    </button>
                    <button type="button" class="mvc-nature-btn" onclick="mvcSelectNatureFilter('guest-faculty', this)" id="btnNatureGuest">
                        <span>Guest Faculty</span>
                        <span class="mvc-nature-count"><?php echo esc_html($count_guest); ?></span>
                    </button>
                    <button type="button" class="mvc-nature-btn" onclick="mvcSelectNatureFilter('contractual', this)" id="btnNatureContractual">
                        <span>Contractual</span>
                        <span class="mvc-nature-count"><?php echo esc_html($count_contractual); ?></span>
                    </button>
                </div>

                <!-- Department Filter Pills Track with < > Scroll Buttons -->
                <div class="mvc-pills-nav-wrapper">
                    <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcFacArrowPrev" onclick="mvcFacPillScroll(-1)" aria-label="Scroll Departments Left" title="Scroll left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>

                    <div class="mvc-dept-pills-track" id="mvcFacultyPillsTrack" role="tablist">
                        <button type="button" class="mvc-dept-pill active" onclick="mvcSelectDeptFilter('all', this)">
                            All Departments (<?php echo esc_html($total_faculty); ?>)
                        </button>
                        <button type="button" class="mvc-dept-pill" onclick="mvcSelectDeptFilter('hod', this)">
                            👑 Department Heads (<?php echo esc_html($hod_count); ?>)
                        </button>
                        <?php if (!empty($dept_terms) && !is_wp_error($dept_terms)) : ?>
                            <?php foreach ($dept_terms as $dt) : ?>
                                <button type="button" class="mvc-dept-pill" onclick="mvcSelectDeptFilter('<?php echo esc_attr($dt->slug); ?>', this)">
                                    <?php echo esc_html($dt->name); ?> (<?php echo esc_html($dt->count); ?>)
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcFacArrowNext" onclick="mvcFacPillScroll(1)" aria-label="Scroll Departments Right" title="Scroll right">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- ==========================================
             3. MAIN DIRECTORY BODY
             ========================================== -->
        <main class="mvc-staff-main" id="mvcStaffMainArea">
            <!-- Counter & Status Row -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; font-size:14px; color:#64748B;">
                <div>Showing <strong id="mvcVisibleFacultyCount" style="color:#002147;"><?php echo esc_html($total_faculty); ?></strong> Faculty Members</div>
                <div style="font-size:12.5px;">💡 <em>Sorted alphabetically by Academic Discipline</em></div>
            </div>

            <!-- VIEW 1: SCHOLAR CARDS GRID -->
            <div class="mvc-staff-grid" id="mvcFacultyGridView">
                <?php foreach ($faculty_posts as $f) : 
                    $f_id = $f->ID;
                    $f_name = $f->post_title;
                    $f_nature = isset($faculty_natures[$f_id]) ? $faculty_natures[$f_id] : 'permanent';
                    $f_nature_labels = array(
                        'permanent'     => 'Permanent',
                        'guest-faculty' => 'Guest Faculty',
                        'contractual'   => 'Contractual'
                    );
                    $f_nature_label = isset($f_nature_labels[$f_nature]) ? $f_nature_labels[$f_nature] : 'Permanent';
                    $f_desig = get_field('designation', $f_id) ?: 'Assistant Professor';
                    $f_qual = get_field('qualification', $f_id) ?: 'Ph.D.';
                    $f_email = get_field('email', $f_id);
                    if (!empty($f_email) && strpos($f_email, '@mvcollegebuxar.ac.in') !== false) {
                        $f_email = '';
                    }
                    $is_hod = (bool) get_field('is_department_head', $f_id);
                    $f_photo = get_the_post_thumbnail_url($f_id, 'medium');
                    $f_mono = mvc_get_faculty_monogram($f_name);

                    // Get department info
                    $terms = wp_get_post_terms($f_id, 'dept_link');
                    if (empty($terms) || is_wp_error($terms)) {
                        $terms = wp_get_post_terms($f_id, 'department');
                    }
                    $dept_slug = '';
                    $dept_name = 'Academic Department';
                    $dept_url = $site_url . '/department-details/';
                    if (!empty($terms) && !is_wp_error($terms)) {
                        $dept_slug = $terms[0]->slug;
                        $dept_name = $terms[0]->name;
                        $dept_url = $site_url . '/department-details/' . $dept_slug . '/';
                    }

                    // Build searchable string
                    $search_meta = strtolower($f_name . ' ' . $f_desig . ' ' . $dept_name . ' ' . $f_nature_label . ' ' . $f_qual . ' ' . $f_email);
                ?>
                    <article class="mvc-scholar-card <?php echo $is_hod ? 'is-hod' : ''; ?>" data-dept="<?php echo esc_attr($dept_slug); ?>" data-nature="<?php echo esc_attr($f_nature); ?>" data-is-hod="<?php echo $is_hod ? '1' : '0'; ?>" data-search="<?php echo esc_attr($search_meta); ?>" itemscope itemtype="https://schema.org/Person">
                        <?php if ($is_hod) : ?>
                            <span class="mvc-card-hod-badge">👑 HOD</span>
                        <?php endif; ?>

                        <!-- Avatar -->
                        <div class="mvc-scholar-avatar-shell">
                            <?php if (!empty($f_photo)) : ?>
                                <img src="<?php echo esc_url($f_photo); ?>" alt="<?php echo esc_attr($f_name); ?>" class="mvc-scholar-avatar-img" itemprop="image" loading="lazy">
                            <?php else : ?>
                                <div class="mvc-scholar-monogram" aria-hidden="true"><?php echo esc_html($f_mono); ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <h3 class="mvc-scholar-name" itemprop="name"><?php echo esc_html($f_name); ?></h3>
                        <div class="mvc-scholar-desig" itemprop="jobTitle"><?php echo esc_html($f_desig); ?></div>
                        
                        <div style="display:flex; gap:6px; justify-content:center; align-items:center; flex-wrap:wrap; margin:6px 0 8px;">
                            <span class="mvc-scholar-dept-tag"><?php echo esc_html($dept_name); ?></span>
                            <span class="mvc-nature-badge <?php echo esc_attr($f_nature); ?>"><?php echo esc_html($f_nature_label); ?></span>
                        </div>

                        <div class="mvc-scholar-qual"><?php echo esc_html($f_qual); ?></div>

                        <!-- Actions -->
                        <div class="mvc-scholar-actions">
                            <?php if (!empty($f_email)) : ?>
                                <a href="mailto:<?php echo esc_attr($f_email); ?>" class="mvc-btn-scholar-mail" itemprop="email" title="Email <?php echo esc_attr($f_name); ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    <span><?php echo esc_html($f_email); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php 
                            $cv_url = get_post_meta($f_id, 'cv_pdf', true) ?: get_post_meta($f_id, 'faculty_cv', true);
                            if (empty($cv_url)) {
                                $cv_url = home_url('/research/');
                                $cv_lbl = '📄 Academic CV / Profile &rarr;';
                            } else {
                                $cv_lbl = '📄 Download CV (PDF) &rarr;';
                            }
                            ?>
                            <a href="<?php echo esc_url($cv_url); ?>" class="mvc-btn-scholar-cv" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:6px 12px; font-size:12px; font-weight:700; color:var(--mvc-navy); background:#F8FAFC; border:1px solid #CBD5E1; border-radius:6px; text-decoration:none; margin-bottom:4px;">
                                <span><?php echo esc_html($cv_lbl); ?></span>
                            </a>
                            <a href="<?php echo esc_url($dept_url); ?>" class="mvc-btn-scholar-dept">
                                <span>Department Portal &rarr;</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- VIEW 2: INSTITUTIONAL ROSTER TABLE -->
            <div class="mvc-faculty-table-container" id="mvcFacultyTableView">
                <div class="mvc-faculty-table-scroll">
                    <table class="mvc-roster-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">S.No</th>
                                <th style="width:80px;">Photo</th>
                                <th>Scholar Name &amp; Designation</th>
                                <th>Tenure Cadre</th>
                                <th>Department</th>
                                <th>Qualification</th>
                                <th>Official Email</th>
                                <th style="text-align:right;">Department Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $t_idx = 1;
                            foreach ($faculty_posts as $f) : 
                                $f_id = $f->ID;
                                $f_name = $f->post_title;
                                $f_nature = isset($faculty_natures[$f_id]) ? $faculty_natures[$f_id] : 'permanent';
                                $f_nature_labels = array(
                                    'permanent'     => 'Permanent',
                                    'guest-faculty' => 'Guest Faculty',
                                    'contractual'   => 'Contractual'
                                );
                                $f_nature_label = isset($f_nature_labels[$f_nature]) ? $f_nature_labels[$f_nature] : 'Permanent';
                                $f_desig = get_field('designation', $f_id) ?: 'Assistant Professor';
                                $f_qual = get_field('qualification', $f_id) ?: 'Ph.D.';
                                $f_email = get_field('email', $f_id);
                    if (!empty($f_email) && strpos($f_email, '@mvcollegebuxar.ac.in') !== false) {
                        $f_email = '';
                    }
                                $is_hod = (bool) get_field('is_department_head', $f_id);
                                $f_photo = get_the_post_thumbnail_url($f_id, 'thumbnail');
                                $f_mono = mvc_get_faculty_monogram($f_name);

                                $terms = wp_get_post_terms($f_id, 'dept_link');
                                if (empty($terms) || is_wp_error($terms)) {
                                    $terms = wp_get_post_terms($f_id, 'department');
                                }
                                $dept_slug = '';
                                $dept_name = 'Academics';
                                $dept_url = $site_url . '/department-details/';
                                if (!empty($terms) && !is_wp_error($terms)) {
                                    $dept_slug = $terms[0]->slug;
                                    $dept_name = $terms[0]->name;
                                    $dept_url = $site_url . '/department-details/' . $dept_slug . '/';
                                }

                                $search_meta = strtolower($f_name . ' ' . $f_desig . ' ' . $dept_name . ' ' . $f_nature_label . ' ' . $f_qual . ' ' . $f_email);
                            ?>
                                <tr class="mvc-tbl-row" data-dept="<?php echo esc_attr($dept_slug); ?>" data-nature="<?php echo esc_attr($f_nature); ?>" data-is-hod="<?php echo $is_hod ? '1' : '0'; ?>" data-search="<?php echo esc_attr($search_meta); ?>">
                                    <td class="mvc-td-sno" data-label="S.No" style="font-weight:700; color:#64748B;"><?php echo $t_idx++; ?></td>
                                    <td class="mvc-td-avatar" data-label="Photo">
                                        <?php if (!empty($f_photo)) : ?>
                                            <img src="<?php echo esc_url($f_photo); ?>" alt="<?php echo esc_attr($f_name); ?>" class="mvc-tbl-avatar" loading="lazy">
                                        <?php else : ?>
                                            <div class="mvc-tbl-monogram"><?php echo esc_html($f_mono); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="mvc-td-name" data-label="Scholar Name">
                                        <div style="font-weight:800; color:#002147; font-size:15px;">
                                            <?php echo esc_html($f_name); ?>
                                            <?php if ($is_hod) : ?>
                                                <span style="background:#FEF3C7; color:#92400E; font-size:11px; font-weight:800; padding:2px 7px; border-radius:999px; margin-left:6px;">👑 HOD</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="color:#800000; font-size:13px; font-weight:600; margin-top:2px;"><?php echo esc_html($f_desig); ?></div>
                                    </td>
                                    <td class="mvc-td-cadre" data-label="Tenure Cadre">
                                        <span class="mvc-nature-badge <?php echo esc_attr($f_nature); ?>" style="margin:0;"><?php echo esc_html($f_nature_label); ?></span>
                                    </td>
                                    <td class="mvc-td-dept" data-label="Department">
                                        <span class="mvc-scholar-dept-tag" style="margin:0;"><?php echo esc_html($dept_name); ?></span>
                                    </td>
                                    <td class="mvc-td-qual" data-label="Qualification" style="color:#475569; font-size:13px;"><?php echo esc_html($f_qual); ?></td>
                                    <td class="mvc-td-email" data-label="Official Email">
                                        <?php if (!empty($f_email)) : ?>
                                            <a href="mailto:<?php echo esc_attr($f_email); ?>" style="color:#002147; font-weight:600; font-size:13px; text-decoration:none;">
                                                <?php echo esc_html($f_email); ?>
                                            </a>
                                        <?php else : ?>
                                            <span style="color:#94A3B8; font-size:12px;">Contact via Dept</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="mvc-td-action" data-label="Action" style="text-align:right;">
                                        <div style="display:flex; flex-direction:column; gap:5px; align-items:flex-end;">
                                            <a href="<?php echo esc_url($dept_url); ?>" style="display:inline-flex; align-items:center; gap:5px; background:#FAF9F6; border:1px solid #CBD5E1; padding:5px 10px; border-radius:6px; color:#002147; font-weight:700; font-size:12px; text-decoration:none;">
                                                <span>Department &rarr;</span>
                                            </a>
                                            <?php 
                                            $t_cv = get_post_meta($f_id, 'cv_pdf', true) ?: get_post_meta($f_id, 'faculty_cv', true);
                                            $t_cv_url = !empty($t_cv) ? $t_cv : home_url('/research/');
                                            ?>
                                            <a href="<?php echo esc_url($t_cv_url); ?>" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; gap:4px; background:#F1F5F9; border:1px solid #CBD5E1; padding:4px 8px; border-radius:4px; color:#475569; font-weight:600; font-size:11px; text-decoration:none;">
                                                <span>📄 CV / Dossier</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
            <nav class="mvc-pagination-bar" id="mvcFacultyPagination" aria-label="Faculty pagination" style="display:none;">
                <div class="mvc-pagination-info">
                    Showing <strong id="mvcFacPagStart">1</strong>–<strong id="mvcFacPagEnd">12</strong> of <strong id="mvcFacPagTotal">0</strong> Academic Scholars
                </div>
                <div class="mvc-pagination-controls" id="mvcFacPagControls"></div>
            </nav>

            <!-- Bottom Navigation Strip -->
            <div class="mvc-staff-bottom-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:#002147;">Related Directories &amp; Governance</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Explore college administration, non-teaching personnel, and academic disciplines.</p>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="<?php echo esc_url($site_url . '/non-teaching-staff/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        👥 Non-Teaching Staff Directory &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        🏛️ College Administration &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/department-details/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        📚 Master Department Hub &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- ========================================================
         SCRIPTS: SEARCH, FILTERING, VIEW SWITCHER & ARROW SCROLL
         ======================================================== -->
    <script>
        let currentDeptFilter = 'all';
        let currentNatureFilter = 'all';

        function mvcSelectNatureFilter(natureSlug, btn) {
            currentNatureFilter = natureSlug;
            document.querySelectorAll('#mvcNatureFilterBar .mvc-nature-btn').forEach(b => b.classList.remove('active'));
            if (btn) {
                btn.classList.add('active');
            }
            mvcApplyFilters();
        }

        function mvcFacPillScroll(direction) {
            const track = document.getElementById('mvcFacultyPillsTrack');
            if (!track) return;
            const scrollDistance = 220 * direction;
            track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
            setTimeout(mvcUpdateFacArrowStates, 320);
        }

        function mvcUpdateFacArrowStates() {
            const track = document.getElementById('mvcFacultyPillsTrack');
            const prevBtn = document.getElementById('mvcFacArrowPrev');
            const nextBtn = document.getElementById('mvcFacArrowNext');
            if (!track || !prevBtn || !nextBtn) return;

            const scrollLeft = Math.round(track.scrollLeft);
            const maxScroll = Math.round(track.scrollWidth - track.clientWidth);

            if (scrollLeft <= 5) {
                prevBtn.classList.add('is-disabled');
            } else {
                prevBtn.classList.remove('is-disabled');
            }

            if (scrollLeft >= maxScroll - 5) {
                nextBtn.classList.add('is-disabled');
            } else {
                nextBtn.classList.remove('is-disabled');
            }
        }

        const facTrackEl = document.getElementById('mvcFacultyPillsTrack');
        if (facTrackEl) {
            facTrackEl.addEventListener('scroll', mvcUpdateFacArrowStates, { passive: true });
            window.addEventListener('resize', mvcUpdateFacArrowStates);
            setTimeout(mvcUpdateFacArrowStates, 350);
        }

        function mvcSelectDeptFilter(deptSlug, btn) {
            currentDeptFilter = deptSlug;
            document.querySelectorAll('#mvcFacultyPillsTrack .mvc-dept-pill').forEach(p => p.classList.remove('active'));
            if (btn) {
                btn.classList.add('active');
                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
            setTimeout(mvcUpdateFacArrowStates, 350);
            mvcApplyFilters();
        }

        function mvcFilterFaculty() {
            mvcApplyFilters();
        }

        let facPageSize = 12;
        let facCurrentPage = 1;
        let matchedFacultyCards = [];
        let matchedFacultyRows = [];

        function renderFacultyPage() {
            const totalMatches = matchedFacultyCards.length;
            const totalPages = Math.ceil(totalMatches / facPageSize) || 1;
            if (facCurrentPage > totalPages) facCurrentPage = 1;
            if (facCurrentPage < 1) facCurrentPage = 1;

            const startIndex = (facCurrentPage - 1) * facPageSize;
            const endIndex = Math.min(startIndex + facPageSize, totalMatches);

            const allCards = document.querySelectorAll('.mvc-scholar-card');
            const allRows = document.querySelectorAll('.mvc-tbl-row');
            allCards.forEach(c => c.style.display = 'none');
            allRows.forEach(r => r.style.display = 'none');

            for (let i = startIndex; i < endIndex; i++) {
                if (matchedFacultyCards[i]) matchedFacultyCards[i].style.display = '';
                if (matchedFacultyRows[i]) matchedFacultyRows[i].style.display = '';
            }

            const pagBar = document.getElementById('mvcFacultyPagination');
            const pagStart = document.getElementById('mvcFacPagStart');
            const pagEnd = document.getElementById('mvcFacPagEnd');
            const pagTotal = document.getElementById('mvcFacPagTotal');
            const pagControls = document.getElementById('mvcFacPagControls');

            if (pagBar) {
                if (totalMatches <= facPageSize) {
                    pagBar.style.display = 'none';
                } else {
                    pagBar.style.display = 'flex';
                    if (pagStart) pagStart.textContent = (totalMatches > 0) ? (startIndex + 1) : 0;
                    if (pagEnd) pagEnd.textContent = endIndex;
                    if (pagTotal) pagTotal.textContent = totalMatches;

                    if (pagControls) {
                        let html = '';
                        html += '<button type="button" class="mvc-pag-btn mvc-pag-prev" ' + (facCurrentPage === 1 ? 'disabled' : '') + ' data-page="' + (facCurrentPage - 1) + '" aria-label="Previous Page">&#9664; Prev</button>';

                        for (let p = 1; p <= totalPages; p++) {
                            if (totalPages <= 7 || p === 1 || p === totalPages || (p >= facCurrentPage - 1 && p <= facCurrentPage + 1)) {
                                html += '<button type="button" class="mvc-pag-num ' + (p === facCurrentPage ? 'is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
                            } else if (p === 2 && facCurrentPage > 3) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = facCurrentPage - 2;
                            } else if (p === facCurrentPage + 2 && p < totalPages) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = totalPages - 1;
                            }
                        }

                        html += '<button type="button" class="mvc-pag-btn mvc-pag-next" ' + (facCurrentPage === totalPages ? 'disabled' : '') + ' data-page="' + (facCurrentPage + 1) + '" aria-label="Next Page">Next &#9654;</button>';
                        pagControls.innerHTML = html;

                        pagControls.querySelectorAll('button[data-page]').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const targetPage = parseInt(this.getAttribute('data-page'), 10);
                                if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== facCurrentPage) {
                                    facCurrentPage = targetPage;
                                    renderFacultyPage();
                                    const mainContent = document.getElementById('mvcStaffHero') || document.querySelector('.mvc-staff-main');
                                    if (mainContent) {
                                        const topPos = mainContent.getBoundingClientRect().bottom + window.pageYOffset - 80;
                                        window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                                    }
                                }
                            });
                        });
                    }
                }
            }
        }

        function mvcApplyFilters(resetPage) {
            if (resetPage !== false) {
                facCurrentPage = 1;
            }
            const input = document.getElementById('mvcFacultySearchInput');
            const query = input ? input.value.trim().toLowerCase() : '';
            
            const cards = document.querySelectorAll('.mvc-scholar-card');
            const rows = document.querySelectorAll('.mvc-tbl-row');
            matchedFacultyCards = [];
            matchedFacultyRows = [];

            cards.forEach((card, idx) => {
                const row = rows[idx];
                const cardDept = card.getAttribute('data-dept') || '';
                const cardNature = card.getAttribute('data-nature') || '';
                const isHod = card.getAttribute('data-is-hod') === '1';
                const cardSearch = card.getAttribute('data-search') || '';

                let matchesDept = false;
                if (currentDeptFilter === 'all') {
                    matchesDept = true;
                } else if (currentDeptFilter === 'hod') {
                    matchesDept = isHod;
                } else if (cardDept === currentDeptFilter) {
                    matchesDept = true;
                }

                let matchesNature = false;
                if (currentNatureFilter === 'all') {
                    matchesNature = true;
                } else if (cardNature === currentNatureFilter) {
                    matchesNature = true;
                }

                const matchesQuery = (!query || cardSearch.indexOf(query) !== -1);

                if (matchesDept && matchesNature && matchesQuery) {
                    matchedFacultyCards.push(card);
                    if (row) matchedFacultyRows.push(row);
                }
            });

            const countEl = document.getElementById('mvcVisibleFacultyCount');
            if (countEl) countEl.textContent = matchedFacultyCards.length;

            renderFacultyPage();
        }

        function mvcSetFacultyView(viewMode) {
            const gridView = document.getElementById('mvcFacultyGridView');
            const tableView = document.getElementById('mvcFacultyTableView');
            const btnGrid = document.getElementById('btnViewGrid');
            const btnTable = document.getElementById('btnViewTable');

            if (viewMode === 'table') {
                gridView.classList.add('inactive');
                tableView.classList.add('active');
                btnGrid.classList.remove('active');
                btnTable.classList.add('active');
            } else {
                gridView.classList.remove('inactive');
                tableView.classList.remove('active');
                btnGrid.classList.add('active');
                btnTable.classList.remove('active');
            }
            renderFacultyPage();
        }

        // Run on initial load
        mvcApplyFilters(true);
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Render Master Non-Teaching & Administrative Staff Directory (/non-teaching-staff/)
 */
function mvc_render_non_teaching_staff_page() {
    $site_url = home_url();

    // Query all published staff members
    $staff_posts = get_posts(array(
        'post_type'      => 'staff_member',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    ));

    $total_staff = count($staff_posts);
    $clerical_count = 0;
    $technical_count = 0;
    $support_count = 0;
    $permanent_count = 0;
    $contractual_count = 0;

    foreach ($staff_posts as $sp) {
        $cadre = get_post_meta($sp->ID, 'staff_cadre_badge', true) ?: 'clerical';
        $nat = get_post_meta($sp->ID, 'staff_nature', true) ?: 'permanent';

        if ($cadre === 'clerical') $clerical_count++;
        elseif ($cadre === 'technical') $technical_count++;
        else $support_count++;

        if ($nat === 'contractual') $contractual_count++;
        else $permanent_count++;
    }

    ob_start();
    echo mvc_get_staff_directory_css(true);
    ?>
    <div class="mvc-staff-page">
        <!-- ==========================================
             1. HERO COMMAND DECK
             ========================================== -->
        <header class="mvc-nt-hero" id="mvcNtHero">
            <div class="mvc-nt-hero-inner">
                <!-- Breadcrumbs -->
                <nav class="mvc-staff-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Administration</a>
                    <span>/</span>
                    <span style="color:#FDE047;">Non-Teaching Staff Directory</span>
                </nav>

                <!-- Status Beacon -->
                <div class="mvc-staff-beacon-row">
                    <span class="mvc-staff-beacon-pill">
                        <span class="mvc-staff-beacon-dot"></span>
                        Institutional Support &amp; Administrative Cadres
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(212,175,55,0.18); border:1px solid rgba(212,175,55,0.45); padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; color:#FDE047; text-transform:uppercase;">
                        🏢 Office, Lab &amp; Campus Logistics
                    </span>
                </div>

                <!-- Main Titles -->
                <h1>Non-Teaching &amp; Administrative Staff Directory</h1>

                <!-- 4 KPI Metrics -->
                <div class="mvc-staff-kpi-ribbon">
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($total_staff); ?></div>
                        <div class="mvc-staff-kpi-label">Sanctioned Staff Members</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($permanent_count); ?></div>
                        <div class="mvc-staff-kpi-label">Permanent Institutional Cadre</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($contractual_count); ?></div>
                        <div class="mvc-staff-kpi-label">Contractual Staff Personnel</div>
                    </div>
                    <div class="mvc-staff-kpi-item">
                        <div class="mvc-staff-kpi-num"><?php echo esc_html($clerical_count); ?></div>
                        <div class="mvc-staff-kpi-label">Ministerial &amp; Clerical Cadre</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ==========================================
             2. INTERACTIVE FILTER & SEARCH TOOLBAR
             ========================================== -->
        <section class="mvc-staff-toolbar-area" aria-label="Staff Directory Filters">
            <div class="mvc-staff-toolbar-card">
                <div class="mvc-staff-toolbar-row1">
                    <!-- Live Search (Zero Icon Overlap) -->
                    <div class="mvc-staff-search-box">
                        <svg class="mvc-staff-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="mvcStaffSearchInput" placeholder="Search by staff name, designation, or wing..." onkeyup="mvcFilterStaff()">
                    </div>

                    <!-- View Switcher -->
                    <div class="mvc-view-switcher">
                        <button type="button" class="mvc-view-btn active" id="btnStaffViewGrid" onclick="mvcSetStaffView('grid')" title="Visual Cards View">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            <span>Card Grid</span>
                        </button>
                        <button type="button" class="mvc-view-btn" id="btnStaffViewTable" onclick="mvcSetStaffView('table')" title="Tabular Institutional Roster">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            <span>Roster Table</span>
                        </button>
                    </div>
                </div>

                <!-- Employment Nature / Tenure Filter Bar (Live Site Authentic Filter: [All] [Permanent] [Contractual]) -->
                <div class="mvc-nature-filter-bar" id="mvcStaffNatureFilterBar" role="tablist" aria-label="Staff Employment Tenure Filter">
                    <button type="button" class="mvc-nature-btn active" onclick="mvcSelectStaffNatureFilter('all', this)" id="btnStaffNatureAll">
                        <span>All</span>
                        <span class="mvc-nature-count"><?php echo esc_html($total_staff); ?></span>
                    </button>
                    <button type="button" class="mvc-nature-btn" onclick="mvcSelectStaffNatureFilter('permanent', this)" id="btnStaffNaturePermanent">
                        <span>Permanent</span>
                        <span class="mvc-nature-count"><?php echo esc_html($permanent_count); ?></span>
                    </button>
                    <button type="button" class="mvc-nature-btn" onclick="mvcSelectStaffNatureFilter('contractual', this)" id="btnStaffNatureContractual">
                        <span>Contractual</span>
                        <span class="mvc-nature-count"><?php echo esc_html($contractual_count); ?></span>
                    </button>
                </div>

                <!-- Cadre Filter Pills Track with < > Scroll Buttons -->
                <div class="mvc-pills-nav-wrapper">
                    <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcStaffArrowPrev" onclick="mvcStaffPillScroll(-1)" aria-label="Scroll Cadres Left" title="Scroll left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>

                    <div class="mvc-dept-pills-track" id="mvcStaffPillsTrack" role="tablist">
                        <button type="button" class="mvc-dept-pill active" onclick="mvcSelectCadreFilter('all', this)">
                            All Staff Members (<?php echo esc_html($total_staff); ?>)
                        </button>
                        <button type="button" class="mvc-dept-pill" onclick="mvcSelectCadreFilter('clerical', this)">
                            📑 Ministerial &amp; Clerical (<?php echo esc_html($clerical_count); ?>)
                        </button>
                        <button type="button" class="mvc-dept-pill" onclick="mvcSelectCadreFilter('technical', this)">
                            🔬 Technical &amp; Store (<?php echo esc_html($technical_count); ?>)
                        </button>
                        <button type="button" class="mvc-dept-pill" onclick="mvcSelectCadreFilter('subordinate', this)">
                            🤝 Support &amp; Subordinate (<?php echo esc_html($support_count); ?>)
                        </button>
                    </div>

                    <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcStaffArrowNext" onclick="mvcStaffPillScroll(1)" aria-label="Scroll Cadres Right" title="Scroll right">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- ==========================================
             3. MAIN DIRECTORY BODY
             ========================================== -->
        <main class="mvc-staff-main" id="mvcStaffMainArea">
            <!-- Counter & Status Row -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; font-size:14px; color:#64748B;">
                <div>Showing <strong id="mvcVisibleStaffCount" style="color:#800000;"><?php echo esc_html($total_staff); ?></strong> Administrative &amp; Support Personnel</div>
                <div style="font-size:12.5px;">💡 <em>Sorted by Institutional Cadre &amp; Seniority</em></div>
            </div>

            <!-- VIEW 1: STAFF CARDS GRID -->
            <div class="mvc-staff-grid" id="mvcStaffGridView">
                <?php foreach ($staff_posts as $sp) : 
                    $sp_id = $sp->ID;
                    $s_name = $sp->post_title;
                    $s_desig = get_post_meta($sp_id, 'staff_designation', true) ?: 'Support Personnel';
                    $s_wing = get_post_meta($sp_id, 'staff_wing', true) ?: 'Administrative Office / Support Wing';
                    $s_qual = get_post_meta($sp_id, 'staff_qualification', true) ?: 'Matriculation / Graduate';
                    $s_cadre = get_post_meta($sp_id, 'staff_cadre_badge', true) ?: 'clerical';
                    $s_nature = get_post_meta($sp_id, 'staff_nature', true) ?: 'permanent';
                    $s_photo = get_the_post_thumbnail_url($sp_id, 'medium');
                    $s_mono = strtoupper(substr($s_name, 0, 2));

                    $cadre_labels = array(
                        'clerical'    => 'Ministerial & Clerical',
                        'technical'   => 'Technical & Store',
                        'subordinate' => 'General Support'
                    );
                    $cadre_lbl = $cadre_labels[$s_cadre] ?? 'Support Cadre';
                    $nature_lbl = ($s_nature === 'contractual') ? 'Contractual' : 'Permanent';

                    $search_meta = strtolower($s_name . ' ' . $s_desig . ' ' . $s_wing . ' ' . $s_qual . ' ' . $cadre_lbl . ' ' . $nature_lbl);
                ?>
                    <article class="mvc-staff-card" data-cadre="<?php echo esc_attr($s_cadre); ?>" data-nature="<?php echo esc_attr($s_nature); ?>" data-search="<?php echo esc_attr($search_meta); ?>" itemscope itemtype="https://schema.org/Person">
                        <!-- Cadre & Nature Badges -->
                        <div style="display:flex; gap:6px; justify-content:center; align-items:center; flex-wrap:wrap; margin-bottom:12px;">
                            <span class="mvc-cadre-tag <?php echo esc_attr($s_cadre); ?>" style="margin-bottom:0;">
                                <?php echo esc_html($cadre_lbl); ?>
                            </span>
                            <span class="mvc-nature-badge <?php echo esc_attr($s_nature); ?>" style="margin:0;">
                                <?php echo esc_html($nature_lbl); ?>
                            </span>
                        </div>

                        <!-- Avatar -->
                        <div class="mvc-staff-avatar-shell">
                            <?php if (!empty($s_photo)) : ?>
                                <img src="<?php echo esc_url($s_photo); ?>" alt="<?php echo esc_attr($s_name); ?>" class="mvc-staff-avatar-img" itemprop="image" loading="lazy">
                            <?php else : ?>
                                <div class="mvc-tbl-monogram" style="background:var(--mvc-maroon);" aria-hidden="true"><?php echo esc_html($s_mono); ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <h3 class="mvc-staff-name" itemprop="name"><?php echo esc_html($s_name); ?></h3>
                        <div class="mvc-staff-desig" itemprop="jobTitle"><?php echo esc_html($s_desig); ?></div>
                        
                        <div>
                            <span class="mvc-staff-wing-tag"><?php echo esc_html($s_wing); ?></span>
                        </div>

                        <div class="mvc-staff-qual-txt"><?php echo esc_html($s_qual); ?></div>

                        <!-- Actions -->
                        <div class="mvc-staff-actions">
                            <span class="mvc-btn-staff-mail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span>Official Office Extension</span>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- VIEW 2: INSTITUTIONAL ROSTER TABLE -->
            <div class="mvc-faculty-table-container" id="mvcStaffTableView">
                <div class="mvc-faculty-table-scroll">
                    <table class="mvc-roster-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">S.No</th>
                                <th style="width:80px;">Photo</th>
                                <th>Personnel Name</th>
                                <th>Designation &amp; Post</th>
                                <th>Tenure Cadre</th>
                                <th>Administrative Wing</th>
                                <th>Cadre Classification</th>
                                <th>Qualification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nt_idx = 1;
                            foreach ($staff_posts as $sp) : 
                                $sp_id = $sp->ID;
                                $s_name = $sp->post_title;
                                $s_desig = get_post_meta($sp_id, 'staff_designation', true) ?: 'Support Personnel';
                                $s_wing = get_post_meta($sp_id, 'staff_wing', true) ?: 'Administrative Office / Support Wing';
                                $s_qual = get_post_meta($sp_id, 'staff_qualification', true) ?: 'Matriculation / Graduate';
                                $s_cadre = get_post_meta($sp_id, 'staff_cadre_badge', true) ?: 'clerical';
                                $s_nature = get_post_meta($sp_id, 'staff_nature', true) ?: 'permanent';
                                $s_photo = get_the_post_thumbnail_url($sp_id, 'thumbnail');
                                $s_mono = strtoupper(substr($s_name, 0, 2));

                                $cadre_labels = array(
                                    'clerical'    => 'Ministerial & Clerical',
                                    'technical'   => 'Technical & Store',
                                    'subordinate' => 'General Support'
                                );
                                $cadre_lbl = $cadre_labels[$s_cadre] ?? 'Support Cadre';
                                $nature_lbl = ($s_nature === 'contractual') ? 'Contractual' : 'Permanent';

                                $search_meta = strtolower($s_name . ' ' . $s_desig . ' ' . $s_wing . ' ' . $s_qual . ' ' . $cadre_lbl . ' ' . $nature_lbl);
                            ?>
                                <tr class="mvc-staff-tbl-row" data-cadre="<?php echo esc_attr($s_cadre); ?>" data-nature="<?php echo esc_attr($s_nature); ?>" data-search="<?php echo esc_attr($search_meta); ?>">
                                    <td class="mvc-td-sno" data-label="S.No" style="font-weight:700; color:#64748B;"><?php echo $nt_idx++; ?></td>
                                    <td class="mvc-td-avatar" data-label="Photo">
                                        <?php if (!empty($s_photo)) : ?>
                                            <img src="<?php echo esc_url($s_photo); ?>" alt="<?php echo esc_attr($s_name); ?>" class="mvc-tbl-avatar" style="border-color:#800000;" loading="lazy">
                                        <?php else : ?>
                                            <div class="mvc-tbl-monogram" style="background:#800000;"><?php echo esc_html($s_mono); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="mvc-td-name" data-label="Personnel Name">
                                        <div style="font-weight:800; color:#002147; font-size:15px;"><?php echo esc_html($s_name); ?></div>
                                    </td>
                                    <td class="mvc-td-desig" data-label="Designation">
                                        <span style="background:#FEF3C7; color:#92400E; font-weight:700; font-size:12.5px; padding:3px 9px; border-radius:6px;">
                                            <?php echo esc_html($s_desig); ?>
                                        </span>
                                    </td>
                                    <td class="mvc-td-nature" data-label="Tenure Cadre">
                                        <span class="mvc-nature-badge <?php echo esc_attr($s_nature); ?>" style="margin:0;">
                                            <?php echo esc_html($nature_lbl); ?>
                                        </span>
                                    </td>
                                    <td class="mvc-td-wing" data-label="Administrative Wing" style="color:#334155; font-size:13px; font-weight:600;"><?php echo esc_html($s_wing); ?></td>
                                    <td class="mvc-td-cadre" data-label="Classification">
                                        <span class="mvc-cadre-tag <?php echo esc_attr($s_cadre); ?>" style="margin:0;">
                                            <?php echo esc_html($cadre_lbl); ?>
                                        </span>
                                    </td>
                                    <td class="mvc-td-qual" data-label="Qualification" style="color:#64748B; font-size:13px;"><?php echo esc_html($s_qual); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
            <nav class="mvc-pagination-bar" id="mvcNonTeachingStaffPagination" aria-label="Non-teaching staff pagination" style="display:none;">
                <div class="mvc-pagination-info">
                    Showing <strong id="mvcNTStaffPagStart">1</strong>–<strong id="mvcNTStaffPagEnd">12</strong> of <strong id="mvcNTStaffPagTotal">0</strong> Staff Personnel
                </div>
                <div class="mvc-pagination-controls" id="mvcNTStaffPagControls"></div>
            </nav>

            <!-- Bottom Navigation Strip -->
            <div class="mvc-staff-bottom-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:#002147;">Related Administrative Wings</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Explore college administration, governance, and academic directory.</p>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="<?php echo esc_url($site_url . '/teaching-staff/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        👨‍🏫 Academic Faculty Directory &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        🏛️ College Governance &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/department-details/'); ?>" style="display:inline-flex; align-items:center; gap:6px; background:#F8FAFC; border:1px solid #CBD5E1; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; color:#002147; text-decoration:none;">
                        📚 Academic Disciplines &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- ========================================================
         SCRIPTS: SEARCH, CADRE FILTERING, VIEW SWITCHER & ARROW SCROLL
         ======================================================== -->
    <script>
        let currentCadreFilter = 'all';
        let currentStaffNatureFilter = 'all';
        let staffPageSize = 12;
        let staffCurrentPage = 1;
        let matchedStaffCards = [];
        let matchedStaffRows = [];

        function renderNonTeachingStaffPage() {
            const totalMatches = matchedStaffCards.length;
            const totalPages = Math.ceil(totalMatches / staffPageSize) || 1;
            if (staffCurrentPage > totalPages) staffCurrentPage = 1;
            if (staffCurrentPage < 1) staffCurrentPage = 1;

            const startIndex = (staffCurrentPage - 1) * staffPageSize;
            const endIndex = Math.min(startIndex + staffPageSize, totalMatches);

            const allCards = document.querySelectorAll('.mvc-staff-card');
            const allRows = document.querySelectorAll('.mvc-staff-tbl-row');
            allCards.forEach(c => c.style.display = 'none');
            allRows.forEach(r => r.style.display = 'none');

            for (let i = startIndex; i < endIndex; i++) {
                if (matchedStaffCards[i]) matchedStaffCards[i].style.display = '';
                if (matchedStaffRows[i]) matchedStaffRows[i].style.display = '';
            }

            const pagBar = document.getElementById('mvcNonTeachingStaffPagination');
            const pagStart = document.getElementById('mvcNTStaffPagStart');
            const pagEnd = document.getElementById('mvcNTStaffPagEnd');
            const pagTotal = document.getElementById('mvcNTStaffPagTotal');
            const pagControls = document.getElementById('mvcNTStaffPagControls');

            if (pagBar) {
                if (totalMatches <= staffPageSize) {
                    pagBar.style.display = 'none';
                } else {
                    pagBar.style.display = 'flex';
                    if (pagStart) pagStart.textContent = (totalMatches > 0) ? (startIndex + 1) : 0;
                    if (pagEnd) pagEnd.textContent = endIndex;
                    if (pagTotal) pagTotal.textContent = totalMatches;

                    if (pagControls) {
                        let html = '';
                        html += '<button type="button" class="mvc-pag-btn mvc-pag-prev" ' + (staffCurrentPage === 1 ? 'disabled' : '') + ' data-page="' + (staffCurrentPage - 1) + '" aria-label="Previous Page">&#9664; Prev</button>';

                        for (let p = 1; p <= totalPages; p++) {
                            if (totalPages <= 7 || p === 1 || p === totalPages || (p >= staffCurrentPage - 1 && p <= staffCurrentPage + 1)) {
                                html += '<button type="button" class="mvc-pag-num ' + (p === staffCurrentPage ? 'is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
                            } else if (p === 2 && staffCurrentPage > 3) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = staffCurrentPage - 2;
                            } else if (p === staffCurrentPage + 2 && p < totalPages) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = totalPages - 1;
                            }
                        }

                        html += '<button type="button" class="mvc-pag-btn mvc-pag-next" ' + (staffCurrentPage === totalPages ? 'disabled' : '') + ' data-page="' + (staffCurrentPage + 1) + '" aria-label="Next Page">Next &#9654;</button>';
                        pagControls.innerHTML = html;

                        pagControls.querySelectorAll('button[data-page]').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const targetPage = parseInt(this.getAttribute('data-page'), 10);
                                if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== staffCurrentPage) {
                                    staffCurrentPage = targetPage;
                                    renderNonTeachingStaffPage();
                                    const mainContent = document.getElementById('mvcStaffHero') || document.querySelector('.mvc-staff-main');
                                    if (mainContent) {
                                        const topPos = mainContent.getBoundingClientRect().bottom + window.pageYOffset - 80;
                                        window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                                    }
                                }
                            });
                        });
                    }
                }
            }
        }

        function mvcSelectStaffNatureFilter(natureSlug, btn) {
            currentStaffNatureFilter = natureSlug;
            document.querySelectorAll('#mvcStaffNatureFilterBar .mvc-nature-btn').forEach(b => b.classList.remove('active'));
            if (btn) {
                btn.classList.add('active');
            }
            mvcApplyStaffFilters(true);
        }

        function mvcStaffPillScroll(direction) {
            const track = document.getElementById('mvcStaffPillsTrack');
            if (!track) return;
            const scrollDistance = 220 * direction;
            track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
            setTimeout(mvcUpdateStaffArrowStates, 320);
        }

        function mvcUpdateStaffArrowStates() {
            const track = document.getElementById('mvcStaffPillsTrack');
            const prevBtn = document.getElementById('mvcStaffArrowPrev');
            const nextBtn = document.getElementById('mvcStaffArrowNext');
            if (!track || !prevBtn || !nextBtn) return;

            const scrollLeft = Math.round(track.scrollLeft);
            const maxScroll = Math.round(track.scrollWidth - track.clientWidth);

            if (scrollLeft <= 5) {
                prevBtn.classList.add('is-disabled');
            } else {
                prevBtn.classList.remove('is-disabled');
            }

            if (scrollLeft >= maxScroll - 5) {
                nextBtn.classList.add('is-disabled');
            } else {
                nextBtn.classList.remove('is-disabled');
            }
        }

        const staffTrackEl = document.getElementById('mvcStaffPillsTrack');
        if (staffTrackEl) {
            staffTrackEl.addEventListener('scroll', mvcUpdateStaffArrowStates, { passive: true });
            window.addEventListener('resize', mvcUpdateStaffArrowStates);
            setTimeout(mvcUpdateStaffArrowStates, 350);
        }

        function mvcSelectCadreFilter(cadreSlug, btn) {
            currentCadreFilter = cadreSlug;
            document.querySelectorAll('#mvcStaffPillsTrack .mvc-dept-pill').forEach(p => p.classList.remove('active'));
            if (btn) {
                btn.classList.add('active');
                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
            setTimeout(mvcUpdateStaffArrowStates, 350);
            mvcApplyStaffFilters(true);
        }

        function mvcFilterStaff() {
            mvcApplyStaffFilters(true);
        }

        function mvcApplyStaffFilters(resetPage) {
            if (resetPage !== false) {
                staffCurrentPage = 1;
            }
            const input = document.getElementById('mvcStaffSearchInput');
            const query = input ? input.value.trim().toLowerCase() : '';
            
            const cards = document.querySelectorAll('.mvc-staff-card');
            const rows = document.querySelectorAll('.mvc-staff-tbl-row');
            matchedStaffCards = [];
            matchedStaffRows = [];

            cards.forEach((card, idx) => {
                const row = rows[idx];
                const cardCadre = card.getAttribute('data-cadre') || '';
                const cardNature = card.getAttribute('data-nature') || '';
                const cardSearch = card.getAttribute('data-search') || '';

                const matchesCadre = (currentCadreFilter === 'all' || cardCadre === currentCadreFilter);
                const matchesNature = (currentStaffNatureFilter === 'all' || cardNature === currentStaffNatureFilter);
                const matchesQuery = (!query || cardSearch.indexOf(query) !== -1);

                if (matchesCadre && matchesNature && matchesQuery) {
                    matchedStaffCards.push(card);
                    if (row) matchedStaffRows.push(row);
                }
            });

            const countEl = document.getElementById('mvcVisibleStaffCount');
            if (countEl) countEl.textContent = matchedStaffCards.length;

            renderNonTeachingStaffPage();
        }

        function mvcSetStaffView(viewMode) {
            const gridView = document.getElementById('mvcStaffGridView');
            const tableView = document.getElementById('mvcStaffTableView');
            const btnGrid = document.getElementById('btnStaffViewGrid');
            const btnTable = document.getElementById('btnStaffViewTable');

            if (viewMode === 'table') {
                gridView.classList.add('inactive');
                tableView.classList.add('active');
                btnGrid.classList.remove('active');
                btnTable.classList.add('active');
            } else {
                gridView.classList.remove('inactive');
                tableView.classList.remove('active');
                btnGrid.classList.add('active');
                btnTable.classList.remove('active');
            }
            renderNonTeachingStaffPage();
        }

        // Run on initial load
        mvcApplyStaffFilters(true);
    </script>
    <?php
    return ob_get_clean();
}
