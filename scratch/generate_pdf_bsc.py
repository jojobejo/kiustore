import os
from reportlab.lib import pagesizes, colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super(NumberedCanvas, self).__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_header_footer(num_pages)
            super(NumberedCanvas, self).showPage()
        super(NumberedCanvas, self).save()

    def draw_header_footer(self, page_count):
        self.saveState()
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748B"))
        
        # Header (pages > 1)
        if self._pageNumber > 1:
            self.drawString(40, 560, "PT. KARISMA INDOAGRO UNIVERSAL | BALANCED SCORECARD & STRATEGIC EXECUTIVE REPORT")
            self.drawRightString(802, 560, "STATUS: 96.79% (LAUNCH READY)")
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(40, 552, 802, 552)
            
        # Footer
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(40, 35, 802, 35)
        self.drawString(40, 22, "Dokumen Resmi Evaluasi Kinerja & Peluncuran Multi-Platform (iOS, Android, CI3 API) - Confidential")
        self.drawRightString(802, 22, f"Halaman {self._pageNumber} dari {page_count}")
        self.restoreState()

def generate_pdf():
    pdf_path = "docs/LAPORAN_EKSEKUTIF_BSC.pdf"
    doc = SimpleDocTemplate(
        pdf_path,
        pagesize=pagesizes.landscape(pagesizes.A4),
        leftMargin=40,
        rightMargin=40,
        topMargin=50,
        bottomMargin=45
    )
    
    styles = getSampleStyleSheet()
    
    # Custom Styles
    c_navy = colors.HexColor("#0B132B")
    c_navy_light = colors.HexColor("#1C2541")
    c_cyan = colors.HexColor("#0284C7")
    c_emerald = colors.HexColor("#059669")
    c_amber = colors.HexColor("#D97706")
    c_rose = colors.HexColor("#DC2626")
    
    style_cover_title = ParagraphStyle(
        'CoverTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=22,
        leading=26,
        textColor=c_navy,
        spaceAfter=6
    )
    style_cover_sub = ParagraphStyle(
        'CoverSub',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=11,
        leading=15,
        textColor=colors.HexColor("#475569"),
        spaceAfter=15
    )
    style_section_h1 = ParagraphStyle(
        'SectionH1',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=16,
        textColor=c_navy,
        spaceBefore=10,
        spaceAfter=6
    )
    style_meta_info = ParagraphStyle(
        'MetaInfo',
        parent=styles['Normal'],
        fontName='Helvetica-Oblique',
        fontSize=9,
        leading=12,
        textColor=colors.HexColor("#64748B"),
        spaceAfter=8
    )
    style_cell = ParagraphStyle(
        'CellText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8,
        leading=10,
        textColor=colors.HexColor("#0F172A")
    )
    style_cell_bold = ParagraphStyle(
        'CellBold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10,
        textColor=colors.HexColor("#0F172A")
    )
    style_cell_header = ParagraphStyle(
        'CellHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10,
        textColor=colors.white,
        alignment=1
    )
    style_note = ParagraphStyle(
        'NoteText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=9.5,
        textColor=colors.HexColor("#334155")
    )
    
    story = []
    
    # ----------------------------------------------------
    # COVER / HEADER BANNER
    # ----------------------------------------------------
    story.append(Paragraph("PT. KARISMA INDOAGRO UNIVERSAL", ParagraphStyle('SubHeader', fontName='Helvetica-Bold', fontSize=10, textColor=c_cyan, leading=12)))
    story.append(Paragraph("BALANCED SCORECARD & STRATEGIC EXECUTIVE REPORT", style_cover_title))
    story.append(Paragraph("<b>Evaluasi Kesiapan Peluncuran Ekosistem Digital Karisma Online Multi-Platform</b> (iOS Swift Native, Android Kotlin/Java, CI3 RESTful API PHP 8.x) | Periode: Q3 2026 | Status Kesiapan: <font color='#059669'><b>96.79% (LAUNCH READY)</b></font>", style_cover_sub))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_cyan, spaceBefore=0, spaceAfter=10))
    
    # ----------------------------------------------------
    # SECTION 1: RINGKASAN PERSPEKTIF BSC SESUAI ALUR PROYEK
    # ----------------------------------------------------
    story.append(Paragraph("1. RINGKASAN PERSPEKTIF BALANCED SCORECARD (BERDASARKAN ALUR PROYEK)", style_section_h1))
    
    overview_headers = [
        Paragraph("No", style_cell_header),
        Paragraph("Perspektif BSC & Alur Proyek", style_cell_header),
        Paragraph("Bobot", style_cell_header),
        Paragraph("Target", style_cell_header),
        Paragraph("Skor (%)", style_cell_header),
        Paragraph("Status", style_cell_header),
        Paragraph("Inisiatif Kunci Terverifikasi", style_cell_header)
    ]
    overview_rows = [
        [
            Paragraph("1", style_cell_bold),
            Paragraph("<b>1. Learning, Growth & System</b><br/><i>(Alur Fondasi Infrastruktur)</i>", style_cell),
            Paragraph("20%", style_cell),
            Paragraph("100%", style_cell),
            Paragraph("<b>98.75%</b>", style_cell),
            Paragraph("<font color='#059669'><b>🟢 Ready</b></font>", style_cell),
            Paragraph("Arsitektur Tri-Platform Native (iOS/Android/CI3), Bearer Token SHA-256, Salesman Mapping, Flagging is_internal.", style_cell)
        ],
        [
            Paragraph("2", style_cell_bold),
            Paragraph("<b>2. Internal Business Process</b><br/><i>(Alur Operasional Rantai Pasok)</i>", style_cell),
            Paragraph("30%", style_cell),
            Paragraph("100%", style_cell),
            Paragraph("<b>92.40%</b>", style_cell),
            Paragraph("<font color='#D97706'><b>🟡 On Progress</b></font>", style_cell),
            Paragraph("RajaOngkir Pro Tingkat Kecamatan, 30-Min Quote Lock, Dual-Unit Stok, Deadstock Engine, ACID Concurrency.", style_cell)
        ],
        [
            Paragraph("3", style_cell_bold),
            Paragraph("<b>3. Customer & Market Access</b><br/><i>(Alur Pasar & Kios Mitra)</i>", style_cell),
            Paragraph("25%", style_cell),
            Paragraph("100%", style_cell),
            Paragraph("<b>100.0%</b>", style_cell),
            Paragraph("<font color='#059669'><b>🟢 Ready</b></font>", style_cell),
            Paragraph("Apple Guideline 5.1.1(v) Account Deletion API, Guest Browsing Flow, In-App Live CS Chat, Android Play Store V1.", style_cell)
        ],
        [
            Paragraph("4", style_cell_bold),
            Paragraph("<b>4. Financial Perspective</b><br/><i>(Alur Finansial & Arus Kas)</i>", style_cell),
            Paragraph("25%", style_cell),
            Paragraph("100%", style_cell),
            Paragraph("<b>98.00%</b>", style_cell),
            Paragraph("<font color='#059669'><b>🟢 Ready</b></font>", style_cell),
            Paragraph("Bank BRI BRIVA SNAP 15-Min VA, Proteksi Multi-Tier Margin Server-Side, Secure Multi-Bank Receipt Hash, Akselerasi DSO.", style_cell)
        ],
        [
            Paragraph("", style_cell),
            Paragraph("<b>SKOR KESIAPAN KESELURUHAN (WEIGHTED COMPOSITE READINESS)</b>", style_cell_bold),
            Paragraph("<b>100%</b>", style_cell_bold),
            Paragraph("<b>100%</b>", style_cell_bold),
            Paragraph("<b><font color='#059669'>96.79%</font></b>", style_cell_bold),
            Paragraph("<font color='#059669'><b>🟢 LAUNCH READY</b></font>", style_cell_bold),
            Paragraph("<b>Status Sistem: Production Ready & Lulus Seluruh Uji Verifikasi</b>", style_cell_bold)
        ]
    ]
    
    t_overview = Table([overview_headers] + overview_rows, colWidths=[25, 170, 45, 45, 55, 75, 347])
    t_overview.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), c_navy_light),
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor("#CBD5E1")),
        ('ROWBACKGROUNDS', (0, 1), (-1, -2), [colors.white, colors.HexColor("#F8FAFC")]),
        ('BACKGROUND', (0, -1), (-1, -1), colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0, 0), (-1, -1), 5),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 5),
    ]))
    story.append(t_overview)
    story.append(Spacer(1, 15))
    
    # ----------------------------------------------------
    # SECTION 2: DETAIL 4 PERSPEKTIF BALANCED SCORECARD
    # ----------------------------------------------------
    story.append(PageBreak())
    
    def render_bsc_table(title, subtitle, meta_text, rows, audit_notes):
        story.append(Paragraph(title, style_section_h1))
        story.append(Paragraph(f"<b>Fokus:</b> {subtitle} | <b>Meta:</b> {meta_text}", style_meta_info))
        
        headers = [
            Paragraph("Kode", style_cell_header),
            Paragraph("Sasaran Strategis (Must-Win)", style_cell_header),
            Paragraph("Indikator / Formula Ukur", style_cell_header),
            Paragraph("Tgt", style_cell_header),
            Paragraph("Capaian", style_cell_header),
            Paragraph("Status", style_cell_header),
            Paragraph("Verifikasi Sumber Sistem / Endpoint", style_cell_header),
            Paragraph("PIC", style_cell_header)
        ]
        table_rows = [headers]
        for r in rows:
            table_rows.append([
                Paragraph(r[0], style_cell_bold),
                Paragraph(r[1], style_cell),
                Paragraph(r[2], style_cell),
                Paragraph(r[3], style_cell),
                Paragraph(r[4], style_cell_bold),
                Paragraph(r[5], style_cell),
                Paragraph(r[6], style_cell),
                Paragraph(r[7], style_cell)
            ])
            
        t = Table(table_rows, colWidths=[42, 140, 150, 30, 45, 55, 210, 90])
        t.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (-1, 0), c_navy_light),
            ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
            ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor("#CBD5E1")),
            ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor("#F8FAFC")]),
            ('TOPPADDING', (0, 0), (-1, -1), 4),
            ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
        ]))
        story.append(t)
        story.append(Spacer(1, 6))
        
        # Notes
        note_p = "<b>System Audit Trail:</b> " + " | ".join([f"<b>{n[0]}:</b> {n[1]}" for n in audit_notes])
        story.append(Paragraph(note_p, style_note))
        story.append(Spacer(1, 14))

    # Persp 1
    render_bsc_table(
        "2. PERSPEKTIF 1: LEARNING, GROWTH & SYSTEM ARCHITECTURE",
        "Alur Fondasi Infrastruktur Teknologi & Produktivitas Tim",
        "Owner: Head of Engineering | Status: 🟢 98.75% (Ready)",
        [
            ("LRN-01", "Arsitektur Tri-Platform Native", "Swift iOS + Kotlin Android + CI3 PHP 8.x", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "Modular RESTful API application/modules/api", "Software Eng"),
            ("LRN-02", "Keamanan Autentikasi Modern", "Bearer Token SHA-256 & BCRYPT Hash", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "Tabel mobile_api_tokens (30-day lifecycle)", "Security & DevOps"),
            ("LRN-03", "Manajemen Wilayah Salesman", "Pemetaan Kios Binaan per Salesman", "100%", "95%", "<font color='#059669'>🟢 95%</font>", "Controller modules/admin/controllers/Salesman.php", "Sales Ops"),
            ("LRN-04", "Isolasi Akun Demo Internal", "Flagging users.is_internal Anti-Distorsi", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "Flag users.is_internal di laporan eksekutif", "Audit & Mgmt"),
        ],
        [
            ("Security Standard", "Bearer SHA-256 unik per device, auto-revoke saat logout."),
            ("ACID Database", "Transaksi DB memakai trans_begin & trans_commit menjamin integritas data.")
        ]
    )

    # Persp 2
    render_bsc_table(
        "3. PERSPEKTIF 2: INTERNAL BUSINESS PROCESS & SUPPLY CHAIN",
        "Alur Operasional Rantai Pasok, Gudang, dan Logistik Presisi",
        "Owner: Head of Supply Chain | Status: 🟡 92.40% (On Progress)",
        [
            ("PRC-01", "Logistik Presisi RajaOngkir Pro", "Kalkulasi Ongkir hingga Subdistrict ID", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "POST /api/v1/shipping/quotes (subdistrict_id)", "Logistik & IT"),
            ("PRC-02", "Penguncian Tarif Ongkir 30 Menit", "Locking Table mobile_shipping_quotes", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "mobile_shipping_quotes table (30-min window)", "IT Engineering"),
            ("PRC-03", "Dual-Unit Gramasi & Stok Real-Time", "Otomasi Konversi Botol vs Karton", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "POST /api/v1/cart unit_type 1 & 2 multiplier", "Gudang & IT"),
            ("PRC-04", "Deadstock Analytics & Clearance", "Deteksi Produk Slow-Moving > 1 Thn (<5%)", "100%", "92%", "<font color='#D97706'>🟡 92%</font>", "CATATAN PENTING.txt / Staging Rule Sync", "Supply Chain"),
            ("PRC-05", "Transaksi Kredit & Approval Mobile", "Limit Plafon Kredit Web-to-Mobile", "100%", "78%", "<font color='#DC2626'>🔴 78%</font>", "Web Credit Active / Mobile Pending Limit Sync", "Finance Comm"),
        ],
        [
            ("Logistik Presisi", "RajaOngkir Pro API membaca ongkir hingga level kecamatan."),
            ("Deadstock Engine", "Mendeteksi obat tidak bergerak > 1 tahun untuk dipicu flash sale clearance.")
        ]
    )
    
    story.append(PageBreak())

    # Persp 3
    render_bsc_table(
        "4. PERSPEKTIF 3: CUSTOMER & MARKET ACCESS",
        "Alur Pasar & Kios Mitra: Kepatuhan App Store, Kepuasan Pengguna & Retensi",
        "Owner: Head of Commercial & Product | Status: 🟢 100.0% (Ready)",
        [
            ("CUS-01", "Kepatuhan Apple Guideline 5.1.1(v)", "Endpoint Deletion & Non-PII Retention Hash", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "DELETE /api/v1/account | mobile_account_deletions", "iOS Eng & IT"),
            ("CUS-02", "Guest Browsing Experience", "Katalog & Promo Terbuka Tanpa Login", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "GET /api/v1/products & GET /api/v1/categories", "Product UI/UX"),
            ("CUS-03", "Layanan Bantuan Terpadu (Live Chat)", "In-App Live Chat Response Endpoint", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "GET /api/v1/messages & POST /api/v1/messages", "Customer Care"),
            ("CUS-04", "Kesiapan Google Play Store", "Android Native MVVM & Data-Light Build", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "kiustore_apps APK Build & Google Play Policy", "Android Eng"),
        ],
        [
            ("Apple Review Resolution", "Rejection Apple teratasi tuntas via Account Deletion di Profil > Pengaturan."),
            ("Non-PII Deletion", "Data pribadi di-hash SHA-256, faktur transaksi dipertahankan untuk akuntansi.")
        ]
    )

    # Persp 4
    render_bsc_table(
        "5. PERSPEKTIF 4: FINANCIAL REVENUE & SETTLEMENT",
        "Alur Finansial & Kas: Akselerasi Cash Flow, Keamanan Transaksi & Margin Guard",
        "Owner: Chief Financial Officer | Status: 🟢 98.00% (Ready)",
        [
            ("FIN-01", "Integrasi BRI BRIVA SNAP VA", "Dynamic VA 15-Min Exp & Auto-Settlement", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "POST /api/v1/orders/{id}/payments/briva | briva_api", "IT & Finance"),
            ("FIN-02", "Proteksi Multi-Tier Margin", "Enkripsi Server-Side 3 Tingkat Harga", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "sql_view.sql / level_product / Mobile_api_model", "Commercial"),
            ("FIN-03", "Otomasi Multi-Bank Transfer", "Validasi MIME & Secure Receipt Hash", "100%", "100%", "<font color='#059669'>🟢 100%</font>", "POST /api/v1/orders/{id}/payments/bank-transfer", "Finance Team"),
            ("FIN-04", "Akselerasi Cash Flow & DSO", "Percepatan Penerimaan Kas vs Manual", "100%", "92%", "<font color='#D97706'>🟡 92%</font>", "Baseline Data Produksi Berjalan (Finance Dashboard)", "Sales & Mgmt"),
        ],
        [
            ("BRIVA SNAP", "Dynamic VA 91118 dengan timer 15 menit dan real-time payment sync."),
            ("Tier Pricing", "Level 1: Retail Petani, Level 2: Grosir Kios Mitra, Level 3: Distributor.")
        ]
    )
    
    # ----------------------------------------------------
    # SECTION 3: 3-PILAR PROBLEM SOLVING MATRIX
    # ----------------------------------------------------
    story.append(PageBreak())
    story.append(Paragraph("6. GROUNDED PROBLEM-SOLVING MATRIX (3-PILAR ANALISIS STRATEGIS)", style_section_h1))
    story.append(Paragraph("Matriks terpadu yang memetakan Accomplishment, Issues & Root Cause, serta Next Steps & Risk Mitigation", style_meta_info))
    
    ps_headers = [
        Paragraph("No", style_cell_header),
        Paragraph("Pilar / Domain Masalah", style_cell_header),
        Paragraph("1. Accomplishment (Pencapaian Nyata)", style_cell_header),
        Paragraph("2. Issues & Root Cause (Tantangan Lapangan)", style_cell_header),
        Paragraph("3. Next Steps & Mitigasi Risiko", style_cell_header),
        Paragraph("Owner", style_cell_header),
        Paragraph("Target", style_cell_header),
        Paragraph("Status", style_cell_header)
    ]
    ps_rows = [
        [
            Paragraph("1", style_cell_bold),
            Paragraph("<b>Regulasi Apple App Store (iOS)</b>", style_cell),
            Paragraph("Lulus 100% Apple Guideline 5.1.1(v) dengan Account Deletion API & Guest Browsing.", style_cell),
            Paragraph("Standar ketat Apple mewajibkan penghapusan akun mandiri & penolakan simpan data PII.", style_cell),
            Paragraph("Terapkan SHA-256 email hashing audit trail dan pertahankan faktur untuk pajak.", style_cell),
            Paragraph("iOS & Backend", style_cell),
            Paragraph("Q3 2026", style_cell),
            Paragraph("<font color='#059669'><b>🟢 Done</b></font>", style_cell)
        ],
        [
            Paragraph("2", style_cell_bold),
            Paragraph("<b>Fintech BRIVA SNAP & Payment</b>", style_cell),
            Paragraph("Integrasi BRI BRIVA SNAP 15-min auto-settlement dan upload bukti bayar terenkripsi.", style_cell),
            Paragraph("Mitra kios di pelosok terkadang mengalami kendala sinyal atau limit transfer kartu ATM.", style_cell),
            Paragraph("Sediakan fallback transfer manual multi-bank (BCA, Mandiri, BRI) verifikasi cepat.", style_cell),
            Paragraph("Finance & IT", style_cell),
            Paragraph("Q3 2026", style_cell),
            Paragraph("<font color='#059669'><b>🟢 Done</b></font>", style_cell)
        ],
        [
            Paragraph("3", style_cell_bold),
            Paragraph("<b>Logistik Pedesaan & Armada</b>", style_cell),
            Paragraph("RajaOngkir Pro API presisi tingkat kecamatan & 30-min price locking engine.", style_cell),
            Paragraph("Pesanan pupuk tonase besar menghasilkan ongkir ekspedisi reguler yang sangat mahal.", style_cell),
            Paragraph("Integrasikan opsi armada truk internal (Internal Fleet) pada modul admin pengiriman.", style_cell),
            Paragraph("Logistik & Gudang", style_cell),
            Paragraph("Q4 2026", style_cell),
            Paragraph("<font color='#D97706'><b>🟡 On Progress</b></font>", style_cell)
        ],
        [
            Paragraph("4", style_cell_bold),
            Paragraph("<b>Manajemen Stok & Deadstock</b>", style_cell),
            Paragraph("Sistem dual-unit (botol/karton) & formula deteksi stok slow-moving > 1 tahun.", style_cell),
            Paragraph("Obat tanaman tertentu menumpuk di gudang jika terjadi pergeseran musim hujan/kemarau.", style_cell),
            Paragraph("Sinkronkan data Deadstock Engine ke modul flash sale promo untuk diskon clearance.", style_cell),
            Paragraph("Supply Chain", style_cell),
            Paragraph("Q4 2026", style_cell),
            Paragraph("<font color='#D97706'><b>🟡 On Progress</b></font>", style_cell)
        ],
        [
            Paragraph("5", style_cell_bold),
            Paragraph("<b>Metode Kredit & Plafon Tempo</b>", style_cell),
            Paragraph("Modul Piutang & limit kredit berjalan stabil di Web Enterprise Back-Office.", style_cell),
            Paragraph("Pelanggan mobile ingin opsi beli tempo langsung tanpa approval web manual.", style_cell),
            Paragraph("Kembangkan scoring kredit otomatis berbasis riwayat transaksi sebelum buka di mobile.", style_cell),
            Paragraph("Credit Comm", style_cell),
            Paragraph("Q1 2027", style_cell),
            Paragraph("<font color='#DC2626'><b>🔴 Planned</b></font>", style_cell)
        ],
    ]
    t_ps = Table([ps_headers] + ps_rows, colWidths=[20, 105, 140, 140, 160, 65, 45, 62])
    t_ps.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), c_navy_light),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor("#CBD5E1")),
        ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor("#F8FAFC")]),
        ('TOPPADDING', (0, 0), (-1, -1), 5),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 5),
    ]))
    story.append(t_ps)
    story.append(Spacer(1, 15))
    
    # ----------------------------------------------------
    # SECTION 4: KESIMPULAN & REKOMENDASI DEWAN DIREKSI
    # ----------------------------------------------------
    story.append(Paragraph("7. KESIMPULAN EKSEKUTIF & REKOMENDASI DEWAN DIREKSI", style_section_h1))
    rec_text = """
    <b>1. Status Kesiapan Komposit:</b> Proyek Karisma Online dinyatakan <b>96.79% (LAUNCH READY)</b> dan telah memenuhi seluruh kualifikasi sistem, keamanan, fintech, logistik, dan audit regulasi.<br/>
    <b>2. Keputusan Rilis:</b> Merekomendasikan kepada Dewan Direksi untuk menyetujui publikasi aplikasi secara serentak di <b>Apple App Store</b> dan <b>Google Play Store</b>.<br/>
    <b>3. Pilot Project Rollout:</b> Mengesahkan pelaksanaan Onboarding 100 Kios Mitra Percontohan pada September 2026 didampingi salesman wilayah.<br/>
    <b>4. Roadmap Berkelanjutan:</b> Menyetujui alokasi pengembangan modul armada internal & deadstock flash sale (Q4 2026) serta smart credit scoring tempo mobile (Q1 2027).
    """
    story.append(Paragraph(rec_text, ParagraphStyle('RecStyle', parent=styles['Normal'], fontName='Helvetica', fontSize=9, leading=14, textColor=c_navy)))
    
    doc.build(story, canvasmaker=NumberedCanvas)
    print("Successfully generated docs/LAPORAN_EKSEKUTIF_BSC.pdf")

if __name__ == "__main__":
    generate_pdf()
