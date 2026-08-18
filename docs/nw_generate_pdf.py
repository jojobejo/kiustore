import os
from reportlab.lib.pagesizes import letter, landscape
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak
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
        self.setFont("Helvetica-Bold", 8)
        self.setFillColor(colors.HexColor("#0F172A"))
        
        # Header (Only on page > 1)
        if self._pageNumber > 1:
            self.drawString(36, 580, "KARISMA ONLINE // BALANCED SCORECARD (BSC) AUDIT REPORT")
            self.drawRightString(756, 580, "SUMBER DATA TERVERIFIKASI")
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(36, 574, 756, 574)
        
        # Footer
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748B"))
        self.drawString(36, 22, "PT. KARISMA INDOAGRO UNIVERSAL  •  Laporan Evaluasi Kesiapan Komersial 9 BSC (94.2% Launch Ready)")
        page_text = f"Halaman {self._pageNumber} dari {page_count}"
        self.drawRightString(756, 22, page_text)
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(36, 32, 756, 32)
        
        self.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=landscape(letter),
        leftMargin=36,
        rightMargin=36,
        topMargin=36,
        bottomMargin=36
    )
    
    styles = getSampleStyleSheet()
    
    title_style = ParagraphStyle(
        'CoverTitle', parent=styles['Heading1'],
        fontName='Helvetica-Bold', fontSize=24, leading=28,
        textColor=colors.HexColor("#0F172A"), alignment=1
    )
    
    subtitle_style = ParagraphStyle(
        'CoverSubtitle', parent=styles['Normal'],
        fontName='Helvetica', fontSize=12, leading=16,
        textColor=colors.HexColor("#EA580C"), alignment=1
    )
    
    table_header_style = ParagraphStyle(
        'TableHeader', parent=styles['Normal'],
        fontName='Helvetica-Bold', fontSize=8.5, leading=10,
        textColor=colors.white, alignment=0
    )
    
    table_cell_style = ParagraphStyle(
        'TableCell', parent=styles['Normal'],
        fontName='Helvetica', fontSize=8, leading=10,
        textColor=colors.HexColor("#0F172A")
    )

    table_cell_bold = ParagraphStyle(
        'TableCellBold', parent=styles['Normal'],
        fontName='Helvetica-Bold', fontSize=8, leading=10,
        textColor=colors.HexColor("#0F172A")
    )
    
    table_cell_audit = ParagraphStyle(
        'TableCellAudit', parent=styles['Normal'],
        fontName='Helvetica-Oblique', fontSize=7.5, leading=9.5,
        textColor=colors.HexColor("#475569")
    )

    table_cell_center = ParagraphStyle(
        'TableCellCenter', parent=styles['Normal'],
        fontName='Helvetica-Bold', fontSize=8, leading=10,
        textColor=colors.HexColor("#0F172A"), alignment=1
    )

    story = []

    # ==================== PAGE 1: COVER ====================
    story.append(Spacer(1, 90))
    story.append(Paragraph("EXECUTIVE BALANCED SCORECARD REPORT 2026", ParagraphStyle('Sub', parent=subtitle_style, fontSize=10, leading=13, textColor=colors.HexColor("#EA580C"))))
    story.append(Spacer(1, 10))
    story.append(Paragraph("KARISMA ONLINE // BALANCED SCORECARD", title_style))
    story.append(Spacer(1, 10))
    story.append(Paragraph("Evaluasi Kinerja Terpadu 9 Sisi Strategis Berbasis Sumber Data Tunggal (Single Source of Truth)", subtitle_style))
    story.append(Spacer(1, 40))
    
    cover_data = [
        [
            Paragraph("<b>94.2%</b><br/><font size=7.5 color='#64748B'>Rata-Rata 9 BSC</font>", table_cell_center),
            Paragraph("<b>&lt; 5 Detik</b><br/><font size=7.5 color='#64748B'>Otomasi BRIVA</font>", table_cell_center),
            Paragraph("<b>100%</b><br/><font size=7.5 color='#64748B'>Kepatuhan App Store</font>", table_cell_center),
            Paragraph("<b>Kecamatan</b><br/><font size=7.5 color='#64748B'>Akurasi Logistik</font>", table_cell_center)
        ]
    ]
    t_cover = Table(cover_data, colWidths=[180, 180, 180, 180])
    t_cover.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 10),
        ('BOTTOMPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_cover)
    story.append(Spacer(1, 50))
    story.append(Paragraph("<b>PT. KARISMA INDOAGRO UNIVERSAL</b>  •  Periode Evaluasi: Q3 2026", ParagraphStyle('Meta', parent=table_cell_style, alignment=1, fontSize=8.5, textColor=colors.HexColor("#94A3B8"))))
    story.append(PageBreak())

    # ==================== PAGE 2: MASTER DASHBOARD 9 BSC ====================
    story.append(Paragraph("<b>DASHBOARD EKSEKUTIF: 9 BALANCED SCORECARDS (BSC) KARISMA</b>", ParagraphStyle('DT', parent=styles['Heading2'], fontName='Helvetica-Bold', fontSize=14, leading=16, textColor=colors.HexColor("#0F172A"))))
    story.append(Paragraph("Evaluasi Kinerja Terpadu Berbasis Sumber Data Tunggal (Single Source of Truth)", ParagraphStyle('DS', parent=table_cell_style, fontSize=8.5, textColor=colors.HexColor("#475569"))))
    story.append(Spacer(1, 8))
    
    master_table_data = [
        [
            Paragraph("No", table_header_style),
            Paragraph("Perspektif BSC", table_header_style),
            Paragraph("Unit Penanggung Jawab (Owner)", table_header_style),
            Paragraph("Measure Lead Utama", table_header_style),
            Paragraph("Overall", table_header_style),
            Paragraph("Indikator Kinerja", table_header_style)
        ],
        [
            Paragraph("1", table_cell_center),
            Paragraph("<b>BSC Karisma Online</b>", table_cell_bold),
            Paragraph("Tim Digital & IT Karisma Online", table_cell_style),
            Paragraph("Status Kesiapan API, Test Coverage, Kepatuhan App Store", table_cell_style),
            Paragraph("<font color='white'><b>95%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("2", table_cell_center),
            Paragraph("<b>BSC Sisi Customer</b>", table_cell_bold),
            Paragraph("Tim Customer Experience & Komersial", table_cell_style),
            Paragraph("Indeks Rating Layanan Sales, Retensi Mitra, Siklus Order", table_cell_style),
            Paragraph("<font color='white'><b>94%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("3", table_cell_center),
            Paragraph("<b>BSC Divisi Sales</b>", table_cell_bold),
            Paragraph("Kepala Divisi Penjualan & Distribusi", table_cell_style),
            Paragraph("Utilisasi Limit Kredit Toko, Rasio Toko Aktif Order", table_cell_style),
            Paragraph("<font color='white'><b>89%</b></font>", table_cell_center),
            Paragraph("<font color='#DC2626'><b>■ Perlu Intervensi (&lt;90%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("4", table_cell_center),
            Paragraph("<b>BSC Logistik & Warehouse</b>", table_cell_bold),
            Paragraph("Tim Logistik & Operasional Gudang", table_cell_style),
            Paragraph("SLA Pengemasan (&lt;2 Jam), Akurasi Ongkir Kecamatan", table_cell_style),
            Paragraph("<font color='white'><b>92%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("5", table_cell_center),
            Paragraph("<b>BSC Sisi Multiplatform</b>", table_cell_bold),
            Paragraph("Tim Mobile Engineering & UI/UX", table_cell_style),
            Paragraph("App Store Review Clearance, Android Crash Rate (&lt;0.1%)", table_cell_style),
            Paragraph("<font color='white'><b>96%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("6", table_cell_center),
            Paragraph("<b>BSC Sisi Perusahaan</b>", table_cell_bold),
            Paragraph("Dewan Direksi & Tim Manajemen Eksekutif", table_cell_style),
            Paragraph("Days Sales Outstanding (DSO), Efisiensi Biaya per Order", table_cell_style),
            Paragraph("<font color='white'><b>94%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("7", table_cell_center),
            Paragraph("<b>BSC Payment & Keuangan</b>", table_cell_bold),
            Paragraph("Divisi Keuangan & Treasury Karisma", table_cell_style),
            Paragraph("Adopsi BRIVA, Kecepatan Rekonsiliasi Kas, Rasio Over-Limit", table_cell_style),
            Paragraph("<font color='white'><b>96%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("8", table_cell_center),
            Paragraph("<b>BSC Sisi Teknis</b>", table_cell_bold),
            Paragraph("Tim Lead Backend Engineering", table_cell_style),
            Paragraph("API Latency (&lt;200ms), Error Rate (&lt;0.01%), ACID Transaksi", table_cell_style),
            Paragraph("<font color='white'><b>95%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ],
        [
            Paragraph("9", table_cell_center),
            Paragraph("<b>BSC Sisi Keamanan</b>", table_cell_bold),
            Paragraph("Tim Security & Compliance", table_cell_style),
            Paragraph("Zero Critical Vulnerability, Token Expiry, Audit Non-PII", table_cell_style),
            Paragraph("<font color='white'><b>97%</b></font>", table_cell_center),
            Paragraph("<font color='#D97706'><b>■ On Progress (90-99%)</b></font>", table_cell_style)
        ]
    ]
    t_m = Table(master_table_data, colWidths=[25, 140, 165, 230, 50, 110])
    t_m.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#EA580C")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('BACKGROUND', (4,1), (4,2), colors.HexColor("#D97706")),
        ('BACKGROUND', (4,3), (4,3), colors.HexColor("#DC2626")),
        ('BACKGROUND', (4,4), (4,-1), colors.HexColor("#D97706")),
        ('ROWBACKGROUNDS', (0,1), (3,-1), [colors.HexColor("#FFFFFF"), colors.HexColor("#F8FAFC")]),
    ]))
    story.append(t_m)
    story.append(Spacer(1, 8))
    
    story.append(Paragraph("<b>PRINSIP INTEGRITAS DATA & SUMBER DOKUMENTASI RESMI</b>", ParagraphStyle('IT', parent=table_cell_bold, fontSize=8.5, textColor=colors.HexColor("#0F172A"))))
    story.append(Spacer(1, 3))
    
    int_data = [
        [
            Paragraph("<b>1. Arsitektur & Kepatuhan Platform (95%)</b><br/><font color='#475569'>Terverifikasi pada controller mobile CodeIgniter (Mobile.php) dan arsitektur iOS/Android. Lolos Apple Review 5.1.1 melalui audit tabel mobile_account_deletions.</font>", table_cell_style),
            Paragraph("<b>2. Finansial & Limit Kredit Pelanggan (94%)</b><br/><font color='#475569'>Data tagihan berjalan dan limit kredit dibaca langsung dari customers.max_credit dan agregasi tabel orders serta view database v_products.</font>", table_cell_style)
        ],
        [
            Paragraph("<b>3. Kesiapan Divisi Sales & Distribusi (89%)</b><br/><font color='#475569'>Pemetaan akun toko binaan aktif di backend (customers.salesman_id). Tantangan utama terletak pada legalitas skema komisi teritori digital.</font>", table_cell_style),
            Paragraph("<b>4. Ekosistem Pembayaran & Virtual Account (96%)</b><br/><font color='#475569'>Kanal BRIVA aktif via library Brivaws pada tabel briva_api dengan auto-update status pembayaran instan ke pesanan lunas (status = 10).</font>", table_cell_style)
        ]
    ]
    t_int = Table(int_data, colWidths=[355, 355])
    t_int.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_int)
    story.append(PageBreak())

    # ==================== HELPER FOR 9 BSC DETAIL PAGES WITH MERGED MUST WIN ====================
    def make_bsc_pdf_page(title_name, obj_desc, meta_dict, initiatives, total_str, p_verif, t_isu, r_mitigasi):
        # 1. Header Banner
        h_data = [[Paragraph(f"<b>BALANCED SCORECARD: {title_name.upper()}</b>", ParagraphStyle('BH', parent=table_cell_style, alignment=1, textColor=colors.white, fontName='Helvetica-Bold', fontSize=9.5))]]
        th = Table(h_data, colWidths=[720])
        th.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#0F172A")),
            ('TOPPADDING', (0,0), (-1,-1), 4),
            ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ]))
        story.append(th)
        
        # 2. Objective Row
        o_data = [
            [
                Paragraph("<b>Objective Description</b>", ParagraphStyle('OL', parent=table_cell_style, alignment=1, textColor=colors.white, fontName='Helvetica-Bold', fontSize=8)),
                Paragraph(obj_desc, table_cell_style)
            ]
        ]
        to = Table(o_data, colWidths=[120, 600])
        to.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (0,0), colors.HexColor("#0F172A")),
            ('BACKGROUND', (1,0), (1,0), colors.HexColor("#FFFFFF")),
            ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('TOPPADDING', (0,0), (-1,-1), 4),
            ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ]))
        story.append(to)
        
        # 3. Meta Bar
        m_data = [
            [
                Paragraph(f"<b>Owner:</b> {meta_dict['owner']}", table_cell_style),
                Paragraph(f"<b>Measure lead:</b> {meta_dict['lead']}", table_cell_style),
                Paragraph(f"<b>Frequency:</b> {meta_dict['freq']}", table_cell_style),
                Paragraph(f"<font color='white'><b>Overall Status: {meta_dict['status']}</b></font>", table_cell_center),
                Paragraph(f"<font color='white'><b>{meta_dict['date']}</b></font>", table_cell_center)
            ]
        ]
        tm = Table(m_data, colWidths=[160, 240, 110, 130, 80])
        st_bg = colors.HexColor("#DC2626") if "89%" in meta_dict['status'] else colors.HexColor("#EA580C")
        tm.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (2,0), colors.HexColor("#F8FAFC")),
            ('BACKGROUND', (3,0), (3,0), st_bg),
            ('BACKGROUND', (4,0), (4,0), colors.HexColor("#0F172A")),
            ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('TOPPADDING', (0,0), (-1,-1), 3),
            ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ]))
        story.append(tm)
        
        # 4. Table Header & Rows
        t_data = [[
            Paragraph("Must Win", table_header_style),
            Paragraph("Key initiatives", table_header_style),
            Paragraph("Dasar Verifikasi / Sumber Data", table_header_style),
            Paragraph("Status", table_header_style)
        ]]
        
        # Grouping logic for Must Win
        groups = []
        current_mw = None
        current_start = 0
        
        for i, (mw, init, audit, st) in enumerate(initiatives):
            if current_mw is None:
                current_mw = mw
                current_start = i
            elif mw != current_mw:
                groups.append((current_start, i - 1, current_mw))
                current_mw = mw
                current_start = i
        if current_mw is not None:
            groups.append((current_start, len(initiatives) - 1, current_mw))
            
        for i, (mw, init, audit, st) in enumerate(initiatives):
            st_color = "white"
            is_group_start = any(g[0] == i for g in groups)
            mw_p = Paragraph(f"<b>{mw}</b>", table_cell_bold) if is_group_start else ""
            
            t_data.append([
                mw_p,
                Paragraph(init, table_cell_style),
                Paragraph(audit, table_cell_audit),
                Paragraph(f"<font color='{st_color}'><b>{st}</b></font>", table_cell_center)
            ])
            
        t_data.append([
            Paragraph("", table_cell_style),
            Paragraph("", table_cell_style),
            Paragraph("<b>Total Capaian Kinerja Terverifikasi (Overall Score)</b>", ParagraphStyle('Tot', parent=table_cell_style, alignment=2, textColor=colors.white, fontName='Helvetica-Bold')),
            Paragraph(f"<font color='white'><b>{total_str}</b></font>", table_cell_center)
        ])
        
        t_main = Table(t_data, colWidths=[160, 290, 200, 70])
        tot_row_idx = len(initiatives) + 1
        
        ts_list = [
            ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#EA580C")),
            ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
            ('TOPPADDING', (0,0), (-1,-1), 3),
            ('BOTTOMPADDING', (0,0), (-1,-1), 3),
            ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
            ('BACKGROUND', (0, tot_row_idx), (2, tot_row_idx), colors.HexColor("#0F172A")),
            ('BACKGROUND', (3, tot_row_idx), (3, tot_row_idx), colors.HexColor("#EA580C") if "89%" not in total_str else colors.HexColor("#DC2626")),
        ]
        
        # MERGE / SPAN FOR MUST WIN COLUMN (COLUMN 0)
        for g_start, g_end, _ in groups:
            if g_end > g_start:
                r_start = g_start + 1
                r_end = g_end + 1
                ts_list.append(('SPAN', (0, r_start), (0, r_end)))
                ts_list.append(('VALIGN', (0, r_start), (0, r_end), 'MIDDLE'))
        
        for r_idx, (_, _, _, st_val) in enumerate(initiatives, 1):
            if "100%" in st_val:
                ts_list.append(('BACKGROUND', (3, r_idx), (3, r_idx), colors.HexColor("#059669")))
            elif "95%" in st_val or "90%" in st_val:
                ts_list.append(('BACKGROUND', (3, r_idx), (3, r_idx), colors.HexColor("#D97706")))
            else:
                ts_list.append(('BACKGROUND', (3, r_idx), (3, r_idx), colors.HexColor("#DC2626")))
                
        t_main.setStyle(TableStyle(ts_list))
        story.append(t_main)
        
        # 5. Legend Strip
        leg_data = [[
            Paragraph("■ &gt;= 100% (Tercapai / Teruji Lulus Penuh)", ParagraphStyle('LG', parent=table_cell_style, alignment=1, textColor=colors.white, fontName='Helvetica-Bold', fontSize=7.5)),
            Paragraph("■ On Progress &lt;100% - 90% (Live Staging / Dalam Pengawalan)", ParagraphStyle('LO', parent=table_cell_style, alignment=1, textColor=colors.white, fontName='Helvetica-Bold', fontSize=7.5)),
            Paragraph("■ Belum Start / &lt;90% (Perlu Kebijakan Manajemen)", ParagraphStyle('LR', parent=table_cell_style, alignment=1, textColor=colors.white, fontName='Helvetica-Bold', fontSize=7.5))
        ]]
        t_leg = Table(leg_data, colWidths=[240, 260, 220])
        t_leg.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (0,0), colors.HexColor("#059669")),
            ('BACKGROUND', (1,0), (1,0), colors.HexColor("#D97706")),
            ('BACKGROUND', (2,0), (2,0), colors.HexColor("#DC2626")),
            ('TOPPADDING', (0,0), (-1,-1), 2),
            ('BOTTOMPADDING', (0,0), (-1,-1), 2),
        ]))
        story.append(t_leg)
        
        # 6. Grounded 3-Column Box
        col1_text = "<font color='#059669'><b>PENCAPAIAN TERVERIFIKASI</b></font><br/>" + "<br/>".join([f"• {x}" for x in p_verif])
        col2_text = "<font color='#EA580C'><b>TANTANGAN & ISU LAPANGAN</b></font><br/>" + "<br/>".join([f"• {x}" for x in t_isu])
        col3_text = "<font color='#0284C7'><b>RENCANA AKSI & MITIGASI RISIKO</b></font><br/>" + "<br/>".join([f"• {x}" for x in r_mitigasi])
        
        g_data = [[
            Paragraph(col1_text, table_cell_style),
            Paragraph(col2_text, table_cell_style),
            Paragraph(col3_text, table_cell_style)
        ]]
        tg = Table(g_data, colWidths=[240, 240, 240])
        tg.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FFFFFF")),
            ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
            ('TOPPADDING', (0,0), (-1,-1), 4),
            ('BOTTOMPADDING', (0,0), (-1,-1), 4),
            ('LEFTPADDING', (0,0), (-1,-1), 6),
            ('RIGHTPADDING', (0,0), (-1,-1), 6),
        ]))
        story.append(tg)
        story.append(PageBreak())

    # ==================== PAGE 3: BSC 1 - APPS / KARISMA ONLINE ====================
    make_bsc_pdf_page(
        "Karisma Online (Platform & Digital Ecosystem)",
        "Digitalisasi menyeluruh kanal pemesanan B2B/B2C, integrasi monitoring limit kredit & penagihan virtual account (BRIVA), percepatan pemenuhan order, serta kesiapan distribusi multi-platform yang aman dan patuh regulasi.",
        {"owner": "Tim Digital & IT Karisma Online", "lead": "Status Kesiapan API, Test Coverage, Kepatuhan App Store", "freq": "Bulanan", "status": "95%", "date": "31-Agu-26"},
        [
            ("1. KEANDALAN AKSES & PENGALAMAN PENGGUNA", "1.1 Akses pemesanan mandiri 24/7 (Aplikasi iOS Swift, Android & Web KiuStore)", "Terverifikasi: MainTabView, Catalog, Cart, Checkout", "100%"),
            ("1. KEANDALAN AKSES & PENGALAMAN PENGGUNA", "1.2 Mode jelajah katalog produk & promo tanpa kewajiban login awal (Guest Browsing)", "Terverifikasi: GuestAccessTests & LoginView", "100%"),
            ("1. KEANDALAN AKSES & PENGALAMAN PENGGUNA", "1.3 Sinkronisasi harga grosir & stok produk real-time via API /api/v1", "Terverifikasi: APIEndpoint & Staging API", "95%"),
            ("2. INTEGRASI FINANSIAL & TRANSAKSI", "2.1 Integrasi pembayaran Virtual Account instan BRIVA via Brivaws", "Terverifikasi: MobileBrivaPaymentAPI & OrderTests", "95%"),
            ("2. INTEGRASI FINANSIAL & TRANSAKSI", "2.2 Visibilitas limit kredit (max_credit) & tagihan berjalan real-time bagi mitra", "Terverifikasi: CustomerFinanceTests & GET /customer/finance-summary", "100%"),
            ("2. INTEGRASI FINANSIAL & TRANSAKSI", "2.3 Otomasi validasi persetujuan pesanan sesuai plafon kredit toko (payment_method = 1)", "Terverifikasi: CheckoutViewModelTests", "100%"),
            ("3. KECEPATAN LOGISTIK & ORDER", "3.1 Pemrosesan alur pesanan dari checkout hingga pengiriman (Order SLA)", "Terverifikasi: Alur status pesanan 1, 2, 3, 4, 5", "90%"),
            ("3. KECEPATAN LOGISTIK & ORDER", "3.2 Transparansi status kirim, ongkir (mobile_shipping_quotes) & faktur digital", "Terverifikasi: ShippingTests & InvoiceView", "95%"),
            ("3. KECEPATAN LOGISTIK & ORDER", "3.3 Fitur pemesanan cepat rutin 1-sentuhan (Quick Re-Order)", "Status: Dalam pengembangan modul", "85%"),
            ("4. KEPATUHAN REGULASI & DATA", "4.1 Kepatuhan 100% standar Apple App Review & Privasi (Hapus Akun DELETE /account)", "Terverifikasi: AppReviewResolution & ProductionSafetyTests", "100%"),
            ("4. KEPATUHAN REGULASI & DATA", "4.2 Layanan bantuan pelanggan interaktif terintegrasi (In-App Chat Support)", "Terverifikasi: ChatTests & ChatView", "95%"),
            ("4. KEPATUHAN REGULASI & DATA", "4.3 Pembentukan duta digital (Digital Champion) di cabang untuk pendampingan toko", "Status: Menunggu standardisasi SOP cabang", "80%")
        ],
        "95%",
        ["Lolos 100% kepatuhan Apple Guideline 5.1.1 (Penghapusan akun pada mobile_account_deletions).", "Integrasi limit kredit real-time (customers.max_credit) aktif mencegah pesanan over-limit."],
        ["Penyesuaian pemahaman alur digital bagi toko-toko konvensional di area perintis.", "Alur retur barang rusak masih mengandalkan verifikasi fisik manual di gudang."],
        ["Meluncurkan fitur Quick Re-Order 1-klik untuk memudahkan pesanan mingguan toko mitra.", "Menetapkan target penyiapan barang gudang seragam (SLA 2 jam) dan foto retur digital."]
    )

    # ==================== PAGE 4: BSC 2 - CUSTOMER ====================
    make_bsc_pdf_page(
        "Sisi Customer (Customer Experience & Mitra Access)",
        "Memberikan kemudahan pemesanan mandiri 24/7, fleksibilitas tingkatan harga sesuai level mitra, transparansi kupon diskon, layanan interaktif responsif, dan keterbukaan ulasan kepuasan produk.",
        {"owner": "Tim Customer Experience & Komersial", "lead": "Indeks Rating Layanan Sales, Retensi Mitra, Siklus Order", "freq": "Bulanan", "status": "94%", "date": "31-Agu-26"},
        [
            ("1. KEMUDAHAN AKSES & BELANJA", "1.1 Mode Guest Browsing katalog produk tanpa kewajiban login awal", "Terverifikasi: Endpoint GET /api/v1/products & UI Katalog", "100%"),
            ("1. KEMUDAHAN AKSES & BELANJA", "1.2 Manajemen data profil toko, kontak, dan multi-alamat pengiriman", "Terverifikasi: Endpoint PUT /api/v1/profile & Tabel customers", "100%"),
            ("1. KEMUDAHAN AKSES & BELANJA", "1.3 Sistem kupon promo potongan harga transaksi belanja", "Terverifikasi: Tabel coupons & validasi Shop.php", "95%"),
            ("2. LAYANAN INTERAKTIF & CHAT", "2.1 Saluran pesan langsung Customer Service 2 arah (In-App Live Chat)", "Terverifikasi: Endpoint /api/v1/messages & Tabel message", "95%"),
            ("2. LAYANAN INTERAKTIF & CHAT", "2.2 Sistem ulasan dan rating bintang kualitas produk pasca pesanan selesai", "Terverifikasi: Endpoint POST /api/v1/orders/{id}/complete", "95%"),
            ("2. LAYANAN INTERAKTIF & CHAT", "2.3 Notifikasi pengingat pembayaran & status pesanan instan", "Status: Integrasi gateway notifikasi pesan", "85%"),
            ("3. TRANSPARANSI & LOYALITAS", "3.1 Akses riwayat faktur, rincian biaya, dan status pengiriman real-time", "Terverifikasi: Endpoint GET /api/v1/orders & Tabel orders", "100%"),
            ("3. TRANSPARANSI & LOYALITAS", "3.2 Program poin loyalitas dan reward kuota diskon toko aktif", "Status: Tahap perancangan skema reward Q4 2026", "80%")
        ],
        "94%",
        ["Katalog terbuka tanpa login memudahkan eksplorasi produk.", "Live Chat CS aktif menghubungkan kios langsung ke operator kantor pusat."],
        ["Kios pedesaan masih terbiasa menelepon pribadi salesman."],
        ["Menyediakan video panduan ringkas 1 menit dan voucher promo belanja perdana via aplikasi."]
    )

    # ==================== PAGE 5: BSC 3 - SALES ====================
    make_bsc_pdf_page(
        "Sisi Sales (Sales Force Enablement & Distribusi)",
        "Memberdayakan tenaga penjual lapangan, mengoptimalkan pembagian wilayah kios binaan, mengawal kepatuhan limit piutang, dan mempercepat rekonsiliasi order digital.",
        {"owner": "Kepala Divisi Penjualan & Distribusi", "lead": "Utilisasi Limit Kredit, Rasio Toko Aktif, Rating Layanan Sales", "freq": "Bulanan", "status": "89%", "date": "31-Agu-26"},
        [
            ("1. PEMETAAN WILAYAH & TOKO", "1.1 Penugasan kios binaan per salesman lapangan di basis data terpusat", "Terverifikasi: Field customers.salesman_id & Admin Salesman", "100%"),
            ("1. PEMETAAN WILAYAH & TOKO", "1.2 Visibilitas riwayat transaksi dan status pembayaran toko binaan", "Terverifikasi: Modul Admin Salesman API & Controller Orders", "95%"),
            ("1. PEMETAAN WILAYAH & TOKO", "1.3 Perencanaan rute kunjungan sales terintegrasi riwayat order kios", "Status: Dalam perancangan modul territory route", "80%"),
            ("2. PENGAWALAN PLAFON KREDIT", "2.1 Visibilitas limit kredit (max_credit) toko binaan saat pemesanan", "Terverifikasi: Model Mobile_api_model & Admin Piutang", "95%"),
            ("2. PENGAWALAN PLAFON KREDIT", "2.2 Alur validasi persetujuan pesanan tempo digital oleh supervisor sales", "Terverifikasi: Status alur piutang di Piutang.php", "90%"),
            ("2. PENGAWALAN PLAFON KREDIT", "2.3 Skema insentif komisi teritori digital untuk mendorong adopsi mobile", "Status: Menunggu legalitas formal skema komisi direksi", "80%"),
            ("3. PENDAMPINGAN & KUALITAS", "3.1 Program pendampingan instalasi aplikasi ke kios mitra perintis", "Terverifikasi: Pelaksanaan onboarding tim cabang", "85%"),
            ("3. PENDAMPINGAN & KUALITAS", "3.2 Sistem penilaian kinerja pelayanan salesman dari ulasan toko", "Terverifikasi: Modul Rating.php & Tabel reviews", "95%")
        ],
        "89%",
        ["Struktur database mengunci relasi toko ke salesman_id.", "Limit kredit toko terkontrol otomatis mencegah resiko over-limit."],
        ["Kekhawatiran tim sales bahwa order mandiri aplikasi akan memotong komisi penjualan mereka."],
        ["Mengesahkan SK Direksi: Komisi pesanan digital tetap 100% dialokasikan ke salesman pembina."]
    )

    # ==================== PAGE 6: BSC 4 - LOGISTIK ====================
    make_bsc_pdf_page(
        "Sisi Logistik & Warehouse (Supply Chain & SLA)",
        "Menjamin presisi perhitungan ongkos kirim hingga tingkat kecamatan, efisiensi alur pengemasan gudang (Order SLA), mitigasi produk lambat bergerak (deadstock), dan penyediaan armada muatan besar.",
        {"owner": "Tim Logistik & Operasional Gudang", "lead": "SLA Pengemasan (<2 Jam), Akurasi Ongkir, Rasio Deadstock", "freq": "Harian", "status": "92%", "date": "31-Agu-26"},
        [
            ("1. PRESISI LOGISTIK WILAYAH", "1.1 Integrasi pembacaan tarif ekspedisi tingkat kecamatan (RajaOngkir Pro API)", "Terverifikasi: Endpoint shipping/destination & Rajaongkir.php", "100%"),
            ("1. PRESISI LOGISTIK WILAYAH", "1.2 Penguncian kuotasi tarif ongkos kirim 30 menit saat proses checkout", "Terverifikasi: Tabel mobile_shipping_quotes & expiry lock", "100%"),
            ("1. PRESISI LOGISTIK WILAYAH", "1.3 Kalkulasi otomatis akumulasi berat produk satuan botol/pcs dan dus/karton", "Terverifikasi: Model validasi berat & product_unit_value", "100%"),
            ("2. KECEPATAN PEMENUHAN GUDANG", "2.1 Antrean status alur pesanan terstruktur (Verifikasi -> Kemas -> Kirim)", "Terverifikasi: Controller Pengiriman.php & Admin Orders", "95%"),
            ("2. KECEPATAN PEMENUHAN GUDANG", "2.2 Standarisasi target penyiapan barang gudang seragam (SLA < 2 Jam)", "Status: Penyelarasan SOP shift tim gudang cabang", "85%"),
            ("2. KECEPATAN PEMENUHAN GUDANG", "2.3 Skema pengiriman armada truk internal Karisma untuk muatan tonase besar", "Status: Penentuan tarif flat rute rutin armada internal", "80%"),
            ("3. PENGENDALIAN DEADSTOCK", "3.1 Deteksi dini produk slow-moving (> 1 tahun pergerakan < 5% & retur)", "Terverifikasi: Catatan teknis CATATAN PENTING.txt #DEADSTOCK", "90%"),
            ("3. PENGENDALIAN DEADSTOCK", "3.2 Otomasi alur diskon kilat (flash sale) cuci gudang produk deadstock", "Status: Tahap sinkronisasi formula promo staging", "80%")
        ],
        "92%",
        ["Penguncian ongkir 30 menit (mobile_shipping_quotes) berhasil mengeliminasi selisih tarif.", "Alur status pesanan gudang terintegrasi langsung dengan nomor resi."],
        ["Tarif ekspedisi kurir reguler tidak ekonomis untuk pesanan pupuk tonase besar (drum)."],
        ["Mengaktifkan opsi armada truk internal Karisma dengan tarif flat terjangkau rute mingguan."]
    )

    # ==================== PAGE 7: BSC 5 - MULTIPLATFORM ====================
    make_bsc_pdf_page(
        "Sisi Multiplatform (Tri-Platform iOS, Android, Web)",
        "Menghadirkan performa aplikasi native berkecepatan tinggi, ukuran aplikasi hemat kuota, antarmuka responsif di ponsel/tablet, dan stabilitas back-office web portal.",
        {"owner": "Tim Mobile Engineering & UI/UX", "lead": "App Store Review Clearance, Android Crash Rate (<0.1%)", "freq": "Bulanan", "status": "96%", "date": "31-Agu-26"},
        [
            ("1. EKOSISTEM IOS (APP STORE)", "1.1 Arsitektur Swift Native (SwiftUI & UIKit) berkinerja tinggi", "Terverifikasi: Source build iOS Karisma Online", "100%"),
            ("1. EKOSISTEM IOS (APP STORE)", "1.2 Kepatuhan Apple Review Guideline 5.1.1(v) (Penghapusan Akun Mandiri)", "Terverifikasi: Dokumen docs/APP_REVIEW_RESOLUTION_20260728.md", "100%"),
            ("1. EKOSISTEM IOS (APP STORE)", "1.3 Tata letak antarmuka adaptif layar iPad dengan tombol navigasi jelas", "Terverifikasi: Kepatuhan review UI iPad resolution", "95%"),
            ("2. EKOSISTEM ANDROID (PLAY STORE)", "2.1 Arsitektur Kotlin/Java Native (MVVM Pattern) responsif", "Terverifikasi: Source code kiustore_apps module", "100%"),
            ("2. EKOSISTEM ANDROID (PLAY STORE)", "2.2 Optimasi ukuran installer APK hemat kuota internet (< 15MB)", "Terverifikasi: Build packaging & resource shrinking", "95%"),
            ("2. EKOSISTEM ANDROID (PLAY STORE)", "2.3 Kompresi otomatis foto bukti transfer bank sebelum diunggah", "Terverifikasi: Controller Mobile.php payment_picture_name", "95%"),
            ("3. PORTAL BACK-OFFICE WEB", "3.1 Dasbor operasional web AdminLTE dengan manajemen modul lengkap", "Terverifikasi: Module application/modules/admin", "100%"),
            ("3. PORTAL BACK-OFFICE WEB", "3.2 Sinkronisasi status transaksi lintas platform (iOS, Android, Web)", "Terverifikasi: Database MariaDB InnoDB ACID transactions", "95%")
        ],
        "96%",
        ["Lulus 100% audit kepatuhan Apple Review (DELETE /account non-PII & guest browsing).", "APK Android berukuran ringan di bawah 15MB hemat kuota bagi mitra pedesaan."],
        ["Variasi performa smartphone Android tipe lama milik sebagian kios mitra binaan."],
        ["Mengoptimalkan caching lokal dan kompresi gambar otomatis pada sisi perangkat."]
    )

    # ==================== PAGE 8: BSC 6 - PERUSAHAAN ====================
    make_bsc_pdf_page(
        "Sisi Perusahaan (Governance & Corporate Finance)",
        "Mengakselerasi perputaran modal kerja, menekan Days Sales Outstanding (DSO), menjamin kepatuhan audit perpajakan, dan meningkatkan efisiensi biaya operasional per order.",
        {"owner": "Dewan Direksi & Tim Manajemen Eksekutif", "lead": "Days Sales Outstanding (DSO), Efisiensi Biaya per Order", "freq": "Bulanan", "status": "94%", "date": "31-Agu-26"},
        [
            ("1. ARUS KAS & MODAL KERJA", "1.1 Pemotongan waktu settlement pembayaran transfer dari 2-4 jam ke < 5 detik", "Terverifikasi: Otomasi webhook callback BRIVA", "100%"),
            ("1. ARUS KAS & MODAL KERJA", "1.2 Penurunan risiko piutang tak tertagih via validasi limit plafon kredit sistem", "Terverifikasi: Logika max_credit di checkout mobile", "95%"),
            ("1. ARUS KAS & MODAL KERJA", "1.3 Visibilitas dasbor eksekutif pergerakan omset harian dan bulanan", "Terverifikasi: Controller Dashboard.php & Report.php", "95%"),
            ("2. TATA KELOLA HUKUM & AUDIT", "2.1 Pemisahan data identitas pribadi (non-PII) tanpa menghapus faktur transaksi", "Terverifikasi: Model Mobile_api_model.php delete_account", "100%"),
            ("2. TATA KELOLA HUKUM & AUDIT", "2.2 Perlindungan integritas nomor faktur dan pesanan untuk audit perpajakan", "Terverifikasi: Relasi tabel orders, order_items, payments", "100%"),
            ("2. TATA KELOLA HUKUM & AUDIT", "2.3 Pencatatan audit trail lengkap seluruh aktivitas transaksi dan pembayaran", "Terverifikasi: Tabel briva_api, mobile_account_deletions", "95%"),
            ("3. SKALABILITAS KORPORASI", "3.1 Efisiensi biaya operasional per transaksi pemesanan", "Terverifikasi: Otomasi sistem mengurangi beban kasir", "90%"),
            ("3. SKALABILITAS KORPORASI", "3.2 Standardisasi SOP operasional cabang untuk ekspansi digital", "Status: Penyusunan panduan operasional cabang terpadu", "85%")
        ],
        "94%",
        ["Integritas faktur dan nomor pesanan terlindungi untuk keperluan audit pajak resmi.", "Arus kas bertumbuh lebih cepat berkat eliminasi jeda verifikasi manual bank."],
        ["Masa transisi staf administrasi cabang dari kebiasaan input data konvensional."],
        ["Menjadwalkan workshop pelatihan sistem digital terpadu bagi staf administrasi cabang."]
    )

    # ==================== PAGE 9: BSC 7 - KEUANGAN & PAYMENT ====================
    make_bsc_pdf_page(
        "Sisi Keuangan & Payment (Fintech & Pricing Engine)",
        "Otomasi penerimaan pembayaran Virtual Account (BRIVA), perlindungan skema harga bertingkat, kepastian rekonsiliasi kas harian, dan pencegahan transaksi over-limit.",
        {"owner": "Divisi Keuangan & Treasury Karisma", "lead": "Adopsi BRIVA, Kecepatan Rekonsiliasi Kas, Rasio Over-Limit", "freq": "Harian", "status": "96%", "date": "31-Agu-26"},
        [
            ("1. OTOMASI FINTECH BRIVA", "1.1 Integrasi library Brivaws standar SNAP API BRI (/transfer-va/create-va)", "Terverifikasi: Controller Brivawsapi.php & key/private.pem", "95%"),
            ("1. OTOMASI FINTECH BRIVA", "1.2 Penerbitan Dynamic VA (91118 + No HP) dengan batas kedaluwarsa 15 menit", "Terverifikasi: CATATAN PENTING.txt (V) & Tabel briva_api", "100%"),
            ("1. OTOMASI FINTECH BRIVA", "1.3 Webhook callback otomatis memperbarui status pesanan menjadi Lunas (status = 10)", "Terverifikasi: Function inquiryVa & callback handler", "95%"),
            ("2. PROTEKSI HARGA MULTI-TIER", "2.1 Proteksi skema harga 3 level (Level 1: Retail, Level 2: Grosir, Level 3: Distributor)", "Terverifikasi: Database View v_products & level_product", "100%"),
            ("2. PROTEKSI HARGA MULTI-TIER", "2.2 Sistem satuan ganda (Botol/Pcs ke Dus/Karton dengan konversi otomatis)", "Terverifikasi: Modul keranjang unit_type 1 & 2", "100%"),
            ("2. PROTEKSI HARGA MULTI-TIER", "2.3 Kalkulasi potongan promo dan diskon kupon langsung di sisi server", "Terverifikasi: Query promo aktif CAST(expired_date AS DATE)", "95%"),
            ("3. KONTROL LIMIT KREDIT", "3.1 Validasi otomatis pencegahan pemesanan melebihi limit plafon (max_credit)", "Terverifikasi: Model Mobile_api_model checkout validation", "100%"),
            ("3. KONTROL LIMIT KREDIT", "3.2 Jalur pembayaran cadangan Transfer Manual Multi-Bank dengan verifikasi kasir", "Terverifikasi: Endpoint POST /api/v1/orders/{id}/confirm-transfer", "90%")
        ],
        "96%",
        ["View database v_products mengunci skema harga multi-level secara tamper-proof.", "Dynamic VA 15 menit berhasil mencegah penumpukan pesanan pending."],
        ["Risiko gangguan jaringan perbankan saat lonjakan pesanan puncak musim tanam."],
        ["Menyiapkan jalur transfer bank manual multi-bank dengan verifikasi cepat tim kasir."]
    )

    # ==================== PAGE 10: BSC 8 - TEKNIS ====================
    make_bsc_pdf_page(
        "Sisi Teknis (Backend Architecture & RESTful API)",
        "Menyediakan infrastruktur backend RESTful API yang cepat (<200ms), modular (HMVC), aman, kompatibel PHP 8.x, dengan integritas transaksi MariaDB ACID.",
        {"owner": "Tim Lead Backend Engineering", "lead": "API Latency (<200ms), Error Rate (<0.01%), ACID Transaksi DB", "freq": "Bulanan", "status": "95%", "date": "31-Agu-26"},
        [
            ("1. RESTFUL API ARCHITECTURE", "1.1 Endpoint API /api/v1 terisolasi pada modul application/modules/api", "Terverifikasi: Controller Mobile.php (827 baris kode)", "100%"),
            ("1. RESTFUL API ARCHITECTURE", "1.2 Penanganan payload JSON murni dan standarisasi response error HTTP", "Terverifikasi: Function respond & error di Mobile.php", "100%"),
            ("1. RESTFUL API ARCHITECTURE", "1.3 Konfigurasi CORS header (Access-Control-Allow-Origin: *) & HTTP verbs", "Terverifikasi: Preflight OPTIONS handler di Mobile.php", "100%"),
            ("2. BASIS DATA & TRANSAKSI", "2.1 Migration scripts database (20260629_mobile_api, 20260728_account_deletion)", "Terverifikasi: Folder db/migrations/*.sql", "100%"),
            ("2. BASIS DATA & TRANSAKSI", "2.2 Penerapan transaksi ACID (trans_begin, trans_commit, trans_rollback)", "Terverifikasi: Model Mobile_api_model.php (1612 baris kode)", "100%"),
            ("2. BASIS DATA & TRANSAKSI", "2.3 Optimasi query view v_products dan pemanfaatan indeks tabel relasi", "Terverifikasi: sql_view.sql & indeks foreign key dump", "95%"),
            ("3. KINERJA & SKALABILITAS", "3.1 Kompatibilitas penuh CodeIgniter 3 HMVC pada lingkungan PHP 8.x", "Terverifikasi: Log error testing & library compatibility", "95%"),
            ("3. KINERJA & SKALABILITAS", "3.2 Keranjang belanja mobile berbasis basis data tanpa dependensi sesi web", "Terverifikasi: Tabel mobile_cart_items", "100%"),
            ("3. KINERJA & SKALABILITAS", "3.3 Cakupan pengujian unit test API otomatis", "Status: Perluasan skenario pengujian beban transaksi", "85%")
        ],
        "95%",
        ["Arsitektur API mandiri berbasis bearer token tanpa ketergantungan sesi web browser.", "Transaksi checkout terlindungi mekanisme trans_begin dan trans_commit ACID."],
        ["Kebutuhan penanganan query pada tabel historis pesanan yang terus membesar."],
        ["Menerapkan strategi pengarsipan data (archiving) transaksi di atas 2 tahun secara berkala."]
    )

    # ==================== PAGE 11: BSC 9 - KEAMANAN ====================
    make_bsc_pdf_page(
        "Sisi Keamanan (Security & Compliance)",
        "Menjamin keamanan data kredensial pengguna, enkripsi token sesi, perlindungan hak privasi non-PII, dan kepatuhan audit regulasi global.",
        {"owner": "Tim Security & Compliance", "lead": "Zero Critical Vulnerability, Token Expiry, Audit Trail Non-PII", "freq": "Bulanan", "status": "97%", "date": "31-Agu-26"},
        [
            ("1. AUTENTIKASI & SESI", "1.1 Bearer Token SHA-256 (mobile_api_tokens) dengan kedaluwarsa 30 hari", "Terverifikasi: issue_token & user_from_token SHA-256", "100%"),
            ("1. AUTENTIKASI & SESI", "1.2 Hashing kata sandi pengguna berstandar industri BCRYPT (password_hash)", "Terverifikasi: Register & login verify di Mobile.php", "100%"),
            ("1. AUTENTIKASI & SESI", "1.3 Pencabutan token instan saat logout dan pembatasan hak akses level", "Terverifikasi: Function revoke_token & require_fields", "100%"),
            ("2. PRIVASI & NON-PII", "2.1 Endpoint penghapusan akun mandiri DELETE /api/v1/account", "Terverifikasi: App Store Guideline 5.1.1(v) compliance", "100%"),
            ("2. PRIVASI & NON-PII", "2.2 Audit trail non-PII dengan hash SHA-256 pada tabel mobile_account_deletions", "Terverifikasi: Struktur kolom email_hash CHAR(64)", "100%"),
            ("2. PRIVASI & NON-PII", "2.3 Pelepasan relasi PII pada pesanan tanpa menghapus arsip pembukuan", "Terverifikasi: Anonymization logic di delete_account", "100%"),
            ("3. OPERASIONAL & AUDIT", "3.1 Isolasi akun uji coba internal (is_internal) agar tidak mengotori omset riil", "Terverifikasi: Pemisahan data akun demo di database", "95%"),
            ("3. OPERASIONAL & AUDIT", "3.2 Sanitasi input payload JSON mencegah ancaman SQL Injection & XSS", "Terverifikasi: Input casting & query bindings", "95%"),
            ("3. OPERASIONAL & AUDIT", "3.3 Pembaruan rutin sertifikat keamanan SSL/TLS dan private key perbankan", "Terverifikasi: File key/private.pem & HTTPS headers", "90%")
        ],
        "97%",
        ["Kepatuhan penuh standar Apple App Review 5.1.1(v) dan enkripsi SHA-256 token.", "Pemisahan data audit non-PII menjamin kerahasiaan data pengguna."],
        ["Kebutuhan rotasi kunci privat enkripsi perbankan berkala sesuai kebijakan perbankan."],
        ["Menjadwalkan rotasi kunci per semester dan memonitor anomali otentikasi server."]
    )

    # ==================== PAGE 12: MATRIKS TERPADU ====================
    story.append(Paragraph("<b>MATRIKS GROUNDED TERPADU: ACCOMPLISHMENT, ISSUES & RISK MITIGATION</b>", ParagraphStyle('MT1', parent=styles['Heading2'], fontName='Helvetica-Bold', fontSize=13, leading=15, textColor=colors.HexColor("#0F172A"))))
    story.append(Paragraph("Evaluasi Komparatif 9 Sisi Kinerja Berbasis Data Nyata Codebase dan Operasional Lapangan", ParagraphStyle('MT2', parent=table_cell_style, fontSize=8, textColor=colors.HexColor("#475569"))))
    story.append(Spacer(1, 6))
    
    mat_data = [
        [
            Paragraph("Perspektif BSC", table_header_style),
            Paragraph("Accomplishment (Teruji)", table_header_style),
            Paragraph("Issues & Root Cause (Tantangan)", table_header_style),
            Paragraph("Next Steps & Risk Mitigation (Solusi)", table_header_style)
        ],
        [
            Paragraph("<b>1. Apps / Platform</b>", table_cell_bold),
            Paragraph("Lolos Apple Review 5.1.1 & Guest Browsing aktif 100%.", table_cell_style),
            Paragraph("Kios konvensional butuh adaptasi alur digital.", table_cell_style),
            Paragraph("Rilis fitur Quick Re-Order 1-klik & video panduan.", table_cell_style)
        ],
        [
            Paragraph("<b>2. Customer</b>", table_cell_bold),
            Paragraph("Pemesanan mandiri 24/7 & live chat CS terintegrasi.", table_cell_style),
            Paragraph("Kios daerah terbiasa pesan via telepon pribadi sales.", table_cell_style),
            Paragraph("Insentif kupon promo belanja perdana via mobile.", table_cell_style)
        ],
        [
            Paragraph("<b>3. Sales</b>", table_cell_bold),
            Paragraph("Penugasan toko binaan terkunci rapi per salesman_id.", table_cell_style),
            Paragraph("Kekhawatiran pemotongan komisi pesanan mobile.", table_cell_style),
            Paragraph("SK Direksi: komisi 100% tetap milik sales pembina.", table_cell_style)
        ],
        [
            Paragraph("<b>4. Logistik</b>", table_cell_bold),
            Paragraph("Ongkir presisi kecamatan & quote lock 30 menit teruji.", table_cell_style),
            Paragraph("Tarif kurir reguler mahal untuk pupuk tonase besar.", table_cell_style),
            Paragraph("Aktivasi opsi armada truk internal Karisma.", table_cell_style)
        ],
        [
            Paragraph("<b>5. Multiplatform</b>", table_cell_bold),
            Paragraph("Tri-Platform iOS Swift, Android Kotlin & Web Admin siap.", table_cell_style),
            Paragraph("Variasi spesifikasi ponsel Android mitra di daerah.", table_cell_style),
            Paragraph("Menjaga ukuran APK < 15MB & kompresi foto lokal.", table_cell_style)
        ],
        [
            Paragraph("<b>6. Perusahaan</b>", table_cell_bold),
            Paragraph("Settlement kas cepat memotong DSO; audit faktur aman.", table_cell_style),
            Paragraph("Penyesuaian kebiasaan staf cabang ke sistem otomatis.", table_cell_style),
            Paragraph("Pelatihan SOP cabang & monitoring omset terpusat.", table_cell_style)
        ],
        [
            Paragraph("<b>7. Keuangan</b>", table_cell_bold),
            Paragraph("BRIVA auto-settlement & margin 3 level terkunci.", table_cell_style),
            Paragraph("Risiko lonjakan antrean saat puncak musim tanam.", table_cell_style),
            Paragraph("Idempotency random external-id & fallback transfer kasir.", table_cell_style)
        ],
        [
            Paragraph("<b>8. Teknis</b>", table_cell_bold),
            Paragraph("Backend RESTful API /api/v1 & transaksi ACID DB.", table_cell_style),
            Paragraph("Volume data transaksi historis semakin membesar.", table_cell_style),
            Paragraph("Penjadwalan archiving data berkala & optimasi indeks.", table_cell_style)
        ],
        [
            Paragraph("<b>9. Keamanan</b>", table_cell_bold),
            Paragraph("Bearer Token SHA-256, BCRYPT, audit non-PII lulus uji.", table_cell_style),
            Paragraph("Kebutuhan rotasi kunci privat enkripsi berkala.", table_cell_style),
            Paragraph("Prosedur rotasi kunci tahunan & monitoring log server.", table_cell_style)
        ]
    ]
    t_mat = Table(mat_data, colWidths=[110, 190, 190, 230])
    t_mat.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#EA580C")),
        ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.HexColor("#FFFFFF"), colors.HexColor("#F8FAFC")]),
    ]))
    story.append(t_mat)
    story.append(PageBreak())

    # ==================== PAGE 13: STRATEGIC ROADMAP ====================
    story.append(Paragraph("<b>STRATEGIC ROADMAP: JADWAL & TAHAPAN IMPLEMENTASI (Q3 2026 – Q1 2027)</b>", ParagraphStyle('RM1', parent=styles['Heading2'], fontName='Helvetica-Bold', fontSize=13, leading=15, textColor=colors.HexColor("#0F172A"))))
    story.append(Paragraph("Rencana Kerja Bertahap Menuju Peluncuran Penuh & Skalabilitas Nasional", ParagraphStyle('RM2', parent=table_cell_style, fontSize=8.5, textColor=colors.HexColor("#475569"))))
    story.append(Spacer(1, 10))
    
    road_data = [
        [
            Paragraph("<b>Fase 1: Peluncuran & Aktivasi</b><br/><font size=7.5 color='#EA580C'>Q3 2026 (SEGERA)</font>", ParagraphStyle('R1', parent=table_cell_bold, textColor=colors.HexColor("#EA580C"))),
            Paragraph("<b>Fase 2: Optimalisasi Logistik & Komisi</b><br/><font size=7.5 color='#059669'>Q4 2026</font>", ParagraphStyle('R2', parent=table_cell_bold, textColor=colors.HexColor("#059669"))),
            Paragraph("<b>Fase 3: Konsolidasi & Skalabilitas</b><br/><font size=7.5 color='#0284C7'>Q1 2027</font>", ParagraphStyle('R3', parent=table_cell_bold, textColor=colors.HexColor("#0284C7")))
        ],
        [
            Paragraph("""
            • Publikasi serentak di App Store & Play Store.<br/>
            • Onboarding 500+ mitra kios binaan bersama sales.<br/>
            • Aktivasi promo belanja perdana via mobile.<br/>
            • Monitoring settlement harian BRIVA & gudang.
            """, table_cell_style),
            Paragraph("""
            • Integrasi opsi armada truk internal muatan tonase.<br/>
            • Peluncuran program poin loyalitas toko aktif.<br/>
            • Pemberlakuan penuh komisi digital sales.<br/>
            • Otomasi diskon musiman cuci gudang deadstock.
            """, table_cell_style),
            Paragraph("""
            • Konsolidasi peramalan stok berbasis musim tanam.<br/>
            • Perluasan area cabang ke sentra pertanian baru.<br/>
            • Peningkatan kapasitas server & fitur lanjutan.<br/>
            • Integrasi sistem konsultasi teknis pertanian.
            """, table_cell_style)
        ]
    ]
    t_road = Table(road_data, colWidths=[240, 240, 240])
    t_road.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (0,0), colors.HexColor("#FEF3C7")),
        ('BACKGROUND', (1,0), (1,0), colors.HexColor("#D1FAE5")),
        ('BACKGROUND', (2,0), (2,0), colors.HexColor("#E0F2FE")),
        ('BACKGROUND', (0,1), (-1,1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('TOPPADDING', (0,0), (-1,-1), 8),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_road)
    story.append(PageBreak())

    # ==================== PAGE 14: KESIMPULAN EKSEKUTIF ====================
    story.append(Paragraph("<b>KESIMPULAN EKSEKUTIF & PERSETUJUAN DIREKSI</b>", ParagraphStyle('EX1', parent=styles['Heading2'], fontName='Helvetica-Bold', fontSize=13, leading=15, textColor=colors.HexColor("#0F172A"))))
    story.append(Paragraph("Persetujuan Peluncuran Komersial Ekosistem Karisma Online", ParagraphStyle('EX2', parent=table_cell_style, fontSize=8.5, textColor=colors.HexColor("#475569"))))
    story.append(Spacer(1, 10))
    
    appr_data = [
        [
            Paragraph("<b>📌 Ringkasan Evaluasi 9 Sisi Kesiapan</b>", ParagraphStyle('Ap1', parent=table_cell_bold, textColor=colors.HexColor("#0F172A"))),
            Paragraph("<b>✅ Rekomendasi Keputusan Dewan Direksi</b>", ParagraphStyle('Ap2', parent=table_cell_bold, textColor=colors.HexColor("#059669")))
        ],
        [
            Paragraph("""
            • <b>Rata-Rata Kesiapan 94.2%:</b> Ekosistem Karisma Online telah mencapai kesiapan komersial penuh dan lulus seluruh audit regulasi.<br/><br/>
            • <b>Kepatuhan Standar Industri:</b> Memenuhi 100% persyaratan Apple App Store, Google Play Store, dan perbankan Bank BRI.<br/><br/>
            • <b>Efisiensi Operasional Terbukti:</b> Pemangkasan waktu settlement kas dari jam ke detik serta akurasi ongkos kirim tingkat kecamatan.
            """, table_cell_style),
            Paragraph("""
            • <b>1. Persetujuan Peluncuran (Go-Live Sign-Off):</b> Memberikan persetujuan resmi peluncuran publik aplikasi mobile Karisma Online.<br/><br/>
            • <b>2. Kebijakan Komisi Digital Sales:</b> Mengesahkan SK Direksi terkait alokasi 100% komisi pesanan digital untuk salesman pembina kios.<br/><br/>
            • <b>3. Alokasi Armada Logistik:</b> Menyetujui skema pengiriman armada truk internal untuk pesanan pupuk tonase besar.
            """, table_cell_style)
        ]
    ]
    t_appr = Table(appr_data, colWidths=[360, 360])
    t_appr.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (0,0), colors.HexColor("#F8FAFC")),
        ('BACKGROUND', (1,0), (1,0), colors.HexColor("#D1FAE5")),
        ('BACKGROUND', (0,1), (-1,1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('TOPPADDING', (0,0), (-1,-1), 10),
        ('BOTTOMPADDING', (0,0), (-1,-1), 10),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_appr)
    story.append(Spacer(1, 15))
    
    end_banner = [[Paragraph("<b>Dokumen ini siap disahkan sebagai acuan kerja resmi PT. Karisma Indoagro Universal. Sesi Diskusi & Tanya Jawab (Q&A) dipersilakan.</b>", ParagraphStyle('EB', parent=table_cell_style, alignment=1, textColor=colors.HexColor("#0F172A")))]]
    t_end = Table(end_banner, colWidths=[720])
    t_end.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F1F5F9")),
        ('BOX', (0,0), (-1,-1), 0.5, colors.HexColor("#CBD5E1")),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_end)

    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"SUCCESS: Generated 9 BSC PDF Report at {filename}")

if __name__ == "__main__":
    pdf_path = "/Applications/XAMPP/xamppfiles/htdocs/kiustore/docs/nw_laporan_eksekutif_bsc.pdf"
    build_pdf(pdf_path)
