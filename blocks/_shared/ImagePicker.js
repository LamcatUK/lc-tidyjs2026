import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { Button, Notice } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import useMediaUrl from './useMediaUrl';

/**
 * One image from the media library: a thumbnail with Select / Replace and Remove.
 * The thumbnail is looked up from the ID when no URL is stored (see useMediaUrl).
 *
 * @param {Object}   props
 * @param {number}   props.id       Media ID, or 0 for none.
 * @param {string}   [props.url]    URL saved with the block, if any.
 * @param {Function} props.onSelect ( media ) => void
 * @param {Function} props.onRemove () => void
 * @param {number}   [props.size]   Thumbnail width in px. Default 120.
 *
 * An image whose URL is on another site (a block copied in from elsewhere)
 * shows an "Import to this site" button: the server downloads it into this
 * site's media library (inc/image-import.php) and onSelect() is called with
 * the new attachment, exactly as if it had been picked from the library.
 */
function isRemoteUrl( url ) {
	try {
		return !! url && new URL( url, window.location.origin ).origin !== window.location.origin;
	} catch ( e ) {
		return false;
	}
}

export default function ImagePicker( { id, url, onSelect, onRemove, size = 120 } ) {
	const preview = useMediaUrl( id, url );
	const [ importing, setImporting ] = useState( false );
	const [ error, setError ] = useState( '' );
	const isRemote = isRemoteUrl( preview );

	function importImage() {
		setImporting( true );
		setError( '' );

		apiFetch( { path: '/lc-tidyjs2026/v1/import-image', method: 'POST', data: { url: preview } } )
			.then( ( media ) => onSelect( media ) )
			.catch( ( e ) => setError( e.message || __( 'The import failed.', 'lc-tidyjs2026' ) ) )
			.finally( () => setImporting( false ) );
	}

	return (
		<MediaUploadCheck>
			<MediaUpload
				onSelect={ onSelect }
				allowedTypes={ [ 'image' ] }
				value={ id }
				render={ ( { open } ) => (
					<div className="lc-tidyjs2026-image-picker">
						{ preview && <img src={ preview } alt="" style={ { width: size + 'px' } } /> }
						<div className="lc-tidyjs2026-image-picker__actions">
							<Button variant="secondary" size="small" onClick={ open }>
								{ preview ? __( 'Replace', 'lc-tidyjs2026' ) : __( 'Select image', 'lc-tidyjs2026' ) }
							</Button>
							{ isRemote && (
								<Button variant="primary" size="small" onClick={ importImage } isBusy={ importing } disabled={ importing }>
									{ __( 'Import to this site', 'lc-tidyjs2026' ) }
								</Button>
							) }
							{ preview && (
								<Button variant="link" size="small" isDestructive onClick={ onRemove }>
									{ __( 'Remove', 'lc-tidyjs2026' ) }
								</Button>
							) }
						</div>
						{ isRemote && ! error && (
							<p className="lc-tidyjs2026-image-picker__remote">
								{ __( 'This image is on another site.', 'lc-tidyjs2026' ) }
							</p>
						) }
						{ error && (
							<Notice status="error" isDismissible={ false }>
								{ error }
							</Notice>
						) }
					</div>
				) }
			/>
		</MediaUploadCheck>
	);
}
