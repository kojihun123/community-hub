<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;

class MyPageController extends Controller
{
    public function index()
    {
        return redirect()->route('mypage.profile.edit');
    }

    public function editProfile()
    {
        return view('mypage.profile.edit', [
            'user' => auth()->user(),
            ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {   
        $user = $request->user();
        $data = $request->validated();  

        $updateData = [
            'name' => $data['name'],
            'bio' => $data['bio'] ?? null,
        ];
        
        if($request->hasFile('avatar')){

            if ($user->avatar !== null && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }        

        $user->update($updateData);

        return redirect()
            ->route('mypage.profile.edit')
            ->with('message', '프로필이 저장되었습니다.');
    }
}
