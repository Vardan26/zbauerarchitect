<?php
// Refactored: Custom Post Type and Meta Fields for Projects

function register_project_components() {
    register_post_type('projects', array(
        'labels' => array(
            'name' => __('Projects'),
            'singular_name' => __('Project')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-building',
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => ['slug' => 'projects']
    ));

    register_taxonomy('project_category', 'projects', [
        'label' => 'Project Categories',
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);
    
   
   // Register taxonomy with sanitization
    register_taxonomy('project_types', 'projects', [
        'label' => 'Project Types',
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'meta_box_cb' => 'post_categories_meta_box',
        'sanitize_callback' => 'sanitize_project_type_term',
    ]);

    // Sanitize term before saving (database)
    function sanitize_project_type_term($term) {
        $term = html_entity_decode($term, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $term = wp_strip_all_tags($term);
        $term = preg_replace('/\s+/', ' ', $term);
        return trim($term);
    }

    // Sanitize term when displayed (everywhere)
    add_filter('get_terms', 'sanitize_project_type_names_display', 20, 2);

    function sanitize_project_type_names_display($terms, $taxonomies) {
        if (!is_array($terms)) return $terms;
        
        // Check if 'project_types' is in the taxonomies being queried
        $is_project_types = (is_array($taxonomies) && in_array('project_types', $taxonomies)) || 
                            ($taxonomies === 'project_types');
        
        if (!$is_project_types) return $terms;
        
        foreach ($terms as $term) {
            if (is_object($term) && isset($term->name)) {
                $term->name = html_entity_decode($term->name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
        return $terms;
    }

    // Register Meta Fields
    foreach (get_project_fields() as $field => $label) {
        register_post_meta('projects', $field, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string'
        ));
    };

    register_post_meta('projects', 'gallery', array(
        'show_in_rest' => array(
            'schema' => array(
                'type' => 'array',
                'items' => array(
                    'type' => 'string',
                    'format' => 'uri'
                )
            )
        ),
        'single' => true,
        'type' => 'array',
    ));

    register_post_meta('projects', 'status', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => [
            'schema' => [
                'type' => 'string',
                'enum' => ['completed', 'conceptual'], // Only allow these two values
            ]
        ],
    ]);

    register_rest_field('projects', 'plain_title', [
        'get_callback' => function ($post) {
            return get_the_title($post['id']);
        },
        'schema' => null,
    ]);

    register_rest_field('projects', 'plain_content', [
        'get_callback' => function ($post) {
            $content = get_post_field('post_content', $post['id']);
            $content = wp_strip_all_tags($content);             // Remove HTML tags
            $content = html_entity_decode($content);            // Convert &nbsp; and others
            $content = preg_replace('/\s+/', ' ', $content);    // Normalize all whitespace to single spaces
            $content = trim($content);                          // Trim leading/trailing whitespace
            return $content;
        },
        'schema' => null,
    ]);

    register_rest_field('projects', 'project_category_data', [
        'get_callback' => function ($post_arr) {
            $terms = get_the_terms($post_arr['id'], 'project_category');
            if (empty($terms) || is_wp_error($terms)) {
                return null;
            }
            $term = $terms[0]; // first category only; adjust if needed
    
            return [
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
            ];
        },
        'schema' => null,
    ]);

    register_rest_field('projects', 'featured_image_urls', [
        'get_callback' => function ($post_arr) {
            $thumbnail_id = get_post_thumbnail_id($post_arr['id']);
            if (!$thumbnail_id) return null;
    
            return [
                'thumb' => wp_get_attachment_image_url($thumbnail_id, 'thumbnail'),
                'medium'    => wp_get_attachment_image_url($thumbnail_id, 'medium'),
                'full'      => wp_get_attachment_image_url($thumbnail_id, 'full'),
            ];
        },
        'schema' => null,
    ]);

    register_post_meta('projects', 'showInHomePage', [
        'type'              => 'boolean',
        'single'            => true,
        'show_in_rest'      => true,
        'default'           => false,     
    ]);

    register_post_meta('projects', 'hasDetailedPage', [
        'type'              => 'boolean',
        'single'            => true,
        'show_in_rest'      => true,
        'default'           => false,
    ]);
}

add_action('init', 'register_project_components');


function get_project_fields() {
    return array(
        'year' => 'Year',
        'places' => 'Places',
        'client' => 'Client',
        'project_number' => 'Project Number',
        'principal_architect' => 'Principal Architect',
        'architects' => 'Architects (comma separated)',
        'gallery' => 'Gallery Images',
        'heading' => 'Heading',
        'wireframe' => 'Wireframe Image',
        'status' => 'Status',
    );
}

function add_project_meta_box() {
    add_meta_box(
        'project_details_box',
        'Project Details',
        'render_project_meta_box',
        'projects',
        'normal',
        'default'
    );
}

add_action('add_meta_boxes', 'add_project_meta_box');

function render_project_meta_box($post) {
    wp_nonce_field('project_save_meta_box', 'project_meta_box_nonce');

    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : get_post_meta($post->ID, 'status', true);    $status = $status !== '' ? esc_attr($status) : '';

    $checked = (get_post_meta($post->ID, 'showInHomePage', true) ?? '0') === '1' ? 'checked' : '';
    $checkedHasDetail = (get_post_meta($post->ID, 'hasDetailedPage', true) ?? '0') === '1' ? 'checked' : '';

    $gallery = get_post_meta($post->ID, 'gallery', true);
    $gallery = is_array($gallery) ? implode(',', $gallery) : $gallery;
    
    $images = $gallery ? explode(',', $gallery) : array();

    $wireframe = get_post_meta($post->ID, 'wireframe', true);
    $wireframe = esc_url($wireframe);

    echo '<table class="form-table">';
    foreach (get_project_fields() as $key => $label) {
        if ($key === 'gallery' || $key === 'showInHomePage' || $key === 'hasDetailedPage' || $key === 'wireframe' || $key === 'status') continue;

        $value = get_post_meta($post->ID, $key, true);
        $value = ($value === '' || $value === false) ? null : esc_attr($value);
        echo "
        <tr>
            <th><label for='{$key}'>{$label}</label></th>
            <td><input type='text' id='{$key}' name='{$key}' value='{$value}' style='width:100%;' /></td>
        </tr>";
    }
    echo '</table>';



    echo '
        <hr><h4>Status</h4>
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
            <select name="status">
                <option value="">Select Status</option>
                <option value="completed" ' . selected($status, 'completed', false) . '>Completed</option>
                <option value="conceptual" ' . selected($status, 'conceptual', false) . '>Conceptual</option>
            </select>

            <label style="margin: 0;">
                <input type="checkbox" name="showInHomePage" value="1" ' . $checked . ' />
                Show in Homepage
            </label>
            <label style="margin: 0;">
                <input type="checkbox" name="hasDetailedPage" value="1" ' . $checkedHasDetail . ' />
                Has Detailed Page
            </label>
        </div>
     ';

     echo '
     <hr>
     <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 40px; flex-wrap: wrap;">
         
         <!-- Gallery Section -->
         <div style="flex: 1;">
             <h5>Gallery</h5>
             <div id="gallery-preview" style="display: flex; flex-wrap: wrap;">';
                 foreach ($images as $img_url) {
                     $img_url = trim($img_url);
                     if (!empty($img_url)) {
                        echo "
                        <div class='gallery-item' style='display:inline-block;position:relative;margin:5px;'>
                            <img src='" . esc_url($img_url) . "' style='max-width:100px;'>
                            <button class='remove-gallery-image' data-url='" . esc_url($img_url) . "' 
                                style='position:absolute;top:0;right:0;background:rgba(0,0,0,0.7);color:#fff;
                                border:none;cursor:pointer;padding:2px 6px;font-size:16px;'>&times;</button>
                        </div>
                    ";
                    }
                 }
     echo '  </div>
             <input type="hidden" name="gallery" id="gallery-field" value="' . esc_attr($gallery) . '" />
             <button type="button" class="button" id="upload-gallery-button">Upload/Add Images</button>
         </div>
     
         <!-- Wireframe Section -->
         <div style="flex: 1;text-align: right;">
             <h5>Wireframe</h5>
             <div id="wireframe-preview">';
            //  if ($wireframe) {
            //     echo "<img src='" . esc_url($wireframe) . "' style='max-width:150px;margin-bottom:10px;'><br>";
            // }
                 if ($wireframe) {
                    echo "
                    <div class='gallery-item' style='display:inline-block;position:relative;margin:5px;'>
                            <img src='" . esc_url($wireframe) . "' style='max-width:100px;'>
                            <button class='remove-wireframe-image' data-url='" . esc_url($wireframe) . "' 
                                style='position:absolute;top:0;right:0;background:rgba(0,0,0,0.7);color:#fff;
                                border:none;cursor:pointer;padding:2px 6px;font-size:16px;'>&times;</button>
                        </div>
                    ";
                 }
     echo '      </div>
             <input type="hidden" name="wireframe" id="wireframe-field" value="' . esc_attr($wireframe) . '" />
             <button type="button" class="button" id="upload-wireframe-button">Upload Wireframe Image</button>
         </div>
     
     </div>';

}

function save_project_meta($post_id) {
    if (!isset($_POST['project_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['project_meta_box_nonce'], 'project_save_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // General fields
    foreach (get_project_fields() as $key => $label) {
        if ($key === 'gallery' || $key === 'wireframe') continue; // handled separately
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }

    // Status
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    update_post_meta($post_id, 'status', $status);

    // Booleans
    update_post_meta($post_id, 'showInHomePage', isset($_POST['showInHomePage']) ? '1' : '0');
    update_post_meta($post_id, 'hasDetailedPage', isset($_POST['hasDetailedPage']) ? '1' : '0');

    // Gallery
    if (isset($_POST['gallery'])) {
        $images_raw = explode(',', $_POST['gallery']);
        $images = array_filter(array_map('esc_url_raw', $images_raw)); // Remove empty values
        update_post_meta($post_id, 'gallery', $images);
    }

    // Wireframe
    if (!empty($_POST['wireframe'])) {
        update_post_meta($post_id, 'wireframe', esc_url_raw($_POST['wireframe']));
    } else {
        delete_post_meta($post_id, 'wireframe'); // remove if empty
    }
}
add_action('save_post', 'save_project_meta');

function enqueue_gallery_script($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'])) return;

    wp_enqueue_media();
    wp_register_script('gallery-js', false);
    wp_add_inline_script('gallery-js', "
        document.addEventListener('DOMContentLoaded', () => {
            // Gallery uploader
            const galleryButton = document.getElementById('upload-gallery-button');
            const galleryField = document.getElementById('gallery-field');
            const galleryPreview = document.getElementById('gallery-preview');
            let galleryFrame;

            if (galleryButton) {
                galleryButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (galleryFrame) {
                        galleryFrame.open();
                        return;
                    }

                    galleryFrame = wp.media({
                        title: 'Select or Upload Gallery Images',
                        button: { text: 'Use these images' },
                        multiple: true
                    });

                   galleryFrame.on('select', () => {
                        const attachments = galleryFrame.state().get('selection').toJSON();
                        let existing = galleryField.value ? galleryField.value.split(',') : [];

                        attachments.forEach(att => {
                            if (!existing.includes(att.url)) {
                                existing.push(att.url);

                                const imgWrapper = document.createElement('div');
                                imgWrapper.style.display = 'inline-block';
                                imgWrapper.style.position = 'relative';
                                imgWrapper.style.margin = '5px';

                                const img = document.createElement('img');
                                img.src = att.url;
                                img.style.maxWidth = '100px';

                                const removeBtn = document.createElement('button');
                                removeBtn.innerHTML = '&times;';
                                removeBtn.style.position = 'absolute';
                                removeBtn.style.top = '0';
                                removeBtn.style.right = '0';
                                removeBtn.style.background = 'rgba(0,0,0,0.7)';
                                removeBtn.style.color = '#fff';
                                removeBtn.style.border = 'none';
                                removeBtn.style.cursor = 'pointer';
                                removeBtn.style.padding = '2px 6px';
                                removeBtn.style.fontSize = '16px';

                                removeBtn.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    imgWrapper.remove();
                                    existing = existing.filter(url => url !== att.url);
                                    galleryField.value = existing.join(',');
                                });

                                imgWrapper.appendChild(img);
                                imgWrapper.appendChild(removeBtn);
                                galleryPreview.appendChild(imgWrapper);
                            }
                        });

                        galleryField.value = existing.join(',');
                    });

                    galleryFrame.open();
                });
                // Remove already existing images
                galleryPreview.querySelectorAll('.remove-gallery-image').forEach(button => {
                    button.addEventListener('click', e => {
                        e.preventDefault();
                        const urlToRemove = button.getAttribute('data-url');
                        const item = button.parentElement;

                        // Remove from preview
                        if (item) item.remove();

                        // Update hidden input
                        let current = galleryField.value.split(',').filter(url => url !== urlToRemove);
                        galleryField.value = current.join(',');
                    });
                });
            }

            // Wireframe uploader
            const wireframeButton = document.getElementById('upload-wireframe-button');
            const wireframeField = document.getElementById('wireframe-field');
            const wireframePreview = document.getElementById('wireframe-preview');
            let wireframeFrame;

            if (wireframeButton) {
                wireframeButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (wireframeFrame) {
                        wireframeFrame.open();
                        return;
                    }

                    wireframeFrame = wp.media({
                        title: 'Select or Upload Wireframe Image',
                        button: { text: 'Use this image' },
                        multiple: false
                    });

                   wireframeFrame.on('select', () => {
                        const attachment = wireframeFrame.state().get('selection').first().toJSON();

                        // Clear previous image preview
                        wireframePreview.innerHTML = '';

                        // Set field value
                        wireframeField.value = attachment.url;

                        // Create image preview
                        const imgWrapper = document.createElement('div');
                        imgWrapper.style.position = 'relative';
                        imgWrapper.style.display = 'inline-block';

                        const img = document.createElement('img');
                        img.src = attachment.url;
                        img.style.maxWidth = '100px';

                        const removeBtn = document.createElement('button');
                        removeBtn.innerHTML = '&times;';
                        removeBtn.style.position = 'absolute';
                        removeBtn.style.top = '0';
                        removeBtn.style.right = '0';
                        removeBtn.style.background = 'rgba(0,0,0,0.7)';
                        removeBtn.style.color = '#fff';
                        removeBtn.style.border = 'none';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.style.padding = '2px 6px';
                        removeBtn.style.fontSize = '16px';
                        removeBtn.classList.add('remove-wireframe-image');

                        removeBtn.addEventListener('click', e => {
                            e.preventDefault();
                            imgWrapper.remove();
                            wireframeField.value = '';
                        });

                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(removeBtn);
                        wireframePreview.appendChild(imgWrapper);
                    });

                    wireframeFrame.open();
                });
                wireframePreview.addEventListener('click', function (e) {
                    const button = e.target.closest('.remove-wireframe-image');
                    if (!button) return;

                    e.preventDefault();

                    const urlToRemove = button.getAttribute('data-url');
                    const item = button.parentElement;

                    // Remove from preview
                    if (item) item.remove();

                    // Update hidden input
                    const current = wireframeField.value.split(',').filter(url => url !== urlToRemove);
                    wireframeField.value = current.join(',');
                });
                
            }
        });
    ");
    wp_enqueue_script('gallery-js');
}

add_action('admin_enqueue_scripts', 'enqueue_gallery_script');
