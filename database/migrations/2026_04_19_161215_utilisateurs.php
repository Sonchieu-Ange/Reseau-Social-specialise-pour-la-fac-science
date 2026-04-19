<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $collection) {
            $collection->index('email', null, null, ['unique' => true]);
            $collection->index('role');
        });
    }

    public function down()
    {
        Schema::drop('utilisateurs');
    }
};