UPDATE wp_options SET option_value = REPLACE(option_value, 'https://www.wgi.jp/', 'http://localhost:10090/') WHERE option_name = 'home' OR option_name = 'siteurl';
UPDATE wp_posts SET guid = REPLACE(guid, 'https://www.wgi.jp/', 'http://localhost:10090/');
UPDATE wp_posts SET post_content = REPLACE(post_content, 'https://www.wgi.jp/', 'http://localhost:10090/');
UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'https://www.wgi.jp/', 'http://localhost:10090/');

