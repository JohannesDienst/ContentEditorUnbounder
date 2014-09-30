<?php

  /*
   * Plugin Name: Content Editor Unbounder
   * Plugin URI: TODO github repository
   * Description: Disable various content editor widgets
   * Version: 1.0.0
   * Author: Johannes Dienst (MultaMedio Informationssystem AG)
   * Author URI: http://www.multamedio.de
   */

  /*
   * Create settings link on plugins page
   */
  define( 'FB_BASENAME', plugin_basename( __FILE__ ) );
  define( 'FB_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
  define( 'FB_FILENAME', str_replace( FB_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );

  function filter_plugin_meta($links, $file) {
    if ( $file == FB_BASENAME ) {
      array_push(
        $links,
        sprintf( '<a href="options-general.php?page=%s">%s</a>', FB_FILENAME, __('Settings') )
      );
    }
    return $links;
  }
  add_filter( 'plugin_row_meta', 'filter_plugin_meta', 10, 2 );

  /*
   *  Runs on the edit post page in wp-admin.
   */
  function ceu_load_post()
  {
    add_filter("format_to_edit", "ceu_format_to_edit");
    add_action("admin_footer", "ceu_admin_footer", 10);
  }
  add_action("load-post.php", "ceu_load_post");

  function ceu_format_to_edit($content)
  {
    add_filter('admin_footer',
      function(){
        echo '<script type="text/javascript">' .
          'jQuery(document).ready(function(){' .
          'jQuery(\'#content-tmce\').attr(\'onclick\', "switchEditors.go(\'content\', \'tinymce\');jQuery(\'#ed_toolbar\').hide();jQuery(\'#mce_43\').show();");' .
          'jQuery(\'#content-html\').attr(\'onclick\', "switchEditors.go(\'content\',\'html\');jQuery(\'#ed_toolbar\').show();jQuery(\'#mce_43\').hide();");'.
          '});</script>';
      }
    );

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_addmedia', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("wp-content-media-buttons");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_visual', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){switchEditors.go(\'content\',\'html\');ceu_disableVisualEditor();});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_text', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disableTextEditor();});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_edit-slug-buttons', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("edit-slug-buttons");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_icldiv', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("icl_div");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_submitdiv', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("submitdiv");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_pageparentdiv', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("pageparentdiv");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_redirect', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("redirect");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_commentsdiv', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("commentsdiv");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_slugdiv', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("slugdiv");});</script>';
        }
      );
    }

    if(isset($_GET['post']) && get_post_meta($_GET['post'], '_ceu_disable_icldivconfig', true) != false)
    {
      add_filter('admin_footer',
        function(){
          echo '<script type="text/javascript">jQuery(document).ready(function(){ceu_disable_div("icl_div_config");});</script>';
        }
      );
    }

    return $content;
  }

  /*
   *  Javascript to select the HTML tab and disable the Visual tab.
   */
  function ceu_admin_footer()
  {
?>
    <script type="text/javascript">
      function ceu_disableVisualEditor()
      {
        jQuery('#content-tmce').attr('onclick', '');
        jQuery('#mce_43').hide();
        jQuery('#tinymce').attr('contenteditable', 'false');
      
        if(jQuery('#edButtonPreviewSpan').length)
        {
          jQuery('#edButtonPreviewSpan').css('text-decoration', 'line-through');
        }
        else
        {
          var edButtonPreviewHTML = jQuery('#content-tmce').html();
          jQuery('#content-tmce').html('<span id="edButtonPreviewSpan" style="text-decoration:line-through">'+edButtonPreviewHTML+'</span>');
        }
      }

      function ceu_enableVisualEditor()
      {
        jQuery('#content-tmce').attr('onclick', "switchEditors.go('content', 'tinymce');jQuery('#ed_toolbar').hide();jQuery('#mce_43').show();");
        jQuery('#edButtonPreviewSpan').attr('style', '');
      
        if (jQuery('#ed_toolbar').css('display') === 'none')
        {
          jQuery('#mce_43').show();
        }
        jQuery('#tinymce').attr('contenteditable', 'true');
      }

      function ceu_disableTextEditor()
      {
        jQuery('#content-html').attr('onclick', '');
        jQuery('#ed_toolbar').hide();
        jQuery('.wp-editor-area').attr('disabled', true);
        if(jQuery('#content-html').length)
        {
          jQuery('#content-html').css('text-decoration', 'line-through');
        }
        else
        {
          var edButtonPreviewHTML = jQuery('#content-html').html();
          jQuery('#content-html').html('<span id="edButtonPreviewSpanText" style="text-decoration:line-through">'+edButtonPreviewHTML+'</span>');
        }
      }

      function ceu_enableTextEditor()
      {
        if ( jQuery('#mce_43').css('display') == undefined || (jQuery('#mce_43').css('display') === 'none') )
        {
          jQuery('#ed_toolbar').show();
        }
        jQuery('.wp-editor-area').removeAttr('disabled');
        jQuery('#content-html').attr('onclick', "switchEditors.go('content','html');jQuery('#ed_toolbar').show();jQuery('#mce_43').hide();");
        jQuery('#edButtonPreviewSpanText').attr('style', '');
        jQuery('#content-html').css('text-decoration', '');
        jQuery('#content-html').show();
      }

      function ceu_disable_div(id)
      {
        jQuery('#' + id).hide();
      }

      function ceu_enable_div(id)
      {
        jQuery('#' + id).show();
      }

      jQuery(document).ready(function(){

        jQuery('#ceu_disable_addmedia').click(function(){

            if(jQuery('#ceu_disable_addmedia').attr('checked'))
            {
              ceu_disable_div('wp-content-media-buttons');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('wp-content-media-buttons');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_addmedia=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              // alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_visual').click(function(){

            if(jQuery('#ceu_disable_visual').attr('checked'))
            {
              ceu_disableVisualEditor();
              var checked = '1';
            }
            else
            {
              ceu_enableVisualEditor();
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_visual=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_text').click(function(){

            if(jQuery('#ceu_disable_text').attr('checked'))
            {
              ceu_disableTextEditor();
              var checked = '1';
            }
            else
            {
              ceu_enableTextEditor();
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_text=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_editslugbuttons').click(function(){

            if(jQuery('#ceu_disable_editslugbuttons').attr('checked'))
            {
              ceu_disable_div('edit-slug-buttons');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('edit-slug-buttons');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_editslugbuttons=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_icldiv').click(function(){

            if(jQuery('#ceu_disable_icldiv').attr('checked'))
            {
              ceu_disable_div('icl_div');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('icl_div');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_icldiv=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_submitdiv').click(function(){

            if(jQuery('#ceu_disable_submitdiv').attr('checked'))
            {
              ceu_disable_div('submitdiv');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('submitdiv');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_submitdiv=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_pageparentdiv').click(function(){

            if(jQuery('#ceu_disable_pageparentdiv').attr('checked'))
            {
              ceu_disable_div('pageparentdiv');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('pageparentdiv');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_pageparentdiv=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_redirect').click(function(){

            if(jQuery('#ceu_disable_redirect').attr('checked'))
            {
              ceu_disable_div('redirect');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('redirect');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_redirect=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_commentsdiv').click(function(){

            if(jQuery('#ceu_disable_commentsdiv').attr('checked'))
            {
              ceu_disable_div('commentsdiv');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('commentsdiv');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_commentsdiv=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_slugdiv').click(function(){

            if(jQuery('#ceu_disable_slugdiv').attr('checked'))
            {
              ceu_disable_div('slugdiv');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('slugdiv');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_slugdiv=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

        jQuery('#ceu_disable_icldivconfig').click(function(){

            if(jQuery('#ceu_disable_icldivconfig').attr('checked'))
            {
              ceu_disable_div('icl_div_config');
              var checked = '1';
            }
            else
            {
              ceu_enable_div('icl_div_config');
              var checked = '';
            }

            data = 'action=ceu_save&post=<?php echo $_GET["post"]; ?>&ceu_disable_icldivconfig=' + checked;
            jQuery.post(ajaxurl, data, function(response) {
              //alert('Got this from the server: ' + response);
            });
        });

      });
     </script>
<?php
  }

  /*
   *  Add checkbox to the screen settings.
   */
  function ceu_screen_settings($current, $screen)
  {
    //only for admins
    if(!current_user_can("manage_options"))
      return;
    
    global $pagenow;

    $addMediaChecked = ' checked="checked" ';
    $visualChecked = ' checked="checked" ';
    $textChecked = ' checked="checked" ';
    $editSlugButtonsChecked = ' checked="checked" ';
    $iclDivChecked = ' checked="checked" ';
    $submitDivChecked = ' checked="checked" ';
    $pageParentDivChecked = ' checked="checked" ';
    $redirectChecked = ' checked="checked" ';
    $commentsDivChecked = ' checked="checked" ';
    $slugDivChecked = ' checked="checked" ';
    $iclDivConfigChecked = ' checked="checked" ';
    if(in_array($screen->id, array("post", "page")) && isset($_GET['post']) && in_array( $pagenow, array( "post.php") ))
    {
      echo 'im an old post<br>';
      if(get_post_meta($_GET['post'], '_ceu_disable_addmedia', true) == '')
      {
        $addMediaChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_visual', true) == '')
      {
        $visualChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_text', true) == '')
      {
        $textChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_editslugbuttons', true) == '')
      {
        $editSlugButtonsChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_icldiv', true) == '')
      {
        $iclDivChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_submitdiv', true) == '')
      {
        $submitDivChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_pageparentdiv', true) == '')
      {
        $pageParentDivChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_redirect', true) == '')
      {
        $redirectChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_commentsdiv', true) == '')
      {
        $commentsDivChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_slugdiv', true) == '')
      {
        $slugDivChecked = '';
      }
      if(get_post_meta($_GET['post'], '_ceu_disable_icldivconfig', true) == '')
      {
        $iclDivConfigChecked = '';
      }
    }

    if (in_array($screen->id, array("post", "page")) && in_array( $pagenow, array( "post-new.php") ))
    {
      echo 'im a new post<br>';
      $settings = (array) get_option( 'ceu-settings' );
      var_dump($settings);
      if ($settings['media'] !== '1')
      {
        $addMediaChecked = '';
      }
      if ($settings['visual'] !== '1')
      {
        $visualChecked = '';
      }
      if ($settings['text'] !== '1')
      {
        $textChecked = '';
      }
      if ($settings['editslug'] !== '1')
      {
        $editSlugButtonsChecked = '';
      }
      if ($settings['icl_div'] !== '1')
      {
        $iclDivChecked = '';
      }
      if ($settings['submitdiv'] !== '1')
      {
        $submitDivChecked = '';
      }
      if ($settings['pageparentdiv'] !== '1')
      {
        $pageParentDivChecked = '';
      }
      if ($settings['redirect'] !== '1')
      {
        $redirectChecked = '';
      }
      if ($settings['commentsdiv'] !== '1')
      {
        $commentsDivChecked = '';
      }
      if ($settings['slugdiv'] !== '1')
      {
        $slugDivChecked = '';
      }
      if ($settings['icl_div_config'] !== '1')
      {
        $iclDivConfigChecked = '';
      }
    }

    $current .= '<h5>Content Editor Unbounder</h5>';

    $current .= '<div><input type="checkbox" id="ceu_disable_addmedia" name="ceu_disable_addmedia" '.$addMediaChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable AddMedia Button", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_visual" name="ceu_disable_visual" '.$visualChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Visual Editor", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_text" name="ceu_disable_text" '.$textChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Text Editor", 'ceu_textbox' );
    $current .= '</label></div>';

    $current .= '<div><input type="checkbox" id="ceu_disable_editslugbuttons" name="ceu_disable_editslugbuttons" '.$editSlugButtonsChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Edit Slug Buttons", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_icldiv" name="ceu_disable_icldiv" '.$iclDivChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Language Widget", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_submitdiv" name="ceu_disable_submitdiv" '.$submitDivChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Publish Widget", 'ceu_textbox' );
    $current .= '</label></div>';

    $current .= '<div><input type="checkbox" id="ceu_disable_pageparentdiv" name="ceu_disable_pageparentdiv" '.$pageParentDivChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Page Attributes Widget", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_redirect" name="ceu_disable_redirect" '.$redirectChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Redirect Widget", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_commentsdiv" name="ceu_disable_commentsdiv" '.$commentsDivChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Comments Widget", 'ceu_textbox' );
    $current .= '</label></div>';

    $current .= '<div><input type="checkbox" id="ceu_disable_slugdiv" name="ceu_disable_slugdiv" '.$slugDivChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Slug Widget", 'ceu_textbox' );
    $current .= '</label>';

    $current .= '<input type="checkbox" id="ceu_disable_icldivconfig" name="ceu_disable_icldivconfig" '.$iclDivConfigChecked.'/> ';
    $current .= '<label for="ceu_checkbox"> ';
    $current .=   __("Disable Multilingual Content Setup Widget", 'ceu_textbox' );
    $current .= '</label></div>';

    return $current;
  }
  add_filter('screen_settings', 'ceu_screen_settings', 10, 2);

  /*
   * Ajax called when saving options
   */
  function ceu_save_options()
  {
    $ceu_disable_addmedia = $_POST['ceu_disable_addmedia'];
    $ceu_disable_visual = $_POST['ceu_disable_visual'];
    $ceu_disable_text = $_POST['ceu_disable_text'];
    $ceu_disable_editslugbuttons = $_POST['ceu_disable_editslugbuttons'];
    $ceu_disable_icldiv = $_POST['ceu_disable_icldiv'];
    $ceu_disable_submitdiv = $_POST['ceu_disable_submitdiv'];
    $ceu_disable_pageparentdiv = $_POST['ceu_disable_pageparentdiv'];
    $ceu_disable_redirect = $_POST['ceu_disable_redirect'];
    $ceu_disable_commentsdiv = $_POST['ceu_disable_commentsdiv'];
    $ceu_disable_slugdiv = $_POST['ceu_disable_slugdiv'];
    $ceu_disable_icldivconfig = $_POST['ceu_disable_icldivconfig'];

    $post_id = $_POST['post'];

    if ($post_id)
    {
      if($ceu_disable_addmedia !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_addmedia", $ceu_disable_addmedia);
      }

      if($ceu_disable_visual !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_visual", $ceu_disable_visual);
      }

      if($ceu_disable_text !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_text", $ceu_disable_text);
      }

      if($ceu_disable_editslugbuttons !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_editslugbuttons", $ceu_disable_editslugbuttons);
      }
  
      if($ceu_disable_icldiv !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_icldiv", $ceu_disable_icldiv);
      }

      if($ceu_disable_submitdiv !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_submitdiv", $ceu_disable_submitdiv);
      }

      if($ceu_disable_pageparentdiv !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_pageparentdiv", $ceu_disable_pageparentdiv);
      }

      if($ceu_disable_redirect !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_redirect", $ceu_disable_redirect);
      }

      if($ceu_disable_commentsdiv !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_commentsdiv", $ceu_disable_commentsdiv);
      }

      if($ceu_disable_slugdiv !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_slugdiv", $ceu_disable_slugdiv);
      }

      if($ceu_disable_icldivconfig !== NULL)
      {
        update_post_meta($post_id, "_ceu_disable_icldivconfig", $ceu_disable_icldivconfig);
      }
    }

    exit;
  }
  add_action("wp_ajax_ceu_save", "ceu_save_options");

  function ceu_initialise_new_post()
  {
    add_action("admin_footer", "ceu_admin_footer", 10);

    add_filter('admin_footer',
      function()
      {
        $scriptTag = '<script type="text/javascript">jQuery(document).ready(function(){';

        $settings = (array) get_option( 'ceu-settings' );
        if ($settings['media'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("wp-content-media-buttons");';
        }
        if ($settings['visual'] === '1')
        {
          $scriptTag .= 'ceu_disableVisualEditor();';
        }
        if ($settings['text'] === '1')
        {
          $scriptTag .= 'ceu_disableTextEditor();';
        }
        if ($settings['editslug'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("edit-slug-buttons");';
        }
        if ($settings['icl_div'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("icl_div");';
        }
        if ($settings['submitdiv'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("submitdiv");';
        }
        if ($settings['pageparentdiv'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("pageparentdiv");';
        }
        if ($settings['redirect'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("redirect");';
        }
        if ($settings['commentsdiv'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("commentsdiv");';
        }
        if ($settings['slugdiv'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("slugdiv");';
        }
        if ($settings['icl_div_config'] === '1')
        {
          $scriptTag .= 'ceu_disable_div("icl_div_config");';
        }
        $scriptTag .= '});</script>';
          
        echo $scriptTag;
      }
    );
  }
  add_action("load-post-new.php", "ceu_initialise_new_post");

  function ceu_admin_menu()
  {
    add_options_page( 'Content Editor Unbounder', 'Content Editor Unbounder', 'manage_options', 'content-editor-unbounder', 'content_editor_unbounder_options_page' );
  }
  add_action( "admin_menu", "ceu_admin_menu" );

  function ceu_admin_init()
  {
    register_setting( 'ceu-settings-group', 'ceu-settings' );
    add_settings_section( 'section-one', 'Content Editor Unbounder', 'section_one_callback', 'content-editor-unbounder' );

    $settings = (array) get_option( 'ceu-settings' );
    add_settings_field( 'media', 'Disable AddMedia Button', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[media]',
        'value' => $settings['media'],
      )
    );
    add_settings_field( 'visual', 'Disable Visual Editor', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[visual]',
        'value' => $settings['visual'],
      )
    );
    add_settings_field( 'text', 'Disable Text Editor', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[text]',
        'value' => $settings['text'],
      )
    );
    add_settings_field( 'editslug', 'Disable Edit Slug Buttons', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[editslug]',
        'value' => $settings['editslug'],
      )
    );
    add_settings_field( 'icl_div', 'Disable Language Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[icl_div]',
        'value' => $settings['icl_div'],
      )
    );
    add_settings_field( 'submitdiv', 'Disable Publish Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[submitdiv]',
        'value' => $settings['submitdiv'],
      )
    );
    add_settings_field( 'pageparentdiv', 'Disable Page Attributes Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[pageparentdiv]',
        'value' => $settings['pageparentdiv'],
      )
    );
    add_settings_field( 'redirect', 'Disable Redirect Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[redirect]',
        'value' => $settings['redirect'],
      )
    );
    
    add_settings_field( 'commentsdiv', 'Disable Comment Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[commentsdiv]',
        'value' => $settings['commentsdiv'],
      )
    );
    add_settings_field( 'slugdiv', 'Disable Slug Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[slugdiv]',
        'value' => $settings['slugdiv'],
      )
    );
    add_settings_field( 'icl_div_config', 'Disable Multilingual Content Setup Widget', 'ceu_checkbox', 'content-editor-unbounder', 'section-one',
      array(
        'name' => 'ceu-settings[icl_div_config]',
        'value' => $settings['icl_div_config'],
      )
    );
  }

  if ( !empty( $GLOBALS['pagenow'] ) and ( 'options-general.php' === $GLOBALS['pagenow'] or 'options.php' === $GLOBALS['pagenow'] ) )
  {
    add_action( "admin_init", "ceu_admin_init");
  }

  function section_one_callback()
  {
    echo 'Set the controls on Post/Page which should be disabled when a new Post/Page is created.';
  }

  function ceu_checkbox( $args )
  {
    echo '<input type="checkbox" id="' . esc_attr( $args['name'] ) . '" name="' . esc_attr( $args['name'] ) .
      '" value="' . 1 . '" ' . checked( 1, esc_attr( $args['value'] ), false ) . '/>';
  }

  function content_editor_unbounder_options_page()
  {
    ?>
    <div class="wrap">
        <h2>Content Editor Unbounder Plugin Options</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'ceu-settings-group' ); ?>
            <?php do_settings_sections( 'content-editor-unbounder' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
  }
?>
