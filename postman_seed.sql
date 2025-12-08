-- SQL Seed Data for Postman Testing
-- Execute this in DBeaver to ensure the endpoints return the expected content.

-- 1. Insert Scenario for /recommendation/next
-- Matches the user request: ID 'pinjol_01', Category 'Utang', Title 'Mengelola Pinjol dengan Bijak'
INSERT INTO scenarios (id, category, title, question, difficulty, expected_benefit, created_at, updated_at)
VALUES 
('pinjol_01', 'utang', 'Mengelola Pinjol dengan Bijak', 'Bagaimana sikap terbaik jika teman mengajak pinjaman online ilegal?', 20, 8, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), category = VALUES(category);

-- 2. Insert Scenario Options (Optional, required for gameplay submission)
INSERT INTO scenario_options (scenario_id, option_id, text, is_correct, response, score_change, created_at, updated_at)
VALUES
('pinjol_01', 'A', 'Menolak dengan tegas dan mengingatkan risiko.', 1, 'Bagus! Anda menghindari risiko besar.', '{"utang": 8, "overall": 5}', NOW(), NOW()),
('pinjol_01', 'B', 'Ikut mencoba sedikit.', 0, 'Bahaya! Bunga pinjol ilegal sangat tinggi.', '{"utang": -10, "overall": -5}', NOW(), NOW())
ON DUPLICATE KEY UPDATE text = VALUES(text);

-- 3. Insert specific Risk/Chance Cards if testing /card/draw
INSERT INTO cards (id, type, title, narration, categories, score_change, action, created_at, updated_at)
VALUES 
('risk_01', 'risk', 'Darurat Medis', 'Anda sakit gigi dan butuh ke dokter.', '["tabungan_dan_dana_darurat"]', -15, 'standard', NOW(), NOW()),
('chance_01', 'chance', 'Bonus Tahunan', 'Perusahaan memberikan bonus kinerja.', '["pendapatan", "tabungan_dan_dana_darurat"]', 20, 'standard', NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- 4. Insert Intervention Template for /intervention/trigger (Level 2)
INSERT INTO intervention_templates (level, title_template, message_template, actions_template, created_at, updated_at)
VALUES 
(2, 'Peringatan', '⚠️ Kamu sudah 3x salah di skenario Utang. Mungkin perlu review konsep bunga majemuk dulu?', '[{"id": "heed", "text": "Lihat Penjelasan Singkat"}, {"id": "ignore", "text": "Lanjut Tanpa Hint"}]', NOW(), NOW())
ON DUPLICATE KEY UPDATE message_template = VALUES(message_template);

-- 5. Ensure a dummy user exists in `users` table (if your auth requires it via token)
-- NOTE: If you are using the 'player_dummy_profiling_infinite' ID directly, the system mock bypasses the DB profile check.
-- However, creating a session usually requires a user record.
INSERT INTO users (id, name, email, password, created_at, updated_at)
VALUES 
(1, 'Dummy User', 'dummy@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- 6. Ensure Player Profile exists (Optional, as code mocks it, but good for completeness)
INSERT INTO player_profiles (player_id, user_id, lifetime_scores, weak_areas, confidence_level, created_at, updated_at)
VALUES 
('player_dummy_profiling_infinite', 1, '{"pendapatan": 65, "anggaran": 65, "tabungan_dan_dana_darurat": 50, "utang": 45, "investasi": 60, "asuransi_dan_proteksi": 60, "tujuan_jangka_panjang": 60, "overall": 58}', '["tabungan_dan_dana_darurat", "utang"]', 0.78, NOW(), NOW())
ON DUPLICATE KEY UPDATE lifetime_scores = VALUES(lifetime_scores);
