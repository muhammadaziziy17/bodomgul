<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Throwable;

/**
 * Rasm yuklash: fayllar `public/uploads/...` ichiga saqlanadi.
 *
 * InfinityFree kabi hostinglarda `storage:link` ishlamasligi mumkin,
 * shu sabab ommaviy papkaga to'g'ridan-to'g'ri yoziladi. Fayl nomi
 * doim generatsiya qilinadi va faqat shu papkadagi eski fayllar
 * o'chiriladi.
 */
class ImageUploader
{
    /**
     * Yuklangan rasmni saqlaydi va nisbiy yo'lni qaytaradi.
     *
     * @param  string  $folder  `uploads` ichidagi papka (hero, products, ...)
     */
    public static function store(UploadedFile $file, string $folder, ?string $previousPath = null): string
    {
        $extension = $file->extension() ?: $file->getClientOriginalExtension();
        $name = Str::random(24).'.'.Str::lower($extension);
        $directory = public_path('uploads/'.$folder);

        $file->move($directory, $name);

        self::delete($previousPath);

        return 'uploads/'.$folder.'/'.$name;
    }

    /**
     * Faqat `uploads` papkasi ichidagi faylni o'chiradi.
     */
    public static function delete(?string $path): void
    {
        if ($path === null || ! Str::startsWith($path, 'uploads/')) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            try {
                unlink($fullPath);
            } catch (Throwable) {
                // Fayl o'chmasa ham so'rovni to'xtatmaymiz.
            }
        }
    }
}
