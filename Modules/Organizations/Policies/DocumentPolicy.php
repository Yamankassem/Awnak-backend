<?php

namespace Modules\Organizations\Policies;

use App\Models\User;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Models\Opportunity;

class DocumentPolicy
{
    

    public function create(User $user, Opportunity $opportunity): bool
    {
        return $user->role === 'admin'
            || $opportunity->organization->user_id === $user->id
            || $user->role === 'opportunity-manager';
    }


    public function update(User $user, Document $document): bool
    {
        return $user->role === 'admin'
            || $document->opportunity->organization->user_id === $user->id
            || $user->role === 'opportunity-manager';
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->role === 'admin'
            || $document->opportunity->organization->user_id === $user->id
            || $user->role === 'opportunity-manager';
    }
}
