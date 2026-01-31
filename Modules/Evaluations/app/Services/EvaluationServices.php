<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;


class EvaluationServices
{
    /**
     * create new evaluation
     */
    public function createEvaluation(array $data): Evaluation
    {
      $user = Auth::user();
        $evaluation= Evaluation::create($data);
         Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'evaluations.created',
                            'subject_type' => Evaluation::class,
                            'subject_id'   => $evaluation->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'evaluation_id' => $evaluation->id,
                                                'evaluation_score' => $evaluation->score,
                                                'created_by' => $user->name,
                                              ],
                         ]);
        return $evaluation;
    }
     
    /**
     *  update evaluation
     */
    public function updateEvaluation(Evaluation $evaluation, array $data): Evaluation
    {
        $user = Auth::user();
        $evaluation->update($data);
        Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'evaluations.updated',
                            'subject_type' => Evaluation::class,
                            'subject_id'   => $evaluation->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'evaluation_id' => $evaluation->id,
                                                'evaluation_score' => $evaluation->score,
                                                'updated_by' => $user->name,
                                              ],
                         ]);
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
            $user = Auth::user();
            $evaluationId   = $evaluation->id;
            $evaluationScore = $evaluation->score;
            $evaluation->delete();  
          Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'evaluations.deleted',
                            'subject_type' => Evaluation::class,
                            'subject_id'   => $evaluationId,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'evaluation_id' => $evaluationId,
                                                'evaluation_score' => $evaluationScore,
                                                'deleted_by' => $user->name,
                                              ],
        ]);
    }
}
