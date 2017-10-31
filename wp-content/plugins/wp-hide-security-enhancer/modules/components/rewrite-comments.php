<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_comments extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Comments";
                }
                                                
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'new_wp_comments_post',
                                                                        'label'         =>  __('New wp-comments-post.php Path',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default path is set to wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'value_description' =>  'e.g. user-input.php',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), array($this->wph->functions, 'php_extension_required')),
                                                                        'processing_order'  =>  60
                                                                        );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'block_wp_comments_post_url',
                                                                        'label'         =>  __('Block wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default wp-comments-post.php.',    'wp-hide-security-enhancer') . '<br />'.__('Apply only if ',    'wp-hide-security-enhancer') . '<b>New wp-comments-post.php Path</b> ' . __('is not empty.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  60
                                                                        
                                                                        );
                    
                                                                    
                    return $this->module_settings;   
                }
                
            
            
            function _init_new_wp_comments_post($saved_field_data)
                {
                   
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'wp-comments-post.php';
                    $replacement    =   trailingslashit(    home_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            function _callback_saved_new_wp_comments_post($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                                                            
                    $default_path   =   $this->wph->functions->get_url_path( trailingslashit(site_url()) . 'wp-comments-post.php', TRUE   );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $saved_field_data   .   ' '. $default_path .' [L,QSA]'; 
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_wp_comments_post" stopProcessing="true">
                                <match url="^'.  $saved_field_data   .'"  />
                                <action type="Rewrite" url="'.  $default_path .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;     
                    
                    
                }
            
            
            function _callback_saved_block_wp_comments_post_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp_comments_post is not modified
                    $new_wp_comments_post     =   ltrim(rtrim($this->wph->functions->get_module_item_setting('new_wp_comments_post'), "/"),  "/");
                    if (empty(  $new_wp_comments_post ))
                        return FALSE;
                    
                    $rewrite_file_base  =   !empty($this->wph->default_variables['wordpress_directory']) ? trailingslashit( $this->wph->default_variables['wordpress_directory'] ) . 'wp-comments-post.php' : 'wp-comments-post.php';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^" . $rewrite_file_base ." ".  $this->wph->default_variables['site_wordpress_relative_path'] ."index.php [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_wp_comments_post_url" stopProcessing="true">
                                            <match url="^' . $rewrite_file_base . '"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_wordpress_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            
        }
?>