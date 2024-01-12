<?php

namespace App\Http\Controllers\Refarral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Http\Requests\Referral\ReferralRequest;

class ReferralController extends Controller
{
    //
    public function index()
    {
        // Get all referrals
        $referrals = Referral::all();
        return response()->json(['referrals' => $referrals, 'message'=>'view all referral']);
    }
    public function show($id)
    {
        // Get a specific referral by ID
        $referral = Referral::find($id);
        return response()->json(['referral' => $referral]);
    }
    public function store(ReferralRequest  $request)
    {
        // validations  referral
        $validatedData = $request->validated();

        // Create a new referral
        $referral = Referral::create($validatedData);

        return response()->json(['referral' => $referral], 201);
    }
    public function update(ReferralRequest $request, $id)
    {
        // Validation check
        $validatedData = $request->validated();

        // Update a specific referral by ID
        $referral = Referral::findOrFail($id);
        $referral->update($validatedData);

        return response()->json(['referral' => $referral]);
    }
    public function destroy($id)
    {
        // Delete a specific referral by ID
        $referral = Referral::findOrFail($id);
        $referral->delete();
        return response()->json(['message' => 'Referral deleted successfully']);
    }

}
