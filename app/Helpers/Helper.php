<?php

if (!function_exists('stripVN')) {
    function stripVN($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return trim(strtolower($str));
    }
}

if (!function_exists('splitTableColumns')) {
    function splitTableColumns($columns, $relation = null)
    {
        if ($relation) {
            return array_map(
                fn($col) => str_replace("$relation.", '', $col),
                array_filter($columns, fn($col) => str_starts_with($col, "$relation."))
            );
        }

        return array_filter($columns, fn($col) => !str_contains($col, '.'));
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember($key, 3600, function () use ($key, $default) {
            return \App\Models\System\Setting::where('key', $key)->value('value') ?? $default;
        });
    }
}

if (!function_exists('responseByStatus')) {
    function responseByStatus(bool $status, string $message, array $data = null)
    {
        return $status ? responder()->success($data, $message) : responder()->fail($message);
    }
}

if (!function_exists('convertToArray')) {
    function convertToArray($data) {
        if (is_array($data)) {
            return $data;
        } else {
            return array($data);
        }
    }
}

if (!function_exists('getDayStartAndEnd')) {
    /**
     * Get the start and end of a specific day.
     *
     * @param string $date
     * @return array
     */
    function getDayStartAndEnd($date, $inputFormat = 'Y-m-d', $outputFormat = 'Y-m-d H:i:s')
    {
        // Parse the input date to a Carbon instance using the provided input format
        $parsedDate = \Carbon\Carbon::createFromFormat($inputFormat, $date);

        // Get the start and end of the day
        $startOfDay = $parsedDate->startOfDay()->format($outputFormat);
        $endOfDay = $parsedDate->endOfDay()->format($outputFormat);

        return [
            $startOfDay,
            $endOfDay
        ];
    }
}

if (!function_exists('getMonthStartAndEnd')) {
    /**
     * Get the start and end of a specific month.
     *
     * @param int $year
     * @param int $month
     * @param string $format
     * @return array
     */
    function getMonthStartAndEnd($year = null, $month = null, $format = 'Y-m-d')
    {
        $year = $year ?? Carbon\Carbon::now()->year;
        $month = $month ?? Carbon\Carbon::now()->month;
        // Create a Carbon instance for the given year and month
        $date = \Carbon\Carbon::create($year, $month, 1);

        // Get the start and end of the month
        $startOfMonth = $date->startOfMonth()->format($format);
        $endOfMonth = $date->endOfMonth()->format($format);

        return [
            $startOfMonth,
            $endOfMonth
        ];
    }
}

if (!function_exists('processBase64Image')) {
    function processBase64Image($base64Image, $type)
    {
        $imageType = strtolower($type); // jpg, png, gif, etc.
        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        $base64Image = str_replace(' ', '+', $base64Image);
        // Decode the image
        $imageData = base64_decode($base64Image);

        $filename = uniqid() . '.' . $imageType;

        return [$imageData, $filename, $imageType];
    }
}
