<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\Interest;
use Modules\Volunteers\Services\InterestService;
use Modules\Volunteers\Transformers\InterestsResource;
use Modules\Volunteers\Http\Requests\Interests\StoreInterestRequest;
use Modules\Volunteers\Http\Requests\Interests\UpdateInterestRequest;

class InterestController extends Controller
{
    public function __construct(
        private InterestService $service
    ) {}

    public function index()
    {
        $paginator = $this->service->paginate();

        $paginator->setCollection(
            InterestsResource::collection($paginator->getCollection())->collection
        );

        return static::paginated(
            paginator: $paginator,
            message: 'interests.listed'
        );
    }

    public function store(StoreInterestRequest $request)
    {
        $interest = $this->service->create($request->validated());

        return static::success(
            data: new InterestsResource($interest),
            message: 'interests.created',
            status: 201
        );
    }

    public function update(UpdateInterestRequest $request, Interest $interest)
    {
        $interest = $this->service->update($interest, $request->validated());

        return static::success(
            data: new InterestsResource($interest),
            message: 'interests.updated'
        );
    }

    public function destroy(Interest $interest)
    {
        $this->service->delete($interest);

        return static::success(
            message: 'interests.deleted'
        );
    }
    
}
