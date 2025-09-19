document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = 'http://localhost/finproj/api/';
    const content = document.getElementById('content');
    const tabs = {
        students: document.getElementById('tab-students'),
        programs: document.getElementById('tab-programs'),
        years: document.getElementById('tab-years'),
        subjects: document.getElementById('tab-subjects'),
        enrollments: document.getElementById('tab-enrollments'),
    };

     
    const formModal = document.getElementById('form-modal');
    const formModalBody = document.getElementById('form-modal-body');
    const formModalClose = document.getElementById('form-modal-close');
    function showFormModal(html) {
        formModalBody.innerHTML = html;
        formModal.style.display = 'block';
    }
    function closeFormModal() {
        formModal.style.display = 'none';
        formModalBody.innerHTML = '';
    }
    formModalClose.onclick = closeFormModal;
    window.onclick = e => { if (e.target == formModal) closeFormModal(); };
 
    Object.keys(tabs).forEach(key => {
        tabs[key].onclick = () => setActiveTab(key);
    });

    async function setActiveTab(tab) {
        Object.values(tabs).forEach(btn => btn.classList.remove('active'));
        tabs[tab].classList.add('active');
        switch(tab) {
            case 'students': await loadStudents(); break;
            case 'programs': await loadPrograms(); break;
            case 'years': await loadYearsAndSemesters(); break;
            case 'subjects': await loadSubjects(); break;
            case 'enrollments': await loadEnrollments(); break;
        }
    }
 
    async function loadStudents() {
        content.innerHTML = `<h2>Students</h2>
        <button id="btn-add-student">Add Student</button>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Program</th><th>Allowance</th><th>Actions</th></tr></thead>
            <tbody id="students-tbody"></tbody>
        </table>`;
        document.getElementById('btn-add-student').onclick = () => renderStudentForm();
        await populateStudentsTable();
    }
    async function populateStudentsTable() {
        const tbody = document.getElementById('students-tbody');
        try {
            const res = await fetch(API_BASE + 'Students/getStudents.php');
            const data = await res.json();
            if (data.success) {
                tbody.innerHTML = '';
                data.data.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.stud_id}</td>
                        <td>${s.name}</td>
                        <td>${s.program_name || ''}</td>
                        <td>${s.allowance || 0}</td>
                        <td>
                            <button class="action-btn edit-btn" data-id="${s.stud_id}">Edit</button>
                            <button class="action-btn delete-btn" data-id="${s.stud_id}">Delete</button>
                        </td>`;
                    tbody.appendChild(tr);
                });
                tbody.onclick = async function(e) {
                    const editBtn = e.target.closest('.edit-btn');
                    const deleteBtn = e.target.closest('.delete-btn');
                    if (editBtn) await editStudent(parseInt(editBtn.dataset.id));
                    else if (deleteBtn) await deleteStudent(parseInt(deleteBtn.dataset.id));
                };
            } else {
                tbody.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
            }
        } catch {
            tbody.innerHTML = `<tr><td colspan="5">Error loading students.</td></tr>`;
        }
    }
   
    function showError(elementId, message) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = message;
            element.style.display = 'block';
            const successElement = elementId.replace('error', 'success');
            const succ = document.getElementById(successElement);
            if (succ) succ.style.display = 'none';
        }
    }
    function showSuccess(elementId, message) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = message;
            element.style.display = 'block';
            const errorElement = elementId.replace('success', 'error');
            const err = document.getElementById(errorElement);
            if (err) err.style.display = 'none';
        }
    }
 
    setActiveTab('students');

async function loadPrograms() {
    content.innerHTML = `<h2>Programs</h2>
    <button id="btn-add-program">Add Program</button>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody id="programs-tbody"></tbody>
    </table>`;
    document.getElementById('btn-add-program').onclick = () => renderProgramForm();
    await populateProgramsTable();
}

async function populateProgramsTable() {
    const tbody = document.getElementById('programs-tbody');
    try {
        const res = await fetch(API_BASE + 'Programs/getPrograms.php');
        const data = await res.json();
        if (data.success) {
            tbody.innerHTML = '';
            data.data.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${p.program_id}</td>
                    <td>${p.program_name}</td>
                    <td>
                        <button class="action-btn edit-program-btn" data-id="${p.program_id}">Edit</button>
                        <button class="action-btn delete-program-btn" data-id="${p.program_id}">Delete</button>
                    </td>`;
                tbody.appendChild(tr);
            });
            tbody.onclick = async function(e) {
                const editBtn = e.target.closest('.edit-program-btn');
                const deleteBtn = e.target.closest('.delete-program-btn');
                if (editBtn) await editProgram(parseInt(editBtn.dataset.id));
                else if (deleteBtn) await deleteProgram(parseInt(deleteBtn.dataset.id));
            };
        } else {
            tbody.innerHTML = `<tr><td colspan="3">${data.message}</td></tr>`;
        }
    } catch {
        tbody.innerHTML = `<tr><td colspan="3">Error loading programs.</td></tr>`;
    }
}

function renderProgramForm(editData) {
    let html = `
    <form id="program-form">
        <h3>${editData ? 'Edit Program' : 'Add Program'}</h3>
        <div id="program-error" class="error-message" style="display: none;"></div>
        <div id="program-success" class="success-message" style="display: none;"></div>
        <label>Program ID</label>
        <input type="number" id="program-id" value="${editData ? editData.program_id : ''}" ${editData ? 'readonly' : 'required'} />
        <label>Program Name</label>
        <input type="text" id="program-name" value="${editData ? editData.program_name : ''}" required />
        <button type="submit">${editData ? 'Update' : 'Add'}</button>
        <button type="button" id="cancel-program-btn">Cancel</button>
    </form>`;
    showFormModal(html);
    document.getElementById('cancel-program-btn').onclick = () => { closeFormModal(); };
    document.getElementById('program-form').onsubmit = async e => {
        e.preventDefault();
        const program_id = document.getElementById('program-id').value;
        const program_name = document.getElementById('program-name').value.trim();
        if (!program_id || !program_name) {
            showError('program-error', 'Program ID and name are required');
            return;
        }
        try {
            let url = API_BASE + 'Programs/addProgram.php';
            let body = {program_id, program_name};
            if (editData) {
                url = API_BASE + 'Programs/updateProgram.php';
                body.program_id = program_id;
            }
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                showSuccess('program-success', data.message);
                setTimeout(() => {
                    closeFormModal();
                    loadPrograms();
                }, 1200);
            } else {
                showError('program-error', data.message);
            }
        } catch {
            showError('program-error', 'Error submitting form');
        }
    };
}

async function editProgram(id) {
    try {
        const res = await fetch(API_BASE + 'Programs/getPrograms.php');
        const data = await res.json();
        if (!data.success) {
            showError('program-error', 'Unable to fetch program for editing');
            return;
        }
        const program = data.data.find(p => p.program_id === id);
        if (!program) {
            showError('program-error', 'Program not found');
            return;
        }
        renderProgramForm(program);
    } catch {
        showError('program-error', 'Error loading program for edit');
    }
}

async function deleteProgram(id) {
    if (!confirm('Are you sure you want to delete this program?')) return;
    try {
        const res = await fetch(API_BASE + 'Programs/deleteProgram.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({program_id: id}),
        });
        const data = await res.json();
        if (data.success) {
            const tbody = document.getElementById('programs-tbody');
            const deleteBtn = tbody.querySelector(`button[data-id="${id}"]`);
            if (deleteBtn) {
                const row = deleteBtn.closest('tr');
                if (row) row.remove();
            }
            alert(data.message);
        } else {
            alert(data.message);
        }
    } catch {
        alert('Error deleting program');
    }
}

}); 
