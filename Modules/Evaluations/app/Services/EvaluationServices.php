<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class EvaluationServices
{
    /**
     * create new evaluation
     */
    public function createEvaluation(array $data): Evaluation
    {
            if (!Auth::check()) {
                    throw new \Exception('Unauthenticated', 401);
            }
        //(only organization or Volunteer Coordinator create evaluation)
        //   if (!Auth::user()->is_admin) {
        //         throw new \Exception('Only admin can create categories', 403);
        //    }
            return Evaluation::create($data);
    }
    /**
     *  update evaluation
     */
    public function updateEvaluation(Evaluation $evaluation, array $data): Evaluation
    {
            if (!Auth::check()) {
                throw new \Exception('Unauthenticated', 401);
            }
            //(only organization or Volunteer Coordinator update evaluation)
            // if (!Auth::user()->is_admin) {
            //     throw new \Exception('Only admin can update categories', 403);
            // }
            $evaluation->update($data);
            return $evaluation;
    }
    /**
     *  get evaluation by id
     */
    public function getEvaluationById($id): Evaluation
    {
            return Evaluation::findOrFail($id);
    }
    /**
     * get all evaluations
     */
    public function getAllEvaluations(int $perPage = 4)
    {
        return Evaluation::query()->paginate($perPage);
    }
    /**
     * delete evaluation 
     */ 
    public function deleteEvaluation(Evaluation $evaluation)
    {
            if (!Auth::check()) {
                throw new \Exception('Unauthenticated', 401);
            }
             //(only organization or Volunteer Coordinator delete evaluation)
            // if (!Auth::user()->is_admin) {
            //     throw new \Exception('Only admin can delete categorie', 403);
            // }
            if (!$evaluation) {
                throw new \Exception('Evaluation not found', 404);
            }
            $evaluation->delete();
                return true;
    }
}
