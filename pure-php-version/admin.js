// admin.js
let chart = null;

async function checkLogin() {
    try {
        const res = await fetch('api.php?action=stats');
        if (res.status === 401) {
            showScreen('login-screen');
        } else {
            const data = await res.json();
            showScreen('dashboard-screen');
            document.getElementById('admin-name').textContent = sessionStorage.getItem('admin_name') || 'المشرف';
            updateStats(data);
            initChart(data.history);
        }
    } catch (e) {
        showScreen('login-screen');
    }
}

async function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const btn = document.getElementById('login-btn');
    
    btn.textContent = 'جاري الدخول...';
    const res = await fetch('api.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    
    if (res.ok) {
        const data = await res.json();
        sessionStorage.setItem('admin_name', data.name);
        location.reload();
    } else {
        alert('بيانات الدخول غير صحيحة');
        btn.textContent = 'دخول النظام';
    }
}

async function logout() {
    await fetch('api.php?action=logout');
    location.reload();
}

function showScreen(id) {
    document.getElementById('login-screen').style.display = 'none';
    document.getElementById('dashboard-screen').style.display = 'none';
    document.getElementById(id).style.display = id === 'dashboard-screen' ? 'flex' : 'flex';
}

function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('.sidebar nav a').forEach(a => a.classList.remove('active'));
    document.getElementById(`tab-${tab}`).style.display = 'block';
    event.target.classList.add('active');
    
    const titles = { 'main': 'لوحة المعلومات', 'procedures': 'إدارة المساطر القانونية', 'unanswered': 'الطلبات المعلقة' };
    document.getElementById('tab-title').textContent = titles[tab];

    if (tab === 'procedures') loadProcedures();
    if (tab === 'unanswered') loadUnanswered();
}

async function loadProcedures() {
    const res = await fetch('api.php?action=get_procedures');
    const data = await res.json();
    const tbody = document.querySelector('#proc-table tbody');
    tbody.innerHTML = '';
    data.forEach(p => {
        tbody.innerHTML += `
            <tr>
                <td><b>${p.title}</b></td>
                <td>${p.description.substring(0, 50)}...</td>
                <td>
                    <button onclick="editProc(${p.id}, '${p.title}', \`${p.description}\`)"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteProc(${p.id})" style="color:red"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

async function loadUnanswered() {
    const res = await fetch('api.php?action=unanswered');
    const data = await res.json();
    const tbody = document.querySelector('#unanswered-table tbody');
    tbody.innerHTML = '';
    data.forEach(q => {
        tbody.innerHTML += `
            <tr>
                <td>${q.text}</td>
                <td>${q.created_at}</td>
                <td><button onclick="resolveQ(${q.id})">تمت الإجابة</button></td>
            </tr>
        `;
    });
}

function updateStats(data) {
    document.getElementById('stat-total').textContent = data.total_questions;
    document.getElementById('stat-pending').textContent = data.unanswered;
    document.getElementById('stat-proc').textContent = data.procedures;
}

function initChart(history) {
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: history.map(h => h.date),
            datasets: [{
                label: 'نشاط الأسئلة اليومي',
                data: history.map(h => h.count),
                borderColor: '#003366',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(0, 51, 102, 0.1)'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
}

// PROCEDURE CRUD
function openProcModal() {
    document.getElementById('proc-id').value = '';
    document.getElementById('proc-title').value = '';
    document.getElementById('proc-desc').value = '';
    document.getElementById('proc-modal').style.display = 'flex';
}

function editProc(id, title, desc) {
    document.getElementById('proc-id').value = id;
    document.getElementById('proc-title').value = title;
    document.getElementById('proc-desc').value = desc;
    document.getElementById('proc-modal').style.display = 'flex';
}

function closeModal() { document.getElementById('proc-modal').style.display = 'none'; }

async function saveProcedure() {
    const id = document.getElementById('proc-id').value;
    const title = document.getElementById('proc-title').value;
    const description = document.getElementById('proc-desc').value;
    
    await fetch('api.php?action=save_procedure', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, title, description })
    });
    closeModal();
    loadProcedures();
}

async function deleteProc(id) {
    if (!confirm('هل أنت متأكد من حذف هذه المسطرة؟')) return;
    await fetch('api.php?action=delete_procedure', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    loadProcedures();
}

async function resolveQ(id) {
    await fetch('api.php?action=resolve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    loadUnanswered();
}

document.addEventListener('DOMContentLoaded', checkLogin);
