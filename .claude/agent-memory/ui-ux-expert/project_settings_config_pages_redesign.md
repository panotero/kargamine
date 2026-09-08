---
name: project-settings-config-pages-redesign
description: Review of the 4 single-record Settings config pages (Theme, Mailer, App Information, Notification Test) — batch 3 of the Settings-area redesign
metadata:
  type: project
---

Batch 3 of the Settings-area redesign (parallel review batches covering the whole Settings hub). These 4 are single-record "edit the one settings row" forms — kept simple, no rail+tabs/wizard.

**Why:** Series of review-only UI/UX passes; findings get synthesized into an approved HTML mockup before any code. I review, don't implement.

**How to apply:** Non-obvious findings worth carrying forward:
- **Mailer SMTP password is echoed to the client in plaintext.** `mailer.blade.php` renders `value="{{ old('mail_password', $config->mail_password ?? '') }}"` into the input; stored plaintext in DB (`MailerController::save` blind `updateOrCreate`). The eye-toggle reveals it. Headline security finding — fix is render blank + "leave blank to keep current".
- **Primary-button color is inconsistent across the app and contradicts a doc.** Theme/App-Info/Notif-Test Save buttons use `bg-blue-600`; Mailer uses `bg-orange-500`. The theme picker's own SLOTS define `main_color` (=hijacked `blue` token) as "Primary buttons", but VISUALS.md line 11 says primary = orange-500/600. Genuine conflict — tech-lead must pick one. Blue-is-primary is what the theme system implies.
- **Theme preview leaks unsaved changes app-wide for the session.** `previewSlot()` mutates `:root` CSS vars live on the once-per-session shell; navigating away without saving leaves the previewed colors applied until a full reload. Needs a revert-on-leave or contained sample panel.
- Mailer test-mail form + Trigger-API button use raw `fetch()` with inline hex-styled status boxes (`#d4edda`), bypass `apiCall`/`showMessage`, no button loading state, no dark mode. Main mailer form is a native full-page POST (`back()->with('success')`) — breaks the SPA shell model. Everything else here correctly uses `apiCall` with `button:`.
