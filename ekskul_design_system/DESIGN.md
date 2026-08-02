---
name: Ekskul Design System
colors:
  surface: '#f8f9ff'
  surface-dim: '#d0dbed'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e6eeff'
  surface-container-high: '#dee9fc'
  surface-container-highest: '#d9e3f6'
  on-surface: '#121c2a'
  on-surface-variant: '#434655'
  inverse-surface: '#27313f'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#585f67'
  on-secondary: '#ffffff'
  secondary-container: '#dce3ec'
  on-secondary-container: '#5e656d'
  tertiary: '#006329'
  on-tertiary: '#ffffff'
  tertiary-container: '#007f36'
  on-tertiary-container: '#c7ffca'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#dce3ec'
  secondary-fixed-dim: '#c0c7d0'
  on-secondary-fixed: '#151c23'
  on-secondary-fixed-variant: '#40484f'
  tertiary-fixed: '#7ffc97'
  tertiary-fixed-dim: '#62df7d'
  on-tertiary-fixed: '#002109'
  on-tertiary-fixed-variant: '#005320'
  background: '#f8f9ff'
  on-background: '#121c2a'
  surface-variant: '#d9e3f6'
typography:
  display:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  title-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  title-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 32px
---

## Brand & Style

The design system is anchored in a philosophy of **Functional Minimalism**. It prioritizes clarity and speed of information retrieval for a diverse user base of students, teachers, and administrators. The brand personality is professional and reliable, yet remains approachable through generous whitespace and a soft, rounded visual language.

The aesthetic avoids unnecessary ornamentation, focusing instead on structural hierarchy and clean lines. By utilizing a "Content-First" approach, the UI recedes to allow school data—grades, schedules, and activities—to remain the focal point. The emotional response should be one of calm productivity and organized simplicity.

## Colors

The palette is rooted in a "Trust Blue" foundation. The primary color is reserved for high-intent actions and active states. A supporting Light Blue is used for subtle background emphasis and non-critical highlights.

The background uses a cool-toned off-white to reduce eye strain during long-term administrative use, while pure white is strictly reserved for cards and elevated surfaces to create a clear "layer" effect. Semantic colors (Success, Warning, Danger) are used sparingly to signal status without overwhelming the user's cognitive load.

## Typography

This design system utilizes **Plus Jakarta Sans** for its friendly, open counters and modern geometric structure. The type scale is designed to handle dense information—such as academic transcripts—while maintaining a high degree of readability.

- **Headlines:** Use Bold or SemiBold weights with tighter letter-spacing for a confident, editorial look.
- **Body:** Standardized at 14px for density with 16px reserved for long-form reading or high-priority instructions.
- **Labels:** Small, uppercase labels are used for metadata and table headers to provide clear distinction from body text.

## Layout & Spacing

The layout follows a **Fluid 12-Column Grid** system for desktop and a single-column stacked layout for mobile. 

- **Grid Logic:** Use a 24px gutter on desktop to maintain "airiness." 
- **Consistency:** All spacing—padding, margins, and gaps—must be multiples of 4px (8px, 16px, 24px, etc.) to ensure visual rhythm.
- **Responsive Behavior:** On mobile devices, side margins shrink to 16px. Cards should typically span the full width of the viewport on small screens to maximize internal real estate.

## Elevation & Depth

To maintain a clean and minimalist look, depth is achieved through **Tonal Layering** and **Soft Shadows** rather than heavy borders or dark gradients.

1. **Level 0 (Base):** Background color (`#F7F8FA`). Used for the main application canvas.
2. **Level 1 (Card):** White surface (`#FFFFFF`) with a 1px solid border (`#E5E7EB`). This is the primary container for content.
3. **Level 2 (Interaction):** A very soft, diffused shadow (Box Shadow: `0px 4px 12px rgba(0, 0, 0, 0.03)`) used only when a card needs to be highlighted or upon hover states.
4. **Level 3 (Overlay):** Modals and dropdowns use a slightly more pronounced shadow (Box Shadow: `0px 10px 24px rgba(0, 0, 0, 0.08)`) to clearly separate from the background.

## Shapes

The shape language is consistently rounded to evoke a "friendly" and "modern" feel. 

- **Primary Radius:** 12px (0.75rem) for all main content cards and containers.
- **Secondary Radius:** 8px (0.5rem) for input fields, buttons, and smaller UI modules.
- **Tertiary Radius:** 4px (0.25rem) for tags or status badges.
- **Pill:** Used exclusively for notification counts or specific "Status" chips to differentiate them from actionable buttons.

## Components

### Buttons
- **Primary:** Solid `#2563EB` with white text. 8px radius.
- **Secondary:** Solid `#EFF6FF` with `#2563EB` text. No border.
- **Ghost:** Transparent background with `#6B7280` text, turning into a light gray background on hover.

### Input Fields
- White background with a 1px `#E5E7EB` border. 
- On focus, the border changes to `#2563EB` with a subtle 2px blue outer glow (halo).
- Placeholder text uses `#6B7280`.

### Cards
- White background, 12px radius, 1px `#E5E7EB` border.
- Padding should be consistently 24px for desktop and 16px for mobile.

### Chips & Badges
- Used for "Subjects," "Extracurriculars," or "Status."
- Low-contrast backgrounds (e.g., Light Green for "Completed") with high-contrast text.
- 4px radius or full pill shape depending on the context of the data.

### Lists
- Clean rows separated by 1px horizontal lines (`#E5E7EB`). 
- Interactive list items should have a subtle background color change on hover (`#F9FAFB`).

### Icons
- Use **Heroicons** (Outline) with a 1.5px or 2px stroke weight.
- Icon color should generally match the text color it accompanies, or use Primary Blue for navigation cues.