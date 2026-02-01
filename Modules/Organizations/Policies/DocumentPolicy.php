<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Models\Opportunity;

class DocumentPolicy
{
    public function viewAny(User $user)
    { // أي مستخدم مسجل دخول بيقدر يشوف
        return $user->exists;
    }


    public function view(User $user, Document $document)
    { // الكل بيقدر يشوف وثيقة محددة
        return true;
    }

    public function create(User $user)
    { // بس أدوار معينة بيقدروا ينشئوا
        return $user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin']);
    }

    public function update(User $user, Document $document)
    { // نفس المنطق: أدوار معينة أو صاحب المنظمة
        return $user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin']) || $document->opportunity->organization->user_id === $user->id;
    }

    public function delete(User $user, Document $document)
    {
        // نفس منطق التعديل
        return $user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin']) || $document->opportunity->organization->user_id === $user->id;
    }
}
