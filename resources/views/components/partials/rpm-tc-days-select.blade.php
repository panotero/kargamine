{{--
    Paired with a .rpm-tc-payment-term select (same data-section) one level
    up - shown only when that select's value is one of the two "Days
    After..." templates (TERMS_OF_PAYMENT_DAY_TEMPLATES in
    logic_prospect_request_proposal.js). Hidden by default; toggled by that
    file's wireTcDaysToggle(). The chosen count is prefixed onto the saved
    terms_of_payment string at save time, not stored as its own column.
--}}
<select class="rpm-tc-days-select hidden mt-2 w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"
    data-section="{{ $section }}">
    <option value="">Select Number of Days</option>
</select>
