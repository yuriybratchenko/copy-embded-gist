<?php
/**
 * Plugin Name: Copy Embeded Gist
 * Plugin URI:  https://crocoblock.com/
 * Description: 
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'wp_footer', function() {
?>
<script type="text/javascript">
	class CopyGistsCode {
		
		constructor() {

			if ( ! navigator.clipboard ) {
				return;
			}

			window.addEventListener( 'load', () => {
				this.init();
			} );
		}

		init() {
			
			const allGists = document.querySelectorAll( '.gist-file' );

			if ( ! allGists.length ) {
				return;
			}

			allGists.forEach( ( gist ) => {
				this.initSingleGist( gist );
			} );

			this.printCSS();

		}

		initSingleGist( gist ) {
			
			const gistTable = gist.querySelector( 'table.highlight' );

			if ( ! gistTable ) {
				return;
			}

			const tableContainer = gistTable.parentNode;
			const button = this.getNewButton();
			const header = this.getNewHeader( gist );

			header.appendChild( button );

			tableContainer.insertBefore( header, gistTable );

			button.addEventListener( 'click', () => {
				
				const code = this.getGistCode( gistTable );

				navigator.clipboard.writeText( code ).then( function() {
					button.innerText = 'Copied!';
					setTimeout( () => {
						button.innerText = 'Copy';
					}, 1000 );
				}, function() {
					// clipboard write failed
				} );

			} );

		}

		getGistCode( gistTable ) {
			let code = '';
			const codeLines = gistTable.querySelectorAll( 'td.blob-code' );

			if ( ! codeLines ) {
				return '';
			}

			codeLines.forEach( ( line ) => {
				code += line.innerText + '\n';
			} );

			return code;
		}

		getNewHeader( gist ) {
			const header = document.createElement( 'div' );
			header.classList.add( 'copy-gist-header' );
				
			const meta = gist.querySelector( '.gist-meta' );

			header.appendChild( meta.cloneNode( true ) );

			return header;
		}

		getNewButton() {
			const button = document.createElement( 'button' );
			button.setAttribute( 'type', 'button' );
			button.innerText = 'Copy';
			button.classList.add( 'copy-gist-button' );
			return button;
		}

		printCSS() {
			const styles = document.createElement( 'style' );
			const head   = document.getElementsByTagName( 'head' )[0];
			
			let stylesheet = '.copy-gist-header {border-bottom:1px solid #ddd; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";background-color: #f7f7f7;border-radius: 6px 6px 0 0;padding: 10px; display: flex; justify-content: space-between; align-items: center;}';
			
			stylesheet += '.copy-gist-button{display: inline-block;padding: 5px 16px;font-size: 14px;font-weight: 500;line-height: 20px;white-space: nowrap;vertical-align: middle;cursor: pointer;-webkit-user-select: none;user-select: none;border: 1px solid rgba(27,31,36,0.15);border-radius: 6px;-webkit-appearance: none;appearance: none;background-color:#2da44e;color:#fff;box-shadow: 0 1px 0 rgba(27,31,36,0.1); margin: 0 0 0 10px;}';
			stylesheet += '.copy-gist-button:hover{background-color:#2c974b;}';

			stylesheet += '.gist .copy-gist-header .gist-meta{ flex: 1; padding: 0; font-size: 14px;margin: -1px 0 0 0;}'

			styles.type = 'text/css';
			styles.innerText = stylesheet;

			

			head.appendChild( styles )

		}

	}

	new CopyGistsCode();

	
</script>
<?php
} );
