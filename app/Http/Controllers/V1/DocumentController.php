<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DocumentController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $documents = Document::where('user_id', Auth::id())
                ->latest()
                ->paginate(15);

            return $this->successResponse(
                'Documents retrieved successfully.',
                DocumentResource::collection($documents)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        try {
            $document = Document::create([
                'user_id' => Auth::id(),
                ...$request->validated(),
            ]);

            return $this->successResponse('Document created successfully.', new DocumentResource($document), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(UpdateDocumentRequest $request, int $id): JsonResponse
    {
        try {
            $document = Document::where('user_id', Auth::id())->findOrFail($id);
            $document->update($request->validated());

            return $this->successResponse('Document updated successfully.', new DocumentResource($document));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Document not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $document = Document::where('user_id', Auth::id())->findOrFail($id);
            $document->delete();

            return $this->successResponse('Document deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Document not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function expiring(): JsonResponse
    {
        try {
            $documents = Document::where('user_id', Auth::id())
                ->expiringSoon()
                ->latest('expiry_date')
                ->paginate(15);

            return $this->successResponse(
                'Expiring documents retrieved successfully.',
                DocumentResource::collection($documents)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
