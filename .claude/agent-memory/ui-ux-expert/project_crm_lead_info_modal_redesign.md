---
name: project-crm-lead-info-modal-redesign
description: SUPERSEDED — old CRM "Lead Info" modal is gone; see [[project_prospect_info_modal_sidebar]] for the current equivalent
metadata:
  type: project
---

**Status update (2026-10-05): this entire modal no longer exists.** The CRM intake
entity moved from `CrmLead`/`crm_leads` to `Prospect`/`prospects` (see `CLAUDE.md`
"Domain model shape"), and `VISUALS.md` explicitly confirms the old `LeadInfoModal`
in `crm.blade.php` was removed, along with `TIMELINE_DOT_COLOR` and the
`renderContainers()` route-card implementation it used. The rail+tabs recommendation
below WAS acted on, just not on this exact modal — it landed as the 4-tab
`components/prospect-modal.blade.php` (Identity/Contact/Requirements/RFP, the edit
form) plus a separate read-only `components/prospect-info-modal.blade.php`
(tab bar: Locations/Contacts/Requirements/Origin&Destination + a persistent right
"Contact Summary" sidebar) — see [[project_prospect_info_modal_sidebar]] for the
current review of that successor.

Original note below kept for historical record only — don't treat any file path or
class name in it as current.

---

(original 2026-09-02 note) The CRM "Lead Info" read-only detail modal was 5 equal-
weight stacked cards with nested scroll-within-scroll; recommended a persistent left
summary rail + right tabbed working area + merged Activity+Notes timeline. That
general shape (rail/sidebar + tabs, merged-ish activity feed) is the lineage the
current `prospect-info-modal.blade.php` follows, confirming the recommendation held
up even though it was implemented on a renamed/restructured entity rather than the
original file.
