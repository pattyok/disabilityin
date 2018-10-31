<?php
namespace Roots\Sage\Meta;
use Roots\Sage\Extras;


//TODO NOT USING
/**
 * Create a metabox with multiple fields.
 */
	//
	// Create Metabox
	//
	/**
	 * Create the metabox
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	function create_metabox() {
		// Can only be used on a single post type (ie. page or post or a custom post type).
		// Must be repeated for each post type you want the metabox to appear on.
		add_meta_box(
			'din_event_data', // Metabox ID
			'Event Data', // Title to display
			__NAMESPACE__ . '\\render_metabox', // Function to call that contains the metabox content
			'post', // Post type to display metabox on
			'side', // Where to put it (normal = main colum, side = sidebar, etc.)
			'high' // Priority relative to other metaboxes
		);
	}
	add_action( 'add_meta_boxes', __NAMESPACE__ . '\\create_metabox' );
	/**
	 * Create the metabox default values
	 * This allows us to save multiple values in an array, reducing the size of our database.
	 * Setting defaults helps avoid "array key doesn't exit" issues.
	 * @todo
	 */
	function metabox_defaults() {
		return array(
			'start_date' => date('Y-m-d\TH:i'),
			'end_date' => date('Y-m-d\TH:i'),
		);
	}
	/**
	 * Render the metabox markup
	 * This is the function called in `create_metabox()`
	 */
	function render_metabox() {
		// Variables
		global $post; // Get the current post data
		$saved = get_post_meta( $post->ID, 'din_event_data', true ); // Get the saved values
		$defaults = metabox_defaults(); // Get the default values
		$details = wp_parse_args( $saved, $defaults ); // Merge the two in case any fields don't exist in the saved data
		$event_term = get_term_by( 'slug', 'events', 'category'); //were going to display this field only for events
		?>

			<fieldset>
				<?php if (!empty($event_term) && !is_wp_error($event_term)) { ?>
				<input type="hidden" id="din_event_term_id" value="<?php echo $event_term->term_id; ?>">
				<?php } ?>
				<?php
					// A simple text input
				?>
				<div>
					<label for="din_event_data_start_date">
						<?php
							// This runs the text through a translation and echoes it (for internationalization)
							_e( 'Event Start Date', 'sage' );
						?>
					</label>
					<?php
						// It's important that the `name` is an array. This let's us
						// easily loop through all fields later when we go to save
						// our submitted data.
						//
						// The `esc_attr()` function here escapes the data for
						// HTML attribute use to avoid unexpected issues
					?>
					<input
						type="datetime-local"
						name="din_event_data[start_date]"
						id="din_event_data_start_date"
						value="<?php echo esc_attr( $details['start_date'] ); ?>"
					>
				</div>


				<?php
					// A simple text input
				?>
				<div>
					<label for="din_event_data_end_date">
						<?php
							// This runs the text through a translation and echoes it (for internationalization)
							_e( 'Event End Date', 'sage' );
						?>
					</label>
					<?php
						// It's important that the `name` is an array. This let's us
						// easily loop through all fields later when we go to save
						// our submitted data.
						//
						// The `esc_attr()` function here escapes the data for
						// HTML attribute use to avoid unexpected issues
					?>
					<input
						type="datetime-local"
						name="din_event_data[end_date]"
						id="din_event_data_end_date"
						value="<?php echo esc_attr( $details['end_date'] ); ?>"
					>
				</div>
			</fieldset>

		<?php
		// Security field
		// This validates that submission came from the
		// actual dashboard and not the front end or
		// a remote server.
		wp_nonce_field( 'din_form_metabox_nonce', 'din_form_metabox_process' );
	}
	//
	// Save our data
	//
	/**
	 * Save the metabox
	 * @param  Number $post_id The post ID
	 * @param  Array  $post    The post data
	 */
	function save_metabox( $post_id, $post ) {
		// Verify that our security field exists. If not, bail.
		if ( !isset( $_POST['din_form_metabox_process'] ) ) return;
		// Verify data came from edit/dashboard screen
		if ( !wp_verify_nonce( $_POST['din_form_metabox_process'], 'din_form_metabox_nonce' ) ) {
			return $post->ID;
		}
		// Verify user has permission to edit post
		if ( !current_user_can( 'edit_post', $post->ID )) {
			return $post->ID;
		}
		// Check that our custom fields are being passed along
		// This is the `name` value array. We can grab all
		// of the fields and their values at once.
		if ( !isset( $_POST['din_event_data'] ) ) {
			return $post->ID;
		}
		/**
		 * Sanitize all data
		 * This keeps malicious code out of our database.
		 */
		// Set up an empty array
		$sanitized = array();
		// Loop through each of our fields
		foreach ( $_POST['din_event_data'] as $key => $detail ) {
			// Sanitize the data and push it to our new array
			// `wp_filter_post_kses` strips our dangerous server values
			// and allows through anything you can include a post.
			$sanitized[$key] = wp_filter_post_kses( $detail );
		}
		// Save our submissions to the database
		update_post_meta( $post->ID, 'din_event_data', $sanitized );
	}
	add_action( 'save_post', __NAMESPACE__ . '\\save_metabox', 1, 2 );
	//
	// Save a copy to our revision history
	// This is optional, and potentially undesireable for certain data types.
	// Restoring a a post to an old version will also update the metabox.
	/**
	 * Save events data to revisions
	 * @param  Number $post_id The post ID
	 */
	function save_revisions( $post_id ) {
		// Check if it's a revision
		$parent_id = wp_is_post_revision( $post_id );
		// If is revision
		if ( $parent_id ) {
			// Get the saved data
			$parent = get_post( $parent_id );
			$details = get_post_meta( $parent->ID, 'din_event_data', true );
			// If data exists and is an array, add to revision
			if ( !empty( $details ) && is_array( $details ) ) {
				// Get the defaults
				$defaults = metabox_defaults();
				// For each default item
				foreach ( $defaults as $key => $value ) {
					// If there's a saved value for the field, save it to the version history
					if ( array_key_exists( $key, $details ) ) {
						add_metadata( 'post', $post_id, 'din_event_data' . $key, $details[$key] );
					}
				}
			}
		}
	}
	add_action( 'save_post', __NAMESPACE__ . '\\save_revisions' );
	/**
	 * Restore events data with post revisions
	 * @param  Number $post_id     The post ID
	 * @param  Number $revision_id The revision ID
	 */
	function restore_revisions( $post_id, $revision_id ) {
		// Variables
		$post = get_post( $post_id ); // The post
		$revision = get_post( $revision_id ); // The revision
		$defaults = metabox_defaults(); // The default values
		$details = array(); // An empty array for our new metadata values
		// Update content
		// For each field
		foreach ( $defaults as $key => $value ) {
			// Get the revision history version
			$detail_revision = get_metadata( 'post', $revision->ID, 'din_event_data' . $key, true );
			// If a historic version exists, add it to our new data
			if ( isset( $detail_revision ) ) {
				$details[$key] = $detail_revision;
			}
		}
		// Replace our saved data with the old version
		update_post_meta( $post_id, 'din_event_data', $details );
	}
	add_action( 'wp_restore_post_revision', __NAMESPACE__ . '\\restore_revisions', 10, 2 );
	/**
	 * Get the data to display on the revisions page
	 * @param  Array $fields The fields
	 * @return Array The fields
	 */
	function get_revisions_fields( $fields ) {
		// Get our default values
		$defaults = metabox_defaults();
		// For each field, use the key as the title
		foreach ( $defaults as $key => $value ) {
			$fields['din_event_data' . $key] = ucfirst( $key );
		}
		return $fields;
	}
	add_filter( '_wp_post_revision_fields', __NAMESPACE__ . '\\get_revisions_fields' );
	/**
	 * Display the data on the revisions page
	 * @param  String|Array $value The field value
	 * @param  Array        $field The field
	 */
	function display_revisions_fields( $value, $field ) {
		global $revision;
		return get_metadata( 'post', $revision->ID, $field, true );
	}
	add_filter( '_wp_post_revision_field_my_meta', __NAMESPACE__ . '\\display_revisions_fields', 10, 2 );