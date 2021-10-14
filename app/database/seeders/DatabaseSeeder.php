<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->id = $i;
            $user->name = $faker->name();
            $user->email = "test_{$i}@example.com";
            $user->email_verified_at = now();
            $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
            $user->remember_token = Str::random(10);

            $user->save();
        }

        DB::table('comments')->truncate();

        $data = $this->data();

        $this->iterate($data);
    }

    private function iterate(array $data)
    {
        foreach ($data as $item) {
            DB::table('comments')->insert($item['item']);

            if ($item['children']) {
                $this->iterate($item['children']);
            }
        }
    }

    private function item(int $id, int $parentId, int $depth, int $order, string $path): array
    {
        $faker = Factory::create();

        return [
            'id' => $id,
            'user_id' => mt_rand(1, 3),
            'body' => $faker->text(),
            'is_deleted' => 0,
            'parent_id' => $parentId,
            'depth' => $depth,
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ];
    }

    private function data()
    {
        return [
            [
                'item' => $this->item(1, 0, 0, 1, '1'),
                'children' => [
                    [
                        'item' => $this->item(2, 1, 1, 1, '1:1'),
                        'children' => [],
                    ],
                    [
                        'item' => $this->item(4, 1, 1, 1, '1:1'),
                        'children' => [
                            [
                                'item' => $this->item(5, 4, 2, 1, '1:1:1'),
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'item' => $this->item(7, 1, 1, 2, '1:2'),
                        'children' => [],
                    ],
                ],
            ],
            [
                'item' => $this->item(3, 0, 0, 1, '2'),
                'children' => [],
            ],
            [
                'item' => $this->item(6, 0, 0, 1, '3'),
                'children' => [],
            ],
        ];
    }
}
