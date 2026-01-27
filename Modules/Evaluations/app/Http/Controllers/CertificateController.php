<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Requests\Certificate\StoreCertificateRequest;
use Modules\Evaluations\Http\Requests\Certificate\UpdateCertificateRequest;
use Modules\Evaluations\Http\Resources\CertificateResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Modules\Evaluations\Models\Certificate;
use Modules\Evaluations\Services\CertificateServices;

class CertificateController extends Controller
{
    use ApiResponse;

    protected $certificateService;

    public function __construct(CertificateServices $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index($taskId)
    {
        try {
            $certificates = $this->certificateService->getByTask($taskId);

            return $this->successResponse(
                CertificateResource::collection($certificates),
                'Certificates retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function show($id)
    {
        try {
            $certificate = $this->certificateService->getById($id);

            return $this->successResponse(
                new CertificateResource($certificate),
                'Certificate retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Certificate not found', 404);
        }
    }

    public function store(StoreCertificateRequest $request)
    {
        try {
            $certificate = $this->certificateService->createCertificate($request->validated());

            return $this->successResponse(
                new CertificateResource($certificate),
                'Certificate created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        try {
            $updated = $this->certificateService->updateCertificate($certificate, $request->validated());

            return $this->successResponse(
                new CertificateResource($updated),
                'Certificate updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(Certificate $certificate)
    {
        try {
            $this->certificateService->deleteCertificate($certificate);

            return $this->successResponse(
                null,
                'Certificate deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
