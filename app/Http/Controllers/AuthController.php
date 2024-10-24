<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed'
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Admin'
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'password.required' => 'กรุณากรอกรหัสผ่าน'
        ])->validate();

        $user = User::where('username', $request->input('username'))->first();

        // ถ้าพบผู้ใช้, ตรวจสอบรหัสผ่านด้วยวิธีที่กำหนดเอง (แก้ไขการ hashing ที่นี่)
        if ($user && $this->customPasswordCheck($request->input('password'), $user->password)) {
            Auth::login($user, $request->boolean('remember'));

            // สร้าง session ใหม่เพื่อป้องกันการโจมตีแบบ session fixation
            $request->session()->regenerate();

            return redirect('/');
        }

        // ถ้าการตรวจสอบสิทธิ์ล้มเหลว, แสดงข้อผิดพลาด
        throw ValidationException::withMessages([
            'username' => trans('auth.failed')
        ]);
    }

    // ฟังก์ชันตรวจสอบรหัสผ่านแบบกำหนดเอง (แก้ไขด้วยตรรกะของคุณเอง)
    private function customPasswordCheck($inputPassword, $storedPassword)
    {
        // ที่นี่คุณสามารถใช้วิธีการ hashing/comparison แบบกำหนดเอง
        // ตัวอย่างการเปรียบเทียบแบบ plain text (ไม่แนะนำใน production)
        return $inputPassword === $storedPassword;
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
