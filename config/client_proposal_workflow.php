<?php

return [
    // Role names (matched case-insensitively against setting_role.role_name)
    // allowed to approve/disapprove a pending proposal. Add "account manager"
    // here once that role exists - no code changes needed.
    'approver_roles' => ['superadmin', 'admin'],

    // Role names allowed to reject a proposal. In addition to this list,
    // the client's assigned Sales Representative (client_masters.sales_rep_id)
    // can always reject their own client's proposals, even without this role.
    'reject_roles' => ['account manager'],
];
