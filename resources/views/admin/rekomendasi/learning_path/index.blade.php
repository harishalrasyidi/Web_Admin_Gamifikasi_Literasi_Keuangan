{{-- filepath: resources/views/admin/rekomendasi/learning_path/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Learning Path Player')

@section('content')
    <h2 class="mb-3">
        <i class="fa fa-route mr-2" style="color:#007bff;"></i>
        Learning Path Player
    </h2>
    <p class="text-muted mb-4">
        Pantau alur pembelajaran jangka panjang yang disarankan sistem untuk setiap pemain, lengkap dengan estimasi waktu, target skor, dan peluang sukses.
    </p>

    <!-- Player Selection Card -->
    <div class="bg-white p-4 rounded shadow mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="playerSelect" class="form-label font-weight-bold">Pilih Player</label>
                <select id="playerSelect" class="form-control form-control-lg" onchange="handlePlayerChange()">
                    <option value="">-- Pilih player dari daftar --</option>
                    <option value="p001">Ahmad (ID: p001)</option>
                    <option value="p002">Siti (ID: p002)</option>
                    <option value="p003" selected>Budi (ID: p003) - Data Mock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Selected Player Info -->
    <div id="selectedPlayerInfo" class="mb-3"></div>

    <!-- Learning Path Area -->
    <div id="learningPathArea" class="mt-4"></div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    body { 
        background: #f4f6f9; 
        font-family: 'Inter', sans-serif;
    }

    /* Header Summary Card */
    .path-summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.15);
    }

    .summary-metric {
        text-align: center;
        padding: 1rem;
    }

    .metric-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .metric-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Timeline Styling */
    .timeline-container {
        position: relative;
        padding-left: 3rem;
    }

    .timeline-line {
        position: absolute;
        left: 1.5rem;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #007bff, #6c757d);
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .timeline-bullet {
        position: absolute;
        left: -3rem;
        top: 1.5rem;
        width: 3rem;
        height: 3rem;
        background: white;
        border: 4px solid #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #007bff;
        z-index: 10;
    }

    .phase-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 1.5rem 2rem;
        margin: 0;
    }

    .phase-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .phase-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .phase-body {
        padding: 2rem;
    }

    .scenario-badges {
        margin-top: 1rem;
    }

    .scenario-badge {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .progress-custom {
        height: 1.5rem;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #f8f9fa;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar-custom {
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 0.75rem;
        transition: width 0.8s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Success Probability Badge */
    .success-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 1rem;
    }

    /* Player Header */
    .player-header {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .timeline-container { padding-left: 2rem; }
        .timeline-bullet { left: -2rem; width: 2rem; height: 2rem; }
        .phase-body { padding: 1.5rem; }
        .metric-number { font-size: 2rem; }
    }
</style>
@endpush

@push('scripts')
<script>
// Mock Data untuk Demo
const MOCK_PATH_DATA = {
    title: "Path Optimal ke Skor 80+",
    current_score: 58,
    target_score: 80,
    estimated_sessions: 14,
    success_probability: 0.78,
    steps: [
        {
            phase: "Phase 1",
            focus: "Dana Darurat",
            estimated_sessions: 6,
            score_gain: 15,
            scenarios: [22, 23, 24],
            current_progress: 40,
            description: "Membangun fondasi pengetahuan tentang dana darurat dan perencanaan keuangan dasar"
        },
        {
            phase: "Phase 2", 
            focus: "Manajemen Utang",
            estimated_sessions: 5,
            score_gain: 12,
            scenarios: [18, 19],
            current_progress: 20,
            description: "Strategi mengelola dan melunasi berbagai jenis utang secara efektif"
        },
        {
            phase: "Phase 3",
            focus: "Investasi Pemula",
            estimated_sessions: 3,
            score_gain: 8,
            scenarios: [31, 32, 33, 34],
            current_progress: 0,
            description: "Pengenalan konsep investasi dan diversifikasi portofolio sederhana"
        }
    ]
};

function escapeHtml(s) {
    return String(s||'').replace(/[&<>"'`=\/]/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',
        "'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[c]);
}

function renderProgressHeader(data) {
    const progressPct = Math.round((data.current_score / data.target_score) * 100);
    const scoreGain = data.target_score - data.current_score;
    const successPct = Math.round(data.success_probability * 100);

    return `
        <div class="path-summary-card">
            <h3 class="text-center mb-4" style="font-weight: 600;">${escapeHtml(data.title)}</h3>
            
            <!-- Main Progress Bar -->
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Progress Menuju Target</span>
                    <span><strong>${data.current_score} / ${data.target_score}</strong></span>
                </div>
                <div class="progress-custom">
                    <div class="progress-bar-custom" style="width: ${progressPct}%">
                        ${progressPct}%
                    </div>
                </div>
            </div>

            <!-- Summary Metrics -->
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="summary-metric">
                        <div class="metric-number">${data.estimated_sessions}</div>
                        <div class="metric-label">Estimasi Sesi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-metric">
                        <div class="metric-number">+${scoreGain}</div>
                        <div class="metric-label">Target Kenaikan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-metric">
                        <div class="metric-number">${successPct}%</div>
                        <div class="metric-label">Peluang Sukses</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderTimeline(steps) {
    let html = `
        <div class="timeline-container">
            <div class="timeline-line"></div>
    `;

    steps.forEach((step, index) => {
        const scenarioBadges = step.scenarios.map(id => 
            `<span class="scenario-badge">Skenario ${id}</span>`
        ).join('');

        html += `
            <div class="timeline-item">
                <div class="timeline-bullet">${index + 1}</div>
                
                <div class="phase-header">
                    <div class="phase-title">${escapeHtml(step.phase)}: ${escapeHtml(step.focus)}</div>
                    <div class="phase-subtitle">${escapeHtml(step.description)}</div>
                </div>
                
                <div class="phase-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Progress Fase Ini</h6>
                                <div class="progress-custom">
                                    <div class="progress-bar-custom" style="width: ${step.current_progress}%">
                                        ${step.current_progress}% Selesai
                                    </div>
                                </div>
                            </div>
                            
                            <div class="scenario-badges">
                                <small class="text-muted d-block mb-2">Skenario yang Akan Dipelajari:</small>
                                ${scenarioBadges}
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <h5 class="text-primary mb-1">${step.estimated_sessions}</h5>
                                    <small class="text-muted">Estimasi Sesi</small>
                                </div>
                                <div class="mb-3">
                                    <h5 class="text-success mb-1">+${step.score_gain}</h5>
                                    <small class="text-muted">Gain Poin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div>';
    return html;
}

function renderLearningPath(data, isDemo = false) {
    let html = '';
    
    if (isDemo) {
        html += '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle mr-2"></i><strong>Mode Demo:</strong> Data simulasi ditampilkan (backend mungkin tidak tersedia).</div>';
    }
    
    html += renderProgressHeader(data);
    html += '<h4 class="mb-4"><i class="fa fa-list-ol mr-2"></i>Timeline Pembelajaran</h4>';
    html += renderTimeline(data.steps);
    
    return html;
}

function selectPlayer(playerId, playerName) {
    document.getElementById('playerSelect').value = playerId;
    handlePlayerChange();
}

// Main Function - dipanggil saat dropdown berubah
function handlePlayerChange() {
    const select = document.getElementById('playerSelect');
    const playerId = select.value;
    const playerName = select.options[select.selectedIndex]?.text || playerId;

    if (!playerId) {
        document.getElementById('selectedPlayerInfo').innerHTML = '';
        document.getElementById('learningPathArea').innerHTML = '';
        return;
    }

    // Update Header
    document.getElementById('selectedPlayerInfo').innerHTML = 
        `<div class="player-header">
            <h4 class="mb-0"><i class="fa fa-user mr-2"></i>Learning Path untuk: ${escapeHtml(playerName)}</h4>
        </div>`;

    const area = document.getElementById('learningPathArea');
    area.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Memuat learning path...</p></div>';

    // Jika p003 (mock data), langsung tampilkan mock data
    if (playerId === 'p003') {
        setTimeout(() => {
            area.innerHTML = renderLearningPath(MOCK_PATH_DATA, false);
        }, 1000);
        return;
    }

    // Fetch dari API dengan fallback ke demo
    fetch('/recommendation/path', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ player_id: playerId })
    })
    .then(r => r.ok ? r.json() : Promise.reject(r))
    .then(json => {
        area.innerHTML = renderLearningPath(json, false);
    })
    .catch(error => {
        console.warn('API Error, falling back to demo data:', error);
        area.innerHTML = renderLearningPath(MOCK_PATH_DATA, true);
    });
}

// Auto-load default player on page load
window.addEventListener('load', function() {
    // Auto-trigger untuk player p003 yang sudah terpilih
    setTimeout(() => {
        handlePlayerChange();
    }, 500);
});
</script>
@endpush