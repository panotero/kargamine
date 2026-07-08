    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('client_contacts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('client_masters')->cascadeOnDelete();
                $table->string('contact_name')->nullable();
                $table->string('contact_number')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('role')->nullable();     // LOV
                $table->string('position')->nullable(); // LOV
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('client_contacts');
        }
    };
