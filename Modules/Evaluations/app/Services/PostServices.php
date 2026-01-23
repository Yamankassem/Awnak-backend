<?php

namespace App\Services;

use App\Jobs\SendCreatePostNotification;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;



class PostServices
{
        public function allPost(){
            $posts=Post::with(['user', 'category'])
                ->paginate(10);
                return $posts;
        }
        public function getPostById($id){
            $post = Post::with(['user', 'category'])->findOrFail($id);
            return $post;
        }
        public function storePost(array $postData){
            if (!Auth::check()) {
                 throw new \Exception('Unauthenticated', 401);
             }
            $postData['user_id'] = Auth::id();
            $post = Post::create($postData);
            SendCreatePostNotification::dispatch($post);
            return $post;
        }
        public function updatePost(array $postData,Post $post ):post{
             if (!Auth::check()) {
                 throw new \Exception('Unauthenticated', 401);
             }
              if (Auth::id() !== $post->user_id) {
                 throw new \Exception('You are not authorized to delete this post', 403);
             }
             if (!$post) {
                 throw new \Exception('Post not found', 404);
             }
            $post->update($postData);
            return $post;
        }
        public function destroyPost(Post $post): array
        {
                    if (!Auth::check()) {
                        return [
                            'status' => false,
                            'code'   => 401,
                            'message'=> 'Unauthenticated'
                        ];
                    }
                    if (Auth::user()->id !== $post->user_id || !Auth::user()->is_admin) {
                        return [
                            'status' => false,
                            'code'   => 403,
                            'message'=> 'You are not authorized to delete this post'
                        ];
                    }
                    if (!$post) {
                        return [
                            'status' => false,
                            'code'   => 404,
                            'message'=> 'Post not found'
                        ];
                    }
                    $post->delete();
                    return [
                        'status' => true,
                        'code'   => 200,
                        'message'=> 'Post deleted successfully'
                    ];
        }
}



     

   

