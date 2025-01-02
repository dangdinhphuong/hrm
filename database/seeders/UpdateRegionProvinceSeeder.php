<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Address\Province;

class UpdateRegionProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $dataIds =[
            1 => [24, 6, 27, 4, 11, 2, 35, 1, 42, 30, 31, 17, 33, 12, 20, 10, 36, 40, 37, 25, 22, 14, 34, 19, 38, 8, 26, 15],
            2 => [48, 44, 49, 51, 45, 46],
            3 => [89, 77, 95, 83, 74, 52, 70, 60, 96, 92, 66, 67, 75, 87, 64, 93, 79, 56, 91, 62, 68, 80, 58, 54, 94, 72, 82, 84, 86]
         ];
        foreach ($dataIds as $key => $dataId) {
            Province::whereIn('id', $dataId)->update(['region' => $key]);
        }
    }
}
