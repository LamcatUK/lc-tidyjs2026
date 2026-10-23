import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import ImagePicker from '../../_shared/ImagePicker';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, imageId, imageUrl, imageAlt, usps } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Hero" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Title', 'lc-tidyjs2026' ) }
				value={ title }
				onChange={ ( value ) => setAttributes( { title: value } ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'lc-tidyjs2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
			/>
			<div className="lc-tidyjs2026-editor-field">
				<label className="lc-tidyjs2026-editor-field__label">{ __( 'Image', 'lc-tidyjs2026' ) }</label>
				<ImagePicker
					id={ imageId }
					url={ imageUrl }
					size={ 200 }
					onSelect={ ( media ) =>
						setAttributes( {
							imageId: media.id,
							imageUrl: media.url,
							imageAlt: media.alt || '',
						} )
					}
					onRemove={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }
				/>
			</div>
			<TextareaControl
				label={ __( 'Usps', 'lc-tidyjs2026' ) }
				value={ usps }
				onChange={ ( value ) => setAttributes( { usps: value } ) }
				help={ __( 'One per line. icon : term', 'lc-tidyjs2026' ) }
			/>
		</EditorBlockShell>
	);
}
