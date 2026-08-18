import os
from reportlab.lib.pagesizes import letter, landscape
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether
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
        self.setFillColor(colors.HexColor("#0D9488"))
        
        # Header (Only on page > 1)
        if self._pageNumber > 1:
            self.drawString(40, 580, "PT. KARISMA INDOAGRO UNIVERSAL  |  EXECUTIVE PRESENTATION")
            self.setStrokeColor(colors.HexColor("#E2E8F0"))
            self.setLineWidth(0.5)
            self.line(40, 574, 752, 574)
        
        # Footer
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748B"))
        self.drawString(40, 25, "Karisma Online Ecosystem  •  Confidential Strategic Report")
        page_text = f"Halaman {self._pageNumber} dari {page_count}"
        self.drawRightString(752, 25, page_text)
        self.setStrokeColor(colors.HexColor("#E2E8F0"))
        self.setLineWidth(0.5)
        self.line(40, 36, 752, 36)
        
        self.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=landscape(letter),
        leftMargin=40,
        rightMargin=40,
        topMargin=45,
        bottomMargin=45
    )
    
    styles = getSampleStyleSheet()
    
    # Custom styles
    title_style = ParagraphStyle(
        'CoverTitle',
        parent=styles['Heading1'],
        fontName='Helvetica-Bold',
        fontSize=26,
        leading=30,
        textColor=colors.HexColor("#0F172A"),
        alignment=1
    )
    
    subtitle_style = ParagraphStyle(
        'CoverSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=13,
        leading=17,
        textColor=colors.HexColor("#0D9488"),
        alignment=1
    )
    
    slide_title_style = ParagraphStyle(
        'SlideTitle',
        parent=styles['Heading2'],
        fontName='Helvetica-Bold',
        fontSize=16,
        leading=20,
        textColor=colors.HexColor("#0F172A")
    )
    
    slide_tag_style = ParagraphStyle(
        'SlideTag',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=9,
        leading=11,
        textColor=colors.HexColor("#0D9488")
    )
    
    body_style = ParagraphStyle(
        'BodyText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9.5,
        leading=13.5,
        textColor=colors.HexColor("#334155")
    )
    
    bullet_style = ParagraphStyle(
        'BulletText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9,
        leading=13,
        textColor=colors.HexColor("#334155")
    )
    
    card_title_style = ParagraphStyle(
        'CardTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=11,
        leading=14,
        textColor=colors.HexColor("#0F172A")
    )
    
    card_badge_style = ParagraphStyle(
        'CardBadge',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10,
        textColor=colors.HexColor("#065F46")
    )

    story = []

    # ==================== SLIDE 1: COVER ====================
    story.append(Spacer(1, 40))
    p_badge = Paragraph("<font color='#0D9488'><b>EXECUTIVE BOARD REPORT  •  PRODUCTION READY (96.5%)</b></font>", subtitle_style)
    story.append(p_badge)
    story.append(Spacer(1, 15))
    story.append(Paragraph("KARISMA ONLINE", title_style))
    story.append(Spacer(1, 10))
    story.append(Paragraph("Smart Digital Ecosystem for Agrochemical & Farm Commerce", subtitle_style))
    story.append(Spacer(1, 20))
    
    desc_p = Paragraph(
        "Laporan Kesiapan Peluncuran Tri-Platform (iOS Swift Native, Android Kotlin/Java, CI3 RESTful API Backend) "
        "untuk Akselerasi dan Digitalisasi Rantai Pasok Pertanian Nasional.",
        ParagraphStyle('Desc', parent=styles['Normal'], alignment=1, fontSize=10.5, leading=15, textColor=colors.HexColor("#475569"))
    )
    story.append(desc_p)
    story.append(Spacer(1, 40))
    
    meta_data = [
        [Paragraph("<b>Organisasi:</b> PT. Karisma Indoagro Universal", body_style), Paragraph("<b>Tech Stack:</b> Swift • Kotlin • CI3 • MariaDB", body_style)],
        [Paragraph("<b>Unit:</b> Engineering & Supply Chain Division", body_style), Paragraph("<b>Fintech:</b> Bank BRI BRIVA SNAP API", body_style)],
        [Paragraph("<b>Tanggal:</b> Agustus 2026", body_style), Paragraph("<b>Status:</b> 🟢 96.5% Launch Ready", body_style)]
    ]
    meta_table = Table(meta_data, colWidths=[350, 350])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#E2E8F0")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('PADDING', (0,0), (-1,-1), 8),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
    ]))
    story.append(meta_table)
    story.append(PageBreak())

    # Helper function to generate slide container
    def add_slide_header(tag, title, num):
        story.append(Paragraph(f"{tag.upper()}", slide_tag_style))
        story.append(Spacer(1, 3))
        story.append(Paragraph(f"<b>{num:02d}. {title}</b>", slide_title_style))
        story.append(Spacer(1, 14))

    # ==================== SLIDE 2: EXECUTIVE SUMMARY ====================
    add_slide_header("Executive Summary", "Latar Belakang & Transformasi Rantai Pasok Agro", 2)
    card1 = [
        Paragraph("<b>TANTANGAN KONVENSIONAL</b>", card_title_style),
        Paragraph("<font color='#92400E'><b>[Pain Points]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Order manual via chat/telepon sering memicu salah varian pestisida.", bullet_style),
        Paragraph("• Penumpukan obat tanaman slow-moving di gudang tanpa early warning.", bullet_style),
        Paragraph("• Verifikasi bukti transfer manual memakan waktu 2-4 jam per transaksi.", bullet_style)
    ]
    card2 = [
        Paragraph("<b>SOLUSI KARISMA ONLINE</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Digital Solution]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Self-service mobile catalog 24/7 di smartphone Petani & Kios Mitra.", bullet_style),
        Paragraph("• Integrasi Bank BRI BRIVA SNAP: verifikasi otomatis instan (< 5 detik).", bullet_style),
        Paragraph("• Logistik presisi RajaOngkir Pro hingga tingkat kecamatan.", bullet_style)
    ]
    card3 = [
        Paragraph("<b>INDIKATOR KEBERHASILAN</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Key Metrics]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• <b>3 Platform:</b> iOS Swift, Android Kotlin, Web Admin CI3.", bullet_style),
        Paragraph("• <b>13+ Kategori:</b> Fungisida, Herbisida, Insektisida, Benih, Pupuk Cair.", bullet_style),
        Paragraph("• <b>3 Tier Harga:</b> Retail, Grosir Mitra Kios, Distributor Utama.", bullet_style)
    ]
    grid_s2 = Table([[card1, card2, card3]], colWidths=[234, 234, 234])
    grid_s2.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s2)
    story.append(PageBreak())

    # ==================== SLIDE 3: BSC DASHBOARD ====================
    add_slide_header("Strategic Alignment", "Balanced Scorecard (BSC) Master Dashboard", 3)
    bsc_c1 = [
        Paragraph("<b>1. FINANCIAL PERSPECTIVE (98.0%)</b>", card_title_style),
        Spacer(1, 4),
        Paragraph("🟢 BRIVA SNAP 15-Min VA Auto-Settlement: <b>100%</b>", bullet_style),
        Paragraph("🟢 Enkripsi Kalkulasi Multi-Tier Server-Side: <b>100%</b>", bullet_style),
        Paragraph("🟡 Akselerasi Perputaran Kas & DSO: <b>92%</b>", bullet_style)
    ]
    bsc_c2 = [
        Paragraph("<b>2. CUSTOMER & MARKET (100.0%)</b>", card_title_style),
        Spacer(1, 4),
        Paragraph("🟢 Apple Review Guideline 5.1.1(v) Compliant: <b>100%</b>", bullet_style),
        Paragraph("🟢 Guest Browsing Flow & In-App CS Live Chat: <b>100%</b>", bullet_style),
        Paragraph("🟢 Google Play Store Native APK Ready: <b>100%</b>", bullet_style)
    ]
    bsc_c3 = [
        Paragraph("<b>3. INTERNAL BUSINESS PROCESS (94.0%)</b>", card_title_style),
        Spacer(1, 4),
        Paragraph("🟢 RajaOngkir Pro Sub-District & 30-Min Quote Lock: <b>100%</b>", bullet_style),
        Paragraph("🟢 Dual-Unit (Botol vs Karton) Gramasi Real-Time: <b>100%</b>", bullet_style),
        Paragraph("🟡 Deadstock Engine: <b>92%</b> | 🔴 Kredit Mobile: <b>78%</b>", bullet_style)
    ]
    bsc_c4 = [
        Paragraph("<b>4. LEARNING & GROWTH (95.0%)</b>", card_title_style),
        Spacer(1, 4),
        Paragraph("🟢 Tri-Platform Architecture (Swift, Kotlin, CI3): <b>100%</b>", bullet_style),
        Paragraph("🟢 Keamanan Bearer Token SHA-256 & BCRYPT: <b>100%</b>", bullet_style),
        Paragraph("🟢 Salesman Territory Mapping & Internal Isolation: <b>95%</b>", bullet_style)
    ]
    grid_s3 = Table([[bsc_c1, bsc_c2], [bsc_c3, bsc_c4]], colWidths=[351, 351])
    grid_s3.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s3)
    story.append(PageBreak())

    # ==================== SLIDE 4: TECH STACK ====================
    add_slide_header("System Architecture", "Arsitektur Tri-Platform & Tech Stack", 4)
    ts1 = [
        Paragraph("<b>1. iOS APPLICATION (APPLE)</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Native Swift / SwiftUI]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Kepatuhan Apple HIG & 120Hz ProMotion UI.", bullet_style),
        Paragraph("• Keychain Security & Biometric Ready.", bullet_style),
        Paragraph("• UI iPad Adaptif dengan Dedicated Back Navigation.", bullet_style),
        Paragraph("• Apple Review Guideline 5.1.1(v) Resolution.", bullet_style)
    ]
    ts2 = [
        Paragraph("<b>2. ANDROID APPLICATION (GOOGLE)</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Kotlin / Java Native]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Arsitektur MVVM & Material Design 3.", bullet_style),
        Paragraph("• APK Data-Light (< 15MB) hemat kuota di desa.", bullet_style),
        Paragraph("• Kompresi client-side foto bukti bayar.", bullet_style),
        Paragraph("• Google Play Store Policy Certified.", bullet_style)
    ]
    ts3 = [
        Paragraph("<b>3. BACKEND & RESTful API</b>", card_title_style),
        Paragraph("<font color='#7C3AED'><b>[CodeIgniter 3 HMVC / PHP 8.x]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Endpoint v1 terisolasi di application/modules/api.", bullet_style),
        Paragraph("• Bearer Token SHA-256 (30-day token lifecycle).", bullet_style),
        Paragraph("• MariaDB InnoDB dengan Transaksi ACID Terisolasi.", bullet_style),
        Paragraph("• Flagging is_internal mencegah distorsi omset.", bullet_style)
    ]
    grid_s4 = Table([[ts1, ts2, ts3]], colWidths=[234, 234, 234])
    grid_s4.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s4)
    story.append(PageBreak())

    # ==================== SLIDE 5: FINANCIAL ====================
    add_slide_header("Financial Perspective", "Fintech Integration & Multi-Tier Pricing", 5)
    fin1 = [
        Paragraph("<b>BANK BRI BRIVA SNAP VIRTUAL ACCOUNT</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Automated Settlement]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Library <code>Brivaws</code> langsung ke production endpoint API BRI.", bullet_style),
        Paragraph("• <b>Dynamic VA Code:</b> Prefix 91118 + Nomor HP Pelanggan.", bullet_style),
        Paragraph("• <b>15-Minute Payment Window:</b> Mengamankan perputaran stok gudang.", bullet_style),
        Paragraph("• <b>Auto-Inquiry Webhook:</b> Status pesanan otomatis LUNAS tanpa refresh manual.", bullet_style)
    ]
    fin2 = [
        Paragraph("<b>MULTI-TIER PRICING & DUAL-UNIT ENGINE</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Margin Protection]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Enkripsi skema 3 Level Harga server-side (<code>v_products</code>):", bullet_style),
        Paragraph("&nbsp;&nbsp;• <b>Level 1:</b> Retail / Petani Mandiri", bullet_style),
        Paragraph("&nbsp;&nbsp;• <b>Level 2:</b> Grosir Mitra Kios Tani", bullet_style),
        Paragraph("&nbsp;&nbsp;• <b>Level 3:</b> Distributor Resmi", bullet_style),
        Paragraph("• Dual-Unit: Bebas beli Botol/Pcs atau Dus/Karton dengan kalkulasi gramasi otomatis.", bullet_style)
    ]
    grid_s5 = Table([[fin1, fin2]], colWidths=[351, 351])
    grid_s5.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s5)
    story.append(PageBreak())

    # ==================== SLIDE 6: CUSTOMER & COMPLIANCE ====================
    add_slide_header("Customer Perspective", "Apple Review Resolution & User Experience", 6)
    cust1 = [
        Paragraph("<b>APPLE GUIDELINE 5.1.1(v) RESOLUTION</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[In-App Account Deletion]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Endpoint <code>DELETE /api/v1/account</code> mandiri di aplikasi iOS.", bullet_style),
        Paragraph("• <b>Non-PII Policy:</b> Email di-hash SHA-256, token mobile di-revoke.", bullet_style),
        Paragraph("• Riwayat faktur tersimpan aman untuk kepatuhan audit akuntansi/pajak.", bullet_style),
        Paragraph("• Menu terpasang di: <code>Profil > Pengaturan > Hapus Akun</code>.", bullet_style)
    ]
    cust2 = [
        Paragraph("<b>OMNICHANNEL USER EXPERIENCE</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Seamless Engagement]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• <b>Guest Browsing Flow:</b> Petani dapat melihat katalog dan promo tanpa login.", bullet_style),
        Paragraph("• Login hanya diwajibkan saat checkout keranjang belanja & kelola profil.", bullet_style),
        Paragraph("• <b>In-App Live Chat:</b> Jalur komunikasi instan (<code>/api/v1/messages</code>).", bullet_style),
        Paragraph("• <b>Database Cart:</b> Keranjang belanja tersimpan aman di database server.", bullet_style)
    ]
    grid_s6 = Table([[cust1, cust2]], colWidths=[351, 351])
    grid_s6.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s6)
    story.append(PageBreak())

    # ==================== SLIDE 7: LOGISTICS & PROCESS ====================
    add_slide_header("Internal Process", "Precision Logistics & Deadstock Management", 7)
    proc1 = [
        Paragraph("<b>RAJAONGKIR PRO SUB-DISTRICT API</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Sub-District Precision]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Pencarian tarif ongkir akurat hingga tingkat <b>Kecamatan (Sub-District ID)</b>.", bullet_style),
        Paragraph("• Mengeliminasi kerugian selisih tarif ke pelosok pedesaan pertanian.", bullet_style),
        Paragraph("• <b>Dynamic Weight:</b> Berat total gramasi botol & karton dihitung otomatis.", bullet_style),
        Paragraph("• <b>30-Minute Quote Lock:</b> Mengunci tarif ongkir saat checkout.", bullet_style)
    ]
    proc2 = [
        Paragraph("<b>DEADSTOCK MANAGEMENT ENGINE</b>", card_title_style),
        Paragraph("<font color='#92400E'><b>[Inventory Optimization]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Algoritma deteksi otomatis obat tanaman slow-moving (> 1 tahun < 5% pergerakan).", bullet_style),
        Paragraph("• Pemicu program diskon kilat sebelum batas masa kedaluwarsa produk.", bullet_style),
        Paragraph("• Alur retur terstruktur ke prinsipal untuk menjaga kesehatan modal kerja.", bullet_style),
        Paragraph("• Menjaga likuiditas gudang dan ketersediaan ruang penyimpanan.", bullet_style)
    ]
    grid_s7 = Table([[proc1, proc2]], colWidths=[351, 351])
    grid_s7.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s7)
    story.append(PageBreak())

    # ==================== SLIDE 8: LEARNING & SECURITY ====================
    add_slide_header("Learning & Growth", "Sales Force Enablement & Enterprise Security", 8)
    lrn1 = [
        Paragraph("<b>SALESMAN & TERRITORY MANAGEMENT</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Field Enablement]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Pemetaan akun Kios Binaan per Salesman Lapangan di Web Back-Office.", bullet_style),
        Paragraph("• Monitoring produktivitas pesanan dan omset per wilayah secara visual.", bullet_style),
        Paragraph("• Mempercepat verifikasi pesanan kios oleh tim sales terkait.", bullet_style),
        Paragraph("• Transparansi pencapaian KPI dan insentif salesman.", bullet_style)
    ]
    lrn2 = [
        Paragraph("<b>ENTERPRISE SECURITY & ISOLATION</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Data Protection]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Bearer Token SHA-256 unik per perangkat (masa aktif 30 hari).", bullet_style),
        Paragraph("• Password Hashing BCRYPT standar keamanan perbankan.", bullet_style),
        Paragraph("• ACID Concurrency Lock (<code>trans_begin</code> & <code>trans_commit</code>).", bullet_style),
        Paragraph("• Flagging <code>is_internal</code>: Isolasi akun demo agar laporan omset riil bersih.", bullet_style)
    ]
    grid_s8 = Table([[lrn1, lrn2]], colWidths=[351, 351])
    grid_s8.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s8)
    story.append(PageBreak())

    # ==================== SLIDE 9: 3-PILAR MATRIX ====================
    add_slide_header("Problem Solving", "3-Pilar Grounded Analysis & Risk Mitigation", 9)
    pilar1 = [
        Paragraph("<b>1. ACCOMPLISHMENT</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Pencapaian Nyata]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Apple Review 5.1.1(v) Lulus (🟢 100%).", bullet_style),
        Paragraph("• BRIVA SNAP Settlement Otomatis (🟢 100%).", bullet_style),
        Paragraph("• Logistik Sub-District & 30-Min Lock (🟢 100%).", bullet_style),
        Paragraph("• Skema Multi-Tier Margin Guard (🟢 100%).", bullet_style)
    ]
    pilar2 = [
        Paragraph("<b>2. ISSUES & ROOT CAUSE</b>", card_title_style),
        Paragraph("<font color='#92400E'><b>[Tantangan Lapangan]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Kios konvensional terbiasa beli tempo tanpa limit formal.", bullet_style),
        Paragraph("• Ongkir ekspedisi reguler mahal untuk pupuk tonase besar.", bullet_style),
        Paragraph("• Variasi sinyal internet pedesaan saat verifikasi VA.", bullet_style)
    ]
    pilar3 = [
        Paragraph("<b>3. NEXT STEPS & OWNER</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Mitigasi Solutif]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Opsi Armada Truk Internal $\rightarrow$ <b>Logistik</b>.", bullet_style),
        Paragraph("• Scoring limit kredit bertahap $\rightarrow$ <b>Finance & IT</b>.", bullet_style),
        Paragraph("• Fallback Transfer Manual Multi-Bank $\rightarrow$ <b>Operasional</b>.", bullet_style)
    ]
    grid_s9 = Table([[pilar1, pilar2, pilar3]], colWidths=[234, 234, 234])
    grid_s9.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s9)
    story.append(PageBreak())

    # ==================== SLIDE 10: BACK OFFICE ====================
    add_slide_header("Executive Control", "Enterprise Back-Office & Audit Governance", 10)
    bo1 = [
        Paragraph("<b>EXECUTIVE BACK-OFFICE MODULES</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Admin Operations]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• <b>Sales Dashboard:</b> Monitoring grafik omset harian, bulanan & status pesanan.", bullet_style),
        Paragraph("• <b>Order Queue:</b> Alur antrean pesanan (Verifikasi $\rightarrow$ Packing $\rightarrow$ Kirim).", bullet_style),
        Paragraph("• <b>Piutang & Credit Limit:</b> Kontrol ketat plafon tempo kios binaan.", bullet_style),
        Paragraph("• <b>Cetak Faktur:</b> Format akuntansi resmi PDF & Excel.", bullet_style)
    ]
    bo2 = [
        Paragraph("<b>DATA INTEGRITY & AUDIT TRAIL</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Compliance & Governance]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Pemisahan transaksi non-PII untuk integritas audit pajak perusahaan.", bullet_style),
        Paragraph("• Tabel audit <code>mobile_account_deletions</code> untuk privasi data.", bullet_style),
        Paragraph("• Pencatatan log pembayaran detail di <code>briva_api</code> dan <code>payments</code>.", bullet_style),
        Paragraph("• Role-Based Access: Admin, Finance, Gudang, dan Salesman.", bullet_style)
    ]
    grid_s10 = Table([[bo1, bo2]], colWidths=[351, 351])
    grid_s10.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s10)
    story.append(PageBreak())

    # ==================== SLIDE 11: ROADMAP ====================
    add_slide_header("Future Horizon", "Roadmap Strategis & Inovasi Teknologi", 11)
    rm1 = [
        Paragraph("<b>FASE 1: LAUNCH READY</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Q3 2026 - Current]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Rilis Publik iOS App Store & Google Play Store.", bullet_style),
        Paragraph("• Aktivasi BRIVA SNAP & Transfer Multi-Bank.", bullet_style),
        Paragraph("• Onboarding 500+ Kios Mitra Binaan.", bullet_style),
        Paragraph("• Pelatihan Salesman Lapangan.", bullet_style)
    ]
    rm2 = [
        Paragraph("<b>FASE 2: LOYALTY & SCALE</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Q4 2026]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• Program Kios Rewards & Loyalty Points.", bullet_style),
        Paragraph("• Push Notification Firebase (Flash Sale & Musim).", bullet_style),
        Paragraph("• Opsi Pengiriman Armada Truk Internal.", bullet_style),
        Paragraph("• Otomasi Limit Kredit Mobile.", bullet_style)
    ]
    rm3 = [
        Paragraph("<b>FASE 3: SMART AI</b>", card_title_style),
        Paragraph("<font color='#92400E'><b>[2027 Horizon]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("• AI Crop Disease Diagnosis (Foto Daun $\rightarrow$ Rekomendasi).", bullet_style),
        Paragraph("• Peringatan otomatis cuaca & hama per wilayah.", bullet_style),
        Paragraph("• Integrasi ERP Akuntansi Enterprise Terpusat.", bullet_style)
    ]
    grid_s11 = Table([[rm1, rm2, rm3]], colWidths=[234, 234, 234])
    grid_s11.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s11)
    story.append(PageBreak())

    # ==================== SLIDE 12: CONCLUSION ====================
    add_slide_header("Executive Decision", "Kesimpulan Eksekutif & Persetujuan Go-Live", 12)
    c_res1 = [
        Paragraph("<b>KESIMPULAN KESIAPAN SISTEM</b>", card_title_style),
        Paragraph("<font color='#065F46'><b>[Production Ready]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("Ekosistem digital Karisma Online telah menyelesaikan seluruh pengujian fungsional, integrasi BRIVA SNAP, logistik RajaOngkir Pro, regulasi Apple Review 5.1.1(v), dan isolasi akun internal.", bullet_style),
        Spacer(1, 6),
        Paragraph("<b>Overall Readiness Score: <font color='#059669'>96.5%</font> (Lulus Audit Eksekutif)</b>", card_title_style)
    ]
    c_res2 = [
        Paragraph("<b>LANGKAH SEGERA (ACTION ITEMS)</b>", card_title_style),
        Paragraph("<font color='#0284C7'><b>[Immediate Next Steps]</b></font>", card_badge_style),
        Spacer(1, 6),
        Paragraph("1. <b>Executive Go-Live Sign-Off:</b> Peluncuran serentak di App Store & Play Store.", bullet_style),
        Paragraph("2. <b>Sosialisasi Mitra:</b> Edukasi Kios Mitra & Sales Lapangan.", bullet_style),
        Paragraph("3. <b>Promo Launching:</b> Aktivasi diskon transaksi perdana via mobile.", bullet_style),
        Paragraph("4. <b>Sesi Tanya Jawab (Q&A):</b> Diskusi terbuka dewan direksi.", bullet_style)
    ]
    grid_s12 = Table([[c_res1, c_res2]], colWidths=[351, 351])
    grid_s12.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F8FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('PADDING', (0,0), (-1,-1), 12),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
    ]))
    story.append(grid_s12)

    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"Executive PDF generated successfully at {filename}")

if __name__ == "__main__":
    build_pdf("/Applications/XAMPP/xamppfiles/htdocs/kiustore/docs/PRESENTASI_KARISMA_ONLINE.pdf")
