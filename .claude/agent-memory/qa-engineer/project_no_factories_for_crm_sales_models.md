---
name: project-no-factories-for-crm-sales-models
description: Prospect/ProposalRequest/ClientMaster/ClientProposal have no database factories — build test fixtures by hand
metadata:
  type: project
---

`database/factories/` only has `UserFactory.php`. There are no factories for `Prospect`, `ProposalRequest` (or its child tables like `proposal_request_company_details`/`_signatories`/`_locations`), `ClientMaster`, or `ClientProposal`.

**Why:** these models have a lot of nullable columns and rely on DB defaults, so hand-built fixtures are usually small — but the one or two NOT-NULL-no-default columns per table aren't obvious from the model/migration alone (e.g. `proposal_request_locations.address_country` is NOT NULL no-default, while `prospect_locations.address_country` is NOT NULL but defaults to `'Philippines'` — same-looking column, different DB behavior).

**How to apply:** when a throwaway feature test needs a real row in one of these tables, don't guess the required fields from the migration filename/model fillable alone. Run `php artisan tinker --execute="foreach (DB::select('SHOW COLUMNS FROM <table>') as $c) { if ($c->Null==='NO' && is_null($c->Default) && $c->Extra!=='auto_increment') echo $c->Field.' | '.$c->Type.PHP_EOL; }"` against the dev DB (same schema as the migrations the test suite runs) to get the exact minimal required-field list before writing the fixture. `Prospect` and `ClientMaster` use `HasUuids` and auto-generate `uuid` on create; `ClientProposal` does NOT use `HasUuids` — its `uuid` must be set manually (e.g. `(string) \Illuminate\Support\Str::uuid()`) or the NOT-NULL `uuid` column insert fails. See also [[project_prospect_module_untracked_in_git]].
