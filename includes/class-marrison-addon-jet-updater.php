<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Marrison_Addon_Jet_Updater {
	private const REPOSITORY = 'marrisonlab/marrison-addon-jetengine';
	private const CACHE_KEY = 'marrison_addon_jetengine_release';

	private $plugin;

	public function __construct( $plugin_file ) {
		$this->plugin = plugin_basename( $plugin_file );
		add_filter( 'update_plugins_github.com', [ $this, 'check_update' ], 10, 3 );
		add_filter( 'plugins_api', [ $this, 'plugin_information' ], 10, 3 );
		add_filter( 'upgrader_pre_download', [ $this, 'verify_download' ], 20, 4 );
		add_filter( 'upgrader_source_selection', [ $this, 'prepare_source' ], 10, 4 );
		add_action( 'delete_site_transient_update_plugins', [ $this, 'clear_cache' ] );
	}

	public function check_update( $update, $plugin_data, $plugin_file ) {
		if ( $plugin_file !== $this->plugin ) {
			return $update;
		}

		$release = $this->latest_release();
		if ( ! $release ) {
			return false;
		}

		return [
			'slug'         => dirname( $this->plugin ),
			'version'      => $release['version'],
			'url'          => 'https://github.com/' . self::REPOSITORY . '/releases/tag/' . $release['tag'],
			'package'      => $release['package'],
			'requires'     => '6.0',
			'requires_php' => '7.4',
		];
	}

	public function plugin_information( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || ( $args->slug ?? '' ) !== dirname( $this->plugin ) ) {
			return $result;
		}

		$release = $this->latest_release();
		if ( ! $release ) {
			return $result;
		}

		return (object) [
			'name'          => 'Marrison Addon JetEngine',
			'slug'          => dirname( $this->plugin ),
			'version'       => $release['version'],
			'homepage'      => 'https://github.com/' . self::REPOSITORY,
			'download_link' => $release['package'],
			'requires'      => '6.0',
			'requires_php'  => '7.4',
			'sections'      => [ 'changelog' => wpautop( esc_html( $release['notes'] ) ) ],
		];
	}

	private function latest_release() {
		$cached = get_site_transient( self::CACHE_KEY );
		if ( false !== $cached ) {
			return $cached ?: false;
		}

		$response = wp_safe_remote_get( 'https://api.github.com/repos/' . self::REPOSITORY . '/releases/latest', [
			'timeout' => 8,
			'headers' => [ 'Accept' => 'application/vnd.github+json' ],
		] );
		$release = false;
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$release = $this->parse_release( json_decode( wp_remote_retrieve_body( $response ), true ) );
		}

		set_site_transient( self::CACHE_KEY, $release ?: [], $release ? HOUR_IN_SECONDS : 15 * MINUTE_IN_SECONDS );
		return $release;
	}

	private function parse_release( $data ) {
		$tag = $data['tag_name'] ?? '';
		$assets = $data['assets'] ?? [];
		if ( ! is_string( $tag ) || ! preg_match( '/^v(\d+\.\d+\.\d+)$/D', $tag, $matches ) || ! is_array( $assets ) ) {
			return false;
		}

		$name = 'marrison-addon-jetengine-' . $matches[1] . '.zip';
		$url = 'https://github.com/' . self::REPOSITORY . '/releases/download/' . $tag . '/' . $name;
		foreach ( $assets as $asset ) {
			if ( ( $asset['name'] ?? '' ) !== $name || ( $asset['state'] ?? '' ) !== 'uploaded' || ( $asset['browser_download_url'] ?? '' ) !== $url ) {
				continue;
			}

			$digest = $asset['digest'] ?? '';
			if ( is_string( $digest ) && preg_match( '/^sha256:([a-f0-9]{64})$/D', $digest, $checksum ) ) {
				return [
					'tag'     => $tag,
					'version' => $matches[1],
					'package' => $url,
					'sha256'  => $checksum[1],
					'notes'   => $data['body'] ?? '',
				];
			}
		}
		return false;
	}

	public function verify_download( $reply, $package, $upgrader, $extra ) {
		if ( is_wp_error( $reply ) || ( $extra['plugin'] ?? '' ) !== $this->plugin ) {
			return $reply;
		}

		$release = $this->latest_release();
		if ( ! $release || $package !== $release['package'] ) {
			return new WP_Error( 'marrison_jet_update_package', __( 'The Marrison Addon JetEngine update package could not be verified. Check for updates again.', 'marrison-addon-jet' ) );
		}

		$file = is_string( $reply ) ? $reply : download_url( $package, 30 );
		if ( is_wp_error( $file ) ) {
			return $file;
		}
		$hash = is_file( $file ) ? hash_file( 'sha256', $file ) : false;
		if ( $hash && hash_equals( $release['sha256'], $hash ) ) {
			return $file;
		}

		if ( false === $reply ) {
			wp_delete_file( $file );
		}
		return new WP_Error( 'marrison_jet_update_checksum', __( 'The Marrison Addon JetEngine update package failed its SHA-256 check.', 'marrison-addon-jet' ) );
	}

	public function prepare_source( $source, $remote_source, $upgrader, $extra ) {
		if ( is_wp_error( $source ) || ( $extra['plugin'] ?? '' ) !== $this->plugin ) {
			return $source;
		}

		global $wp_filesystem;
		$source = trailingslashit( $source );
		$destination = trailingslashit( $remote_source ) . dirname( $this->plugin ) . '/';
		if ( $wp_filesystem->is_file( $source . basename( $this->plugin ) ) &&
			( $source === $destination || ( ! $wp_filesystem->exists( $destination ) && $wp_filesystem->move( $source, $destination ) ) ) ) {
			return $destination;
		}

		return new WP_Error( 'marrison_jet_update_folder', __( 'The Marrison Addon JetEngine update could not be prepared in the installed plugin folder.', 'marrison-addon-jet' ) );
	}

	public function clear_cache() {
		delete_site_transient( self::CACHE_KEY );
	}
}
