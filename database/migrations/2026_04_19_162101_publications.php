<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('publications', function (Blueprint $collection) {
            $collection->index('auteur_id');
        });
    }

    public function down()
    {
        Schema::drop('publications');
    }
};