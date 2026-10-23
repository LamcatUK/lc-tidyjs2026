import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
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
 */
export default function ImagePicker( { id, url, onSelect, onRemove, size = 120 } ) {
	const preview = useMediaUrl( id, url );

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
							{ preview && (
								<Button variant="link" size="small" isDestructive onClick={ onRemove }>
									{ __( 'Remove', 'lc-tidyjs2026' ) }
								</Button>
							) }
						</div>
					</div>
				) }
			/>
		</MediaUploadCheck>
	);
}
