<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by App Settings sync methods (Container::syncCatalog(),
 * Location::syncPorts()) when a row about to be dropped is still
 * referenced through a foreign key that's SET NULL rather than
 * RESTRICT - meaning the database wouldn't naturally block the delete,
 * but doing it anyway would silently orphan real operational data (a
 * container asset's current location, a CRM lead's port, a booking's
 * relay port). Caught by the owning controller and turned into the
 * same friendly 422 response as a raw FK violation.
 */
class ProtectedRecordException extends RuntimeException
{
}
