<?php
namespace App\Faker;


use Faker\Provider\Base;
class ImageFakerProvider extends Base
{
    public function imageUrl($width = 640, $height = 480, $category = null)
    {
        if (!$category) {
            return "https://picsum.photos/{$width}/{$height}?random=" . uniqid();
        }

        // simulate categories by using them as a seed
        $seed = urlencode($category . '-' . substr(md5(uniqid()), 0, 8));
        return "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    }
}
