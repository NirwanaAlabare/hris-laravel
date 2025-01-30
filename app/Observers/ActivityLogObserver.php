<?php
namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ActivityLogObserver
{
    /**
     * Handle any "created" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function created(Model $model)
    {
        $this->logActivity($model, 'create', null, $model->getAttributes());
    }

    /**
     * Handle any "updated" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updated(Model $model)
    {
        // Get old data and new data
        $oldData = $model->getOriginal();
        $newData = $model->getAttributes();

        $this->logActivity($model, 'update', $oldData, $newData);
    }

    /**
     * Handle any "deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $this->logActivity($model, 'delete', $model->getAttributes(), null);
    }

    /**
     * Handle any "restored" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function restored(Model $model)
    {
        $this->logActivity($model, 'restore', null, $model->getAttributes());
    }

    /**
     * Handle any "force deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        $this->logActivity($model, 'force delete', $model->getAttributes(), null);
    }

    /**
     * Method to log activity
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $action
     * @param  array|null  $oldData
     * @param  array|null  $newData
     * @return void
     */
    protected function logActivity(Model $model, $action, $oldData = null, $newData = null)
    {
        $loggedAdmin = session('loggedAdmin');
      
        if($loggedAdmin){
            ActivityLog::create([
                'action_by_id' => $loggedAdmin->id,
                'action_by_name' => $loggedAdmin->name,
                'log_name' => $action . ' ' . $model->getTable(),
                'table_name' =>  $model->getTable(),
                'record_id' => $model->getKey(),
                'action' => $action,
                'old_data' => $oldData ? json_encode($oldData) : null,
                'new_data' => $newData ? json_encode($newData) : null,
            ]);
        }
    }
}
