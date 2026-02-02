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
    /**
     * @param InterestService $service Interest business logic service
     */
    public function __construct(private InterestService $service) {}

    /**
     * List interests with pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Store a new interest.
     *
     * @param StoreInterestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreInterestRequest $request)
    {
        $interest = $this->service->create($request->validated());

        return static::success(
            data: new InterestsResource($interest),
            message: 'interests.created',
            status: 201
        );
    }

    /**
     * Update an existing interest.
     *
     * @param UpdateInterestRequest $request
     * @param Interest $interest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInterestRequest $request, Interest $interest)
    {
        $interest = $this->service->update($interest, $request->validated());

        return static::success(
            data: new InterestsResource($interest),
            message: 'interests.updated'
        );
    }

    /**
     * Delete an interest.
     *
     * @param Interest $interest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Interest $interest)
    {
        $this->service->delete($interest);

        return static::success(
            message: 'interests.deleted'
        );
    }
    
}
