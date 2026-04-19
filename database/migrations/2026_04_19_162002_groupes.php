<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('groupes', function (Blueprint $collection) {
            $collection->index('communaute_id');
            $collection->index('createur_id');
        });
    }

    public function down()
    {
        Schema::drop('groupes');
    }
};