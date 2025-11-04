<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Check if Elementor is loaded
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

/**
 * Elementor Aparat Single Video Player widget.
 *
 * Elementor widget that displays a video player for Aparat and self-hosted videos.
 *
 * @since 1.0.0
 */
class Aparat_Elementor_Single_Video_Player_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve video widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'aparat-video-player';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve video widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Aparat Video Player', 'aparat-elementor-single-video-player' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve video widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-video-playlist';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the video widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'video', 'player', 'aparat', 'embed', 'self-hosted' ];
	}

	/**
	 * Register video widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_video',
			[
				'label' => esc_html__( 'Video', 'aparat-elementor-single-video-player' ),
			]
		);

		$this->add_control(
			'video_type',
			[
				'label' => esc_html__( 'Source', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'aparat',
				'options' => [
					'aparat' => esc_html__( 'Aparat', 'aparat-elementor-single-video-player' ),
					'hosted' => esc_html__( 'Self Hosted', 'aparat-elementor-single-video-player' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'aparat_url',
			[
				'label' => esc_html__( 'Aparat Video URL', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your Aparat URL', 'aparat-elementor-single-video-player' ) . ' (e.g., https://www.aparat.com/v/ror49jn)',
				'label_block' => true,
				'condition' => [
					'video_type' => 'aparat',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'insert_url',
			[
				'label' => esc_html__( 'External URL', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'Choose Video File', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'media_types' => [
					'video',
				],
				'condition' => [
					'video_type' => 'hosted',
					'insert_url' => '',
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label' => esc_html__( 'URL', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::URL,
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your URL', 'aparat-elementor-single-video-player' ),
				'condition' => [
					'video_type' => 'hosted',
					'insert_url' => 'yes',
				],
			]
		);

		$this->add_control(
			'start',
			[
				'label' => esc_html__( 'Start Time', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'aparat-elementor-single-video-player' ),
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'end',
			[
				'label' => esc_html__( 'End Time', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'aparat-elementor-single-video-player' ),
				'condition' => [
					'video_type' => 'hosted',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'video_options',
			[
				'label' => esc_html__( 'Video Options', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'play_on_mobile',
			[
				'label' => esc_html__( 'Play On Mobile', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => esc_html__( 'Mute', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'controls',
			[
				'label' => esc_html__( 'Player Controls', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'aparat-elementor-single-video-player' ),
				'label_on' => esc_html__( 'Show', 'aparat-elementor-single-video-player' ),
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'download_button',
			[
				'label' => esc_html__( 'Download Button', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'aparat-elementor-single-video-player' ),
				'label_on' => esc_html__( 'Show', 'aparat-elementor-single-video-player' ),
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'preload',
			[
				'label' => esc_html__( 'Preload', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'metadata' => esc_html__( 'Metadata', 'aparat-elementor-single-video-player' ),
					'auto' => esc_html__( 'Auto', 'aparat-elementor-single-video-player' ),
					'none' => esc_html__( 'None', 'aparat-elementor-single-video-player' ),
				],
				'default' => 'metadata',
				'condition' => [
					'video_type' => 'hosted',
					'autoplay' => '',
				],
			]
		);

		$this->add_control(
			'poster',
			[
				'label' => esc_html__( 'Poster', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Image Overlay', 'aparat-elementor-single-video-player' ),
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label' => esc_html__( 'Image Overlay', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'aparat-elementor-single-video-player' ),
				'label_on' => esc_html__( 'Show', 'aparat-elementor-single-video-player' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label' => esc_html__( 'Choose Image', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_overlay',
				'default' => 'full',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_play_icon',
			[
				'label' => esc_html__( 'Play Icon', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Hide', 'aparat-elementor-single-video-player' ),
				'label_on' => esc_html__( 'Show', 'aparat-elementor-single-video-player' ),
				'separator' => 'before',
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_control(
			'play_icon',
			[
				'label' => esc_html__( 'Icon', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'eicon-play',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'play-circle',
					],
					'fa-solid' => [
						'play',
						'play-circle',
					],
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon!' => '',
				],
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'label_off' => esc_html__( 'Off', 'aparat-elementor-single-video-player' ),
				'label_on' => esc_html__( 'On', 'aparat-elementor-single-video-player' ),
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Video', 'aparat-elementor-single-video-player' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				],
				'selectors_dictionary' => [
					'169' => '1.77777',
					'219' => '2.33333',
					'43' => '1.33333',
					'32' => '1.5',
					'11' => '1',
					'916' => '0.5625',
				],
				'default' => '169',
				'selectors' => [
					'{{WRAPPER}} .aparat-elementor-wrapper' => '--video-aspect-ratio: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .aparat-elementor-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_overlay_style',
			[
				'label' => esc_html__( 'Image Overlay', 'aparat-elementor-single-video-player' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'play_icon_title',
			[
				'label' => esc_html__( 'Play Icon', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => esc_html__( 'Color', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aparat-elementor-custom-embed-play i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .aparat-elementor-custom-embed-play svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => esc_html__( 'Size', 'aparat-elementor-single-video-player' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aparat-elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .aparat-elementor-custom-embed-play svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Extract Aparat video ID from URL
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $url Aparat video URL
	 * @return string|false Video ID or false on failure
	 */
	private function get_aparat_video_id( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		// Pattern to match Aparat URLs like: https://www.aparat.com/v/{video_id}
		preg_match( '/aparat\.com\/v\/([a-zA-Z0-9_-]+)/', $url, $matches );

		if ( ! empty( $matches[1] ) ) {
			return $matches[1];
		}

		return false;
	}

	/**
	 * Get Aparat embed URL
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $video_id Aparat video ID
	 * @return string Embed URL
	 */
	private function get_aparat_embed_url( $video_id ) {
		return 'https://www.aparat.com/video/video/embed/videohash/' . $video_id . '/vt/frame';
	}

	/**
	 * Get Aparat video thumbnail
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $video_id Aparat video ID
	 * @return string|false Thumbnail URL or false on failure
	 */
	private function get_aparat_thumbnail( $video_id ) {
		if ( empty( $video_id ) ) {
			return false;
		}

		// Try to get thumbnail from Aparat API
		$api_url = 'https://www.aparat.com/api/video/video/show/videohash/' . $video_id;
		
		// Use WordPress HTTP API with timeout
		$response = wp_remote_get( $api_url, [
			'timeout' => 5,
			'sslverify' => true,
		] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! empty( $data['data']['attributes']['big_poster'] ) ) {
			return $data['data']['attributes']['big_poster'];
		}

		if ( ! empty( $data['data']['attributes']['small_poster'] ) ) {
			return $data['data']['attributes']['small_poster'];
		}

		// Fallback: Try to get from video page HTML
		$video_page_url = 'https://www.aparat.com/v/' . $video_id;
		$html_response = wp_remote_get( $video_page_url, [
			'timeout' => 5,
			'sslverify' => true,
		] );

		if ( ! is_wp_error( $html_response ) ) {
			$html = wp_remote_retrieve_body( $html_response );
			
			// Try to find og:image meta tag
			if ( preg_match( '/<meta\s+property=["\']og:image["\']\s+content=["\']([^"\']+)["\']/i', $html, $matches ) ) {
				return $matches[1];
			}

			// Try to find thumbnail in JSON-LD
			if ( preg_match( '/"thumbnailUrl":\s*"([^"]+)"/i', $html, $matches ) ) {
				return $matches[1];
			}
		}

		return false;
	}

	/**
	 * Get embed parameters for Aparat
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return array Embed parameters
	 */
	private function get_aparat_embed_params() {
		$settings = $this->get_settings_for_display();
		$params = [];

		if ( $settings['autoplay'] && ! $this->has_image_overlay() ) {
			$params['autoplay'] = '1';
		}

		return $params;
	}

	/**
	 * Whether the video widget has an overlay image or not.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return bool Whether an image overlay was set for the video.
	 */
	protected function has_image_overlay() {
		$settings = $this->get_settings_for_display();
		return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
	}

	/**
	 * Get hosted video URL
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return string Video URL
	 */
	private function get_hosted_video_url() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['insert_url'] ) ) {
			$video_url = $settings['external_url']['url'];
		} else {
			$video_url = $settings['hosted_url']['url'];
		}

		if ( empty( $video_url ) ) {
			return '';
		}

		if ( $settings['start'] || $settings['end'] ) {
			$video_url .= '#t=';
		}

		if ( $settings['start'] ) {
			$video_url .= $settings['start'];
		}

		if ( $settings['end'] ) {
			$video_url .= ',' . $settings['end'];
		}

		return $video_url;
	}

	/**
	 * Get hosted video parameters
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return array Video parameters
	 */
	private function get_hosted_params() {
		$settings = $this->get_settings_for_display();
		$video_params = [];

		foreach ( [ 'autoplay', 'loop', 'controls' ] as $option_name ) {
			if ( $settings[ $option_name ] ) {
				$video_params[ $option_name ] = '';
			}
		}

		if ( $settings['preload'] ) {
			$video_params['preload'] = $settings['preload'];
		}

		if ( $settings['mute'] ) {
			$video_params['muted'] = 'muted';
		}

		if ( $settings['play_on_mobile'] ) {
			$video_params['playsinline'] = '';
		}

		if ( ! $settings['download_button'] ) {
			$video_params['controlsList'] = 'nodownload';
		}

		if ( $settings['poster']['url'] ) {
			$video_params['poster'] = $settings['poster']['url'];
		}

		return $video_params;
	}

	/**
	 * Render hosted video
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function render_hosted_video() {
		$video_url = $this->get_hosted_video_url();
		if ( empty( $video_url ) ) {
			return;
		}

		$video_params = $this->get_hosted_params();
		?>
		<video class="aparat-elementor-video" src="<?php echo esc_attr( $video_url ); ?>" <?php Utils::print_html_attributes( $video_params ); ?>></video>
		<?php
	}

	/**
	 * Render Aparat video
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function render_aparat_video() {
		$settings = $this->get_settings_for_display();
		$video_id = $this->get_aparat_video_id( $settings['aparat_url'] );

		if ( ! $video_id ) {
			return;
		}

		$embed_url = $this->get_aparat_embed_url( $video_id );
		$embed_params = $this->get_aparat_embed_params();

		// Build iframe URL with parameters
		$iframe_url = $embed_url;
		if ( ! empty( $embed_params ) ) {
			$iframe_url .= '?' . http_build_query( $embed_params );
		}

		?>
		<iframe 
			class="aparat-elementor-video"
			src="<?php echo esc_url( $iframe_url ); ?>"
			allowfullscreen="true"
			webkitallowfullscreen="true"
			mozallowfullscreen="true"
			frameborder="0"
			style="width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
		</iframe>
		<?php
	}

	/**
	 * Print accessibility text
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_a11y_text( $image_overlay ) {
		if ( empty( $image_overlay['alt'] ) ) {
			echo esc_html__( 'Play Video', 'aparat-elementor-single-video-player' );
		} else {
			echo esc_html__( 'Play Video about', 'aparat-elementor-single-video-player' ) . ' ' . esc_attr( $image_overlay['alt'] );
		}
	}

	/**
	 * Render video widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$video_html = '';
		$aparat_thumbnail = '';

		if ( 'aparat' === $settings['video_type'] ) {
			$video_id = $this->get_aparat_video_id( $settings['aparat_url'] );
			if ( empty( $video_id ) ) {
				return;
			}

			// Get Aparat thumbnail if image overlay is not set or empty
			if ( empty( $settings['image_overlay']['url'] ) || $settings['show_image_overlay'] !== 'yes' ) {
				$aparat_thumbnail = $this->get_aparat_thumbnail( $video_id );
				if ( $aparat_thumbnail ) {
					$settings['image_overlay']['url'] = $aparat_thumbnail;
					$settings['show_image_overlay'] = 'yes';
				}
			}

			ob_start();
			$this->render_aparat_video();
			$video_html = ob_get_clean();
		} elseif ( 'hosted' === $settings['video_type'] ) {
			$video_url = $this->get_hosted_video_url();
			if ( empty( $video_url ) ) {
				return;
			}

			ob_start();
			$this->render_hosted_video();
			$video_html = ob_get_clean();
		}

		if ( empty( $video_html ) ) {
			return;
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'aparat-elementor-wrapper' );
		$this->add_render_attribute( 'video-wrapper', 'class', 'apart-elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ) );
		?>
		<div <?php $this->print_render_attribute_string( 'video-wrapper' ); ?>>
			<?php
			if ( ! $settings['lightbox'] ) {
				Utils::print_unescaped_internal_string( $video_html );
			}

			if ( $this->has_image_overlay() ) {
				$this->add_render_attribute( 'image-overlay', 'class', 'aparat-elementor-custom-embed-image-overlay' );

				if ( $settings['lightbox'] ) {
					if ( 'hosted' === $settings['video_type'] ) {
						$lightbox_url = $this->get_hosted_video_url();
					} else {
						$video_id = $this->get_aparat_video_id( $settings['aparat_url'] );
						$lightbox_url = $this->get_aparat_embed_url( $video_id );
					}

					$lightbox_options = [
						'type' => 'video',
						'videoType' => $settings['video_type'],
						'url' => $lightbox_url,
						'autoplay' => $settings['autoplay'],
						'modalOptions' => [
							'id' => 'aparat-elementor-lightbox-' . $this->get_id(),
							'videoAspectRatio' => $settings['aspect_ratio'] ?? '169',
						],
					];

					if ( 'hosted' === $settings['video_type'] ) {
						$lightbox_options['videoParams'] = $this->get_hosted_params();
					}

					$this->add_render_attribute( 'image-overlay', [
						'data-elementor-open-lightbox' => 'yes',
						'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
					] );

					if ( class_exists( '\Elementor\Plugin' ) && Plugin::$instance && Plugin::$instance->editor && Plugin::$instance->editor->is_edit_mode() ) {
						$this->add_render_attribute( 'image-overlay', [
							'class' => 'elementor-clickable',
						] );
					}
				} else {
					if ( empty( $settings['image_overlay']['id'] ) && ! empty( $settings['image_overlay']['url'] ) ) {
						$image_url = $settings['image_overlay']['url'];
					} else {
						if ( class_exists( '\Elementor\Group_Control_Image_Size' ) ) {
							$image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings );
						} else {
							$image_url = ! empty( $settings['image_overlay']['url'] ) ? $settings['image_overlay']['url'] : '';
						}
					}

					$this->add_render_attribute( 'image-overlay', 'style', 'background-image: url(' . $image_url . ');' );
				}
				?>
				<div <?php $this->print_render_attribute_string( 'image-overlay' ); ?>>
					<?php if ( $settings['lightbox'] ) : ?>
						<?php 
						if ( class_exists( '\Elementor\Group_Control_Image_Size' ) ) {
							Group_Control_Image_Size::print_attachment_image_html( $settings, 'image_overlay' );
						} else {
							$image_url = ! empty( $settings['image_overlay']['url'] ) ? $settings['image_overlay']['url'] : '';
							if ( $image_url ) {
								echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( ! empty( $settings['image_overlay']['alt'] ) ? $settings['image_overlay']['alt'] : '' ) . '" />';
							}
						}
						?>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_play_icon'] ) : ?>
						<div class="aparat-elementor-custom-embed-play" role="button" aria-label="<?php $this->print_a11y_text( $settings['image_overlay'] ); ?>" tabindex="0">
							<?php
							if ( empty( $settings['play_icon']['value'] ) ) {
								$settings['play_icon'] = [
									'library' => 'eicons',
									'value' => 'eicon-play',
								];
							}
							if ( class_exists( '\Elementor\Icons_Manager' ) ) {
								Icons_Manager::render_icon( $settings['play_icon'], [ 'aria-hidden' => 'true' ] );
							} else {
								echo '<i class="eicon-play"></i>';
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			<?php } ?>
		</div>
		<style>
			.aparat-elementor-wrapper {
				position: relative;
				overflow: hidden;
				width: 100%;
				height: 0;
				padding-bottom: calc(100% / var(--video-aspect-ratio, 1.77777));
			}
			.aparat-elementor-wrapper .aparat-elementor-video,
			.aparat-elementor-wrapper iframe {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
			.aparat-elementor-custom-embed-image-overlay {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-size: cover;
				background-position: center;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.aparat-elementor-custom-embed-play {
				width: 68px;
				height: 68px;
				border-radius: 50%;
				background-color: rgba(0, 0, 0, 0.5);
				display: flex;
				align-items: center;
				justify-content: center;
				color: #fff;
				font-size: 20px;
				transition: all 0.3s;
			}
			.aparat-elementor-custom-embed-play:hover {
				background-color: rgba(0, 0, 0, 0.7);
				transform: scale(1.1);
			}
			.aparat-elementor-custom-embed-play i,
			.aparat-elementor-custom-embed-play svg {
				margin-left: 3px;
			}
		</style>
		<?php
	}

	/**
	 * Render video widget as plain content.
	 *
	 * Override the default behavior, by printing the video URL instead of rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		$settings = $this->get_settings_for_display();

		if ( 'hosted' === $settings['video_type'] ) {
			$url = $this->get_hosted_video_url();
		} else {
			$url = $settings['aparat_url'];
		}

		echo esc_url( $url );
	}
}

