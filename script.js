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

    // --- STUDENTS ---
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
                            <button class="action-btn edit-student-btn" data-id="${s.stud_id}">Edit</button>
                            <button class="action-btn delete-student-btn" data-id="${s.stud_id}">Delete</button>
                        </td>`;
                    tbody.appendChild(tr);
                });
                tbody.onclick = async function(e) {
                    const editBtn = e.target.closest('.edit-student-btn');
                    const deleteBtn = e.target.closest('.delete-student-btn');
                    if (editBtn) await editStudent(parseInt(editBtn.dataset.id));
                    else if (deleteBtn) await deleteStudent(parseInt(deleteBtn.dataset.id));
                };
            } else {
                tbody.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
            }
        } catch (err) {
            console.error('Error loading students:', err);
            tbody.innerHTML = `<tr><td colspan="5">Error loading students.</td></tr>`;
        }
    }
    function renderStudentForm(editData) {
        fetch(API_BASE + 'Programs/getPrograms.php')
        .then(res => res.json())
        .then(programsData => {
            let html = `
            <form id="student-form">
                <h3>${editData ? 'Edit Student' : 'Add Student'}</h3>
                <div id="student-error" class="error-message" style="display: none;"></div>
                <div id="student-success" class="success-message" style="display: none;"></div>
                <label>ID</label>
                <input type="number" id="student-id" value="${editData ? editData.stud_id : ''}" ${editData ? 'readonly' : 'required'} />
                <label>First Name</label>
                <input type="text" id="student-fname" value="${editData ? editData.first_name : ''}" required />
                <label>Middle Name</label>
                <input type="text" id="student-mname" value="${editData ? editData.middle_name : ''}" />
                <label>Last Name</label>
                <input type="text" id="student-lname" value="${editData ? editData.last_name : ''}" required />
                <label>Program</label>
                <select id="student-program" required>
                    <option value="">Select Program</option>
                    ${programsData.data.map(p => `<option value="${p.program_id}" ${editData && editData.program_id == p.program_id ? 'selected' : ''}>${p.program_name}</option>`).join('')}
                </select>
                <label>Allowance</label>
                <input type="number" id="student-allowance" value="${editData ? editData.allowance : ''}" required />
                <button type="submit">${editData ? 'Update' : 'Add'}</button>
                <button type="button" id="cancel-student-btn">Cancel</button>
            </form>`;
            showFormModal(html);
            document.getElementById('cancel-student-btn').onclick = () => { closeFormModal(); };
            document.getElementById('student-form').onsubmit = async e => {
                e.preventDefault();
                const first_name = document.getElementById('student-fname').value.trim();
                const middle_name = document.getElementById('student-mname').value.trim();
                const last_name = document.getElementById('student-lname').value.trim();
                const program_id = document.getElementById('student-program').value;
                const allowance = document.getElementById('student-allowance').value;
                if (!first_name || !last_name || !program_id || !allowance) {
                    showError('student-error', 'All fields except middle name are required');
                    return;
                }
                try {
                    const stud_id = document.getElementById('student-id').value;
                    let url, body;
                    if (editData) {
                        url = API_BASE + 'Students/updateStudent.php';
                        body = {
                            stud_id: editData.stud_id,
                            first_name, middle_name, last_name,
                            program_id, allowance
                        };
                    } else {
                        url = API_BASE + 'Students/addStudent.php';
                        body = { stud_id, first_name, middle_name, last_name, program_id, allowance };
                    }
                    console.log('Sending:', body, 'to', url);
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(body),
                    });
                    const data = await res.json();
                    if (data.success) {
                        showSuccess('student-success', data.message);
                        setTimeout(() => {
                            closeFormModal();
                            loadStudents();
                        }, 1200);
                    } else {
                        showError('student-error', data.message);
                    }
                } catch (err) {
                    console.error('Error submitting student form:', err);
                    showError('student-error', 'Error submitting form');
                }
            };
        });
    }
    async function editStudent(id) {
        try {
            const res = await fetch(API_BASE + 'Students/getStudents.php');
            const data = await res.json();
            if (!data.success) {
                showError('student-error', 'Unable to fetch student for editing');
                return;
            }
            const student = data.data.find(s => s.stud_id === id);
            if (!student) {
                showError('student-error', 'Student not found');
                return;
            }
            renderStudentForm(student);
        } catch (err) {
            console.error('Error loading student for edit:', err);
            showError('student-error', 'Error loading student for edit');
        }
    }
    async function deleteStudent(id) {
        if (!confirm('Are you sure you want to delete this student?')) return;
        try {
            const url = API_BASE + 'Students/deleteStudent.php';
            const body = {stud_id: id};
            console.log('Deleting:', body, 'to', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                loadStudents();
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error('Error deleting student:', err);
            alert('Error deleting student');
        }
    }

    // --- PROGRAMS ---
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
        } catch (err) {
            console.error('Error loading programs:', err);
            tbody.innerHTML = `<tr><td colspan="3">Error loading programs.</td></tr>`;
        }
    }
    function renderProgramForm(editData) {
        let html = `
        <form id="program-form">
            <h3>${editData ? 'Edit Program' : 'Add Program'}</h3>
            <div id="program-error" class="error-message" style="display: none;"></div>
            <div id="program-success" class="success-message" style="display: none;"></div>
            ${editData ? `
            <label>Program ID</label>
            <input type="number" id="program-id" value="${editData.program_id}" readonly />
            ` : ''}
            <label>Program Name</label>
            <input type="text" id="program-name" value="${editData ? editData.program_name : ''}" required />
            <button type="submit">${editData ? 'Update' : 'Add'}</button>
            <button type="button" id="cancel-program-btn">Cancel</button>
        </form>`;
        showFormModal(html);
        document.getElementById('cancel-program-btn').onclick = () => { closeFormModal(); };
        document.getElementById('program-form').onsubmit = async e => {
            e.preventDefault();
            const program_name = document.getElementById('program-name').value.trim();
            if (!program_name) {
                showError('program-error', 'Program name is required');
                return;
            }
            try {
                let url, body;
                if (editData) {
                    url = API_BASE + 'Programs/updateProgram.php';
                    body = { program_id: editData.program_id, program_name };
                } else {
                    url = API_BASE + 'Programs/addProgram.php';
                    body = { program_name, ins_id };
                }
                console.log('Sending:', body, 'to', url);
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
            } catch (err) {
                console.error('Error submitting program form:', err);
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
        } catch (err) {
            console.error('Error loading program for edit:', err);
            showError('program-error', 'Error loading program for edit');
        }
    }
    async function deleteProgram(id) {
        if (!confirm('Are you sure you want to delete this program?')) return;
        try {
            const url = API_BASE + 'Programs/deleteProgram.php';
            const body = {program_id: id};
            console.log('Deleting:', body, 'to', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                loadPrograms();
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error('Error deleting program:', err);
            alert('Error deleting program');
        }
    }

    // --- YEARS & SEMESTERS ---
    async function loadYearsAndSemesters() {
        content.innerHTML = `<h2>Years & Semesters</h2>
        <div style="margin-bottom: 20px;">
            <button id="btn-add-year">Add Year</button>
           
        </div>

        <h3>Years</h3>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
            <tbody id="years-tbody"></tbody>
        </table>
 <button id="btn-add-semester">Add Semester</button>
        <h3>Semesters</h3>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
            <tbody id="semesters-tbody"></tbody>
        </table>`;

        document.getElementById('btn-add-year').onclick = () => renderYearForm();
        document.getElementById('btn-add-semester').onclick = () => renderSemesterForm();
        await Promise.all([populateYearsTable(), populateSemestersTable()]);
    }
    async function populateSemestersTable() {
        const tbody = document.getElementById('semesters-tbody');
        try {
            const res = await fetch(API_BASE + 'Years&Semesters/getSemesters.php');
            const data = await res.json();
            if (data.success) {
                tbody.innerHTML = '';
                data.data.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.sem_id}</td>
                        <td>${s.sem_name}</td>
                        <td>
                            <button class="action-btn edit-semester-btn" data-id="${s.sem_id}">Edit</button>
                            <button class="action-btn delete-semester-btn" data-id="${s.sem_id}">Delete</button>
                        </td>`;
                    tbody.appendChild(tr);
                });
                tbody.onclick = async function(e) {
                    const editBtn = e.target.closest('.edit-semester-btn');
                    const deleteBtn = e.target.closest('.delete-semester-btn');
                    if (editBtn) await editSemester(parseInt(editBtn.dataset.id));
                    else if (deleteBtn) await deleteSemester(parseInt(deleteBtn.dataset.id));
                };
            } else {
                tbody.innerHTML = `<tr><td colspan="3">${data.message}</td></tr>`;
            }
        } catch (err) {
            console.error('Error loading semesters:', err);
            tbody.innerHTML = `<tr><td colspan="3">Error loading semesters.</td></tr>`;
        }
    }
    function renderSemesterForm(editData) {
        let html = `
        <form id="semester-form">
            <h3>${editData ? 'Edit Semester' : 'Add Semester'}</h3>
            <div id="semester-error" class="error-message" style="display: none;"></div>
            <div id="semester-success" class="success-message" style="display: none;"></div>
            ${editData ? `
            <label>Semester ID</label>
            <input type="number" id="semester-id" value="${editData.sem_id}" readonly />
            ` : ''}
            <label>Semester Name</label>
            <input type="text" id="semester-name" value="${editData ? editData.sem_name : ''}" required />
            <button type="submit">${editData ? 'Update' : 'Add'}</button>
            <button type="button" id="cancel-semester-btn">Cancel</button>
        </form>`;
        showFormModal(html);
        document.getElementById('cancel-semester-btn').onclick = () => { closeFormModal(); };
        document.getElementById('semester-form').onsubmit = async e => {
            e.preventDefault();
            const sem_name = document.getElementById('semester-name').value.trim();
            if (!sem_name) {
                showError('semester-error', 'Semester name is required');
                return;
            }
            try {
                let url, body;
                if (editData) {
                    url = API_BASE + 'Years&Semesters/updateSemester.php';
                    body = { sem_id: editData.sem_id, sem_name };
                } else {
                    url = API_BASE + 'Years&Semesters/addSemester.php';
                    body = { sem_name };
                }
                console.log('Sending:', body, 'to', url);
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(body),
                });
                const data = await res.json();
                if (data.success) {
                    showSuccess('semester-success', data.message);
                    setTimeout(() => {
                        closeFormModal();
                        loadYearsAndSemesters();
                    }, 1200);
                } else {
                    showError('semester-error', data.message);
                }
            } catch (err) {
                console.error('Error submitting semester form:', err);
                showError('semester-error', 'Error submitting form');
            }
        };
    }
    async function editSemester(id) {
        try {
            const res = await fetch(API_BASE + 'Years&Semesters/getSemesters.php');
            const data = await res.json();
            if (!data.success) {
                showError('semester-error', 'Unable to fetch semester for editing');
                return;
            }
            const semester = data.data.find(s => s.sem_id === id);
            if (!semester) {
                showError('semester-error', 'Semester not found');
                return;
            }
            renderSemesterForm(semester);
        } catch (err) {
            console.error('Error loading semester for edit:', err);
            showError('semester-error', 'Error loading semester for edit');
        }
    }
    async function deleteSemester(id) {
        if (!confirm('Are you sure you want to delete this semester?')) return;
        try {
            const url = API_BASE + 'Years&Semesters/deleteSemester.php';
            const body = {sem_id: id};
            console.log('Deleting:', body, 'to', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                loadYearsAndSemesters();
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error('Error deleting semester:', err);
            alert('Error deleting semester');
        }
    }

    // --- SUBJECTS ---
    async function loadSubjects() {
        content.innerHTML = `<h2>Subjects</h2>
        <button id="btn-add-subject">Add Subject</button>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Semester</th><th>Actions</th></thead>
            <tbody id="subjects-tbody"></tbody>
        </table>`;
        document.getElementById('btn-add-subject').onclick = () => renderSubjectForm();
        await populateSubjectsTable();
    }
    async function populateSubjectsTable() {
        const tbody = document.getElementById('subjects-tbody');
        try {
            const res = await fetch(API_BASE + 'Subjects/getSubjects.php');
            const data = await res.json();
            if (data.success) {
                tbody.innerHTML = '';
                for (const s of data.data) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.subject_id}</td>
                        <td>${s.subject_name}</td>
                        <td>${s.sem_name || ''}</td>
                        <td>
                            <button class="action-btn edit-subject-btn" data-id="${s.subject_id}">Edit</button>
                            <button class="action-btn delete-subject-btn" data-id="${s.subject_id}">Delete</button>
                        </td>`;
                    tbody.appendChild(tr);
                }
                tbody.onclick = async function(e) {
                    const editBtn = e.target.closest('.edit-subject-btn');
                    const deleteBtn = e.target.closest('.delete-subject-btn');
                    if (editBtn) await editSubject(parseInt(editBtn.dataset.id));
                    else if (deleteBtn) await deleteSubject(parseInt(deleteBtn.dataset.id));
                };
            } else {
                tbody.innerHTML = `<tr><td colspan="4">${data.message}</td></tr>`;
            }
        } catch (err) {
            console.error('Error loading subjects:', err);
            tbody.innerHTML = `<tr><td colspan="4">Error loading subjects.</td></tr>`;
        }
    }
    function renderSubjectForm(editData) {
        fetch(API_BASE + 'Years&Semesters/getSemesters.php')
        .then(res => res.json())
        .then(semestersData => {
            let html = `
            <form id="subject-form">
                <h3>${editData ? 'Edit Subject' : 'Add Subject'}</h3>
                <div id="subject-error" class="error-message" style="display: none;"></div>
                <div id="subject-success" class="success-message" style="display: none;"></div>
                ${editData ? `
                <label>Subject ID</label>
                <input type="number" id="subject-id" value="${editData.subject_id}" readonly />
                ` : ''}
                <label>Subject Name</label>
                <input type="text" id="subject-name" value="${editData ? editData.subject_name : ''}" required />
                <label>Semester</label>
                <select id="subject-semester" required>
                    <option value="">Select Semester</option>
                    ${semestersData.data.map(s => `<option value="${s.sem_id}" ${editData && editData.sem_id == s.sem_id ? 'selected' : ''}>${s.sem_name}</option>`).join('')}
                </select>
                <button type="submit">${editData ? 'Update' : 'Add'}</button>
                <button type="button" id="cancel-subject-btn">Cancel</button>
            </form>`;
            showFormModal(html);
            document.getElementById('cancel-subject-btn').onclick = () => { closeFormModal(); };
            document.getElementById('subject-form').onsubmit = async e => {
                e.preventDefault();
                const subject_name = document.getElementById('subject-name').value.trim();
                const sem_id = document.getElementById('subject-semester').value;
                if (!subject_name || !sem_id) {
                    showError('subject-error', 'Subject name and semester are required');
                    return;
                }
                try {
                    let url, body;
                    if (editData) {
                        url = API_BASE + 'Subjects/updateSubject.php';
                        body = { subject_id: editData.subject_id, subject_name, sem_id };
                    } else {
                        url = API_BASE + 'Subjects/addSubject.php';
                        body = { subject_name, sem_id };
                    }
                    console.log('Sending:', body, 'to', url);
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(body),
                    });
                    const data = await res.json();
                    if (data.success) {
                        showSuccess('subject-success', data.message);
                        setTimeout(() => {
                            closeFormModal();
                            loadSubjects();
                        }, 1200);
                    } else {
                        showError('subject-error', data.message);
                    }
                } catch (err) {
                    console.error('Error submitting subject form:', err);
                    showError('subject-error', 'Error submitting form');
                }
            };
        });
    }
    async function editSubject(id) {
        try {
            const res = await fetch(API_BASE + 'Subjects/getSubjects.php');
            const data = await res.json();
            if (!data.success) {
                showError('subject-error', 'Unable to fetch subject for editing');
                return;
            }
            const subject = data.data.find(s => s.subject_id === id);
            if (!subject) {
                showError('subject-error', 'Subject not found');
                return;
            }
            renderSubjectForm(subject);
        } catch (err) {
            console.error('Error loading subject for edit:', err);
            showError('subject-error', 'Error loading subject for edit');
        }
    }
    async function deleteSubject(id) {
        if (!confirm('Are you sure you want to delete this subject?')) return;
        try {
            const url = API_BASE + 'Subjects/deleteSubject.php';
            const body = {subject_id: id};
            console.log('Deleting:', body, 'to', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                loadSubjects();
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error('Error deleting subject:', err);
            alert('Error deleting subject');
        }
    }

    // --- ENROLLMENTS ---
    async function loadEnrollments() {
        content.innerHTML = `<h2>Enrollments</h2>
        <button id="btn-add-enrollment">Add Enrollment</button>
        <table>
            <thead><tr><th>ID</th><th>Student</th><th>Subject</th><th>Actions</th></thead>
            <tbody id="enrollments-tbody"></tbody>
        </table>`;
        document.getElementById('btn-add-enrollment').onclick = () => renderEnrollmentForm();
        await populateEnrollmentsTable();
    }
    async function populateEnrollmentsTable() {
        const tbody = document.getElementById('enrollments-tbody');
        try {
            const res = await fetch(API_BASE + 'Enrollments/getEnrollments.php');
            const data = await res.json();
            if (data.success) {
                tbody.innerHTML = '';
                data.data.forEach(e => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${e.enrollment_id}</td>
                        <td>${e.student_name} (${e.stud_id})</td>
                        <td>${e.subject_name} (${e.subject_id})</td>
                        <td>
                            <button class="action-btn edit-enrollment-btn" data-id="${e.enrollment_id}">Edit</button>
                            <button class="action-btn delete-enrollment-btn" data-id="${e.enrollment_id}">Delete</button>
                        </td>`;
                    tbody.appendChild(tr);
                });
                tbody.onclick = async function(e) {
                    const editBtn = e.target.closest('.edit-enrollment-btn');
                    const deleteBtn = e.target.closest('.delete-enrollment-btn');
                    if (editBtn) await editEnrollment(parseInt(editBtn.dataset.id));
                    else if (deleteBtn) await deleteEnrollment(parseInt(deleteBtn.dataset.id));
                };
            } else {
                tbody.innerHTML = `<tr><td colspan="4">${data.message}</td></tr>`;
            }
        } catch (err) {
            console.error('Error loading enrollments:', err);
            tbody.innerHTML = `<tr><td colspan="4">Error loading enrollments.</td></tr>`;
        }
    }
    function renderEnrollmentForm(editData) {
        Promise.all([
            fetch(API_BASE + 'Students/getStudents.php').then(res => res.json()),
            fetch(API_BASE + 'Subjects/getSubjects.php').then(res => res.json())
        ]).then(([studentsData, subjectsData]) => {
            let html = `
            <form id="enrollment-form">
                <h3>${editData ? 'Edit Enrollment' : 'Add Enrollment'}</h3>
                <div id="enrollment-error" class="error-message" style="display: none;"></div>
                <div id="enrollment-success" class="success-message" style="display: none;"></div>
                <label>Student</label>
                <select id="enrollment-student" required>
                    <option value="">Select Student</option>
                    ${studentsData.data.map(s => `<option value="${s.stud_id}" ${editData && (editData.stud_id == s.stud_id) ? 'selected' : ''}>${s.name} (${s.stud_id})</option>`).join('')}
                </select>
                <label>Subject</label>
                <select id="enrollment-subject" required>
                    <option value="">Select Subject</option>
                    ${subjectsData.data.map(s => `<option value="${s.subject_id}" ${editData && (editData.subject_id == s.subject_id) ? 'selected' : ''}>${s.subject_name} (${s.subject_id})</option>`).join('')}
                </select>
                <button type="submit">${editData ? 'Update' : 'Add'}</button>
                <button type="button" id="cancel-enrollment-btn">Cancel</button>
            </form>
            `;
            showFormModal(html);
            document.getElementById('cancel-enrollment-btn').onclick = () => { closeFormModal(); };
            document.getElementById('enrollment-form').onsubmit = async e => {
                e.preventDefault();
                const stud_id = document.getElementById('enrollment-student').value;
                const subject_id = document.getElementById('enrollment-subject').value;
                if (!stud_id || !subject_id) {
                    showError('enrollment-error', 'Please select both student and subject.');
                    return;
                }
                try {
                    let url, body;
                    if (editData) {
                        url = API_BASE + 'Enrollments/updateEnrollment.php';
                        body = { enrollment_id: editData.enrollment_id, stud_id, subject_id };
                    } else {
                        url = API_BASE + 'Enrollments/enrollStudent.php';
                        body = { stud_id, subject_id };
                    }
                    console.log('Sending:', body, 'to', url);
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(body),
                    });
                    const data = await res.json();
                    if (data.success) {
                        showSuccess('enrollment-success', data.message);
                        setTimeout(() => {
                            closeFormModal();
                            loadEnrollments();
                        }, 1200);
                    } else {
                        showError('enrollment-error', data.message);
                    }
                } catch (err) {
                    console.error('Error submitting enrollment form:', err);
                    showError('enrollment-error', 'Error submitting form');
                }
            };
        });
    }
    async function editEnrollment(id) {
        try {
            const res = await fetch(API_BASE + 'Enrollments/getEnrollments.php');
            const data = await res.json();
            if (!data.success) {
                showError('enrollment-error', 'Unable to fetch enrollment for editing');
                return;
            }
            const enrollment = data.data.find(e => e.enrollment_id == id);
            if (!enrollment) {
                showError('enrollment-error', 'Enrollment not found');
                return;
            }
            renderEnrollmentForm(enrollment);
        } catch (err) {
            console.error('Error loading enrollment for edit:', err);
            showError('enrollment-error', 'Error loading enrollment for edit');
        }
    }
    async function deleteEnrollment(id) {
        if (!confirm('Are you sure you want to delete this enrollment?')) return;
        try {
            const url = API_BASE + 'Enrollments/removeEnrollment.php';
            const body = {enrollment_id: id};
            console.log('Deleting:', body, 'to', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                loadEnrollments();
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error('Error deleting enrollment:', err);
            alert('Error deleting enrollment');
        }
    }

    // --- UTILS ---
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
});  