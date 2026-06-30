# UI/UX & Layout Guide: USU-Inspired Academic Theme

This guide details the style, layout rules, and components inspired by the Universitas Sumatera Utara (USU) portal, and defines how they are integrated into the **EduCampus WordPress Theme** with a drag-and-drop editing capability.

---

## 1. Analysis of USU Web Pages

### A. Tentang (https://usu.ac.id/id/tentang)
* **Header & Hero**:
  * Clean, minimal header with a secondary green bar.
  * Hero section features a prominent title ("Tentang Universitas Sumatera Utara") alongside an accreditation badge ("Terakreditasi Unggul BAN-PT") to establish authority immediately.
* **Key Statistics**:
  * Grid structure highlighting enrollment and faculty counts (e.g., "42,687 Mahasiswa", "218 Guru Besar").
  * Layout: Large counter numbers in green, followed by descriptions.
* **Rankings Bar**:
  * A horizontal array showing international rankings (QS World, THE World, Webometrics) inside clean cards.
* **Leadership / Organisasi**:
  * Large spotlight card for the Rector on the right.
  * Sidebar-style links for university organs (MWA, SA, DGB) and Vice Rectors on the left.
  * Gradient overlay and green accents.

### B. Identitas (https://usu.ac.id/id/tentang/identitas)
* **Lambang & Makna**:
  * Grid layout: Symbol image on one side, hierarchical numbered list explaining meaning on the other.
* **Himne & Mars**:
  * Two-column layout showcasing lyrics in italicized styled typography.
  * White surface boxes with grey borders and subtle drop shadows.

### C. Tugas & Fungsi (https://usu.ac.id/id/tentang/tugas-fungsi)
* **Layout**:
  * Structured vertical flow representing Vision, Mission, Tasks, and Functions.
  * Utilizes section titles with decorative icons (e.g., custom floral/flower SVGs) on the left.

### D. Struktur Organisasi (https://usu.ac.id/id/tentang/struktur-organisasi)
* **Layout Grid**:
  * Responsive hierarchy grid.
  * Cards are flat, separated cleanly.
  * Top Level (Rector/Dean) spans full width, followed by row grids for Vice Rectors/Vice Deans (`col-md-6` or `col-lg-4`).

---

## 2. UI/UX Design System Tokens

| Category | Token / Value | Application |
|---|---|---|
| **Primary Color** | `#038A47` (Bright Academic Green) | Headers, active menu borders, icons, buttons |
| **Secondary Accent** | `#F5A623` / `#E8A238` (Academic Gold) | Sub-headings, active indicators, decorative highlights |
| **Background Color** | `#FFFFFF` (Content Base) / `#F8F9FA` (Alternating section grey) | White paper surfaces, page backgrounds |
| **Typography (Headings)** | `'Poppins', sans-serif` | Strong, professional, clean readability |
| **Typography (Body)** | `'Inter', sans-serif` | Clean sans-serif with high contrast |
| **Cards & Surfaces** | `border: 1px solid #E5E7EB; border-radius: 12px; shadow-sm` | Default containers |

---

## 3. Drag-and-Drop Editing Architecture (Gutenberg Block Patterns)

To support drag-and-drop layout editing without requiring paid/bloated plugins, we will implement **Gutenberg Custom Block Patterns**. This allows users to add and arrange the university sections in the default editor.

### Pattern Blocks to Register:
1. **Hero Header Pattern**: Hero title + accreditation badge.
2. **Stats Grid Pattern**: 3-4 column academic numbers.
3. **Rankings Bar Pattern**: Flexible horizontal badge container.
4. **Leadership Grid Pattern**: Two-column layout (Left: Navigation Links; Right: Profile Cards).
5. **Hymne & Mars Pattern**: Double lyric blocks side-by-side.
6. **Symbol & Makna Pattern**: Left image + right list blocks.

---

## 4. Layout Templates Setup

We will create page templates mapping to the four sections:
- `page-tentang.php`: Main portal page layout.
- `page-identitas.php`: Symbolic layout (lyrics, logo descriptions).
- `page-tugas-fungsi.php`: Vertical list style with icon headings.
- `page-struktur-organisasi.php`: Clean card grid for organizational hierarchies.

Each template will render `the_content()`, enabling full editing and layout building using block patterns.
