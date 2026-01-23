<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserServices
{

    public function createToken(User $user): string
    {
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return $token;
    }
    public function registerUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function loginUser(array $data): ?User
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }
        return $user;
    }
     public function logoutUser( $user): void
    {
          $user->tokens()->delete();
    }



    public function getUserById($id){
        return User::find($id);
    }
    
    public function allUser(){
         $users = User::withCount([
                'issues as open_issues_count' => function ($query) {$query->open();},
                'issues as high_priority_issues_count' => function ($query) {$query->urgent();},
                'issues as completed_issues_count' => function ($query) {$query->completed();},
            ])->get();
             return  $users;
    }

    public function storeUser(array $userData){
        $user = User::create($userData);
        return $user;
    }

    public function updateUser(array $userData , User $user){
        $user->update($userData);
         return $user;
    }

    public function destroyUser(User $user){
       $user->delete();
       return true;
    }

   
}
