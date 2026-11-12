<?php
namespace Marrison_Addon_Jet\Modules\Listing_Columns\Widgets;

use Elementor\Controls_Manager as Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Listing_Columns_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'marrison_listing_columns'; }
	public function get_title() { return esc_html__( 'Listing Columns', 'marrison-addon-jet' ); }
	public function get_icon() { return 'eicon-columns'; }
	public function get_categories() { return [ 'general' ]; }
	public function get_keywords() { return [ 'listing', 'grid', 'columns', 'jetengine', 'marrison' ]; }
	public function get_style_depends() { return [ 'marrison-listing-columns' ]; }
	public function get_script_depends() { return [ 'marrison-listing-columns' ]; }

	protected function register_controls() {
		$this->start_controls_section( 'listing', [ 'label' => esc_html__( 'Listing and columns', 'marrison-addon-jet' ) ] );
		$this->add_control( 'target_selector', [
			'label' => esc_html__( 'Listing CSS selector', 'marrison-addon-jet' ),
			'type' => Controls::TEXT, 'default' => '.griglia-variabile', 'placeholder' => '#catalog',
			'label_block' => true,
			'description' => esc_html__( 'Assign a CSS ID to the Listing Grid widget (e.g. catalog) and enter #catalog here, or use a class (e.g. .listing-grid). One selector can control several grids. Use with regular grids only.', 'marrison-addon-jet' ),
		] );
		foreach ( [ 'min_columns' => [ esc_html__( 'Minimum columns', 'marrison-addon-jet' ), 2 ], 'max_columns' => [ esc_html__( 'Maximum columns', 'marrison-addon-jet' ), 4 ], 'default_columns' => [ esc_html__( 'Initial columns', 'marrison-addon-jet' ), 3 ] ] as $name => $control ) {
			$this->add_responsive_control( $name, [ 'label' => $control[0], 'type' => Controls::NUMBER, 'min' => 1, 'max' => 12, 'step' => 1, 'default' => $control[1] ] );
		}
		$this->add_control( 'remember', [ 'label' => esc_html__( 'Remember selection', 'marrison-addon-jet' ), 'type' => Controls::SWITCHER, 'default' => 'yes', 'description' => esc_html__( 'Save the selection in the browser per page, selector, and breakpoint. This feature is disabled in the editor.', 'marrison-addon-jet' ) ] );
		$this->add_control( 'min_viewport', [ 'label' => esc_html__( 'Minimum viewport width (px)', 'marrison-addon-jet' ), 'type' => Controls::NUMBER, 'min' => 0, 'max' => 3840, 'step' => 1, 'default' => 768, 'description' => esc_html__( 'Below this width, the buttons are hidden and the grid uses its responsive columns. Enter 0 to show buttons on all devices.', 'marrison-addon-jet' ) ] );
		$this->end_controls_section();

		$this->start_controls_section( 'appearance', [ 'label' => esc_html__( 'Buttons and icons', 'marrison-addon-jet' ) ] );
		$this->add_control( 'display_mode', [ 'label' => esc_html__( 'Show', 'marrison-addon-jet' ), 'type' => Controls::SELECT, 'default' => 'both', 'options' => [ 'both' => esc_html__( 'Icon and number', 'marrison-addon-jet' ), 'icon' => esc_html__( 'Icon only', 'marrison-addon-jet' ), 'number' => esc_html__( 'Number only', 'marrison-addon-jet' ) ] ] );
		$this->add_control( 'icon_position', [ 'label' => esc_html__( 'Icon position', 'marrison-addon-jet' ), 'type' => Controls::SELECT, 'default' => 'before', 'options' => [ 'before' => esc_html__( 'Before', 'marrison-addon-jet' ), 'after' => esc_html__( 'After', 'marrison-addon-jet' ), 'above' => esc_html__( 'Above', 'marrison-addon-jet' ) ], 'condition' => [ 'display_mode' => 'both' ] ] );
		$this->add_control( 'group_label', [ 'label' => esc_html__( 'Accessible label', 'marrison-addon-jet' ), 'type' => Controls::TEXT, 'default' => esc_html__( 'Number of columns', 'marrison-addon-jet' ) ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'columns', [ 'label' => esc_html__( 'Column count', 'marrison-addon-jet' ), 'type' => Controls::NUMBER, 'min' => 1, 'max' => 12, 'default' => 2 ] );
		$repeater->add_control( 'label', [ 'label' => esc_html__( 'Button text', 'marrison-addon-jet' ), 'type' => Controls::TEXT, 'description' => esc_html__( 'Leave blank to display the number of columns.', 'marrison-addon-jet' ) ] );
		$repeater->add_control( 'icon', [ 'label' => esc_html__( 'Custom icon', 'marrison-addon-jet' ), 'type' => Controls::ICONS, 'description' => esc_html__( 'Leave blank to use the automatic SVG grid. You can choose an icon from the Elementor library or upload an SVG.', 'marrison-addon-jet' ) ] );
		$this->add_control( 'custom_buttons', [ 'label' => esc_html__( 'Customize individual buttons', 'marrison-addon-jet' ), 'type' => Controls::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [], 'title_field' => '{{{ columns }}}' ] );
		$this->end_controls_section();

		$this->start_controls_section( 'layout_style', [ 'label' => esc_html__( 'Layout', 'marrison-addon-jet' ), 'tab' => Controls::TAB_STYLE ] );
		$this->add_responsive_control( 'alignment', [ 'label' => esc_html__( 'Alignment', 'marrison-addon-jet' ), 'type' => Controls::CHOOSE, 'options' => [
			'flex-start' => [ 'title' => esc_html__( 'Left', 'marrison-addon-jet' ), 'icon' => 'eicon-text-align-left' ],
			'center' => [ 'title' => esc_html__( 'Center', 'marrison-addon-jet' ), 'icon' => 'eicon-text-align-center' ],
			'flex-end' => [ 'title' => esc_html__( 'Right', 'marrison-addon-jet' ), 'icon' => 'eicon-text-align-right' ],
		], 'default' => 'flex-end', 'selectors' => [ '{{WRAPPER}} .marrison-listing-columns' => 'justify-content: {{VALUE}};' ] ] );
		$this->add_responsive_control( 'direction', [ 'label' => esc_html__( 'Direction', 'marrison-addon-jet' ), 'type' => Controls::SELECT, 'default' => 'row', 'options' => [ 'row' => esc_html__( 'Horizontal', 'marrison-addon-jet' ), 'column' => esc_html__( 'Vertical', 'marrison-addon-jet' ) ], 'selectors' => [ '{{WRAPPER}} .marrison-listing-columns' => 'flex-direction: {{VALUE}};' ] ] );
		$this->slider( 'buttons_gap', esc_html__( 'Button gap', 'marrison-addon-jet' ), '{{WRAPPER}} .marrison-listing-columns', 'gap', 8 );
		$this->dimensions( 'group_padding', esc_html__( 'Group padding', 'marrison-addon-jet' ), '{{WRAPPER}} .marrison-listing-columns', 'padding' );
		$this->end_controls_section();

		$this->start_controls_section( 'buttons_style', [ 'label' => esc_html__( 'Buttons', 'marrison-addon-jet' ), 'tab' => Controls::TAB_STYLE ] );
		$button = '{{WRAPPER}} .marrison-listing-columns__button';
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'typography', 'selector' => $button ] );
		$this->dimensions( 'button_padding', esc_html__( 'Padding', 'marrison-addon-jet' ), $button, 'padding' );
		$this->dimensions( 'button_radius', esc_html__( 'Border Radius', 'marrison-addon-jet' ), $button, 'border-radius' );
		$this->slider( 'button_width', esc_html__( 'Minimum width', 'marrison-addon-jet' ), $button, 'min-width' );
		$this->slider( 'button_height', esc_html__( 'Minimum height', 'marrison-addon-jet' ), $button, 'min-height' );
		$this->slider( 'icon_gap', esc_html__( 'Icon and text gap', 'marrison-addon-jet' ), $button, 'gap', 6 );
		$this->start_controls_tabs( 'button_states' );
		foreach ( [ 'normal' => [ esc_html__( 'Normal', 'marrison-addon-jet' ), $button ], 'hover' => [ esc_html__( 'Hover', 'marrison-addon-jet' ), $button . ':hover' ], 'active' => [ esc_html__( 'Active', 'marrison-addon-jet' ), $button . '[aria-pressed="true"]' ], 'focus' => [ esc_html__( 'Focus', 'marrison-addon-jet' ), $button . ':focus-visible' ] ] as $state => $data ) {
			$this->start_controls_tab( $state . '_tab', [ 'label' => $data[0] ] );
			$this->add_control( $state . '_color', [ 'label' => esc_html__( 'Text Color', 'marrison-addon-jet' ), 'type' => Controls::COLOR, 'selectors' => [ $data[1] => 'color: {{VALUE}};' ] ] );
			$this->add_control( $state . '_icon_color', [ 'label' => esc_html__( 'Icon Color', 'marrison-addon-jet' ), 'type' => Controls::COLOR, 'selectors' => [ $data[1] . ' .marrison-listing-columns__icon' => 'color: {{VALUE}};' ] ] );
			$this->add_group_control( \Elementor\Group_Control_Background::get_type(), [ 'name' => $state . '_background', 'types' => [ 'classic', 'gradient' ], 'selector' => $data[1] ] );
			$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => $state . '_border', 'selector' => $data[1] ] );
			$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => $state . '_shadow', 'selector' => $data[1] ] );
			$this->end_controls_tab();
		}
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section( 'icons_style', [ 'label' => esc_html__( 'Icons', 'marrison-addon-jet' ), 'tab' => Controls::TAB_STYLE, 'condition' => [ 'display_mode!' => 'number' ] ] );
		$this->slider( 'icon_size', esc_html__( 'Size', 'marrison-addon-jet' ), '{{WRAPPER}} .marrison-listing-columns__icon', 'font-size', 24 );
		$this->add_control( 'icon_stroke', [ 'label' => esc_html__( 'SVG grid stroke width', 'marrison-addon-jet' ), 'type' => Controls::SLIDER, 'range' => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ], 'selectors' => [ '{{WRAPPER}} .marrison-listing-columns__grid' => 'stroke-width: {{SIZE}};' ] ] );
		$this->end_controls_section();
		// Elementor supplies the native Advanced tab (responsive visibility, motion, attributes, CSS, etc.).
	}

	private function slider( $name, $label, $selector, $property, $default = null ) {
		$args = [ 'label' => $label, 'type' => Controls::SLIDER, 'size_units' => [ 'px', 'em', 'rem' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 300 ] ], 'selectors' => [ $selector => $property . ': {{SIZE}}{{UNIT}};' ] ];
		if ( null !== $default ) { $args['default'] = [ 'size' => $default, 'unit' => 'px' ]; }
		$this->add_responsive_control( $name, $args );
	}

	private function dimensions( $name, $label, $selector, $property ) {
		$this->add_responsive_control( $name, [ 'label' => $label, 'type' => Controls::DIMENSIONS, 'size_units' => [ 'px', '%', 'em', 'rem' ], 'selectors' => [ $selector => $property . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
	}

	/** Resolve empty responsive values using Elementor's desktop-to-mobile inheritance. */
	private function get_column_profiles( $settings ) {
		$base = [];
		foreach ( [ 'min_columns' => 2, 'max_columns' => 4, 'default_columns' => 3 ] as $key => $fallback ) {
			$base[ $key ] = isset( $settings[ $key ] ) && '' !== $settings[ $key ] ? (int) $settings[ $key ] : $fallback;
		}
		$profiles = [ $this->column_profile( 'desktop', $base ) ];
		$breakpoints = isset( \Elementor\Plugin::$instance->breakpoints ) ? \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() : [];
		$ordered = [];
		foreach ( $breakpoints as $name => $breakpoint ) {
			$ordered[] = [ 'name' => $name, 'width' => (int) $breakpoint->get_value(), 'direction' => $breakpoint->get_direction() ];
		}
		usort( $ordered, static function ( $a, $b ) { return $b['width'] <=> $a['width']; } );
		$inherited = $base;
		foreach ( $ordered as $breakpoint ) {
			// Widescreen inherits desktop, never a tablet/laptop override.
			$values = 'min' === $breakpoint['direction'] ? $base : $inherited;
			foreach ( array_keys( $base ) as $key ) {
				$responsive_key = $key . '_' . $breakpoint['name'];
				if ( isset( $settings[ $responsive_key ] ) && '' !== $settings[ $responsive_key ] ) {
					$values[ $key ] = (int) $settings[ $responsive_key ];
				}
			}
			$profiles[] = array_merge( $breakpoint, $this->column_profile( $breakpoint['name'], $values ) );
			if ( 'max' === $breakpoint['direction'] ) { $inherited = $values; }
		}
		return $profiles;
	}

	private function column_profile( $name, $values ) {
		$min = max( 1, min( 12, $values['min_columns'] ) );
		$max = max( $min, min( 12, $values['max_columns'] ) );
		return [ 'name' => $name, 'minColumns' => $min, 'maxColumns' => $max, 'defaultColumns' => max( $min, min( $max, $values['default_columns'] ) ) ];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$profiles = $this->get_column_profiles( $settings );
		$desktop = $profiles[0];
		$min = min( array_column( $profiles, 'minColumns' ) );
		$max = max( array_column( $profiles, 'maxColumns' ) );
		$default = $desktop['defaultColumns'];
		$mode = in_array( $settings['display_mode'] ?? '', [ 'both', 'icon', 'number' ], true ) ? $settings['display_mode'] : 'both';
		$position = in_array( $settings['icon_position'] ?? '', [ 'before', 'after', 'above' ], true ) ? $settings['icon_position'] : 'before';
		$config = [ 'selector' => trim( $settings['target_selector'] ?? '.griglia-variabile' ), 'defaultColumns' => $default, 'profiles' => $profiles, 'remember' => 'yes' === ( $settings['remember'] ?? 'yes' ), 'minViewport' => max( 0, min( 3840, (int) ( $settings['min_viewport'] ?? 768 ) ) ) ];
		$custom = [];
		foreach ( (array) ( $settings['custom_buttons'] ?? [] ) as $item ) {
			$custom[ (int) ( $item['columns'] ?? 0 ) ] = $item;
		}
		?>
		<div class="marrison-listing-columns marrison-listing-columns--<?php echo esc_attr( $position ); ?>" role="group" aria-label="<?php echo esc_attr( $settings['group_label'] ?? __( 'Number of columns', 'marrison-addon-jet' ) ); ?>" data-marrison-listing-columns="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			<?php for ( $columns = $min; $columns <= $max; $columns++ ) :
				$item = $custom[ $columns ] ?? [];
				$label = isset( $item['label'] ) && '' !== trim( $item['label'] ) ? $item['label'] : (string) $columns;
				/* translators: %d: number of columns in the listing. */
				$accessible = sprintf( _n( '%d column', '%d columns', $columns, 'marrison-addon-jet' ), $columns );
				?>
				<button class="marrison-listing-columns__button" type="button" data-cols="<?php echo esc_attr( $columns ); ?>" aria-label="<?php echo esc_attr( $accessible ); ?>" aria-pressed="<?php echo $columns === $default ? 'true' : 'false'; ?>"<?php echo $columns < $desktop['minColumns'] || $columns > $desktop['maxColumns'] ? ' hidden' : ''; ?>>
					<?php if ( 'number' !== $mode ) : ?>
						<span class="marrison-listing-columns__icon" aria-hidden="true">
							<?php if ( ! empty( $item['icon']['value'] ) ) {
								\Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
							} else {
								$this->render_grid_icon( $columns );
							} ?>
						</span>
					<?php endif; ?>
					<?php if ( 'icon' !== $mode ) : ?><span class="marrison-listing-columns__label"><?php echo esc_html( $label ); ?></span><?php endif; ?>
				</button>
			<?php endfor; ?>
		</div>
		<?php
	}

	private function render_grid_icon( $columns ) {
		$gap = $columns <= 4 ? 2 : ( $columns <= 8 ? 1 : 0.6 );
		$width = ( 20 - $gap * ( $columns - 1 ) ) / $columns;
		echo '<svg class="marrison-listing-columns__grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true" focusable="false">';
		for ( $column = 0; $column < $columns; $column++ ) {
			for ( $row = 0; $row < 2; $row++ ) {
				echo '<rect x="' . esc_attr( round( 2 + $column * ( $width + $gap ), 3 ) ) . '" y="' . (int) ( 3 + $row * 10 ) . '" width="' . esc_attr( round( $width, 3 ) ) . '" height="8" rx="0.6" />';
			}
		}
		echo '</svg>';
	}
}
