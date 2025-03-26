<?php
namespace App\Actions\Backup;

use Carbon\Carbon;
use App\Models\Backup\Backup;

class BackupAction
{
    public function store()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/public/backup/" . $filename;
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
        $backup = Backup::create([
            'file_name'=>$filename,
            'is_manual'=>true,
        ]);
        return $backup;
    }
}