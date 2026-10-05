<?php

return [
    // Approval/disapproval is no longer role-config driven - see
    // ClientProposal::canBeApprovedBy(). It's now a two-step, permission-gated
    // chain: the deal's assigned Relationship Manager approves first
    // (canBeApprovedByRm(), permission "proposal.approve.rm"), then whoever
    // holds "proposal.approve.manager" (canBeApprovedByManager()) makes the
    // final call - both toggleable per role from /page_roles_permissions.
    // superadmin always can, at either stage.

    // Role names allowed to reject a proposal. In addition to this list,
    // the client's assigned Sales Representative (client_masters.sales_rep_id)
    // can always reject their own client's proposals, even without this role.
    'reject_roles' => ['account manager'],
];
