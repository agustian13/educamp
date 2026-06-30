# EduCampus Theme — DESIGN.md

## Visual Theme & Atmosphere

**Academic Professional dengan identitas hijau khas universitas negeri Indonesia.**

- **Mood:** Formal, terpercaya, modern, akademik
- **Density:** Lapang dengan banyak white space, grid terstruktur
- **Design Philosophy:** "Kredibel dan modern" — kombinasi warna institusional yang kuat dengan tata letak bersih dan elemen dekoratif tradisional Indonesia (ornamen floral/pattern)
- **Target Audience:** Calon mahasiswa, orang tua, akademisi, mitra institusi

## Color Palette & Roles

| Token | Hex | Role |
|---|---|---|
| `--color-primary` | `#006747` | Hijau khas universitas — branding utama, header, footer, tombol primer |
| `--color-primary-dark` | `#004d33` | Footer background, hover states |
| `--color-primary-light` | `#00885a` | Hover, highlights |
| `--color-accent` | `#E8A238` | Aksen emas/oranye — CTA sekunder, highlight khusus |
| `--color-accent-light` | `#F5C26B` | Hover aksen |
| `--color-bg` | `#FFFFFF` | Background utama |
| `--color-bg-alt` | `#F8F9FA` | Background abu-abu ringan untuk section bergantian |
| `--color-bg-dark` | `#004d33` | Background hijau gelap untuk footer/section khusus |
| `--color-text` | `#1a1a1a` | Teks utama |
| `--color-text-light` | `#6B7280` | Teks sekunder, metadata |
| `--color-text-on-dark` | `#FFFFFF` | Teks di atas background gelap |
| `--color-text-on-primary` | `#FFFFFF` | Teks di atas primary |
| `--color-border` | `#E5E7EB` | Border ringan, pemisah |
| `--color-white` | `#FFFFFF` | Kartu, surface |

## Typography Rules

| Level | Font | Weight | Size | Line Height | Letter Spacing |
|---|---|---|---|---|---|
| Display/Hero | `'Poppins', sans-serif` | 700 (Bold) | `clamp(2rem, 5vw, 3.5rem)` | 1.2 | -0.02em |
| Heading 1 | `'Poppins', sans-serif` | 700 | `clamp(1.5rem, 3vw, 2.25rem)` | 1.3 | -0.01em |
| Heading 2 | `'Poppins', sans-serif` | 600 | `clamp(1.25rem, 2.5vw, 1.75rem)` | 1.3 | normal |
| Heading 3 | `'Poppins', sans-serif` | 600 | `clamp(1.1rem, 2vw, 1.35rem)` | 1.4 | normal |
| Body | `'Inter', sans-serif` | 400 | `1rem` | 1.6 | normal |
| Body Small | `'Inter', sans-serif` | 400 | `0.875rem` | 1.5 | normal |
| Caption/Label | `'Inter', sans-serif` | 500 | `0.75rem` | 1.4 | 0.02em |
| Button | `'Poppins', sans-serif` | 600 | `0.9375rem` | 1 | 0.01em |
| Nav Link | `'Poppins', sans-serif` | 500 | `0.9375rem` | 1 | 0.01em |
| Stat Number | `'Poppins', sans-serif` | 700 | `2.5rem` | 1 | normal |

## Component Stylings

### Buttons

| Type | Style |
|---|---|
| **Primary** | `bg: var(--color-primary)`, `color: white`, `border-radius: 50px` (pill shape), `padding: 12px 32px`, `font-weight: 600`, `transition: all 0.3s ease` |
| Primary Hover | `bg: var(--color-primary-dark)`, `transform: translateY(-2px)`, `box-shadow: 0 4px 12px rgba(0,103,71,0.3)` |
| **Secondary/Outline** | `border: 2px solid var(--color-primary)`, `color: var(--color-primary)`, `bg: transparent`, `border-radius: 50px` |
| Secondary Hover | `bg: var(--color-primary)`, `color: white` |
| **Accent CTA** | `bg: var(--color-accent)`, `color: white`, `pill shape` |
| Accent CTA Hover | `bg: var(--color-accent-light)`, `transform: translateY(-2px)` |

### Cards

| Property | Value |
|---|---|
| Background | `var(--color-white)` |
| Border Radius | `12px` |
| Box Shadow | `0 2px 8px rgba(0,0,0,0.06)` |
| Hover | `transform: translateY(-4px)`, `box-shadow: 0 12px 24px rgba(0,0,0,0.1)` |
| Transition | `all 0.3s ease` |
| Padding | `24px` |

### Navigation (Header)

- Background: `white` dengan `box-shadow: 0 2px 8px rgba(0,0,0,0.06)`
- Logo: Hijau, di kiri, tinggi `40-48px`
- Menu: Horizontal, `Poppins 500`, `color: var(--color-text)`
- Active menu item: `color: var(--color-primary)`, `border-bottom: 3px solid var(--color-primary)`
- Dropdown: Mega menu full-width dengan grid, background white, `border-top: 3px solid var(--color-primary)`
- Sticky: Ya, `position: sticky; top: 0; z-index: 100`

### Hero Section

- Layout: Full-width, `min-height: 80vh` atau `600px`
- Background: Gambar full-bleed dengan `object-fit: cover`
- Overlay: Gradient semi-transparan hijau tua (`rgba(0,77,51,0.6)` ke `rgba(0,77,51,0.3)`)
- Content: Center alignment, teks putih
- Welcome text: Heading besar putih, `text-shadow` ringan
- CTA: Tombol putih outline atau solid

### Stats / Angka Section

- Layout: Grid 4-6 kolom
- Items: Icon + angka besar + label
- Angka: `font-size: 2.5rem`, `font-weight: 700`, `color: var(--color-primary)`
- Label: `font-size: 0.875rem`, `color: var(--color-text-light)`
- Icon: SVG outline, `width: 48px`, `color: var(--color-primary)`

### Section Dekoratif

- Ornamen floral/pattern tradisional Indonesia:
  - Digunakan sebagai elemen dekoratif di pojok section
  - Warna: `var(--color-primary)` dengan opacity rendah (`0.1` - `0.15`)
  - Ukuran: `clamp(64px, 8vw, 128px)`
  - Jenis: bunga, pattern geometris, garis lengkung

### Cards Grid (Berita)

- Grid: `grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))`
- Gap: `24px`
- Featured item pertama: `grid-column: 1 / -1` atau lebih lebar
- Thumbnail: `aspect-ratio: 16/9`, `object-fit: cover`
- Date: Small text abu-abu di atas judul

### Footer

- Background: `var(--color-bg-dark)` atau `var(--color-primary-dark)`
- Text: `var(--color-text-on-dark)` (putih)
- Layout: Multi-column grid
- Logo: Putih/light version
- Social icons: Bulat, `border: 1px solid rgba(255,255,255,0.3)`, hover solid putih
- Copyright bar: Lapisan terpisah, `border-top: 1px solid rgba(255,255,255,0.1)`

## Layout Principles

| Property | Value |
|---|---|
| Max content width | `1200px` |
| Section padding Y | `clamp(3rem, 6vw, 6rem)` |
| Section padding X | `clamp(1rem, 3vw, 2rem)` |
| Grid gap | `24px` |
| Container | `max-width: 1200px; margin: 0 auto; padding: 0 1rem` |
| White space philosophy | Lapang, setiap section punya breathing room yang cukup |

## Depth & Elevation

| Level | Shadow | Use |
|---|---|---|
| 0 (base) | none | Cards default |
| 1 (raised) | `0 2px 8px rgba(0,0,0,0.06)` | Cards resting |
| 2 (elevated) | `0 4px 16px rgba(0,0,0,0.1)` | Cards hover |
| 3 (floating) | `0 8px 32px rgba(0,0,0,0.12)` | Dropdown, modal |
| 4 (overlay) | `0 12px 48px rgba(0,0,0,0.15)` | Fixed header, drawer |

## Do's and Don'ts

### Do's
- Gunakan hijau sebagai warna dominan dan pengenal brand
- Pertahankan banyak white space antar section
- Ornamen dekoratif Indonesia hanya sebagai aksen kecil, jangan berlebihan
- Foto/gambar berkualitas tinggi dan profesional
- Konsisten dengan border-radius 12px untuk card

### Don'ts
- Jangan gunakan warna neon atau terlalu kontras
- Jangan animasi berlebihan — cukup subtle transitions
- Jangan gunakan emoji sebagai ikon (gunakan SVG outline)
- Jangan gunakan mode gelap (dark mode) untuk section utama
- Jangan gunakan gradient "AI purple/pink" yang tidak sesuai identitas akademik

## Responsive Behavior

| Breakpoint | Target |
|---|---|
| `375px` | Mobile kecil |
| `640px` | Mobile besar |
| `768px` | Tablet portrait |
| `1024px` | Tablet landscape / desktop kecil |
| `1200px` | Desktop |

- Mobile: Nav jadi hamburger menu, grid jadi single column
- Tablet: Grid 2 kolom, nav tetap horizontal
- Desktop: Grid multi-column, mega menu
- Touch targets: Minimum `44x44px` untuk semua interactive elements
- Hero: Pada mobile, kurangi `min-height` jadi `50vh`

## Accessibility

- Contrast ratio minimum `4.5:1` untuk text normal, `3:1` untuk large text
- Focus states: `outline: 2px solid var(--color-primary)` dengan `outline-offset: 2px`
- `prefers-reduced-motion`: Nonaktifkan animasi non-esensial
- Semua ikon SVG harus punya `role="img"` dan `aria-label`
- Skip to content link di atas halaman

## Agent Prompt Guide

When asked to build UI for this project:
- Refer to this DESIGN.md for all styling decisions
- Use `var(--color-primary)` (`#006747`) as the main brand color
- Use Poppins for headings, Inter for body text
- Use pill-shaped buttons with 50px border-radius
- Use white cards with subtle shadows for content grouping
- Maintain generous whitespace between sections
- Full-width hero sections with overlay are the primary entry pattern
