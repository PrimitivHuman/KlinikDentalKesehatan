{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{ url('/') }}</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{ url('/appointment') }}</loc>
    <changefreq>monthly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ url('/berita') }}</loc>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/agenda') }}</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  @foreach ($beritas as $item)
  <url>
    <loc>{{ url('/berita/' . $item->slug) }}</loc>
    <lastmod>{{ $item->updated_at ? $item->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach
  @if(isset($kegiatans))
  @foreach ($kegiatans as $keg)
  <url>
    <loc>{{ url('/agenda/' . $keg->id_kegiatan) }}</loc>
    <lastmod>{{ $keg->updated_at ? $keg->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach
  @endif
</urlset>
