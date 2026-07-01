<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Concrete child table for the Popup DocumentModel. Document meta (title,
     * content, approval, counters) lives on the central documents /
     * document_contents tables; only the scheduling and presentation columns
     * plus a local read_count mirror are kept here.
     */
    public function up(): void
    {
        Schema::create('popups', function (Blueprint $table): void {
            $table->id();
            $table->integer('ignore_days')->nullable()->comment('days to hide after "do not show again"');
            $table->boolean('show_title')->default(true);
            $table->json('styles')->nullable();
            $table->unsignedInteger('read_count')->default(0);
            $table->timestamp('started_at')->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
