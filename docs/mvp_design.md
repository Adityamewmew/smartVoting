# SmartVoting UI/UX MVP (Compound Theme)

## Core Philosophy
- **Monochrome**: Only black, white, gray. One accent (Cream `#ffe9bf`).
- **Typography**: Single weight (400). Inter/General Sans. Hierarchy via size jumps (14px -> 36px -> 60px).
- **Shapes**: Pill buttons (9999px), rounded cards (20px).
- **Depth**: Mostly flat. Soft shadows (`rgba 0.01-0.10`) for elevated cards only.

## Global Setup
- **Font**: Inter (Weight 400 only).
- **Background**: `#ffffff` (Paper).
- **Text**: `#171717` (Ink).
- **Borders**: `#e5e7eb` (Graphite Hairline) 1px.

## Specific Views

### 1. Admin Dashboard & Sidebar
- **Sidebar**:
  - Background: White.
  - Right Border: 1px `#e5e7eb`.
  - Links: 14px text. Hover/Active: `#f3f3f3` background, `#171717` text.
- **Top Nav**:
  - Invisible/White. Profile dropdown uses pill style.
- **Tables (Riwayat & Data)**:
  - Clean headers (12px, `#6f6f6f`).
  - Rows: 14px `#171717`. Bottom border 1px `#e5e7eb`.
  - Status Badges: Pill shaped (9999px). Draft/Active/Closed using grayscale, except active can use subtle outline.
- **Empty States**:
  - Centered. 16px `#6f6f6f`. No illustrations.

### 2. Public Landing Page (`/pemilihan/{slug}`)
- **Layout**: Centered, max-width 1200px. 80px section gaps.
- **Hero**:
  - Display Headline: 60px - 72px `#171717`. Line height 1.
  - Subtext: 18px `#6f6f6f`.
- **Candidates Grid**:
  - Cards: 20px radius, 1px border `#e5e7eb`.
  - Hover: Add soft shadow (`--shadow-xl`).
  - Candidate Numbers: Circular (9999px), 40px diameter, `#f3f3f3` background.

### 3. Bilik Suara (Kiosk)
- **Token Input (Start)**:
  - Input Field: 1px border, large text (24px).
  - Button: Primary Pill (`#171717` bg, `#ffffff` text).
- **Voting Interface**:
  - Max-width 1200px.
  - Grid Paslon: Large cards (24px radius).
  - Selection: When selected, card gets 2px `#171717` border (Ink). No colored rings.
  - Submit Button: Pill (`#171717`). Fixed at bottom.

## Development Rules (Tailwind v4)
- **No colors**: Do not use `bg-blue-500`, `text-red-500` (except maybe destructive actions, but use grayscale first).
- **No font-bold**: `font-normal` everywhere. Use `text-2xl` or `text-4xl` for hierarchy.
- **Rounded**: `rounded-full` for buttons/icons. `rounded-[20px]` for cards.
- **Shadows**: Use custom soft shadow variables from `Design.md`.
