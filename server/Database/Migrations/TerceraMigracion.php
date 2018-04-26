<?php
namespace Database\Migrations;

use Database\Schema;

class TerceraMigracion extends Migrations
{
    public function up()
    {
        Schema::create('users', function(Schema $table){
            $table->bigInt('id');
            $table->string('socket', 16, [
                'comment' => 'conexión websocket'
            ]);
            $table->string('origin', 64, [
                'comment' => 'ultimo ip conocido',
            ]);
            $table->string('name', 32);
            $table->string('email', 64);
            $table->int('status', 1, [
              'comment' => '0:desconectado 1:conectado 2:ocupado',
            ]);

            $table->primaryKey('id');
            $table->unique('email');
        });

        Schema::create('messages', function(Schema $table){
            $table->increment('id');
            $table->int('from', 11, [
                'attribute' => 'unsigned',
                'comment' => 'user_id'
            ]);
            $table->int('to', 11, [
                'attribute' => 'unsigned',
                'comment' => 'user_id'
            ]);
            $table->string('data');
            $table->int('resource_type', 11, ['comment' => 'resource_id']);
            $table->string('resource', 256, ['default' => 'NULL']);
            $table->int('status', 2);
            $table->bool('tracked', ['default' => 'true']);
            $table->timestamps();

            $table->primaryKey('id');
        });

        Schema::create('resource_types', function(Schema $table){
            $table->increment('id');
            $table->string('name', 64);
            $table->int('opt', 2);

            $table->primaryKey('id');
        });

        Schema::create('status_types', function(Schema $table){
            $table->increment('id');
            $table->string('name', 64);
            $table->int('opt', 2);

            $table->primaryKey('id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('users');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('resource_types');
        Schema::dropIfExists('status_types');
    }
}
