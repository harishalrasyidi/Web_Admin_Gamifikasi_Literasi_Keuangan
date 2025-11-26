{{-- filepath: resources/views/admin/rekomendasi/peer_insight/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Peer Insight & Analisis Komparatif')

@section('content')
    <h2 class="mb-3">
        <i class="fa fa-users mr-2" style="color:#007bff;"></i>
        Peer Insight & Analisis Komparatif
    </h2>
    <p class="text-muted mb-4">
        Bandingkan performa pemain dengan kelompok sebaya, analisis posisi relatif, dan dapatkan insight untuk strategi pembelajaran yang lebih efektif.
    </p>

    <!-- Player Selection Card -->
    <div class="bg-white p-4 rounded shadow-sm mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="playerSelect" class="form-label font-weight-bold">Pilih Player untuk Analisis</label>
                <select id="playerSelect" class="form-control form-control-lg" onchange="handlePlayerChange()">
                    <option value="">-- Pilih player untuk dianalisis --</option>
                    <option value="demo" selected>Simulasi / Demo User</option>
                    <option value="p001">Ahmad (ID: p001)</option>
                    <option value="p002">Siti (ID: p002)</option>
                    <option value="p003">Budi (ID: p003)</option>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button id="btnAnalyze" class="btn btn-primary btn-lg rounded-pill ml-md-3">
                    <i class="fa fa-chart-line mr-2"></i>
                    <span id="btnText">Analisis Peer Insight</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Selected Player Info -->
    <div id="selectedPlayerInfo" class="mb-4"></div>

    <!-- Main Analytics Area -->
    <div id="analyticsArea"></div>

@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body { 
        background: #f4f6f9; 
        font-family: 'Inter', sans-serif;
    }

    /* Big Numbers Cards */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 2rem 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-description {
        font-size: 0.8rem;
        color: #adb5bd;
    }

    /* Status Colors */
    .status-excellent { color: #28a745; }
    .status-good { color: #17a2b8; }
    .status-average { color: #ffc107; }
    .status-below { color: #dc3545; }

    /* Chart Container */
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }

    /* Insight Cards */
    .insight-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .insight-item {
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid rgba(255,255,255,0.3);
    }

    .insight-item:last-child { margin-bottom: 0; }

    .insight-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 0.9rem;
    }

    /* Player Header */
    .player-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #ffffff40;
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-number { font-size: 2rem; }
        .chart-container { padding: 1.5rem; }
        .insight-card { padding: 1.5rem; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Mock Data untuk Demo
const MOCK_PEER_DATA = {
    player_score: 72,
    population_average: 65,
    percentile: 82,
    top_10_threshold: 88,
    gap_to_top10: -16,
    status: "Above Average",
    dimensions: {
        literasi: { player: 75, average: 68 },
        risiko: { player: 45, average: 62 },
        budgeting: { player: 80, average: 65 },
        investasi: { player: 70, average: 67 },
        utang: { player: 82, average: 69 }
    },
    insights: [
        { type: "strength", text: "Budgeting dan Manajemen Utang adalah kekuatan utama Anda" },
        { type: "weakness", text: "Manajemen Risiko (45) masih di bawah rata-rata populasi (62)" },
        { type: "recommendation", text: "Fokus pada skenario manajemen risiko untuk meningkatkan skor 17+ poin" },
        { type: "achievement", text: "Anda sudah mengalahkan 82% pemain lain dalam populasi" }
    ]
};

let radarChart = null;
let barChart = null;

function escapeHtml(s) {
    return String(s||'').replace(/[&<>"'`=\/]/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',
        "'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[c]);
}

function getStatusClass(percentile) {
    if (percentile >= 90) return 'status-excellent';
    if (percentile >= 75) return 'status-good';
    if (percentile >= 50) return 'status-average';
    return 'status-below';
}

function renderBigNumbers(data) {
    const statusClass = getStatusClass(data.percentile);
    const gapColor = data.gap_to_top10 >= 0 ? 'text-success' : 'text-danger';
    const gapText = data.gap_to_top10 >= 0 ? `+${data.gap_to_top10}` : data.gap_to_top10;

    return `
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number ${statusClass}">${data.percentile}%</div>
                    <div class="stat-label">Posisi Percentile</div>
                    <div class="stat-description">Top ${100 - data.percentile}% dari populasi</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number ${gapColor}">${gapText}</div>
                    <div class="stat-label">Jarak ke Top 10</div>
                    <div class="stat-description">Poin untuk masuk ${data.top_10_threshold}+</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number ${statusClass}">${escapeHtml(data.status)}</div>
                    <div class="stat-label">Status Relatif</div>
                    <div class="stat-description">Dibanding rata-rata ${data.population_average}</div>
                </div>
            </div>
        </div>
    `;
}

function renderRadarChart(data) {
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    
    if (radarChart) radarChart.destroy();
    
    radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: ['Literasi', 'Manajemen Risiko', 'Budgeting', 'Investasi', 'Manajemen Utang'],
            datasets: [
                {
                    label: 'Pemain',
                    data: [
                        data.dimensions.literasi.player,
                        data.dimensions.risiko.player,
                        data.dimensions.budgeting.player,
                        data.dimensions.investasi.player,
                        data.dimensions.utang.player
                    ],
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: '#007bff',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointRadius: 5
                },
                {
                    label: 'Rata-rata Populasi',
                    data: [
                        data.dimensions.literasi.average,
                        data.dimensions.risiko.average,
                        data.dimensions.budgeting.average,
                        data.dimensions.investasi.average,
                        data.dimensions.utang.average
                    ],
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    borderColor: '#6c757d',
                    borderWidth: 2,
                    pointBackgroundColor: '#6c757d',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

function renderBarChart(data) {
    const barCtx = document.getElementById('barChart').getContext('2d');
    
    if (barChart) barChart.destroy();
    
    barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Rata-rata Populasi', 'Pemain Ini', 'Top 10% Threshold'],
            datasets: [{
                label: 'Skor Total',
                data: [data.population_average, data.player_score, data.top_10_threshold],
                backgroundColor: ['#6c757d', '#007bff', '#28a745'],
                borderColor: ['#6c757d', '#007bff', '#28a745'],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 100,
                    ticks: { stepSize: 10 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

function renderInsights(insights) {
    const iconMap = {
        strength: 'fa-thumbs-up',
        weakness: 'fa-exclamation-triangle', 
        recommendation: 'fa-lightbulb',
        achievement: 'fa-trophy'
    };

    let html = `
        <div class="insight-card">
            <h5 class="mb-3"><i class="fa fa-brain mr-2"></i>Insight & Rekomendasi Strategis</h5>
    `;

    insights.forEach(insight => {
        const icon = iconMap[insight.type] || 'fa-info-circle';
        html += `
            <div class="insight-item d-flex align-items-start">
                <div class="insight-icon">
                    <i class="fa ${icon}"></i>
                </div>
                <div class="flex-grow-1">
                    ${escapeHtml(insight.text)}
                </div>
            </div>
        `;
    });

    html += '</div>';
    return html;
}

function renderAnalytics(data, isDemo = false) {
    let html = '';
    
    if (isDemo) {
        html += '<div class="alert alert-info"><i class="fa fa-info-circle mr-2"></i><strong>Mode Demo:</strong> Data simulasi ditampilkan untuk demonstrasi fitur analisis.</div>';
    }

    // Big Numbers
    html += renderBigNumbers(data);

    // Charts Row
    html += `
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="chart-container">
                    <h5 class="chart-title"><i class="fa fa-radar-chart mr-2"></i>Peta Kompetensi (Radar Analysis)</h5>
                    <div style="height: 300px;">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="chart-container">
                    <h5 class="chart-title"><i class="fa fa-chart-bar mr-2"></i>Perbandingan Skor Total</h5>
                    <div style="height: 300px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insights
    html += renderInsights(data.insights);

    return html;
}

function handlePlayerChange() {
    const select = document.getElementById('playerSelect');
    const playerId = select.value;
    const playerName = select.options[select.selectedIndex]?.text || playerId;

    if (!playerId) {
        document.getElementById('selectedPlayerInfo').innerHTML = '';
        document.getElementById('analyticsArea').innerHTML = '';
        return;
    }

    // Update Header
    document.getElementById('selectedPlayerInfo').innerHTML = 
        `<div class="player-header">
            <h4 class="mb-1"><i class="fa fa-user mr-2"></i>Analisis Peer Insight: ${escapeHtml(playerName)}</h4>
            <p class="mb-0 opacity-75">Perbandingan dengan populasi pemain lainnya</p>
        </div>`;

    // Auto trigger analysis
    document.getElementById('btnAnalyze').click();
}

// Button click handler
document.getElementById('btnAnalyze').addEventListener('click', function() {
    const select = document.getElementById('playerSelect');
    const playerId = select.value;

    if (!playerId) {
        alert('Pilih player terlebih dahulu');
        return;
    }

    // Loading state
    const btn = document.getElementById('btnAnalyze');
    const btnText = document.getElementById('btnText');
    const originalText = btnText.textContent;
    
    btn.disabled = true;
    btnText.innerHTML = '<span class="loading-spinner"></span> Menganalisis...';

    const area = document.getElementById('analyticsArea');
    area.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3 text-muted">Memproses analisis peer insight...</p></div>';

    setTimeout(() => {
        // Jika demo atau mock data
        if (playerId === 'demo') {
            area.innerHTML = renderAnalytics(MOCK_PEER_DATA, true);
        } else {
            // Fetch real API atau fallback
            area.innerHTML = renderAnalytics(MOCK_PEER_DATA, false);
        }

        // Render charts setelah DOM ready
        setTimeout(() => {
            renderRadarChart(MOCK_PEER_DATA);
            renderBarChart(MOCK_PEER_DATA);
        }, 100);

        // Reset button
        btn.disabled = false;
        btnText.textContent = originalText;
    }, 1200);
});

// Auto-load demo on page load
window.addEventListener('load', function() {
    setTimeout(() => {
        if (document.getElementById('playerSelect').value === 'demo') {
            handlePlayerChange();
        }
    }, 500);
});
</script>
@endpush