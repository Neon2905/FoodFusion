<?php
namespace App\Faker;


use Faker\Provider\Base;
class ImageFakerProvider extends Base
{
    public function imageUrl($width = 640, $height = 480, $category = null)
    {
        if (!$category) {
            // picsum's random endpoint may be unreliable; use a stable placeholder service as fallback
            $uid = uniqid();
            return "https://loremflickr.com/{$width}/{$height}";
        }

        // simulate categories by using them as a seed
        $seed = urlencode($category . '-' . substr(md5(uniqid()), 0, 8));
        return "https://loremflickr.com/{$width}/{$height}/{$category}";
    }
}
