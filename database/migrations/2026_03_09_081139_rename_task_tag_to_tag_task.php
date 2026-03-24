<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('task_tag', 'tag_task');
    }

    public function down()
    {
        Schema::rename('tag_task', 'task_tag');
    }
};
