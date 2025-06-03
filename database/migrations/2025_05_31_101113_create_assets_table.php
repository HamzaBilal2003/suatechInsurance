<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('type');
            $table->string('description');
            $table->string('make');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->string('supplier_name')->nullable();
            $table->date('purchase_date');
            $table->string('attached_files')->nullable(); // Store file paths as JSON
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
}