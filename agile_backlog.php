<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agile Scheduling Portal — Backlog &amp; Sprint Management</title>
<style>
  :root{
    --indigo-900:#312e81; --indigo-700:#4338ca; --indigo-600:#4f46e5; --indigo-500:#6366f1;
    --indigo-100:#e0e7ff; --indigo-50:#eef2ff;
    --ink:#1e1b3a; --muted:#6b7280; --line:#e5e7eb; --bg:#f5f6fb; --card:#ffffff;
    --high:#dc2626; --high-bg:#fee2e2; --med:#d97706; --med-bg:#fef3c7; --low:#16a34a; --low-bg:#dcfce7;
    --todo:#6b7280; --todo-bg:#f3f4f6; --progress:#2563eb; --progress-bg:#dbeafe; --done:#16a34a; --done-bg:#dcfce7;
    --radius:14px;
  }
  *{box-sizing:border-box;}
  body{margin:0;font-family:'Segoe UI',-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:var(--ink);}
  .wrap{max-width:1180px;margin:0 auto;padding:28px 20px 60px;}

  .portal-header{display:flex;align-items:center;gap:10px;margin-bottom:22px;}
  .portal-header .logo{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--indigo-600),var(--indigo-900));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;}
  .portal-header .title{font-weight:700;font-size:15px;line-height:1.1;}
  .portal-header .sub{font-size:11px;color:var(--muted);}

  .hero{background:linear-gradient(120deg,var(--indigo-700),var(--indigo-500));border-radius:18px;padding:26px 30px;color:#fff;margin-bottom:16px;box-shadow:0 10px 30px -12px rgba(67,56,202,.55);}
  .hero .badge{display:inline-block;background:rgba(255,255,255,.18);padding:4px 12px;border-radius:999px;font-size:11px;font-weight:600;letter-spacing:.04em;margin-bottom:10px;}
  .hero h1{margin:0 0 6px;font-size:26px;}
  .hero p{margin:0;font-size:13.5px;opacity:.92;max-width:640px;}

  .status-bar{font-size:12px;padding:8px 14px;border-radius:10px;margin-bottom:18px;display:none;}
  .status-bar.show{display:block;}
  .status-ok{background:var(--done-bg);color:var(--done);}
  .status-err{background:var(--high-bg);color:var(--high);}

  .grid{display:grid;grid-template-columns:1.05fr .95fr;gap:20px;}
  @media(max-width:920px){.grid{grid-template-columns:1fr;}}

  .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);padding:22px;box-shadow:0 1px 2px rgba(16,24,40,.04);}
  .card h2{margin:0 0 4px;font-size:16px;}
  .card .desc{margin:0 0 16px;font-size:12.5px;color:var(--muted);}

  .field-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
  .field label{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:6px;}
  .field input, .field select{width:100%;border:1px solid var(--line);border-radius:10px;padding:9px 11px;font-size:13px;font-family:inherit;background:#fbfbfe;}
  .field input:focus, .field select:focus{outline:none;border-color:var(--indigo-500);box-shadow:0 0 0 3px var(--indigo-100);}
  .field-full{grid-column:1/-1;}

  .btn-primary{background:var(--indigo-600);color:#fff;border:none;border-radius:10px;padding:11px 18px;font-size:13.5px;font-weight:600;cursor:pointer;width:100%;transition:background .15s;}
  .btn-primary:hover{background:var(--indigo-700);}
  .btn-primary:disabled{opacity:.6;cursor:wait;}
  .error-text{color:var(--high);font-size:12px;margin-top:6px;display:none;}

  .section-title{display:flex;align-items:center;justify-content:space-between;margin:26px 0 12px;}
  .section-title h2{margin:0;font-size:16px;display:flex;align-items:center;gap:8px;}
  .count-pill{background:var(--indigo-50);color:var(--indigo-700);font-size:11.5px;font-weight:700;padding:3px 10px;border-radius:999px;}

  table{width:100%;border-collapse:collapse;font-size:12.8px;}
  thead th{text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);padding:9px 10px;border-bottom:1px solid var(--line);}
  tbody td{padding:11px 10px;border-bottom:1px solid var(--line);vertical-align:top;}
  tbody tr:last-child td{border-bottom:none;}
  tbody tr:hover{background:#fafaff;}
  .id-tag{font-weight:700;color:var(--indigo-600);white-space:nowrap;}

  .badge-priority{display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:700;}
  .p-high{color:var(--high);background:var(--high-bg);}
  .p-medium{color:var(--med);background:var(--med-bg);}
  .p-low{color:var(--low);background:var(--low-bg);}

  .badge-status{display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:700;white-space:nowrap;}
  .s-todo{color:var(--todo);background:var(--todo-bg);}
  .s-progress{color:var(--progress);background:var(--progress-bg);}
  .s-done{color:var(--done);background:var(--done-bg);}

  .points-chip{font-weight:700;color:var(--indigo-700);}

  .icon-btn{border:none;background:none;cursor:pointer;color:#9ca3af;font-size:15px;padding:4px 6px;border-radius:6px;}
  .icon-btn:hover{color:var(--high);background:var(--high-bg);}
  .sprint-btn{border:1px solid #c7d2fe;background:var(--indigo-50);color:var(--indigo-700);font-size:11px;font-weight:700;padding:5px 10px;border-radius:8px;cursor:pointer;white-space:nowrap;}
  .sprint-btn:hover{background:var(--indigo-100);}
  .sprint-btn:disabled{opacity:.45;cursor:not-allowed;}

  .advance-btn{border:1px solid var(--line);background:#fff;color:var(--ink);font-size:11px;font-weight:700;padding:5px 10px;border-radius:8px;cursor:pointer;white-space:nowrap;}
  .advance-btn:hover{border-color:var(--indigo-500);color:var(--indigo-700);}
  .advance-btn:disabled{opacity:.4;cursor:default;}

  .empty-row td{text-align:center;color:var(--muted);padding:26px 10px;font-size:12.5px;}

  .stat-strip{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;}
  .stat-chip{flex:1;min-width:110px;background:var(--indigo-50);border-radius:10px;padding:10px 12px;}
  .stat-chip .n{font-size:18px;font-weight:800;color:var(--indigo-700);line-height:1;}
  .stat-chip .l{font-size:10.5px;color:var(--muted);margin-top:3px;}

  .assignee-select{width:100%;}
</style>
</head>
<body>
<div class="wrap">

  <div class="portal-header">
    <div class="logo">AS</div>
    <div>
      <div class="title">Agile Scheduling Portal</div>
      <div class="sub">Interactive Educational Platform</div>
    </div>
  </div>

  <div class="hero">
    <span class="badge">MODULE 2</span>
    <h1>User Stories &amp; Backlog Management</h1>
    <p>Define end-user features as user stories, prioritize them in the Product Backlog, and commit the top items into the team's Active Sprint Backlog for execution. Data is saved to the database in real time.</p>
  </div>

  <div id="statusBar" class="status-bar"></div>

  <div class="grid">

    <!-- LEFT: Builder + Product Backlog -->
    <div>
      <div class="card">
        <h2>🛠️ Interactive User Story Builder</h2>
        <p class="desc">Draft a user story below and add it to the active Product Backlog list.</p>

        <div class="field-row">
          <div class="field">
            <label>As a (User Persona)</label>
            <input id="persona" type="text" placeholder="e.g., Student, Librarian, Admin">
          </div>
          <div class="field">
            <label>I want (Goal / Feature)</label>
            <input id="goal" type="text" placeholder="e.g., register online">
          </div>
        </div>

        <div class="field-row">
          <div class="field field-full">
            <label>So that (Benefit) — optional</label>
            <input id="benefit" type="text" placeholder="e.g., so I can enroll without visiting campus">
          </div>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Priority</label>
            <select id="priority">
              <option value="High">High Priority</option>
              <option value="Medium">Medium Priority</option>
              <option value="Low">Low Priority</option>
            </select>
          </div>
          <div class="field">
            <label>Points</label>
            <select id="points">
              <option>1</option><option>2</option><option>3</option>
              <option selected>5</option><option>8</option><option>13</option><option>21</option>
            </select>
          </div>
        </div>

        <div class="error-text" id="errorText">Please fill in both "As a" and "I want" fields.</div>
        <button class="btn-primary" id="addBtn" onclick="addToBacklog()">+ Add to Product Backlog</button>
      </div>

      <div class="section-title">
        <h2>📚 Product Backlog <span class="count-pill" id="backlogCount">0 items</span></h2>
      </div>
      <div class="card" style="padding:0;overflow:hidden;">
        <table>
          <thead><tr><th>ID</th><th>User Story</th><th>Priority</th><th>Pts</th><th></th></tr></thead>
          <tbody id="backlogBody">
            <tr class="empty-row"><td colspan="5">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- RIGHT: Active Sprint Backlog -->
    <div>
      <div class="card">
        <div class="section-title" style="margin-top:0;">
          <h2>🏃 Active Sprint Backlog <span class="count-pill" id="sprintCount">0 committed</span></h2>
        </div>
        <p class="desc" style="margin-top:-8px;">Items committed by the team for execution this sprint. Assign an owner and advance status as work progresses.</p>

        <table>
          <thead><tr><th>Task</th><th>Assignee</th><th>Status</th><th></th></tr></thead>
          <tbody id="sprintBody">
            <tr class="empty-row"><td colspan="4">Loading...</td></tr>
          </tbody>
        </table>

        <div class="stat-strip">
          <div class="stat-chip"><div class="n" id="statCommitted">0</div><div class="l">Committed pts</div></div>
          <div class="stat-chip"><div class="n" id="statTodo">0</div><div class="l">To Do</div></div>
          <div class="stat-chip"><div class="n" id="statProgress">0</div><div class="l">In Progress</div></div>
          <div class="stat-chip"><div class="n" id="statDone">0</div><div class="l">Completed</div></div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
const API = 'api.php';
const ASSIGNEES = ["Unassigned","Developer A","Developer B","Database Admin","QA Team"];
const STATUS_ORDER = ["To Do","In Progress","Completed"];
const STATUS_CLASS = {"To Do":"s-todo","In Progress":"s-progress","Completed":"s-done"};

let backlogData = [];
let sprintData = [];

function priorityClass(p){
  if(p==="High") return "p-high";
  if(p==="Medium") return "p-medium";
  return "p-low";
}

function showStatus(message, ok){
  const bar = document.getElementById('statusBar');
  bar.textContent = message;
  bar.className = 'status-bar show ' + (ok ? 'status-ok' : 'status-err');
  setTimeout(() => bar.classList.remove('show'), 3500);
}

async function apiCall(action, params={}){
  const formData = new FormData();
  formData.append('action', action);
  for(const key in params) formData.append(key, params[key]);

  try{
    const res = await fetch(API, { method:'POST', body: formData });
    const data = await res.json();
    if(!data.success){
      showStatus(data.message || 'Something went wrong.', false);
    }
    return data;
  }catch(err){
    showStatus('Could not reach the server. Check your database connection.', false);
    return { success:false };
  }
}

async function loadData(){
  const data = await apiCall('list');
  if(data.success){
    backlogData = data.backlog;
    sprintData = data.sprint;
    renderBacklog();
    renderSprint();
  }
}

async function addToBacklog(){
  const persona = document.getElementById('persona').value.trim();
  const goal = document.getElementById('goal').value.trim();
  const benefit = document.getElementById('benefit').value.trim();
  const priority = document.getElementById('priority').value;
  const points = document.getElementById('points').value;
  const errorText = document.getElementById('errorText');
  const addBtn = document.getElementById('addBtn');

  if(!persona || !goal){
    errorText.style.display = 'block';
    return;
  }
  errorText.style.display = 'none';

  addBtn.disabled = true;
  addBtn.textContent = 'Adding...';

  const result = await apiCall('add_story', { persona, goal, benefit, priority, points });

  addBtn.disabled = false;
  addBtn.textContent = '+ Add to Product Backlog';

  if(result.success){
    document.getElementById('persona').value = '';
    document.getElementById('goal').value = '';
    document.getElementById('benefit').value = '';
    showStatus(`${result.story_code} added to Product Backlog.`, true);
    await loadData();
  }
}

async function removeFromBacklog(id){
  const result = await apiCall('delete_story', { id });
  if(result.success){
    showStatus('Story removed.', true);
    await loadData();
  }
}

async function commitToSprint(id){
  const result = await apiCall('commit_to_sprint', { id });
  if(result.success){
    showStatus('Committed to Active Sprint Backlog.', true);
    await loadData();
  }
}

async function updateAssignee(id, value){
  const result = await apiCall('update_assignee', { id, assignee: value });
  if(result.success){
    const item = sprintData.find(s => s.id == id);
    if(item) item.assignee = value;
  }
}

async function advanceStatus(id){
  const result = await apiCall('advance_status', { id });
  if(result.success && !result.unchanged){
    showStatus(`Status updated to "${result.status}".`, true);
    await loadData();
  }
}

function renderBacklog(){
  const body = document.getElementById('backlogBody');
  document.getElementById('backlogCount').textContent = `${backlogData.length} item${backlogData.length!==1?'s':''}`;

  if(backlogData.length === 0){
    body.innerHTML = '<tr class="empty-row"><td colspan="5">No user stories yet. Add one above to get started.</td></tr>';
    return;
  }

  body.innerHTML = backlogData.map(item => `
    <tr>
      <td class="id-tag">${item.story_code}</td>
      <td>${escapeHtml(item.full_story)}</td>
      <td><span class="badge-priority ${priorityClass(item.priority)}">${item.priority}</span></td>
      <td><span class="points-chip">${item.points} pts</span></td>
      <td style="text-align:right;white-space:nowrap;">
        <button class="sprint-btn" onclick="commitToSprint(${item.id})">→ Sprint</button>
        <button class="icon-btn" title="Remove" onclick="removeFromBacklog(${item.id})">🗑</button>
      </td>
    </tr>
  `).join('');
}

function renderSprint(){
  const body = document.getElementById('sprintBody');
  document.getElementById('sprintCount').textContent = `${sprintData.length} committed`;

  const committedPts = sprintData.reduce((sum,s)=>sum+parseInt(s.points,10),0);
  document.getElementById('statCommitted').textContent = committedPts;
  document.getElementById('statTodo').textContent = sprintData.filter(s=>s.status==="To Do").length;
  document.getElementById('statProgress').textContent = sprintData.filter(s=>s.status==="In Progress").length;
  document.getElementById('statDone').textContent = sprintData.filter(s=>s.status==="Completed").length;

  if(sprintData.length === 0){
    body.innerHTML = '<tr class="empty-row"><td colspan="4">No tasks committed to this sprint yet.<br>Use "→ Sprint" on a backlog item to commit it.</td></tr>';
    return;
  }

  body.innerHTML = sprintData.map(item => {
    const isDone = item.status === "Completed";
    return `
    <tr>
      <td style="max-width:220px;">${escapeHtml(item.task)}<div style="margin-top:4px;"><span class="badge-priority ${priorityClass(item.priority)}">${item.priority}</span> <span class="points-chip" style="font-size:11px;">${item.points} pts</span></div></td>
      <td>
        <select class="assignee-select" onchange="updateAssignee(${item.id}, this.value)">
          ${ASSIGNEES.map(a => `<option ${a===item.assignee?'selected':''}>${a}</option>`).join('')}
        </select>
      </td>
      <td><span class="badge-status ${STATUS_CLASS[item.status]}">${item.status}</span></td>
      <td style="text-align:right;">
        <button class="advance-btn" ${isDone?'disabled':''} onclick="advanceStatus(${item.id})">
          ${isDone ? 'Done' : 'Advance →'}
        </button>
      </td>
    </tr>
  `}).join('');
}

function escapeHtml(str){
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

loadData();
</script>
</body>
</html>
