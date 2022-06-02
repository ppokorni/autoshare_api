<?php

namespace App\Services;

use Exception;

class ImageService {

    // Stores base64 encoded image to specified path with specified filename
    public function storeImage($encoded_image, $path, $id) {
        // Creates directory if necessary
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        if (file_exists("$path/$id.png")) {
            unlink("$path/$id.png");
        }

        $data = base64_decode($encoded_image);
        $image = imagecreatefromstring($data);
        imagepng($image, "$path/$id.png");
    }

    // Encodes image to base64
    public function encodeImage($image): ?string {
        if (!file_exists($image)) return null;
        $data = file_get_contents($image);
        return base64_encode($data);
    }

    // Deletes image if present
    public function destroyImage($image) {
        if (file_exists($image)) {
            unlink($image);
        }
    }
}
