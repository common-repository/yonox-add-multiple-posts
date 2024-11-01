<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">

<h2 style="font-weight:600;margin-bottom:6px;">Yonox Add Multiple Posts</h2>
<hr style="margin:10px 0 20px;">

<div style="display:none;" id="msg_finished" class="updated"><p><?php _e('Process finished OK.','ynxadmp'); ?></p></div>
<div style="display:none;" id="msg_notitles" class="error"><p><?php _e('No titles found.','ynxadmp'); ?></p></div>
	
<table class="widefat" style="border-radius:4px;">
	<thead>
		<tr>
			<th class="manage-column" style="width:180px;font-weight:600;"><?php _e('Option','ynxadmp'); ?></th>
			<th colspan="2" class="manage-column" style="font-weight:600;"><?php _e('Setting','ynxadmp'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="alternate iedit">
			<td valign="middle" style="padding:14px 10px;font-weight:600;font-size:14px;"><?php _e('Post Type:','ynxadmp'); ?></td>
			<td>
				<select id="ynxadmp_post_type" name="ynxadmp_post_type" style="border-radius:4px;">
					<option value="ynxadmp_type_post" selected="selected"><?php _e('Post','ynxadmp'); ?></option>
					<option value="ynxadmp_type_page"><?php _e('Page','ynxadmp'); ?></option>
				</select>
			</td>
		</tr>
		<tr class="alternate iedit">
			<td valign="middle" style="padding:14px 10px;font-weight:600;font-size:14px;"><?php _e('Status:','ynxadmp'); ?></td>
			<td>
				<select id="ynxadmp_post_status" name="ynxadmp_post_status">
					<option value="publish" selected="selected"><?php _e('Published','ynxadmp'); ?></option>
					<option value="pending"><?php _e('Pending Review','ynxadmp'); ?></option>
					<option value="draft"><?php _e('Draft','ynxadmp'); ?></option>
				</select>
			</td>
		</tr>
		<tr id="ynxadmp_titles_section" class="alternate iedit">
			<td valign="top" style="font-weight:600;font-size:14px;padding: 20px 10px;"><?php _e('Posts Titles:','ynxadmp'); ?><br />
			<small><?php _e('One post on each line','ynxadmp'); ?></small></td>
			<td style="padding:8px 12px;">
				<textarea style="width:100%;border-radius:4px;" name="ynxadmp_titles_texarea" id="ynxadmp_titles_texarea" rows="8"></textarea>
			</td>
		</tr>
		<tr id="ynxadmp_content_section" class="alternate iedit">
			<td valign="top" style="font-weight:600;font-size:14px;padding: 20px 10px;"><?php _e('Posts Content:','ynxadmp'); ?><br />
			<small><?php _e('Can be shortcodes or any things','ynxadmp'); ?></small></td>
			<td style="padding:8px 12px;">
				<?php $editorSettings = array( 'textarea_name' => 'ynxadmp_content_texarea', 'textarea_rows' => '8' ); ?>
				<?php wp_editor( '', 'ynxadmp_content_texarea', $editorSettings ); ?>
			</td>
		</tr>
		<tr class="alternate iedit ynxadmp_post-options">
			<td style="padding:14px 10px;font-weight:600;font-size:14px;"><?php _e('Category:','ynxadmp'); ?></td>
			<td>
				<?php
					$args = array(
						'hide_empty' 	=> 0,
						'hierarchical'	=> 1,
					); 
					wp_dropdown_categories( $args );
				?>
			</td>
		</tr>
		<tr class="alternate iedit ynxadmp_page-options">
			<td style="padding:14px 10px;font-weight:600;font-size:14px;"><?php _e('Parent:','ynxadmp'); ?></td>
			<td>
				<?php
					$arr = array(
						'option_none_value' => 0,
						'show_option_none' => __('none','ynxadmp')
					);
					wp_dropdown_pages($arr);
				?>
			</td>
		</tr>
		<tr class="alternate iedit">
			<td valign="middle" style="padding:14px 10px;font-weight:600;font-size:14px;"><?php _e('Author of Posts:','ynxadmp'); ?></td>
			<td colspan="2">
				<select name="author_id" style="border-radius:4px;">
				<?php
				global $wpdb;
				$user_query = "SELECT ID, user_login, display_name, user_email FROM $wpdb->users ORDER BY ID ASC";
				$users = $wpdb->get_results($user_query);
				foreach ($users as $row) {
					echo '<option value="'.$row->ID.'">'.$row->display_name. '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="iedit">
			<td valign="middle" style="padding:13px 10px;font-weight:600;font-size:14px;"><?php _e('Progress Bar:','ynxadmp'); ?></td>
			<td style="padding:8px 14px;" colspan="2">
				<div id="ynxadmp_progressbar" style="height:26px;width:100%;border:1px solid rgb(197, 197, 197);border-radius:4px;padding:1px;background:#fff;"><span style="position:absolute;margin:4px 0 0 8px;"></span></div>
			</td>
		</tr>
	</tbody>
</table>

<div style="margin:15px 0;">
	<a id='btn_ynxadmp_create_posts' class='button button-primary'><?php _e('Create Multiple Posts','ynxadmp'); ?></a>
	<a id='btn_ynxadmp_reset_form' class='button' style="margin-left:8px;"><?php _e('Reset Form','ynxadmp'); ?></a>
</div>

</div>