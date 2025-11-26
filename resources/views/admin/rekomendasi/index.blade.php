{{-- filepath: resources/views/admin/rekomendasi/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Rekomendasi Pembelajaran')

@section('content')
    <h2 class="mb-3">
        <i class="fa fa-lightbulb mr-2" style="color:#ffc107;"></i>
        Rekomendasi Pembelajaran Lanjutan
    </h2>
    <p class="text-muted mb-4">
        Monitoring saran sistem untuk pembelajaran berikutnya berdasarkan hasil sesi pemain.
    </p>

    <!-- Player Selection Card -->
    <div class="bg-white p-4 rounded shadow mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="playerSelect" class="form-label font-weight-bold">Pilih Pemain (Player ID):</label>
                <select id="playerSelect" class="form-control form-control-lg" onchange="handlePlayerChange()">
                    <option value="">-- Pilih player dari daftar --</option>
                    <option value="p001">Pemain A (ID: p001)</option>
                    <option value="p002">Pemain B (ID: p002)</option>
                    <option value="p123" selected>Pemain C (ID: p123) - Data Mock</option>
                    @forelse($players as $p)
                        <option value="{{ $p->id }}" data-name="{{ $p->name ?? $p->id }}">{{ $p->name ?? $p->id }} (ID: {{ $p->id }})</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>

    <!-- Selected Player Info -->
    <div id="selectedPlayerInfo" class="mb-3"></div>

    <!-- Recommendations Area -->
    <div id="recommendationsArea" class="mt-4"></div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    body { 
        background:#f7f9fc; 
        font-family: 'Inter', sans-serif;
    }
    
    /* Modern Recommendation Card */
    .recommendation-card {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 2px 12px rgba(49,130,206,0.07);
        border-left: 4px solid #2563eb;
        margin-bottom: 2rem;
        padding: 2rem;
        transition: all 0.3s ease;
    }
    .recommendation-card:hover { 
        box-shadow: 0 8px 25px rgba(49,130,206,0.15);
        transform: translateY(-2px);
    }
    
    /* Title Styling */
    .rec-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }
    
    /* Section Labels */
    .section-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2563eb;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    /* Info Panels */
    .info-panel {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.25rem;
        height: 100%;
        border: 1px solid #e2e8f0;
    }
    
    /* Progress Bars */
    .score-item {
        margin-bottom: 1rem;
    }
    .score-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.25rem;
        display: flex;
        justify-content: space-between;
    }
    .progress-thin {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        background: #e2e8f0;
    }
    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    
    /* Peer Insight */
    .insight-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #059669;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .insight-text {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.4;
    }
    
    /* Alert Styling */
    .player-header {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    /* Demo Warning */
    .demo-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fbbf24;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        margin-top: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .recommendation-card {
            padding: 1.25rem;
        }
        .rec-title {
            font-size: 1.25rem;
        }
        .insight-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Mock Data untuk Demo
const MOCK_DATA = {
    recommendations: [
        {
            title: 'Mengelola Pinjol dengan Bijak',
            reason: 'Top weak area: utang (weighted gap=91)',
            expected_benefit: 'Mengurangi risiko keterlambatan pembayaran',
            peer_insight: { peer_success_rate: 0.64 },
            scores: { content: 75, collaborative: 40, performance: 62 },
            scenario_id: 67
        },
        {
            title: 'Menyusun Anggaran Rumah Tangga',
            reason: 'Top weak area: budgeting (weighted gap=85)',
            expected_benefit: 'Meningkatkan kemampuan perencanaan keuangan',
            peer_insight: { peer_success_rate: 0.72 },
            scores: { content: 82, collaborative: 50, performance: 70 },
            scenario_id: 45
        }
    ]
};

// Helper Functions
function getScoreColor(score) {
    score = parseInt(score) || 0;
    if (score >= 80) return 'bg-success';
    if (score >= 60) return 'bg-warning';
    return 'bg-danger';
}

function createScoreItem(label, score) {
    score = parseInt(score) || 0;
    const colorClass = getScoreColor(score);
    return `
        <div class="score-item">
            <div class="score-label">
                <span>${label}</span>
                <span class="font-weight-bold">${score}%</span>
            </div>
            <div class="progress-thin">
                <div class="progress-bar ${colorClass}" style="width: ${score}%"></div>
            </div>
        </div>
    `;
}

function escapeHtml(s) {
    return String(s||'').replace(/[&<>"'`=\/]/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',
        "'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[c]);
}

function renderRecommendations(data, isDemo = false) {
    const list = data.recommendations || [];
    if (!list.length) {
        return '<div class="alert alert-info"><i class="fa fa-info-circle mr-2"></i>Tidak ada rekomendasi untuk pemain ini.</div>';
    }
    
    let html = '';
    if (isDemo) {
        html += '<div class="demo-warning"><i class="fa fa-exclamation-triangle mr-2"></i><strong>Mode Demo:</strong> Data simulasi ditampilkan (backend mungkin tidak tersedia).</div>';
    }
    
    list.forEach(r => {
        const peer = Math.round((r.peer_insight?.peer_success_rate || 0) * 100);
        html += `
        <div class="recommendation-card">
            <!-- Title -->
            <h3 class="rec-title">${escapeHtml(r.title)}</h3>
            
            <!-- Kuadran Atas: Alasan dan Manfaat -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <span class="section-label">Alasan Rekomendasi</span>
                    <p class="text-muted mb-0">${escapeHtml(r.reason)}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="section-label">Manfaat yang Diharapkan</span>
                    <p class="text-muted mb-0">${escapeHtml(r.expected_benefit)}</p>
                </div>
            </div>
            
            <!-- Kuadran Bawah -->
            <div class="row">
                <!-- Kuadran Kiri Bawah: Skor Komponen -->
                <div class="col-lg-7 mb-3">
                    <div class="info-panel">
                        <h5 class="font-weight-bold text-secondary mb-3">
                            <i class="fa fa-chart-bar mr-2"></i>Skor Komponen (Dampak Pembelajaran)
                        </h5>
                        ${createScoreItem('Content-Based Score', r.scores?.content)}
                        ${createScoreItem('Collaborative Score', r.scores?.collaborative)}
                        ${createScoreItem('Performance Score', r.scores?.performance)}
                    </div>
                </div>
                
                <!-- Kuadran Kanan Bawah: Peer Insight & Aksi -->
                <div class="col-lg-5 mb-3">
                    <div class="info-panel text-center">
                        <h5 class="font-weight-bold text-secondary mb-3">
                            <i class="fa fa-users mr-2"></i>Wawasan Rekan (Peer Insight)
                        </h5>
                        <div class="insight-number">${peer}%</div>
                        <p class="insight-text">
                            Rata-rata Tingkat Keberhasilan Peer
                            <br><small>Menunjukkan efektivitas skenario ini pada kelompok pemain dengan profil serupa.</small>
                        </p>
                        <button class="btn btn-primary rounded-pill mt-2" onclick="viewScenarioDetail(${r.scenario_id})">
                            <i class="fa fa-eye mr-2"></i>Lihat Detail Skenario
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    });
    
    return html;
}

function viewScenarioDetail(scenarioId) {
    alert(`Navigasi ke Detail Skenario ID: ${scenarioId}\n\nDalam implementasi nyata, ini akan redirect ke:\n/admin/scenario/${scenarioId}`);
    // window.location.href = `/admin/scenario/${scenarioId}`;
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
        document.getElementById('recommendationsArea').innerHTML = '';
        return;
    }

    // Update Header
    document.getElementById('selectedPlayerInfo').innerHTML = 
        `<div class="player-header">
            <h4 class="mb-0"><i class="fa fa-user mr-2"></i>Rekomendasi untuk: ${escapeHtml(playerName)}</h4>
        </div>`;

    const area = document.getElementById('recommendationsArea');
    area.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Memuat rekomendasi...</p></div>';

    // Jika p123 (mock data), langsung tampilkan mock data
    if (playerId === 'p123') {
        setTimeout(() => {
            area.innerHTML = renderRecommendations(MOCK_DATA, false);
        }, 800);
        return;
    }

    // Fetch dari API dengan fallback ke demo
    fetch('/recommendation/next', {
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
        area.innerHTML = renderRecommendations(json, false);
    })
    .catch(error => {
        console.warn('API Error, falling back to demo data:', error);
        area.innerHTML = renderRecommendations(MOCK_DATA, true);
    });
}

// Auto-load default player on page load
window.addEventListener('load', function() {
    // Auto-trigger untuk player p123 yang sudah terpilih
    setTimeout(() => {
        handlePlayerChange();
    }, 500);
});
</script>
@endpush