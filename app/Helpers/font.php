<?php

namespace EHXDonate\Helpers;

class Font
{
    public static function getAll()
    {
        $fonts = [
            // System fonts
            'Inter Tight' => 'Inter Tight, Arial, sans-serif',
            'Arial' => 'Arial, Helvetica, sans-serif',
            'Helvetica' => 'Helvetica, Arial, sans-serif',
            'Times New Roman' => '"Times New Roman", Times, serif',
            'Courier New' => '"Courier New", Courier, monospace',
            'Verdana' => 'Verdana, Geneva, sans-serif',
            'Georgia' => 'Georgia, serif',
            'Palatino' => 'Palatino, "Palatino Linotype", serif',
            'Garamond' => 'Garamond, serif',
            'Bookman' => 'Bookman, serif',
            'Comic Sans MS' => '"Comic Sans MS", cursive, sans-serif',
            'Trebuchet MS' => '"Trebuchet MS", sans-serif',
            'Arial Black' => '"Arial Black", Gadget, sans-serif',
            'Impact' => 'Impact, Charcoal, sans-serif',
            'Tahoma' => 'Tahoma, Geneva, sans-serif',

            // Google / Web fonts
            'Roboto' => 'Roboto, sans-serif',
            'Open Sans' => '"Open Sans", sans-serif',
            'Lato' => 'Lato, sans-serif',
            'Montserrat' => 'Montserrat, sans-serif',
            'Oswald' => 'Oswald, sans-serif',
            'Raleway' => 'Raleway, sans-serif',
            'Merriweather' => 'Merriweather, serif',
            'PT Sans' => '"PT Sans", sans-serif',
            'Noto Sans' => '"Noto Sans", sans-serif',
            'Ubuntu' => 'Ubuntu, sans-serif',
            'Playfair Display' => '"Playfair Display", serif',
            'Source Sans Pro' => '"Source Sans Pro", sans-serif',
            'Poppins' => 'Poppins, sans-serif',
            'Fira Sans' => '"Fira Sans", sans-serif',
            'Nunito' => 'Nunito, sans-serif',
            'Cabin' => 'Cabin, sans-serif',

            // Script / decorative fonts
            'Dancing Script' => '"Dancing Script", cursive',
            'Pacifico' => 'Pacifico, cursive',
            'Great Vibes' => '"Great Vibes", cursive',
            'Lobster' => 'Lobster, cursive',
            'Sacramento' => 'Sacramento, cursive',
            'Cookie' => 'Cookie, cursive',
            'Satisfy' => 'Satisfy, cursive',
            'Kaushan Script' => '"Kaushan Script", cursive',
            'Caveat' => 'Caveat, cursive',
            'Amatic SC' => '"Amatic SC", cursive',
            'Indie Flower' => '"Indie Flower", cursive',
        ];

        return apply_filters('exh_donate_fonts', $fonts);
    }

    public static function getFontSample($font)
    {
        $samples = [];
        foreach (self::getAll() as $key => $stack) {
            $samples[$key] = 'The quick brown fox jumps over the lazy dog.';
        }

        return $samples[$font] ?? '';
    }
}
