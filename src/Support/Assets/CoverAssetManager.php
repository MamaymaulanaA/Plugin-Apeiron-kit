<?php

namespace ApeironKit\Support\Assets;

use ApeironKit\Support\CoverTypeRegistry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class CoverAssetManager {

	public function __construct( DocumentWidgetIndex $document_index ) {
	}

	public function enqueue_all_type_styles(): void {
		$this->enqueue_style_handles( $this->sort_style_handles( CoverTypeRegistry::all_style_handles() ) );
	}

	public function enqueue_all_type_scripts(): void {
		$this->enqueue_script_handles( CoverTypeRegistry::all_script_handles() );
	}

	public function enqueue_current_type_styles(): void {
		$this->enqueue_all_type_styles();
	}

	public function enqueue_current_type_scripts(): void {
		$this->enqueue_all_type_scripts();
	}

	/**
	 * @param string[] $handles Style handles.
	 * @return string[]
	 */
	private function sort_style_handles( array $handles ): array {
		$handles = array_values( array_unique( $handles ) );
		$tail    = [];

		$handles = array_values(
			array_filter(
				$handles,
				static function ( string $handle ) use ( &$tail ): bool {
					if ( 'apeiron-kit-cover-responsive' === $handle ) {
						$tail[] = $handle;
						return false;
					}

					return true;
				}
			)
		);

		return array_merge( $handles, array_values( array_unique( $tail ) ) );
	}

	/**
	 * @param string[] $handles Style handles.
	 */
	private function enqueue_style_handles( array $handles ): void {
		foreach ( array_unique( $handles ) as $handle ) {
			if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * @param string[] $handles Script handles.
	 */
	private function enqueue_script_handles( array $handles ): void {
		foreach ( array_unique( $handles ) as $handle ) {
			if ( wp_script_is( $handle, 'registered' ) || wp_script_is( $handle, 'enqueued' ) ) {
				wp_enqueue_script( $handle );
			}
		}
	}
}
