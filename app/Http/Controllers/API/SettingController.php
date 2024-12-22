<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return [
            'privacy-policy' => '',
            'terms-of-use' => '' ,
            'faq' => '',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkVersion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'platform' => 'required|in:android,ios',
            'version'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $currentVersion = AppVersion::where('platform', $request->platform)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentVersion) {
            return response(['message' => 'platform not exists'], 422);

        }

        $isOutdated = version_compare($request->version, $currentVersion->version, '<');
        $forceUpdate = $currentVersion->force_update;

        $result = [
            'is_outdated' => $isOutdated,
            'force_update' => $forceUpdate,
            'latest_version' => $currentVersion->version,
        ];

        // return response()->json([
        //     'is_outdated' => $isOutdated,
        //     'force_update' => $forceUpdate,
        //     'latest_version' => $currentVersion->version,
        // ]);

        return response($result, 200);

    }
}
