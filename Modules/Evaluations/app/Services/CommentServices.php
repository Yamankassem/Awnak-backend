<?php

namespace App\Services;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;



class CommentServices
{
    /**
     * add comment
     * only auth can add comment 
    */
    public function addComment(array $commentData): Comment
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Unauthenticated', 401);
        }

        $commentData['user_id'] = $user->id;
        $commentData['status'] = 'pending'; 
        return Comment::create($commentData);
    }
     /**
      * only comment's owner can update comment and comment's status will be pending
     */
    public function updateComment(array $commentData, Comment $comment): Comment
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Unauthenticated', 401);
        }

        if ($user->id !== $comment->user_id) {
            throw new \Exception('Unauthorized: only owner can update comment', 403);
        }
        $comment->update(array_merge($commentData, ['status' => 'pending']));
        return $comment;
    }
    /**
     * delete comment 
     *  only comment's owner can delete comment 
     */
    public function deleteComment(Comment $comment)
    {
        $user = Auth::user();
        if (!$user) {
                throw new \Exception('Unauthenticated', 401);
        }
        if ($user->id !== $comment->user_id) {
                throw new \Exception('Unauthorized to delete this comment', 403);
        }
        $comment->delete();
        return true;
    }
    /**
     * only admin can see all comments
     * auth users can see only approved comment
     */
    public function getComments($postId)
    {
        $comment = Comment::where('post_id', $postId);
        if (!Auth::user()?->is_admin) {
            $comment->where('status', 'approved');
        }
        return $comment->with('user')->orderBy('created_at', 'desc')->get();
    }
    /**
     * if comment is pending , only admin or owner can see it 
     * if comment is approved , all can see it
     */
   public function getCommentById($id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();
        if ($comment->status === 'approved') {
            return $comment;
        }else{
            if (!$user) {
                throw new \Exception('Unauthorized: Only owner or admin can view pending comments', 403);
            }
            if ($user->id === $comment->user_id || $user->is_admin) {
                    return $comment;
            }
        }
            throw new \Exception('Unauthorized: You cannot view this pending comment', 403);

    }

    /**
     * only admin approve on comment
     */
    public function approveComment(Comment $comment): Comment
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            throw new \Exception('Unauthorized: only admin can approve', 403);
        }
        $comment->update(['status' => 'approved']);
        return $comment;
    }
}
