<?='<'?>?xml version="1.0" encoding="UTF-8"?<?='>'?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
  <loc>http://isharp.me/</loc>
  <lastmod><?=date('Y-m-d\TH:i:s+08:00') ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>1.00</priority>
</url>
<?php foreach($post as $v){ ?>
<url>
  <loc>http://isharp.me/post/<?=$v['id']?>.html</loc>
  <lastmod><?=date('Y-m-d\TH:i:s+08:00', $v['mtime']) ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.80</priority>
</url>
<?php } ?>
</urlset>