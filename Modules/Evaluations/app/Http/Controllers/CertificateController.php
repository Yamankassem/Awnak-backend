<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Evaluations\Http\Requests\Certificate\StoreCertificateRequest;
use Modules\Evaluations\Http\Requests\Certificate\UpdateCertificateRequest;
use Modules\Evaluations\Http\Resources\CertificateResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Modules\Evaluations\Models\Certificate;
use Modules\Evaluations\Services\CertificateServices;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
     use AuthorizesRequests;

    protected $certificateService;

    public function __construct(CertificateServices $certificateService)
    {
        $this->certificateService = $certificateService;
    }
// only system-admin & performance-auditor can getAllCertificates 
// and the volunteer only can show his certificates

    public function index()
    {
            
            $this->authorize('viewAny', Certificate::class);

            $certificates = $this->certificateService->getAll();

            return static::paginated(
                paginator: $certificates,
                message: 'certificates.listed'
            );
       
    }  

    
    public function show($id)
    {
        try {
             $certificate = $this->certificateService->getById($id);
                $this->authorize('view', $certificate);
                  return static::success(
                        data: $certificate,
                        message: 'certificates.retrieved',
                        status: 200
        );
        } catch (\Exception $e) {
            return $this->error('Certificate not found', 404);
        }
    }
// only system-admin & performance-auditor can store certificate
    public function store(StoreCertificateRequest $request)
    {
        try {
             $this->authorize('create', Certificate::class);
             $certificate = $this->certificateService->createCertificate($request->validated());
              return static::success(
                                        data:  $certificate,
                                        message: 'certificates.created',
                                        status: 201
                                    );
        }
         catch (\Exception $e) {
             return $this->error($e->getMessage(), $e->getCode() ?: 500);
}

        
    }
// only system-admin & performance-auditor can update certificate

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        try {
                $data = $request->validated();
                $this->authorize('update', $certificate);
                $updated = $this->certificateService->updateCertificate($certificate, $data);
                return static::success(
                data: $updated,
                message: 'certificates.updated',
                status: 200
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
// only system-admin & performance-auditor can destroy certificate

    public function destroy(Certificate $certificate)
    {
        try {
            $this->authorize('delete', $certificate);
            $this->certificateService->deleteCertificate($certificate);
            return $this->success(
                data: null,
                message: 'certificates.deleted',
                status: 200
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
