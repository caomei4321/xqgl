<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name'        => '自然资源',
                'description' => '保护自然资源',
            ],
            [
                'name'        => '生态环境',
                'description' => '保护生态环境',
            ],
            [
                'name'        => '城乡建设',
                'description' => '城乡建设发展',
            ],
            [
                'name'        => '应急管理',
                'description' => '应急管理处理',
            ],
            [
                'name'        => '市场监管',
                'description' => '发展良好市场',
            ],
            [
                'name'        => '综合执法',
                'description' => '综合执法保障',
            ],
            [
                'name'        => '重点工作',
                'description' => '重点工作指导',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
