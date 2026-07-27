<?php

return [
    // Approval/disapproval is no longer role-config driven - see
    // ClientProposal::canBeApprovedBy(). It now follows the team hierarchy:
    // the team leader over the lead's assigned rep (or a leader further up
    // the pyramid) can approve, plus superadmin always can.

    // Role names allowed to reject a proposal. In addition to this list,
    // the client's assigned Sales Representative (client_masters.sales_rep_id)
    // can always reject their own client's proposals, even without this role.
    'reject_roles' => ['account manager'],
];
