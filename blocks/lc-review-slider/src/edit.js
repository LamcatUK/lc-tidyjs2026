import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { reviewButtonShortcode } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Review Slider" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Review Button Shortcode', 'lc-tidyjs2026' ) }
				value={ reviewButtonShortcode }
				onChange={ ( value ) => setAttributes( { reviewButtonShortcode: value } ) }
			/>
		</EditorBlockShell>
	);
}
