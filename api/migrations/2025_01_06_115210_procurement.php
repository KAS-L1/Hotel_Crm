<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->unsignedBigInteger('product_id')->unique()->startingValue(22); // 6-digit incremental ID starting at 22
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->enum('status', ['Available', 'Unavailable'])->default('Available');
            $table->double('unit_price', 15, 2);
            $table->integer('stock')->unsigned();
            $table->timestamps();
        });

        // Purchase Requisitions Table
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->unsignedBigInteger('requisition_id')->unique()->startingValue(33); // 6-digit incremental ID starting at 22
            $table->uuid('vendor_id');
            $table->uuid('created_by')->nullable();
            $table->double('total_quantity', 15, 2);
            $table->double('total_cost', 15, 2);
            $table->double('total_price', 15, 2);
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->dateTime('request_date');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });


        // Purchase Items Table
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->unsignedBigInteger('purchase_id')->unique()->startingValue(44); // 6-digit incremental ID starting at 22
            $table->uuid('requisition_id');
            $table->uuid('product_id');
            $table->integer('quantity')->unsigned();
            $table->double('cost', 15, 2);
            $table->double(
                'price',
                15,
                2
            );
            $table->timestamps();

            $table->foreign('requisition_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Budget Approvals Table
        Schema::create('budget_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->unsignedBigInteger('approval_id')->unique()->startingValue(55); // 6-digit incremental ID starting at 22
            $table->uuid('requisition_id');
            $table->double('amount', 15, 2);
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('approved_by', 100)->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('requisition_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
        });

        // Request Quotations Table
        Schema::create('request_quotations', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->unsignedBigInteger('rfq_id')->unique()->startingValue(66); // 6-digit incremental ID starting at 22
            $table->uuid('product_id');
            $table->uuid('vendor_id');
            $table->integer('requested_qty')->unsigned();
            $table->enum('status', ['Pending', 'Responded', 'Rejected'])->default('Pending');
            $table->dateTime('response_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_quotations');
        Schema::dropIfExists('budget_approvals');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchase_requisitions');
        Schema::dropIfExists('products');
    }
};
