<?php
/**
 * Plugin Name: CWS Temporary Restore
 * Description: Administrator-only one-time restoration of the reviewed ChinaWorthSeeing public demo.
 * Version: 1.0.0
 */
defined('ABSPATH') || exit;

function cws_restore_data() { return require __DIR__ . '/payload.php'; }
function cws_restore_manifest() { return require __DIR__ . '/manifest.php'; }
function cws_restore_error($message) { throw new RuntimeException($message); }
function cws_restore_rewrite($value) {
    if (is_array($value)) {
        foreach ($value as $key => $item) $value[$key] = cws_restore_rewrite($item);
        return $value;
    }
    if (!is_string($value)) return $value;
    if (is_serialized($value)) {
        $decoded = unserialize($value, ['allowed_classes' => false]);
        return serialize(cws_restore_rewrite($decoded));
    }
    $old = ['http://localhost:8882','http://localhost:8881','http://localhost:8880','https://www.chinaworthseeing.com','http://www.chinaworthseeing.com','https://chinaworthseeing.com','http://chinaworthseeing.com','https://wordpress-1659769-6611899.cloudwaysapps.com','http://wordpress-1659769-6611899.cloudwaysapps.com'];
    foreach ($old as $url) {
        $value = str_replace([$url, str_replace('/', '\\/', $url)], [home_url(), str_replace('/', '\\/', home_url())], $value);
    }
    return $value;
}

function cws_restore_upload() {
    if (get_option('cws_restore_complete')) cws_restore_error('Restoration is already complete.');
    $upload = $_FILES['restore_zip'] ?? null;
    if (!$upload || $upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) cws_restore_error('Upload failed.');
    $manifest = cws_restore_manifest();
    $name = basename($upload['name']);
    if (!isset($manifest[$name]) || !hash_equals($manifest[$name]['sha256'], hash_file('sha256', $upload['tmp_name']))) cws_restore_error('Archive is not in the reviewed manifest.');
    if (!class_exists('ZipArchive')) cws_restore_error('ZipArchive is unavailable.');
    $zip = new ZipArchive();
    if ($zip->open($upload['tmp_name']) !== true) cws_restore_error('Cannot open archive.');
    $expected = $manifest[$name]['files'];
    if ($zip->numFiles !== count($expected)) cws_restore_error('Unexpected archive entries.');
    // Verify every member before writing any file. No extraction of arbitrary paths.
    for ($i=0; $i<$zip->numFiles; $i++) {
        $path = $zip->getNameIndex($i);
        if (!isset($expected[$path]) || strpos($path, '..') !== false || strpos($path, '\\') !== false || substr($path,0,1)==='/') cws_restore_error('Invalid member path.');
        $bytes = $zip->getFromIndex($i);
        if ($bytes === false || !hash_equals($expected[$path], hash('sha256',$bytes))) cws_restore_error('Member checksum mismatch.');
        $target = WP_CONTENT_DIR . '/' . $path;
        if (is_file($target) && !hash_equals($expected[$path],hash_file('sha256',$target))) cws_restore_error('Existing destination differs; refusing overwrite: '.$path);
    }
    foreach ($expected as $path=>$hash) {
        $target=WP_CONTENT_DIR.'/'.$path;
        if (!wp_mkdir_p(dirname($target))) cws_restore_error('Cannot create destination directory.');
        if (!is_file($target) && file_put_contents($target,$zip->getFromName($path)) === false) cws_restore_error('Cannot write file.');
        if (!hash_equals($hash,hash_file('sha256',$target))) cws_restore_error('Written file verification failed.');
    }
    $zip->close();
    $done=get_option('cws_restore_packs',[]); $done[$name]=count($expected); update_option('cws_restore_packs',$done,false);
    return 'Verified and restored '.$name.' ('.count($expected).' files).';
}

function cws_restore_dependencies() {
    $manifest=cws_restore_manifest(); $done=get_option('cws_restore_packs',[]);
    if (count($done)!==count($manifest)) cws_restore_error('Upload all file packs first.');
    require_once ABSPATH.'wp-admin/includes/plugin.php';
    foreach (['fluentform/fluentform.php','smart-slider-3/smart-slider-3.php'] as $plugin) {
        $result=activate_plugin($plugin);
        if (is_wp_error($result)) cws_restore_error($result->get_error_message());
    }
    update_option('cws_restore_dependencies',1,false);
    return 'Required existing plugins activated. Load this page again before restoring content.';
}

function cws_restore_content() {
    global $wpdb;
    if (get_option('cws_restore_complete')) cws_restore_error('Restoration already complete.');
    if (!get_option('cws_restore_dependencies')) cws_restore_error('Activate dependencies first.');
    $data=cws_restore_data();
    $tables=['posts','postmeta','terms','term_taxonomy','term_relationships','termmeta','fluentform_forms','fluentform_form_meta','nextend2_section_storage','nextend2_image_storage','nextend2_smartslider3_sliders','nextend2_smartslider3_sliders_xref','nextend2_smartslider3_slides','nextend2_smartslider3_generators'];
    foreach ($tables as $suffix) {
        $table=$wpdb->prefix.$suffix;
        if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s',$wpdb->esc_like($table))) !== $table) cws_restore_error('Required table missing: '.$suffix);
    }
    // Retain the empty installation's affected content/settings in a private DB option.
    // Never replace users, authentication, mail credentials or existing enquiry records.
    if (!get_option('cws_restore_before')) {
        if ((int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='page' AND post_status='publish'")>3) cws_restore_error('Target is no longer an empty installation.');
        if ((int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}fluentform_submissions")>0) cws_restore_error('Target contains enquiries; review before import.');
        $backup=['tables'=>[],'options'=>[]];
        foreach($tables as $suffix) $backup['tables'][$suffix]=$wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.$suffix.'`',ARRAY_A);
        foreach($data['options'] as $opt) $backup['options'][$opt['option_name']]=get_option($opt['option_name']);
        foreach(['stylesheet','template','blog_public','upload_path','upload_url_path'] as $key) $backup['options'][$key]=get_option($key);
        if (!add_option('cws_restore_before',$backup,'',false)) cws_restore_error('Could not save rollback snapshot.');
    }
    $wpdb->query('START TRANSACTION');
    try {
        // Preserve installation sample content as drafts, with its original data in the snapshot.
        $wpdb->query("UPDATE {$wpdb->posts} SET post_status='draft' WHERE post_type IN ('post','page') AND post_status='publish'");
        foreach($tables as $suffix) {
            foreach($data[$suffix] as $row) {
                $row=cws_restore_rewrite($row);
                if ($suffix==='posts') $row['post_author']=get_current_user_id();
                if ($suffix==='fluentform_forms') $row['created_by']=get_current_user_id();
                if ($wpdb->replace($wpdb->prefix.$suffix,$row)===false) cws_restore_error('Import failed in '.$suffix.': '.$wpdb->last_error);
            }
        }
        $wpdb->query('COMMIT');
    } catch (Throwable $e) { $wpdb->query('ROLLBACK'); throw $e; }
    switch_theme('hever-wpcom');
    foreach($data['options'] as $opt) update_option($opt['option_name'],maybe_unserialize(cws_restore_rewrite($opt['option_value'])));
    update_option('blog_public',0);
    update_option('upload_path',''); update_option('upload_url_path','');
    // This is a public demo restoration. Existing SMTP/Turnstile keys are intentionally not imported.
    wp_cache_flush(); flush_rewrite_rules();
    update_option('cws_restore_complete',gmdate('c'),false);
    return 'Public website content restored. New administrator preserved; no old enquiries or credentials imported.';
}

add_action('admin_menu',static function() {
    add_management_page('CWS Restore','CWS Restore','manage_options','cws-restore','cws_restore_page');
});
function cws_restore_page() {
    if (!current_user_can('manage_options') || !current_user_can('install_plugins')) wp_die('Administrator access required.');
    echo '<div class="wrap"><h1>CWS Restore</h1>';
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        check_admin_referer('cws-restore');
        try {
            $action=sanitize_key($_POST['restore_action']??'');
            if ($action==='files') $message=cws_restore_upload();
            elseif ($action==='dependencies') $message=cws_restore_dependencies();
            elseif ($action==='content') $message=cws_restore_content();
            else cws_restore_error('Unknown action.');
            echo '<div class="notice notice-success"><p>'.esc_html($message).'</p></div>';
        } catch(Throwable $e) { echo '<div class="notice notice-error"><p>'.esc_html($e->getMessage()).'</p></div>'; }
    }
    echo '<p>Only reviewed public website files and content. Existing administrator is preserved.</p>';
    echo '<p>Upload limit: '.esc_html(ini_get('upload_max_filesize')).'; POST limit: '.esc_html(ini_get('post_max_size')).'; ZipArchive: '.(class_exists('ZipArchive')?'available':'unavailable').'</p>';
    echo '<h2>File packs</h2><ul>';
    $done=get_option('cws_restore_packs',[]);
    foreach(cws_restore_manifest() as $name=>$pack) echo '<li>'.esc_html($name).' — '.(isset($done[$name])?'VERIFIED':'pending').'</li>';
    echo '</ul>';
    if (!get_option('cws_restore_complete')) {
        echo '<form method="post" enctype="multipart/form-data">'; wp_nonce_field('cws-restore');
        echo '<input type="hidden" name="restore_action" value="files"><label for="restore_zip">Reviewed file pack</label> <input type="file" id="restore_zip" name="restore_zip" accept=".zip" required> ';
        submit_button('Restore file pack','primary','submit',false); echo '</form>';
        foreach(['dependencies'=>'Activate required plugins','content'=>'Restore public content'] as $action=>$label) {
            echo '<form method="post">'; wp_nonce_field('cws-restore'); echo '<input type="hidden" name="restore_action" value="'.esc_attr($action).'">'; submit_button($label); echo '</form>';
        }
    }
    echo '<h2>Status</h2><p>Content: '.esc_html(get_option('cws_restore_complete','pending')).'</p>';
    global $wpdb;
    echo '<p>Published pages: '.(int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='page' AND post_status='publish'").'; theme: '.esc_html(get_stylesheet()).'; administrators preserved.</p>';
    echo '<p><a href="'.esc_url(home_url('/')).'">View restored website</a></p></div>';
}
