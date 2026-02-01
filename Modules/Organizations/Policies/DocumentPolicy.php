<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User;
use Modules\Organizations\Models\Document;

/**
 * Policy: DocumentPolicy
 *
 * Defines authorization rules for Document actions.
 * - All users can view documents.
 * - Only system-admin, opportunity-manager, or the organization owner
 *   can create, update, or delete documents.
 * - Uses Spatie permissions for fine-grained control.
 */
class DocumentPolicy
{
    /**
     * Determine whether the user can view any documents.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Any Authintication User
        return $user !== null;
    }

    /**
     * Determine whether the user can view a specific document.
     *
     * @param User $user
     * @param Document $document
     * @return bool
     */
    public function view(User $user, Document $document): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a document.
     *
     * @param User $user
     * @param Document $document
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.create')
            || $user->can('opportunities.create');
    }


    /**
     * Determine whether the user can update a document.
     *
     * @param User $user
     * @param Document $document
     * @return bool
     */
    public function update(User $user, Document $document): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.update')
            || $user->can('opportunities.update.own')
            || $document->opportunity->organization->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete a document.
     *
     * @param User $user
     * @param Document $document
     * @return bool
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.delete')
            || $user->can('opportunities.delete.own')
            || $document->opportunity->organization->user_id === $user->id;
    }
}
