<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceFile;
use Illuminate\Console\Command;

class RollbackResourceFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rollback-resource-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Resource::whereNotNull('resource_file_id')->update(['resource_file_id' => null]);

        ResourceFile::where('id', '>=', 1)->delete();
    }
}
