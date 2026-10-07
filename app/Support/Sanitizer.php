<?php

namespace App\Support;

use DOMComment;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Sanitizer HTML berbasis allowlist (tanpa dependency eksternal).
 *
 * Dipakai untuk konten rich-text (informasi umum, visi, misi, tupoksi, isi berita)
 * baik saat disimpan maupun saat ditampilkan, agar data lama pun aman dari XSS.
 */
class Sanitizer
{
    /** Tag yang diizinkan. Tag lain di-"unwrap" (isi teks dipertahankan). */
    private const ALLOWED = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'ul', 'ol', 'li',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'span', 'div', 'a', 'hr',
    ];

    /** Tag yang dibuang beserta seluruh isinya. */
    private const DROP = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button',
        'textarea', 'select', 'link', 'meta', 'base', 'svg', 'math', 'noscript',
    ];

    public static function html(?string $html): string
    {
        $html = (string) $html;

        if (trim($html) === '') {
            return '';
        }

        // Data lama tersimpan sebagai entity (&lt;p&gt;...) — decode dulu sebelum disanitasi.
        if (!str_contains($html, '<') && str_contains($html, '&lt;')) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $previous = libxml_use_internal_errors(true);
        $doc      = new DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML(
            '<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__root');

        if (!$root) {
            return e(strip_tags($html));
        }

        self::clean($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return $out;
    }

    private static function clean(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMComment) {
                $node->removeChild($child);
                continue;
            }

            if (!$child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->nodeName);

            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);
                continue;
            }

            self::clean($child);

            if (!in_array($tag, self::ALLOWED, true)) {
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attr) {
                $isSafeHref = $tag === 'a'
                    && strtolower($attr->name) === 'href'
                    && preg_match('#^(https?://|mailto:|tel:|/|\#)#i', trim($attr->value));

                if (!$isSafeHref) {
                    $child->removeAttribute($attr->name);
                }
            }

            if ($tag === 'a') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }
}
