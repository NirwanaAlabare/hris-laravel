<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeAtribut;

class updateStatusAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statusAktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        EmployeeAtribut::where('tanggal_resign',date('Y-m-d'))->update([
            'status_aktif'=>'TIDAK AKTIF'
        ]);
    }
}
