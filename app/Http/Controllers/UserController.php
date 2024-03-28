<?php
    namespace App\Http\Controllers;

    use App\Models\Movement;
    use App\Models\User;
    use Illuminate\Http\Request;

    class UserController extends Controller
    {
        public function showUserMovements(User $user)
        {
            $movements = Movement::where('user_id', $user->id)->with('material')->get();

            return view('user-movements', [
                'user' => $user,
                'movements' => $movements
            ]);
        }

        public function myAccount(User $user)
        {
            $movements = Movement::where('user_id', $user->id)->with('material')->get();

            return view('account', [
                'user' => $user,
                'movements' => $movements
            ]);
        }

        public function updatePassword(User $user)
        {
            return view('confirm-password');
        }
    }
