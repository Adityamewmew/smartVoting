<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidateUsecase extends Usecase
{
    public function __construct()
    {
        $this->className = CandidateUsecase::class;
    }

    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::CANDIDATES().' as c')
                ->leftJoin(DatabaseConst::ELECTIONS().' as e', 'c.election_id', '=', 'e.id')
                ->select('c.*', 'e.name as election_name')
                ->whereNull('c.deleted_at')
                ->when(! empty($filterData['election_id']), function ($query) use ($filterData) {
                    return $query->where('c.election_id', $filterData['election_id']);
                })
                ->when(! empty($filterData['keywords']), function ($query) use ($filterData) {
                    return $query->where(function ($q) use ($filterData) {
                        $q->where('c.chairman_name', 'like', '%'.$filterData['keywords'].'%')
                            ->orWhere('c.vice_chairman_name', 'like', '%'.$filterData['keywords'].'%');
                    });
                })
                ->orderBy('c.order_number', 'asc');

            if (! empty($filterData['no_pagination'])) {
                $data = $query->get();
            } else {
                $data = $query->paginate(20);
                if (! empty($filterData)) {
                    $data->appends($filterData);
                }
            }

            return Response::buildSuccess(['list' => $data], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByID(int $id): array
    {
        try {
            $data = DB::table(DatabaseConst::CANDIDATES())
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->first();

            if (! $data) {
                return Response::buildErrorNotFound();
            }

            return Response::buildSuccess(data: collect($data)->toArray());
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'election_id' => 'required|integer',
            'order_number' => 'required|integer|min:1',
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vice_chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        // Custom validation for photo dimensions & orientation
        if ($data->hasFile('photo')) {
            $photoError = $this->validatePhotoDimensions($data->file('photo'), 'Foto Calon Ketua');
            if ($photoError) {
                return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $photoError);
            }
        }

        if ($data->hasFile('vice_chairman_photo')) {
            $photoError = $this->validatePhotoDimensions($data->file('vice_chairman_photo'), 'Foto Calon Wakil Ketua');
            if ($photoError) {
                return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $photoError);
            }
        }

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($data->hasFile('photo')) {
                $photoPath = $this->processAndStorePhoto($data->file('photo'));
            }

            $viceChairmanPhotoPath = null;
            if ($data->hasFile('vice_chairman_photo')) {
                $viceChairmanPhotoPath = $this->processAndStorePhoto($data->file('vice_chairman_photo'));
            }

            DB::table(DatabaseConst::CANDIDATES())->insert([
                'election_id' => $data->input('election_id'),
                'order_number' => $data->input('order_number'),
                'chairman_name' => $data->input('chairman_name'),
                'vice_chairman_name' => $data->input('vice_chairman_name'),
                'vision' => $data->input('vision'),
                'mission' => $data->input('mission'),
                'photo_path' => $photoPath,
                'vice_chairman_photo_path' => $viceChairmanPhotoPath,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated();
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'election_id' => 'required|integer',
            'order_number' => 'required|integer|min:1',
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vice_chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        // Custom validation for photo dimensions & orientation
        if ($data->hasFile('photo')) {
            $photoError = $this->validatePhotoDimensions($data->file('photo'), 'Foto Calon Ketua');
            if ($photoError) {
                return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $photoError);
            }
        }

        if ($data->hasFile('vice_chairman_photo')) {
            $photoError = $this->validatePhotoDimensions($data->file('vice_chairman_photo'), 'Foto Calon Wakil Ketua');
            if ($photoError) {
                return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $photoError);
            }
        }

        DB::beginTransaction();
        try {
            $candidate = DB::table(DatabaseConst::CANDIDATES())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (! $candidate) {
                DB::rollback();

                return Response::buildErrorNotFound();
            }

            $payload = [
                'election_id' => $data->input('election_id'),
                'order_number' => $data->input('order_number'),
                'chairman_name' => $data->input('chairman_name'),
                'vice_chairman_name' => $data->input('vice_chairman_name'),
                'vision' => $data->input('vision'),
                'mission' => $data->input('mission'),
                'updated_by' => Auth::user()?->id,
                'updated_at' => now(),
            ];

            if ($data->hasFile('photo')) {
                if ($candidate->photo_path && Storage::disk('public')->exists($candidate->photo_path)) {
                    Storage::disk('public')->delete($candidate->photo_path);
                }

                $payload['photo_path'] = $this->processAndStorePhoto($data->file('photo'));
            }

            if ($data->hasFile('vice_chairman_photo')) {
                if (! empty($candidate->vice_chairman_photo_path) && Storage::disk('public')->exists($candidate->vice_chairman_photo_path)) {
                    Storage::disk('public')->delete($candidate->vice_chairman_photo_path);
                }

                $payload['vice_chairman_photo_path'] = $this->processAndStorePhoto($data->file('vice_chairman_photo'));
            }

            DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->update($payload);
            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_UPDATED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
            $candidate = DB::table(DatabaseConst::CANDIDATES())
                ->where('id', $id)
                ->first();

            $delete = DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->update([
                'deleted_by' => Auth::user()?->id,
                'deleted_at' => now(),
            ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('FAILED DELETE DATA');
            }

            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_DELETED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Validate that photo is not landscape and does not exceed 700px.
     */
    private function validatePhotoDimensions($file, string $fieldLabel = 'Foto'): ?string
    {
        $imageInfo = @getimagesize($file->getRealPath());
        if (! $imageInfo) {
            return "File {$fieldLabel} tidak valid sebagai gambar.";
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Must not be landscape (width cannot be greater than height)
        if ($width > $height) {
            return "{$fieldLabel} tidak boleh berformat landscape (harus berorientasi portrait atau persegi).";
        }

        // Must not exceed 700px in either dimension
        if ($width > 700 || $height > 700) {
            return "Ukuran dimensi {$fieldLabel} tidak boleh lebih dari 700px (terdeteksi {$width}x{$height} px).";
        }

        return null;
    }

    /**
     * Resize and convert photo to standard 354 x 472 px (ratio 3:4) via PHP GD.
     */
    private function processAndStorePhoto($file): string
    {
        $imageData = file_get_contents($file->getRealPath());
        $srcImage = @imagecreatefromstring($imageData);

        if (! $srcImage) {
            // Fallback store directly if GD cannot parse
            return $file->store('candidates', 'public');
        }

        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);

        $targetWidth = 354;
        $targetHeight = 472;
        $targetRatio = $targetWidth / $targetHeight;
        $srcRatio = $srcWidth / $srcHeight;

        if ($srcRatio > $targetRatio) {
            $cropHeight = $srcHeight;
            $cropWidth = (int) round($srcHeight * $targetRatio);
            $cropX = (int) round(($srcWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) round($srcWidth / $targetRatio);
            $cropX = 0;
            $cropY = (int) round(($srcHeight - $cropHeight) / 2);
        }

        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $dstImage,
            $srcImage,
            0,
            0,
            $cropX,
            $cropY,
            $targetWidth,
            $targetHeight,
            $cropWidth,
            $cropHeight
        );

        Storage::disk('public')->makeDirectory('candidates');

        if (function_exists('imagewebp')) {
            $filename = 'candidates/'.uniqid('cand_', true).'.webp';
            $fullPath = Storage::disk('public')->path($filename);
            imagewebp($dstImage, $fullPath, 90);
        } else {
            $filename = 'candidates/'.uniqid('cand_', true).'.jpg';
            $fullPath = Storage::disk('public')->path($filename);
            imagejpeg($dstImage, $fullPath, 90);
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $filename;
    }
}
