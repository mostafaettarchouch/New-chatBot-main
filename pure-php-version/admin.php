<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة القيادة - برو 2026</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    
    <!-- LOGIN SCREEN -->
    <div id="login-screen" class="login-wrapper">
        <div class="login-card glass">
            <h2><i class="fas fa-lock"></i> نظام الإدارة</h2>
            <div class="form-group">
                <input type="email" id="email" placeholder="admin@example.com">
            </div>
            <div class="form-group">
                <input type="password" id="password" placeholder="password123">
            </div>
            <button onclick="login()" id="login-btn">دخول النظام</button>
        </div>
    </div>

    <!-- DASHBOARD -->
    <div id="dashboard-screen" class="dashboard-layout" style="display: none;">
        <aside class="sidebar">
            <div class="sidebar-brand">المساعد القانوني</div>
            <nav>
                <a href="#" class="active" onclick="showTab('main')"><i class="fas fa-chart-line"></i> الرئيسية</a>
                <a href="#" onclick="showTab('procedures')"><i class="fas fa-folder-open"></i> الإجراءات</a>
                <a href="#" onclick="showTab('unanswered')"><i class="fas fa-question-circle"></i> طلبات معلقة</a>
                <a href="index.php"><i class="fas fa-comments"></i> وضع المحادثة</a>
                <a href="#" onclick="logout()" class="logout"><i class="fas fa-sign-out-alt"></i> خروج</a>
            </nav>
        </aside>

        <main class="content">
            <header class="content-header">
                <h2 id="tab-title">لوحة المعلومات</h2>
                <div class="user-profile">أهلاً، <span id="admin-name">المشرف</span></div>
            </header>

            <!-- MAIN STATS TAB -->
            <div id="tab-main" class="tab-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <div class="stat-info"><h3>إجمالي الأسئلة</h3><p id="stat-total">0</p></div>
                    </div>
                    <div class="stat-card urgent">
                        <i class="fas fa-clock"></i>
                        <div class="stat-info"><h3>في الانتظار</h3><p id="stat-pending">0</p></div>
                    </div>
                    <div class="stat-card success">
                        <i class="fas fa-book"></i>
                        <div class="stat-info"><h3>المساطر القانونية</h3><p id="stat-proc">0</p></div>
                    </div>
                </div>
                <div class="chart-container glass">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>

            <!-- PROCEDURES TAB -->
            <div id="tab-procedures" class="tab-content" style="display:none;">
                <div class="action-bar">
                    <button class="btn-add" onclick="openProcModal()"><i class="fas fa-plus"></i> إضافة مسطرة جديدة</button>
                </div>
                <div class="table-wrapper glass">
                    <table id="proc-table">
                        <thead><tr><th>العنوان</th><th>الوصف</th><th>إجراءات</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- UNANSWERED TAB -->
            <div id="tab-unanswered" class="tab-content" style="display:none;">
                <div class="table-wrapper glass">
                    <table id="unanswered-table">
                        <thead><tr><th>السؤال</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL FOR PROCEDURES -->
    <div id="proc-modal" class="modal">
        <div class="modal-content glass">
            <h3>تفاصيل المسطرة القانونية</h3>
            <input type="hidden" id="proc-id">
            <input type="text" id="proc-title" placeholder="عنوان المسطرة">
            <textarea id="proc-desc" placeholder="شرح المسطرة والوثائق المطلوبة..."></textarea>
            <div class="modal-btns">
                <button onclick="saveProcedure()" class="btn-save">حفظ</button>
                <button onclick="closeModal()" class="btn-cancel">إلغاء</button>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>
